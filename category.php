<?php
##########################################################################
## @package Joomla 1.5
## @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
## @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
##
## @component RD-Autos - Version 1.5.5
## @copyright Copyright (C) Robert Dam - http://www.rd-media.org
## @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
## Please do NOT remove this licence statement
###########################################################################

defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class RDAutosModelCategory extends JModel {

	   var $_total = null;
	   var $_pagination = null;
	   var $_data = null;
	   var $_ordering = null;

   function __construct(){
   
      parent::__construct();
		
		$this->id 		= JRequest::getInt('id', 0); 
		$this->ordering = JRequest::getInt('ordering', 0);
		
		global $mainframe;
		
		$config = JFactory::getConfig();
		
		// Get the pagination request variables
		$limit        = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		//$limitstart    = $mainframe->getUserStateFromRequest( 'limitstart', 'limitstart', 0, 'int' );
		$limitstart    = JRequest::getInt('limitstart', 0);
		
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	  
   }

	function getPagination() {
		
		if (empty($this->_pagination)) {
		
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
	
		return $this->_pagination;
	}
    
    function getTotal() {
	
        if (empty($this->_total)) {

			$where		= $this->_buildContentWhere();
			$orderby	= $this->_buildContentOrderBy();
		
            $query = 'SELECT * FROM #__rdautos_information AS a, #__rdautos_makes AS c, #__rdautos_models AS d'
					  .$where
					  .$orderby;
            $this->_total = $this->_getListCount($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_total;
    }  

   function getData()
   {
      if (empty($this->_data))
      {
         $db = JFactory::getDBO();

		## Making the query for showing all the cars in list function
		$sql = 'SELECT * FROM #__rdautos_config WHERE id = 1 ';
		 
         $db->setQuery($sql);
         $this->data = $db->loadObject();
      }
      return $this->data;
   }

	function _buildContentOrderBy() {
	
			global $mainframe, $option;
	 
			$filter_order     = $mainframe->getUserStateFromRequest( $option.'filter_ordering', 'filter_ordering', 'a.price', 'cmd' );
			$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
	 
			if ($filter_order == 'a.price') {
				$filter_order = ' (a.price - (1&&discount)*(a.price-a.discount))'; 
			}
	 
			return $orderby;
	}

	function _buildContentWhere() {
	
		global $mainframe, $option;
		
		$db					=& JFactory::getDBO();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_ordering', 'filter_ordering', 'a.price', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		$where[] = 'a.makeid = c.makeid';
		$where[] = 'a.modelid = d.modelid';
		$where[] = 'a.catid = '.(int)$this->id.'';
		$where[] = 'a.published = 1';
		
		if ($search) {
			$where[] = 'LOWER(c.makename) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}


   function getList() {
   
		if (empty($this->_data)) {

		 	$db = JFactory::getDBO();
			
			$where		= $this->_buildContentWhere();
			$orderby	= $this->_buildContentOrderBy();
		
			## Making the query for showing all the cars in list function
			$sql='SELECT * FROM #__rdautos_information AS a, #__rdautos_makes AS c, #__rdautos_models AS d'
					.$where
					.$orderby; 
		 
		 	$db->setQuery($sql, $this->getState('limitstart'), $this->getState('limit' ));
		 	$this->data = $db->loadObjectList();
		}
		return $this->data;
	}

   function getFeatured() {
   
		if (empty($this->_data)) {
		
		 	$db = JFactory::getDBO();
		
			## Making the query for showing all the cars in list function
			$sql = "SELECT * FROM #__rdautos_information AS a, #__rdautos_makes AS c, #__rdautos_models AS d
					WHERE a.makeid = c.makeid
					AND a.modelid = d.modelid
					AND a.catid = ".(int)$this->id."
					AND a.published = 1
					AND a.featured = 1
					ORDER BY RAND() LIMIT 2 ";
		 
		 	$db->setQuery($sql);
		 	$this->data = $db->loadObjectList();
		}
		return $this->data;
	}	

   function getCat() {
   
		if (empty($this->_data)) {
		
		 	$db = JFactory::getDBO();
		
			$sql = "SELECT catname, decription FROM #__rdautos_categories 
					WHERE catid = ".(int)$this->id." ";
		 
		 	$db->setQuery($sql);
		 	$this->data = $db->loadObject();
		}
		return $this->data;
	}
	
}
?>