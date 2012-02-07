<?php

/**
 * @version $Id: controller.php 1 $
 * @package COM_JBSMIGRATION
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
include_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jbsmigration' . DIRECTORY_SEPARATOR . 'backup.php');

/**
 * JBS Export Migration Controller
 *
 *
 */
class jbsmigrationController extends JController {

    /**
     * Method to display the view
     *
     * @access	public
     */
    function display() {

        $application = JFactory::getApplication();
        $task = JRequest::getWord('task', '', 'get');
        $run = 0;
        $run = JRequest::getInt('run', '', 'get');

        if ($task == 'export' && $run == 1) {
            $export = new JBSExport();
            $result = $export->exportdb();
            if ($result) {
                $application->enqueueMessage('' . JText::_('JBS_EI_SUCCESS') . '');
            } else {
                $application->enqueueMessage('' . JText::_('JBS_EI_FAILURE') . '');
            }
        }
        parent::display();
    }


    function performdb($query) {
        $db = JFactory::getDBO();
        $results = false;
        $db->setQuery($query);
        $db->query();
        if ($db->getErrorNum() != 0) {
            $results = JText::_('JBS_EI_DB_ERROR') . ': ' . $db->getErrorNum() . "<br /><font color=\"red\">";
            $results .= $db->stderr(true);
            $results .= "</font>";
            return $results;
        } else {
            $results = false;
            return $results;
        }
    }


}

// end of class