<?php
/**
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

/**
 * This is for Retrieving Admin and Template db
 *
 * @package  BibleStudy.Admin
 * @since    7.0.0
 *
 * @property $template->params JRegistry
 */
class JBSMParams
{

	public static $extension = 'com_biblestudy';

	/**
	 * Gets the settings from Admin
	 *
	 * @return object Return Admin table
	 */
	public static function getAdmin()
	{
		$db     = JFactory::getDBO();
		$tables = $db->getTableList();
		$prefix = $db->getPrefix();
		$admin  = null;
		$adminTable = 0;

		// Check to see if version is newer then 7.0.2
		foreach ($tables as $table)
		{
			$subTable    = $prefix . 'bsms_admin';
			$adminTable = substr_count($table, $subTable);

		}
		if ($adminTable)
		{
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__bsms_admin')
				->where($db->qn('id') . ' = ' . (int) 1);
			$db->setQuery($query);
			$admin    = $db->loadObject();
			$registry = new JRegistry;
			$registry->loadString($admin->params);
			$admin->params = $registry;

			// Add the current user id
			$user           = JFactory::getUser();
			$admin->user_id = $user->id;
		}

		return $admin;
	}

	/**
	 * Get Template Params
	 *
	 * @return object Return active template info
	 */
	public static function getTemplateparams()
	{
		$db = JFactory::getDbo();
		$pk = JFactory::getApplication()->input->getInt('t', '1');

		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__bsms_templates')
			->where('published = ' . (int) 1)
			->where('id = ' . (int) $pk);
		$db->setQuery($query);
		$template = $db->loadObject();

		if ($template)
		{
			$registry = new JRegistry;
			$registry->loadString($template->params);
			$template->params = $registry;
		}

		return $template;
	}

}
