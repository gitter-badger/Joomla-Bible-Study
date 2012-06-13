<?php

/**
 * Controller for Message
 * @package BibleStudy.Admin
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class BiblestudyControllerMessage extends JControllerForm {
    /*
     * NOTE: This is needed to prevent Joomla 1.6's pluralization mechanisim from kicking in
     *
     * @todo  bcc  We should rename this controler to "study" and the list view controller
     * to "studies" so that the pluralization in 1.6 would work properly
     *
     * @since 7.0
     */

    protected $view_list = 'messages';

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Reset Hits
     */
    function resetHits() {
        $msg = null;
        $id = JRequest::getInt('id', 0, 'post');
        $db = JFactory::getDBO();
        $db->setQuery("UPDATE #__bsms_studies SET hits='0' WHERE id = " . $id);
        $reset = $db->query();
        if ($db->getErrorNum() > 0) {
            $error = $db->getErrorMsg();
            $msg = JText::_('JBS_CMN_ERROR_RESETTING_HITS') . ' ' . $error;
            $this->setRedirect('index.php?option=com_biblestudy&view=message&controller=admin&layout=form&cid[]=' . $id, $msg);
        } else {
            $updated = $db->getAffectedRows();
            $msg = JText::_('JBS_CMN_RESET_SUCCESSFUL') . ' ' . $updated . ' ' . JText::_('JBS_CMN_ROWS_RESET');
            $this->setRedirect('index.php?option=com_biblestudy&view=message&controller=message&layout=form&cid[]=' . $id, $msg);
        }
    }

    /**
     * @todo may need to be removed before relices
     */
    function topics() {
        die('test');
    }

}