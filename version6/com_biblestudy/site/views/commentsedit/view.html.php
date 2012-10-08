<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class biblestudyViewcommentsedit extends JView
{
	
	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication();
		$params =& $mainframe->getPageParameters();
		$this->assignRef('params', $params);
		$commentsedit		=& $this->get('Data');
		$isNew		= ($commentsedit->id < 1);
		//$editor =& JFactory::getEditor();
		//this->assignRef( 'editor', $editor );
		$lists = array();
		/*
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Edit Comment' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
			// initialise new record
			//$studiesedit->teacher_id 	= JRequest::getVar( 'teacher_id', 0, 'post', 'int' );
			
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'Cancel', 'Close' );
		}
		jimport( 'joomla.i18n.help' );
		JToolBarHelper::help( 'biblestudy.commentsedit', true );
		*/
		// build the html select list for ordering
		
		$database	= & JFactory::getDBO();
			
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $commentsedit->published);
		
		
		$query = "SELECT id AS value, CONCAT(studytitle,' - ', date_format(studydate, '%a %b %e %Y'), ' - ', studynumber) AS text FROM #__bsms_studies WHERE published = 1 ORDER BY studydate DESC";
		$database->setQuery($query);
		//$studies = $database->loadObjectList();
		$studies[] = JHTML::_('select.option', '0', '- '. JText::_( 'Select a Study' ) .' -' );
		$studies = array_merge($studies,$database->loadObjectList() );
		$lists['studies'] = JHTML::_('select.genericlist', $studies, 'study_id', 'class="inputbox" size="1" ', 'value', 'text', $commentsedit->study_id);
		

	
	
		$this->assignRef('lists',		$lists);
		$this->assignRef('commentsedit',		$commentsedit);
		parent::display($tpl);
	}
}
?>