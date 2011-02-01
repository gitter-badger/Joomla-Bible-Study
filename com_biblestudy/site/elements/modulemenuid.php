<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementmodulemenuid extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'modulemenuid';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();


		$query = "SELECT m.id, CONCAT(m.id,' - ',m.title) AS text"
		. "\n FROM #__bsms_templates AS m"
		. "\n ORDER BY m.title ASC"
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList( );
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('JBS_CMN_CHOOSE_IMAGE_TEMPLATE').' -', 'id', 'text'));
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'text', $value, $control_name.$name );
	}
}