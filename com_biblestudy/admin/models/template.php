<?php
/**
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 */
// No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

JLoader::register('JBSMBibleStudyHelper', BIBLESTUDY_PATH_ADMIN_HELPERS . '/biblestudy.php');

/**
 * Template model class
 *
 * @package  BibleStudy.Admin
 * @since    7.0.0
 */
class BiblestudyModelTemplate extends JModelAdmin
{
	/**
	 * Store record
	 *
	 * @param   array   $data  ?
	 * @param   string  $tmpl  ?
	 *
	 * @return boolean
	 */
	public function store($data = null, $tmpl = null)
	{
		$row = $this->getTable();
		$input = new JInput;

		// @todo Clean this up
		if (!isset($data))
		{
			$data  = $input->get('post');
		}
		$data['tmpl'] = $input->get('tmpl', '', 'string');

		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		// Make sure the record is valid
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		// Store the table to the database
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Copy Template
	 *
	 * @param   array  $cid  ID of template
	 *
	 * @return boolean
	 */
	public function copy($cid)
	{
		foreach ($cid as $id)
		{
			$tmplCurr = JTable::getInstance('template', 'Table');

			$tmplCurr->load($id);
			$tmplCurr->id = null;
			$tmplCurr->title .= " - copy";

			if (!$tmplCurr->store())
			{
				JFactory::getApplication()->enqueueMessage($tmplCurr->getError(), 'error');

				return false;
			}
		}

		return true;
	}

	/**
	 * Get the form data
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since  7.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_biblestudy.template', 'template', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Get Items
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Load Form Date
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   7.0
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_biblestudy.edit.template.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since       1.6
	 */
	public function getTable($name = 'template', $prefix = 'Table', $options = array())
	{
		return JTable::getInstance($name, $prefix, $options);
	}

	/**
	 * Custom clean the cache of com_biblestudy and biblestudy modules
	 *
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 *
	 * @return  void
	 *
	 * @since    1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_biblestudy');
		parent::cleanCache('mod_biblestudy');
	}

}
