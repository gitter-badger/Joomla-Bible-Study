<?php

/**
 * @author Tom Fuller
 * @copyright 2011
 */
defined( '_JEXEC' ) or die('Restricted access');

class jbs611Install{
    
function upgrade611()
{
$query = "CREATE TABLE IF NOT EXISTS `#__bsms_locations` (
					`id` INT NOT NULL AUTO_INCREMENT,
					`location_text` VARCHAR(250) NULL,
					`published` TINYINT(1) NOT NULL DEFAULT '1',
					PRIMARY KEY (`id`) ) TYPE=MyISAM CHARACTER SET `utf8`";
            $msg = $this->performdb($query);
              
			
            $query = "ALTER TABLE #__bsms_studies ADD COLUMN show_level varchar(100) NOT NULL default '0' AFTER user_name";
			$msg = $this->performdb($query);
            
            $query = "ALTER TABLE #__bsms_studies ADD COLUMN location_id INT(3) NULL AFTER show_level";
            $msg = $this->performdb($query);
$application = JFactory::getApplication();            
$application->enqueueMessage( ''. JText::_('Upgrading to 6.0.11a') .'' ) ;
        return $msg;
}
function performdb($query)
    {
        $db = JFactory::getDBO();
        $results = false;
        $db->setQuery($query);
        $db->query();
        
		if ($db->getErrorNum() != 0)
			{
				$results = false; return $results;
			}
			else
			{
				$results = true; return $results;
            }
    }
    
}
?>