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

## Check if the file is included in the Joomla Framework
defined('_JEXEC') or die ('No Acces to this file!');

## Adding the AJAX part to the dropdowns & Lightbox functions
$document =& JFactory::getDocument();
$document->addScript( JURI::root(true).'/administrator/components/com_rdautos/helper/ajax.js');
$document->addStyleSheet( 'components/com_rdautos/assets/horizontal-detail-view.css' );

?>

<script type="text/javascript">
	
var ajax = new Array();

function getModelList(sel)
{
	var Code = sel.options[sel.selectedIndex].value;
	document.getElementById('modelid').options.length = 0;	
	if(Code.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = 'index.php?option=com_rdautos&controller=search&task=getModels&format=raw&makeid='+Code;	
		ajax[index].onCompletion = function(){ createModels(index) };	
		ajax[index].runAJAX();		// Execute AJAX function
	}
}

function createModels(index)
{
	var obj = document.getElementById('modelid');
	eval(ajax[index].response);	// Executing the response from Ajax as Javascript code	
}

</script>

<script language="javascript">
 
function getKeyCode(eventObject)

{

if (!eventObject) keyCode = window.event.keyCode; //IE

else keyCode = eventObject.which; //Mozilla

return keyCode;

}

function onlyNumeric(eventObject)

{

keyCode = getKeyCode(eventObject);

if (((keyCode > 31) && (keyCode < 48)) || ((keyCode > 57) && (keyCode < 127)))

{

if (!eventObject) window.event.keyCode = 0; //IE

else eventObject.preventDefault(); //Mozilla

return false;

}

}

</script>

<style type="text/css">
select{
	width:140px;
}
</style>

<h1 class="contentheading">
<?php echo JText::_( 'SEARCH VEHICLE' ); ?> </h1>
<?php echo JText::_( 'SEARCH EXPLANATION' ); ?><br><br>

<?php $link = JRoute::_( 'index.php?option=com_rdautos&view=search&layout=results' ); ?>

<div id="leaseinformation">

<form action = "<?php echo $link; ?>" method="POST" name="Form" id="Form">
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="69%">
        <div id="container">
        	
          	<div id="normal-search">			
                
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr> 
                <td width="33%"><strong><?php echo JText::_( 'SEARCH MAKE' ); ?></strong></td>
                <td width="33%"><strong><?php echo JText::_( 'SEARCH MODEL' ); ?></strong></td>
                <td width="34%"><strong><?php echo JText::_( 'SEARCH CATEGORY' ); ?></strong></td>
              </tr>
              <tr> 
                <td><?php echo $this->lists[make]; ?></td>
                <td><?php echo $this->lists[model]; ?></td>
                <td><?php echo $this->lists[catid]; ?></td>
              </tr>
             <!-- DAN REMOVED THE SECONDARY SEARCH FIELDS 
	 			<tr> 
                <td><strong><?php echo JText::_( 'SEARCH TRANS' ); ?></strong></td>
                <td><strong><?php echo JText::_( 'SEARCH FUEL' ); ?></strong></td>
                <td><strong><?php echo JText::_( 'SPEC DEALER' ); ?></strong></td>
              </tr>
              <tr> 
                <td><?php echo $this->lists[transmission]; ?></td>
                <td><?php echo $this->lists[fuel]; ?></td>
                <td><?php echo $this->lists[dealer]; ?></td>
              </tr>
              <tr> 
			-->
			
                <td><strong><?php echo JText::_( 'SEARCH YEAR' ); ?></strong></td>
                <td><strong><!-- SEARCH STOCK # HEADER  HERE  <?php echo JText::_( 'Search Stock #' ); ?> --></strong></td> 
                <td> <div align="left"><strong><?php echo JText::_( 'SEARCH PRICE' ); ?></strong> 
                    <br>
                  </div></td>
              </tr>
              <tr> 
                <td height="24"><input name="buildFrom" type="text" class="inputbox" id="buildFrom" size="5" maxlength="4"
                    onkeypress="onlyNumeric(arguments[0])" value="<?php echo $this->lists['yearfrom'];?>"/>
-
  <input name="buildTo" type="text" class="inputbox" id="buildTo" size="5" maxlength="4"
                    onkeypress="onlyNumeric(arguments[0])" value="<?php echo $this->lists['yearto'];?>"/>                    </td>
                




<!-- STOCK NUMBER SEARCH HACK GOES HERE-->



<td valign="top"> <!--
	
	<input name="mileagefrom" type="text" class="inputbox" id="mileagefrom" size="7" maxlength="7" 
                value="<?php echo $this->lists['mileagefrom'];?>"/>

  <input name="mileageTo" type="text" class="inputbox" id="mileageTo" size="5" maxlength="7"
                  value="<?php echo $this->lists['mileageto'];?>" />

