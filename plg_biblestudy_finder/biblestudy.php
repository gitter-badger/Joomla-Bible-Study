<?php

/**
 * @package     Joomla Bible Study
 * @subpackage  Finder.biblestudy
 *
 * @copyright   Copyright (C) 2007 - 2012 Joomla Bible Study, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('JPATH_BASE') or die;

jimport('joomla.application.component.helper');

// Load the base adapter.
require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

/**
 * Finder adapter for Biblestudy.
 *
 * @package     Biblestudy
 * @subpackage  Finder.BibleStudy
 * @since       2.5
 */
class plgFinderBiblestudy extends FinderIndexerAdapter {

    /**
     * The plugin identifier.
     *
     * @var    string
     * @since  2.5
     */
    protected $context = 'Biblestudy';

    /**
     * The extension name.
     *
     * @var    string
     * @since  2.5
     */
    protected $extension = 'com_biblestudy';

    /**
     * The sublayout to use when rendering the results.
     *
     * @var    string
     * @since  2.5
     */
    protected $layout = 'sermon';

    /**
     * The type of content that the adapter indexes.
     *
     * @var    string
     * @since  2.5
     */
    protected $type_title = 'Studies';

    /**
     * The table name.
     *
     * @var    string
     * @since  2.5
     */
    protected $table = '#__bsms_studies';

    /**
     * The state field
     * @var string
     * @since 2.5
     */
    protected $state_field = 'published';

    /**
     * Constructor
     *
     * @param   object  &$subject  The object to observe
     * @param   array   $config    An array that holds the plugin configuration
     *
     * @since   2.5
     */
    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    /**
     * Method to update the item link information when the item category is
     * changed. This is fired when the item category is published or unpublished
     * from the list view.
     *
     * @param   string   $extension  The extension whose category has been updated.
     * @param   array    $pks        A list of primary key ids of the content that has changed state.
     * @param   integer  $value      The value of the state that the content has been changed to.
     *
     * @return  void
     *
     * @since   2.5
     */
    public function onFinderCategoryChangeState($extension, $pks, $value) {
        //we probably dont' need this
        if ($extension == 'com_biblestudy') {
            //	$this->categoryStateChange($pks, $value);
        }
    }

    /**
     * Method to remove the link information for items that have been deleted.
     *
     * @param   string  $context  The context of the action being performed.
     * @param   JTable  $table    A JTable object containing the record to be deleted
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderAfterDelete($context, $table) {
        if ($context == 'com_biblestudy.message') {
            $id = $table->id;
        } elseif ($context == 'com_finder.index') {
            $id = $table->link_id;
        } else {
            return true;
        }
        // Remove the items.
        return $this->remove($id);
    }

    /**
     * Method to determine if the access level of an item changed.
     *
     * @param   string   $context  The context of the content passed to the plugin.
     * @param   JTable   $row      A JTable object
     * @param   boolean  $isNew    If the content has just been created
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderAfterSave($context, $row, $isNew) {

        if ($context == 'com_biblestudy.message') {
            // Check if the access levels are different

            if (!$isNew && $this->old_access != $row->access) {
                // Process the change.
                $this->itemAccessChange($row);
            }

            // Reindex the item
//			$this->reindex($row->id);
        }
        /*
          // Check for access changes in the category
          if ($context == 'com_categories.category')
          {
          // Check if the access levels are different
          if (!$isNew && $this->old_cataccess != $row->access)
          {
          $this->categoryAccessChange($row);
          }
          }
         */
        return true;
    }

    /**
     * Method to reindex the link information for an item that has been saved.
     * This event is fired before the data is actually saved so we are going
     * to queue the item to be indexed later.
     *
     * @param   string   $context  The context of the content passed to the plugin.
     * @param   JTable   $row     A JTable object
     * @param   boolean  $isNew    If the content is just about to be created
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderBeforeSave($context, $row, $isNew) {
        // We only want to handle sermons here
        if ($context == 'com_biblestudy.message') {
            // Query the database for the old access level if the item isn't new

            if (!$isNew) {
                $this->checkItemAccess($row);
            }
        }
        /*
          // Check for access levels from the category
          if ($context == 'com_categories.category')
          {
          // Query the database for the old access level if the item isn't new
          if (!$isNew)
          {
          $this->checkCategoryAccess($row);
          }
          }
         */
        return true;
    }

    /**
     * Method to update the link information for items that have been changed
     * from outside the edit screen. This is fired when the item is published,
     * unpublished, archived, or unarchived from the list view.
     *
     * @param   string   $context  The context for the content passed to the plugin.
     * @param   array    $pks      A list of primary key ids of the content that has changed state.
     * @param   integer  $value    The value of the state that the content has been changed to.
     *
     * @return  void
     *
     * @since   2.5
     */
    public function onFinderChangeState($context, $pks, $value) {
        // We only want to handle sermons here
        if ($context == 'com_biblestudy.message') {
            $this->itemStateChange($pks, $value);
        }
        // Handle when the plugin is disabled
        if ($context == 'com_plugins.plugin' && $value === 0) {
            $this->pluginDisable($pks);
        }
    }

    /**
     * Method to index an item. The item must be a FinderIndexerResult object.
     *
     * @param   FinderIndexerResult  $item    The item to index as an FinderIndexerResult object.
     * @param   string               $format  The item format
     *
     * @return  void
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    protected function index(FinderIndexerResult $item, $format = 'html') {
        // Check if the extension is enabled
        if (JComponentHelper::isEnabled($this->extension) == false) {
            return;
        }

        // Initialize the item parameters.
        $registry = new JRegistry;
        $registry->loadString($item->params);
        $item->params = $registry;

        $registry = new JRegistry;
        $registry->loadString($item->metadata);
        $item->metadata = $registry;

        // Trigger the onContentPrepare event.
        $item->summary = FinderIndexerHelper::prepareContent($item->studyintro, $item->params);
        $item->body = FinderIndexerHelper::prepareContent($item->studytext, $item->params);
        $item->title = $item->studytitle;
        // Build the necessary route and path information.
        $item->url = $this->getURL($item->id, $this->extension, $this->layout);
        $item->route = BiblestudyHelperRoute::getArticleRoute($item->id);
        $item->path = FinderIndexerHelper::getContentPath($item->route);

        /*
         * Add the meta-data processing instructions based on the newsfeeds
         * configuration parameters.
         */
        // Add the meta-author.
