<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2008 Fritz Elfert. All rights reserved.
 * @license		GNU/GPLv2
 * @uses			This file was edited by Tom Fuller from JoomlaBibleStudy to accomodate problems AVR was having with com_biblestudy. Version 2
 * @desc			Version 1 added a method to create an Itemid when one was not passed to AVR
 * 				Version 2 pulls the id of the media file being played from the $divid variable then increments the hit counter for plays
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * Popup View
 */
class AvReloadedViewPopup extends JView
{
	/**
	 * display method of the Popup view
	 * @return void
	 **/
	function display($tpl = null) {
		$app =& JFactory::getApplication();
		$doc =& JFactory::getDocument();
		$doc->addStyleDeclaration('html, body {background-color:#000;border:0px;padding:0px;margin:0px;width:100%;height:100%;}');
		$video = $this->_getVideo($app, $doc);
		$this->assignRef('video', $video);
		parent::display($tpl);
	}

	function _getVideo(&$app, &$doc) {
		$ret = '';
		$code = '';
		 
		//JoomlaBibleStudy added this to handle problems with not finding itemid JoomlaBibleStudy 6.1.0
		jimport('joomla.filesystem.file');
		$bsms = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'biblestudy.php';
		$biblestudyinstalled = JFile::getName($bsms);
		if (!$biblestudyinstalled)
		{
			$itemid = JRequest::getInt('Itemid', -1);
		}
		else
		{
			 
			$itemid = JRequest::getInt('Itemid');
			if (!$itemid)
			{
				$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
				include_once($path1.'helper.php');
				$admin_params = getAdminsettings();
				$itemid = getItemidLink();

			}
		}
		//End of JoomlaBibleStudy entry 6.1.0
		 
		$divid = JRequest::getString('divid', null);

		//code added JoomlaBibleStudy 6.2.0
		require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.media.class.php');
		$media = new jbsMedia();
		$play = $media->hitPlay($divid);
		//code to determine user browser and add code to force IE8 into IE7 mode
		if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {
			header("X-UA-Compatible: IE=7");
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {
			header("X-UA-Compatible: IE=EmulateIE7");
		}
		//end code added JoomlaBibleStudy 6.2.0

		if (($itemid >= 0) && ($divid != null)) {
			$db =& JFactory::getDBO();
			$query = "SELECT code FROM #__avr_popup WHERE id = ".
			$itemid." AND divid ='".$divid."'";
			$db->setQuery($query);
			$db->query();
			$data =& $db->loadObject();
			// Cleanup record older than 1 day
			// TODO: Investigate caching problem
			if (empty($data) || empty($data->code)) {
				$ret = '<span style="color:red"><b>'.
				JText::_('AVR_ERR_POPUP_DATABASE').'</b></span>';
			} else {
				$cfg =& JFactory::getConfig();
				$js_swf = 'swfobject.js';
				$js_avr = 'avreloaded.js';
				$js_wmv = 'wmvplayer.js';
				$debug = $cfg->getValue('config.debug');
				$konqcheck = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "konqueror");
				// If global debugging is enabled or the browser is konqueror,
				// we use uncompressed JavaScript
				if ($debug || $konqcheck) {
					$js_swf = 'swfobject-uncompressed.js';
					$js_avr = 'avreloaded-uncompressed.js';
					$js_wmv = 'wmvplayer-uncompressed.js';
				}
				if (is_int(strpos($data->code, 'swfobject.'))) {
					JHTML::script($js_swf, 'plugins/content/avreloaded/');
				}
				if (is_int(strpos($data->code, 'jeroenwijering.'))) {
					JHTML::script('silverlight.js', 'plugins/content/avreloaded/');
					JHTML::script($js_wmv, 'plugins/content/avreloaded/');
				}
				JHTML::script($js_avr, 'plugins/content/avreloaded/');
				$ret = $data->code;
			}
		}
		return $ret;
	}
}