--></td> 




<!-- STOCK SEARCH HACK ENDS HERE -->





                <td valign="top"> <div align="left">
                    <input name="priceFrom" type="text" class="inputbox" id="priceFrom" size="5" maxlength="7"
                    onkeypress="onlyNumeric(arguments[0])" value="<?php echo $this->lists['pricefrom'];?>" />
                    - 
                    <input name="priceTo" type="text" class="inputbox" id="priceTo" size="5" maxlength="7"
                    onkeypress="onlyNumeric(arguments[0])" value="<?php echo $this->lists['priceto'];?>"/>
                    <br>
                  </div></td>
              </tr>
            </table>
       	  </div>             
        </div>
    </td>
  </tr>  
</table>                             

Stock Number: <input name="vin">

<!-- REMOVE ADITIONAL SEARCH OPTIONS  
	
	
 <?php if ($this->config->show_optionlist != 0) { ?>	
<h1 class="contentheading"><?php echo ' '. JText::_( 'EXTRA SEARCH OPTIONS' ); ?></h1>  

<table width="100%" border="0">
  <tr>
	<td width="52%" align="left" valign="top"><table width="100%" border="0">
	  <tr>
		<td width="50%"><?php echo JText::_( 'AIRBAGS DRIVER' ); ?></td>
		<td width="2%"><div align="center">:</div></td>
		<td width="48%"><?php echo $this->lists[airbag_driver]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'AIRBAGS PASSENGER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[airbag_passengers]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'AIRBAGS LATTERAL' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[airbag_latteral]; ?></td>
	  </tr>
	  <tr>
		<td width="50%"><?php echo JText::_( 'AIRBAGS BACK' ); ?></td>
		<td width="2%"><div align="center">:</div></td>
		<td width="48%"><?php echo $this->lists[airbag_back]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'WINDOW HEATERS' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[windowheaters]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'SUNROOF' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[sunroof]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'DIGI AIR CONDTIONER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[digitalaircondition]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'ELECTRIC WINDOWS' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[electricwindows]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'ELECTRIC MIRRORS' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[electricmirrors]; ?></td>
	  </tr>
	  <tr>
		<td><?php echo JText::_( 'ELECTRIC LOCKS' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[electriclocks]; ?></td>
	  </tr>                  
	  <tr>
		<td><?php echo JText::_( 'HEATER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[heather]; ?></td>
	  </tr>
			  
	</table></td>
	<td width="48%" align="left" valign="top"><table width="100%" border="0">
	  <tr>
		<td><?php echo JText::_( 'CD PLAYER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[cdplayer]; ?></td>
	  </tr>  
	  <tr>
		<td><?php echo JText::_( 'DVD PLAYER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[dvdplayer]; ?></td>
	  </tr>   
	  <tr>
		<td><?php echo JText::_( 'MP3 PLAYER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[mp3player]; ?></td>
	  </tr>                                          			  
	  <tr>
		<td><?php echo JText::_( 'IPOD COMPATIBLE' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[ipodcompatible]; ?></td>
	  </tr>   
	  <tr>
		<td><?php echo JText::_( 'AMFM RADIO' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[amfmradio]; ?></td>
	  </tr>     
	  <tr>
		<td><?php echo JText::_( 'HYDROLIC STEERING' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[hydraulicsteering]; ?></td>
	  </tr>                           
	  <tr>
		<td><?php echo JText::_( 'CRUISE CONTROL' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[cruisecontrol]; ?></td>
	  </tr>    
	  <tr>
		<td><?php echo JText::_( 'ABS BRAKES' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[absbrakes]; ?></td>
	  </tr>   
	  <tr>
		<td><?php echo JText::_( 'FOG LIGHTS' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[foglights]; ?></td>
	  </tr>
	  <tr>
		<td width="51%"><?php echo JText::_( 'LEATHER SEATS' ); ?></td>
		<td width="4%"><div align="center">:</div></td>
		<td width="45%"><?php echo $this->lists[leatherseats]; ?> </td>
	  </tr> 
	  <tr>
		<td><?php echo JText::_( 'SEAT HEATER' ); ?></td>
		<td><div align="center">:</div></td>
		<td><?php echo $this->lists[seatsheater]; ?></td>
	  </tr>                                  
	</table></td>
 </tr>
</table>            

<?php } ?>

-->


<br />
<table width="100%" border="0">
  <tr>
    <td><input type="submit" name="button" id="button" class="button" 
    value="<?php echo JText::_( 'PERFORM SEARCH NOW' ); ?>" /></td>
  </tr>
</table>

</div>


<br /><br />
<input type="hidden" name="layout" value="results" />
</form>
