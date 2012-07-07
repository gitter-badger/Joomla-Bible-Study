<?php

/**
 * @package BibleStudy.Site
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package BibleStudy.Site
 * @since 7.0.0
 */
class biblestudyController extends JController {

    /**
     *
     * @param type $cachable
     * @param type $urlparams
     * @return \biblestudyController
     */
    public function display($cachable = false, $urlparams = false) {
        $cachable = true;
        //clear the user state

        JHtml::_('behavior.caption');

        // Set the default view name and format from the Request.
        // Note we are using a_id to avoid collisions with the router and the return page.
        // Frontend is a bit messier than the backend.
        $vName = JRequest::getCmd('view', 'landingpage');
        JRequest::setVar('view', $vName);
        if ($vName == 'popup')
            $cachable = false;

        $user = JFactory::getUser();

        if ($user->get('id') ||
                ($_SERVER['REQUEST_METHOD'] == 'POST' &&
                ($vName == 'archive' ))) {
            $cachable = false;
        }

        //attempt to change mysql for error in large select
        $db = JFactory::getDBO();
        $db->setQuery('SET SQL_BIG_SELECTS=1');
        $db->query();
        $t = JRequest::getInt('t', 'get');
        if (!$t) {
            $t = 1;
        }
        JRequest::setVar('t', $t, 'get');

        $safeurlparams = array('id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'INT', 'limitstart' => 'INT',
            'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD');

        parent::display($cachable, $safeurlparams);

        return $this;
    }

    /**
     *
     * @return type
     */
    function comment() {

        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');

        $model = $this->getModel('sermon');
        $menu = JSite::getMenu();
        $item = $menu->getActive();
        $params = $mainframe->getPageParameters();
        $t = $params->get('t');
        if (!$t) {
            $t = 1;
        }
        JRequest::setVar('t', $t, 'get');

        // Convert parameter fields to objects.
        $registry = new JRegistry;
        $registry->loadJSON($model->_template[0]->params);
        $params = $registry;
        $cap = 1;

        if ($params->get('use_captcha') > 0) {
            //Begin reCaptcha
            require_once(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR . 'recaptchalib.php');
            $privatekey = $params->get('private_key');
            $challenge = JRequest::getVar('recaptcha_challenge_field', '', 'post');
            $response = JRequest::getVar('recaptcha_response_field', '', 'post');
            $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $challenge, $response);
            if (!$resp->is_valid) {
                // What happens when the CAPTCHA was entered incorrectly
                $mess = JText::_('JBS_STY_INCORRECT_KEY');
                echo "<script language='javascript' type='text/javascript'>alert('" . $mess . "')</script>";
                echo "<script language='javascript' type='text/javascript'>window.history.back()</script>";
                return;
                $cap = 0;
            } else {
                $cap = 1;
            }
        }

        if ($cap == 1) {
            if (JRequest::getInt('published', '', 'post') == 0) {
                $msg = JText::_('JBS_STY_COMMENT_UNPUBLISHED');
            } else {
                $msg = JText::_('JBS_STY_COMMENT_SUBMITTED');
            }
            if (!$model->storecomment()) {
                $msg = JText::_('JBS_STY_ERROR_SUBMITTING_COMMENT');
            }

            if ($params->get('email_comments') > 0) {
                $EmailResult = $this->commentsEmail($params);
            }
            $study_detail_id = JRequest::getVar('study_detail_id', 0, 'POST', 'INT');

            $mainframe->redirect('index.php?option=com_biblestudy&id=' . $study_detail_id . '&view=sermon&t=' . $t, $msg);
        } // End of $cap
    }

    /**
     *
     * @param type $params
     */
    function commentsEmail($params) {
        $mainframe = JFactory::getApplication();
        $menuitemid = JRequest::getInt('Itemid');
        if ($menuitemid) {
            $menu = JSite::getMenu();
            $menuparams = $menu->getParams($menuitemid);
        }
        $comment_author = JRequest::getVar('full_name', 'Anonymous', 'POST', 'WORD');
        $comment_study_id = JRequest::getVar('study_detail_id', 0, 'POST', 'INT');
        $comment_email = JRequest::getVar('user_email', 'No Email', 'POST', 'WORD');
        $comment_text = JRequest::getVar('comment_text', 'None', 'POST', 'WORD');
        $comment_published = JRequest::getVar('published', 0, 'POST', 'INT');
        $comment_date = JRequest::getVar('comment_date', 0, 'POST', 'INT');
        $comment_date = date('Y-m-d H:i:s');
        $config = JFactory::getConfig();
        $comment_abspath = JPATH_SITE;
        $comment_mailfrom = $config->getValue('config.mailfrom');
        $comment_fromname = $config->getValue('config.fromname');
        ;
        $comment_livesite = JURI::root();
        $db = JFactory::getDBO();
        $query = 'SELECT id, studytitle, studydate FROM #__bsms_studies WHERE id = ' . $comment_study_id;
        $db->setQuery($query);
        $comment_details = $db->loadObject();
        $comment_title = $comment_details->studytitle;
        $comment_study_date = $comment_details->studydate;
        $mail = JFactory::getMailer();
        $ToEmail = $params->get('recipient', '');
        $Subject = $params->get('subject', 'Comments');
        $FromName = $params->get('fromname', $comment_fromname);
        if (empty($ToEmail))
            $ToEmail = $comment_mailfrom;
        $Body = $comment_author . ' ' . JText::_('JBS_STY_HAS_ENTERED_COMMENT') . ': ' . $comment_title . ' - ' . $comment_study_date . ' ' . JText::_('JBS_STY_ON') . ': ' . $comment_date;
        if ($comment_published > 0) {
            $Body = $Body . ' ' . JText::_('JBS_STY_COMMENT_PUBLISHED');
        } else {
            $Body = $Body . ' ' . JText::_('JBS_STY_COMMENT_NOT_PUBLISHED');
        }
        $Body = $Body . ' ' . JText::_('JBS_STY_REVIEW_COMMENTS_LOGIN') . ': ' . $comment_livesite;
        $mail->addRecipient($ToEmail);
        $mail->setSubject($Subject . ' ' . $comment_livesite);
        $mail->setBody($Body);
        $mail->Send();
    }

    /**
     *
     */
    function download() {
        $abspath = JPATH_SITE;
        require_once($abspath . DIRECTORY_SEPARATOR . 'components/com_biblestudy/lib/biblestudy.download.class.php');
        $task = JRequest::getVar('task');
        if ($task == 'download') {
            $mid = JRequest::getInt('mid', '0');
            $downloader = new Dump_File();
            $downloader->download($mid);

            die;
        }
    }

    /**
     *
     * @return type
     */
    function avplayer() {
        $task = JRequest::getVar('task');
        if ($task == 'avplayer') {
            $mediacode = JRequest::getVar('code');
            $this->mediaCode = $mediacode;
            echo $mediacode;
            return;
        }
    }

    /**
     *
     */
    function playHit() {
        require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'biblestudy.media.class.php');
        $getMedia = new jbsMedia();
        $getMedia->hitPlay(JRequest::getInt('id'));
    }

    /**
     * @desc: This function is supposed to generate the Media Player that is requested via AJAX
     * from the sermons view "default.php". It has not been implemented yet, so its not used.
     * @return unknown_type
     */
    function inlinePlayer() {
        echo('{m4vremote}http://www.livingwatersweb.com/video/John_14_15-31.m4v{/m4vremote}');
    }

}