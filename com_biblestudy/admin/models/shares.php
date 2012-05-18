<?php

/**
 * @version     $Id: share.php 2025 2011-08-28 04:08:06Z genu $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 */
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

abstract class modelClass extends JModelList {

}

class BiblestudyModelShares extends modelClass {

    /**
     * teacherlist data array
     *
     * @var array
     */
   

    function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'share.id',
                'published', 'share.published',
                'name', 'share.name,',
                'ordering', 'share.ordering',
                'access', 'share.access',
            );
        }
        parent::__construct($config);
    }

  
    /**
     * @since   7.0
     */
    protected function populateState($ordering = null, $direction = null) {
        //// Adjust the context to support modal layouts.
        if ($layout = JRequest::getVar('layout')) {
            $this->context .= '.' . $layout;
        }

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        parent::populateState('share.name', 'ASC');
    }

    /**
     *
     * @since   7.0
     */
    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'share.id, share.name, share.params, share.published,' .
                        'share.ordering, share.access'));

        $query->from('#__bsms_share AS share');

        // Join over the asset groups.
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = share.access');

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('share.published = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(share.published = 0 OR share.published = 1)');
        }

        //Add the list ordering clause
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        $query->order($db->getEscaped($orderCol . ' ' . $orderDirn));

        return $query;
    }

}