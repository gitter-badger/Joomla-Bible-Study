<?php

/**
 * @version     $Id: view.html.php 1466 2011-01-31 23:13:03Z bcordis $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();
require_once (JPATH_ADMINISTRATOR  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
jimport('joomla.application.component.view');

class biblestudyViewtemplateslist extends JView {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->types = $this->get('Types');

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('JBS_CMN_TEMPLATES'), 'templates.png');
        JToolBarHelper::addNew('templateedit.add');
        JToolBarHelper::editList('templateedit.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publishList('templateslist.publish');
        JToolBarHelper::unpublishList('templateslist.unpublish');
        JToolBarHelper::divider();
        if($this->state->get('filter.state') == -2)
            JToolBarHelper::deleteList('', 'templateslist.delete','JTOOLBAR_EMPTY_TRASH');
        else
            JToolBarHelper::trash('templateslist.trash');
    }

}
?>