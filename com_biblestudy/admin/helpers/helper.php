<?php defined('_JEXEC') or die('Restriced Access');
require_once (JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
/**
 * @author Joomla Bible Study
 * @copyright 2009
 * @desc This is a general helper file. In the future we should bring lots of small functions into this file
 */
//This function is designed to extract an Itemid for the component if none exists in the GET variable. Mainly to address problems with 
// All Videos Reloaded
 function getItemidLink(){
  $itemid = null;
  
  $admin_params = getAdminsettings();
  $itemidlinktype = $admin_params->get('itemidlinktype', 1);
  $itemidlinkview = $admin_params->get('itemidlinkview', 'studieslist');
  $itemidlinknumber = $admin_params->get('itemidlinknumber',1);
  $menus  = &JApplication::getMenu('site', array()); 
  
  //Get the correct componentid from #__menu
  $db	= & JFactory::getDBO();
  
  $jversion = JOOMLA_VERSION;
    if ($jversion == '5') 
        {
                $query = "SELECT id, componentid, link, params FROM #__menu WHERE link LIKE '%com_biblestudy%';";
                $db->setQuery($query);
                $db->query();
                $component = $db->loadObject();
                $items  = $menus->getItems('componentid', $component->componentid);
        }
        else {
                $query = "SELECT id, component_id, link, params FROM #__menu WHERE link LIKE '%com_biblestudy%';";
                $db->setQuery($query);
                $db->query();
                $component = $db->loadObject();
                $items  = $menus->getItems('component_id', $component->componentid);
            }
  
   foreach ($items as $menu) {
    if (@$menu->query['view'] == $itemidlinkview) {
     $itemid = $menu->id; 
     break;
    }
   }
   if (!isset($itemid) && count($items)) {
    $itemid = $items[0]->id; 
   }
 
  switch ($itemidlinktype)
   			{
			   	case 0:
			   	$itemid = '';
			   	break;
			   	
			   	case 1:
   				//Look for an itemid in the com_menu table from the /helpers/helper.php file
   				$itemid = $itemid; //dump($itemid, 'itemid: ');
   				break;
   				
			   	case 2:
   				//Add in an Itemid from the parameter
   				$itemid = $itemidlinknumber;
   				break;
   			} 
   			
  return($itemid);
 }
function getAdminsettings()
	{
			$db =& JFactory::getDBO();
		//	$query = 'SELECT *'
		//	. ' FROM #__bsms_admin'
		//	. ' WHERE id = 1';
		//	$adminsettings = $db->loadAssoc();
		//	$admin_params = null;
		//	$admin_params = new JParameter($adminsettings['params']); 
		
	//	ToDo: A better way to access parameters. maybe use the model/table from admin?
	//	jimport( 'joomla.application.component.view' );
	//	jimport( 'joomla.application.component.model' );
	
		$db->setQuery ("SELECT params FROM #__bsms_admin WHERE id = 1");
		$db->query();
		$compat = $db->loadObject();
		$admin_params = new JParameter($compat->params);
					
		return $admin_params;
	}

function getTooltip($rowid, $row, $params, $admin_params, $template)
	{
		$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
		include_once($path1.'elements.php');
		
		//Tom added the below because tooltip wasn't working as of 6.1.1
		$toolTipArray = array('className' => 'custom', 'showDelay'=>'500', 
		'hideDelay'=>'500', 'fixed'=>true,
		'onShow'=>"function(tip) {tip.effect('opacity', 
 		{duration: 500, wait: false}).start(0,1)}", 
		'onHide'=>"function(tip) {tip.effect('opacity', 
		{duration: 500, wait: false}).start(1,0)}");
		JHTML::_('behavior.tooltip', '.hasTip', $toolTipArray); 

		//$toolTipArray = array('className'=>'custom');
		//JHTML::_('behavior.mootools');
		//JHTML::_('behavior.tooltip', '.zoomTip', $toolTipArray);

        $linktext = '<span class="zoomTip" title="<strong>'.$params->get('tip_title').'  :: ';
       	$tip1 = getElementid($params->get('tip_item1'), $row, $params, $admin_params, $template);  
		$tip2 = getElementid($params->get('tip_item2'), $row, $params, $admin_params, $template);
		$tip3 = getElementid($params->get('tip_item3'), $row, $params, $admin_params, $template);
		$tip4 = getElementid($params->get('tip_item4'), $row, $params, $admin_params, $template);
		$tip5 = getElementid($params->get('tip_item5'), $row, $params, $admin_params, $template);
		$test = $params->get('tip_item1');
		//dump ($test, 'tip1: ');
		$linktext .= '<strong>'.$params->get('tip_item1_title').'</strong>: '.$tip1->element.'<br />';
		$linktext .= '<strong>'.$params->get('tip_item2_title').'</strong>: '.$tip2->element.'<br /><br />';
		$linktext .= '<strong>'.$params->get('tip_item3_title').'</strong>: '.$tip3->element.'<br />';
		$linktext .= '<strong>'.$params->get('tip_item4_title').'</strong>: '.$tip4->element.'<br />';
		$linktext .= '<strong>'.$params->get('tip_item5_title').'</strong>: '.$tip5->element;
 		$linktext .= '">';
	return $linktext;	
	}
	
	function getShowhide ()
	{
		$showhide = '
function HideContent(d) {
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
document.getElementById(d).style.display = "block";
}
function ReverseDisplay(d) {
if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
else { document.getElementById(d).style.display = "none"; }
}
';

		return $showhide;
	}
?>