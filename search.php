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


class RDAutosModelSearch extends JModel {

	   var $_total = null;
	   var $_pagination = null;
	   var $_data = null;
	   var $_ordering = null;

   function __construct(){
   
      parent::__construct();
		
		global $mainframe;
		
		$config = JFactory::getConfig();
		
		// Get the pagination request variables
		$limit        = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart    = JRequest::getInt('limitstart', 0);
		
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
  
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
			
				$query = 'SELECT  a.*, b.*, c.*, d.* 
						  FROM #__rdautos_information AS a, #__rdautos_models AS b, 
						  #__rdautos_makes AS c, #__rdautos_categories as d'
						.$where;
					  
            $this->_total = $this->_getListCount($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_total;
    }  

	function _buildContentWhere() {
	
		global $mainframe, $option;
		
		$db					=& JFactory::getDBO();
		
		## Pick filled in values first, we can use them later on if a user wants to change his values. (DROPDOWNS)
		$makeid     		= $mainframe->getUserStateFromRequest( $option.'makeid', 'makeid', '0', 'cmd' );
		$modelid     		= $mainframe->getUserStateFromRequest( $option.'modelid', 'modelid', '0', 'cmd' );
		$catid	     		= $mainframe->getUserStateFromRequest( $option.'catid', 'catid', '0', 'cmd' );
		$dealerid     		= $mainframe->getUserStateFromRequest( $option.'dealerid', 'dealerid', '0', 'cmd' );
		$fueltype     		= $mainframe->getUserStateFromRequest( $option.'fueltype', 'fueltype', '0', 'cmd' );
		$transmission		= $mainframe->getUserStateFromRequest( $option.'transmission', 'transmission', '0', 'cmd' );
		
		## This part will pick up all the filled in values.	(INPUTBOXES)
		$vin        		= strtoupper($mainframe->getUserStateFromRequest( $option.'vin', 'vin', '0', 'string' ));
		$mileagefrom		= $mainframe->getUserStateFromRequest( $option.'mileageFrom', 'mileageFrom', '0', 'string' );
		$mileagefrom		= JString::strtolower( $mileagefrom );	
		$mileageto			= $mainframe->getUserStateFromRequest( $option.'mileageTo', 'mileageTo', '0', 'string' );
		$mileageto			= JString::strtolower( $mileageto );	
		
		$yearfrom			= $mainframe->getUserStateFromRequest( $option.'buildFrom', 'buildFrom', '0', 'string' );
		$yearfrom			= JString::strtolower( $yearfrom );	
		$yearto				= $mainframe->getUserStateFromRequest( $option.'buildTo', 'buildTo', '0', 'string' );
		$yearto				= JString::strtolower( $yearto );	
		
		$pricefrom			= $mainframe->getUserStateFromRequest( $option.'priceFrom', 'priceFrom', '0', 'string' );
		$pricefrom			= JString::strtolower( $pricefrom );	
		$priceto			= $mainframe->getUserStateFromRequest( $option.'priceTo', 'priceTo', '1', 'string' );
		$priceto			= JString::strtolower( $priceto );	
		
		$leatherseats = $mainframe->getUserStateFromRequest( $option.'leatherseats', 'leatherseats', '0', 'int' );
		$airbag_driver = $mainframe->getUserStateFromRequest( $option.'airbag_driver', 'airbag_driver', '0', 'string' );
		$airbag_passengers = $mainframe->getUserStateFromRequest( $option.'airbag_passengers', 'airbag_passengers', '0', 'string' );
		$airbag_latteral = $mainframe->getUserStateFromRequest( $option.'airbag_latteral', 'airbag_latteral', '0', 'string' );
		$airbag_back = $mainframe->getUserStateFromRequest( $option.'airbag_back', 'airbag_back', '0', 'string' );
		$windowheaters = $mainframe->getUserStateFromRequest( $option.'windowheaters', 'windowheaters', '0', 'string' );
		$sunroof = $mainframe->getUserStateFromRequest( $option.'sunroof', 'sunroof', '0', 'string' );
		$digitalaircondition = $mainframe->getUserStateFromRequest( $option.'digitalaircondition','digitalaircondition','0','string');
		$electricwindows = $mainframe->getUserStateFromRequest( $option.'electricwindows', 'electricwindows', '0', 'string' );
		$electriclocks = $mainframe->getUserStateFromRequest( $option.'electriclocks', 'electriclocks', '0', 'string' );
		$electricmirrors = $mainframe->getUserStateFromRequest( $option.'electricmirrors', 'electricmirrors', '0', 'string' );
		$cdplayer = $mainframe->getUserStateFromRequest( $option.'cdplayer', 'cdplayer', '0', 'string' );
		$dvdplayer = $mainframe->getUserStateFromRequest( $option.'dvdplayer', 'dvdplayer', '0', 'string' );
		$mp3player = $mainframe->getUserStateFromRequest( $option.'mp3player', 'mp3player', '0', 'string' );
		$ipodcompatible = $mainframe->getUserStateFromRequest( $option.'ipodcompatible', 'ipodcompatible', '0', 'string' );
		$amfmradio = $mainframe->getUserStateFromRequest( $option.'amfmradio', 'amfmradio', '0', 'string' );
		$cruisecontrol = $mainframe->getUserStateFromRequest( $option.'cruisecontrol', 'cruisecontrol', '0', 'string' );
		$absbrakes = $mainframe->getUserStateFromRequest( $option.'absbrakes', 'absbrakes', '0', 'string' );
		$heather = $mainframe->getUserStateFromRequest( $option.'heather', 'heather', '0', 'string' );
		$seatsheater = $mainframe->getUserStateFromRequest( $option.'seatsheater', 'seatsheater', '0', 'string' );
		$foglights = $mainframe->getUserStateFromRequest( $option.'foglights', 'foglights', '0', 'string' );
		$hydraulicsteering = $mainframe->getUserStateFromRequest( $option.'hydraulicsteering', 'hydraulicsteering', '0', 'string' );		

		$where = array();

		$where[] = 'a.makeid = c.makeid';
		$where[] = 'a.modelid = b.modelid';
		$where[] = 'a.catid = d.catid';
		$where[] = 'a.published = 1';
		
		if ($makeid != 0){ 

            $where[] = 'a.makeid = '.(int)$makeid;
		
               if ($modelid > 0){ 
                            $where[] = 'a.modelid = '.(int)$modelid;
                }
        }
		
		if ($catid != 0){ 
			$where[] = 'a.catid = '.(int)$catid; 
		}
		if ($dealerid != 0){ 
			$where[] = 'a.dealerid = '.(int)$dealerid;
		}
		if ($fueltype != 0){ 
			$where[] = 'a.fueltype = '.(int)$fueltype;
		}
		if ($transmission != 0){ 
			$where[] = 'a.transmission = '.(int)$transmission;
		}
		
		## Now let's make a filter on the inputboxes	
		if ($mileagefrom != 0){ 
			$where[] = 'a.mileage > '.(int)$mileagefrom; 
		}
		if ($mileageto != 0){ 
			$where[] = 'a.mileage < '.(int)$mileageto; 
		}
		
		if ($pricefrom != 0){ 
			$where[] = 'a.price > '.(int)$pricefrom; 
		}
		if ($priceto != 0){ 
			$where[] = 'a.price < '.(int)$priceto; 
		}		
		
		if ($yearfrom != 0 && $yearto == 0){ 
			$where[] = 'a.constructed > '.(int)$yearfrom-1; 
		}
		if ($yearfrom == 0 && $yearto != 0){ 
			$where[] = 'a.constructed < '.(int)$yearto+1;  
		}		
		if ($yearfrom != 0 && $yearto != 0)  {
			## Create year + one to get till begin of the year
			$year = $yearto+1;
			$where[] = 'a.constructed > '.(int)$yearfrom; 
			$where[] = 'a.constructed < '.(int)$year; 
		}
		
		if ((int)$leatherseats != 0){
			$where[] = 'a.leatherseats = 1';
		}
		if ((int)$airbag_driver != 0){
			$where[] = 'a.airbag_driver = 1';
		}
		if ((int)$airbag_passengers != 0){
			$where[] = 'a.airbag_passengers = 1';
		}
		if ((int)$airbag_latteral != 0){
			$where[] = 'a.airbag_latteral = 1';
		}			
		if ((int)$airbag_back != 0){
			$where[] = 'a.airbag_back = 1';
		}
		if ((int)$windowheaters != 0){
			$where[] = 'a.windowheaters = 1';
		}	
		if ((int)$sunroof != 0){
			$where[] = 'a.sunroof = 1';
		}		
		if ((int)$digitalaircondition != 0){
			$where[] = 'a.digitalaircondition = 1';
		}	
		if ((int)$electricwindows != 0){
			$where[] = 'a.electricwindows = 1';
		}	
		if ((int)$electriclocks != 0){
			$where[] = 'a.electriclocks = 1';
		}		
		if ((int)$electricmirrors != 0){
			$where[] = 'a.electricmirrors = 1';
		}	
		if ((int)$cdplayer != 0){
			$where[] = 'a.cdplayer = 1';
		}
		if ((int)$dvdplayer != 0){
			$where[] = 'a.dvdplayer = 1';
		}	
		if ((int)$mp3player != 0){
			$where[] = 'a.mp3player = 1';
		}	
		if ((int)$ipodcompatible != 0){
			$where[] = 'a.ipodcompatible = 1';
		}
		if ((int)$amfmradio != 0){
			$where[] = 'a.amfmradio = 1';
		}	
		if ((int)$hydraulicsteering != 0){
			$where[] = 'a.hydraulicsteering = 1';
		}	
		if ((int)$cruisecontrol != 0){
			$where[] = 'a.cruisecontrol = 1';
		}	
		if ((int)$absbrakes != 0){
			$where[] = 'a.absbrakes = 1';
		}
		if ((int)$heather != 0){
			$where[] = 'a.heather = 1';
		}	
		if ((int)$seatsheater != 0){
			$where[] = 'a.seatsheater = 1';
		}	
		if ((int)$foglights != 0){
			$where[] = 'a.foglights = 1';
		}
		
		if ($vin != ''){
			$db = JFactory::getDBO();
			$safe_vin = $db->quote( $db->getEscaped( $vin ), false );
			
			$where[] = "a.vin = ".$safe_vin;
		}																																										
		
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}


   function getList() {
   
		if (empty($this->_data)) {

		 	$db = JFactory::getDBO();
			
			$where		= $this->_buildContentWhere();
						
				## Making the query for showing all the cars in list function
				$sql='SELECT  a.*, b.*, c.*, d.* 
						  FROM #__rdautos_information AS a, #__rdautos_models AS b, 
						  #__rdautos_makes AS c, #__rdautos_categories as d'
						.$where; 
			 
				$db->setQuery($sql, $this->getState('limitstart'), $this->getState('limit' ));
				$this->data = $db->loadObjectList();
		}
		return $this->data;
	}
	
}
?>