//		$item->metaauthor = $item->metadata->get('author');
        // Handle the link to the meta-data.
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');
//		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
//		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');
//		$item->addInstruction(FinderIndexer::META_CONTEXT, 'created_by_alias');
        // Add the type taxonomy data.
        $item->addTaxonomy('Type', 'Sermon');

        // Add the category taxonomy data.
        //	$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);
        // Add the language taxonomy data.
        $item->addTaxonomy('Language', $item->language);

        // Get content extras.
        FinderIndexerHelper::getContentExtras($item);

        // Index the item.
        FinderIndexer::index($item);
    }

    /**
     * Method to setup the indexer to be run.
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     */
    protected function setup() {
        // Load dependent classes.
        require_once JPATH_SITE . '/components/com_biblestudy/helpers/route.php';
        $params = JComponentHelper::getParams('com_biblestudy');
        $this->access = $params->get('access', 1);

        return true;
    }

    /**
     * Override the change of state query due to errors
     * @param $table
     * @param $state_field
     */
    protected function getStateQuery($sql = null) {
        $db = JFactory::getDBO();
        $sql = is_a($sql, 'JDatabaseQuery') ? $sql : $db->getQuery(true);
        $sql->select('a.id, a.published AS state, a.access');
        $sql->from('#__bsms_studies AS a');
        return $sql;
    }

    /**
     * Method to get the SQL query used to retrieve the list of content items.
     *
     * @param   mixed  $sql  A JDatabaseQuery object or null.
     *
     * @return  JDatabaseQuery  A database object.
     *
     * @since   2.5
     */
    protected function getListQuery($sql = null) {
        $db = JFactory::getDbo();
        // Check if we can use the supplied SQL query.
        $sql = is_a($sql, 'JDatabaseQuery') ? $sql : $db->getQuery(true);
        $sql->select('a.id, a.studytitle AS title, a.alias, a.studyintro AS summary, a.studytext as body');
        $sql->select('a.published, a.studydate AS start_date, a.user_id');
        $sql->select((int) $this->access . ' AS access, a.ordering');
        $sql->select('a.studydate AS publish_start_date');
        //	$sql->select('c.title AS category, c.published AS cat_state, c.access AS cat_access');
        // Handle the alias CASE WHEN portion of the query
        $case_when_item_alias = ' CASE WHEN ';
        $case_when_item_alias .= $sql->charLength('a.alias');
        $case_when_item_alias .= ' THEN ';
        $a_id = $sql->castAsChar('a.id');
        $case_when_item_alias .= $sql->concatenate(array($a_id, 'a.alias'), ':');
        $case_when_item_alias .= ' ELSE ';
        $case_when_item_alias .= $a_id . ' END as studytitle';
        $sql->select($case_when_item_alias);
        /*
          $case_when_category_alias = ' CASE WHEN ';
          $case_when_category_alias .= $sql->charLength('c.alias');
          $case_when_category_alias .= ' THEN ';
          $c_id = $sql->castAsChar('c.id');
          $case_when_category_alias .= $sql->concatenate(array($c_id, 'c.alias'), ':');
          $case_when_category_alias .= ' ELSE ';
          $case_when_category_alias .= $c_id.' END as catslug';
          $sql->select($case_when_category_alias);
         */
        $sql->select('u.name AS author');
        $sql->from('#__bsms_studies AS a');
//		$sql->join('LEFT', '#__categories AS c ON c.id = a.catid');
        $sql->join('LEFT', '#__users AS u ON u.id = a.user_id');

        return $sql;
    }

    /**
     * Method to get the query clause for getting items to update by time.
     *
     * @param   string  $time  The modified timestamp.
     *
     * @return  JDatabaseQuery  A database object.
     *
     * @since   2.5
     */
    protected function getUpdateQueryByTime($time) {
        // Build an SQL query based on the modified time.
        // We don't have a modified time, so we just give the query back unchanged.
        $sql = $this->db->getQuery(true);

        return $sql;
    }

}