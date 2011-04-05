<?php

/**
 * @author Tom Fuller - Joomla Bible Study
 * @copyright 2011
 */

defined( '_JEXEC' ) or die('Restricted access');


require_once ( JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'html'.DS.'parameter.php' );

class jbs700Install{

        function upgrade700()
    {
        $db = JFactory::getDBO();
       $messages = array();
        
        //Alter some tables
        $msg = '';
                
        $query = "ALTER TABLE #__bsms_mediafiles ADD COLUMN `player` int(2) NULL";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "ALTER TABLE #__bsms_mediafiles ADD COLUMN `popup` int(2) NULL";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "DROP TABLE #__bsms_timeset";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "CREATE TABLE IF NOT EXISTS `#__bsms_timeset` (
                    `timeset` VARCHAR(14) ,
                    `backup` VARCHAR(14) ,
                    KEY `timeset` (`timeset`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
         $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "INSERT INTO `#__bsms_timeset` SET `timeset`='1281646339', `backup` = '1281646339'";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "INSERT  INTO `#__bsms_media` VALUES (15,'You Tube','You Tube','','youtube24.png','You Tube Video', 1)";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = "ALTER TABLE `#__bsms_admin` ADD COLUMN `drop_tables` int(3) NULL default '0' AFTER `showhide`";
        $msg = $this->performdb($query);
        
        
        $query = "UPDATE `#__bsms_admin` SET `drop_tables` = 0 WHERE id = 1";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
       
       $query = "ALTER Table `#__bsms_order` MODIFY value VARCHAR(50)";
       $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
       $query = "ALTER Table `#__bsms_mediafiles` MODIFY podcast_id VARCHAR(50)";
       $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }     
       $query = "UPDATE `#__bsms_order` SET text = 'JBS_CMN_ASCENDING' WHERE id = 1";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
             
         $query = "UPDATE `#__bsms_order` SET text = 'JBS_CMN_DESCENDING' WHERE id = 2";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        $query = "INSERT INTO #__bsms_version SET `version` = '7.0.0', `installdate`='2011-03-12', `build`='700', `versionname`='1Kings', `versiondate`='2011-03-15'";
        $msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
        $query = 'SELECT `id`, `params` FROM #__bsms_mediafiles';
        $db->setQuery($query);
        $db->query();
        $results = $db->loadObjectList();
if ($results)
{        
    //Now run through all the results, pull out the media player and the popup type and move them to their respective db fields
    foreach ($results AS $result)
    {
        $params = new JParameter($result->params);
        $player = $params->get('player');
        $popup = $params->get('internal_popup');
        if ($player)
        {
            if ($player == 2)
            {
                $player = 3;
            }
            $query = "UPDATE #__bsms_mediafiles SET `player` = '$player' WHERE `id` = $result->id LIMIT 1";
            $msg = $this->performdb($query);
            
        }
        if ($popup)
        {
            $query = "UPDATE #__bsms_mediafiles SET `popup` = '$popup' WHERE `id` = $result->id LIMIT 1";
            $msg = $this->performdb($query);
            
        }
        //Update the params to json
        $params = new JParameter($result->params);
            $params2	= $params->toObject();
            $params2 = json_encode($params2);
            
        
        $query = "UPDATE #__bsms_mediafiles SET `params` = '$params2' WHERE `id` = $result->id LIMIT 1";
        $msg = $this->performdb($query);
        
        
    }
}
 $query = "ALTER TABLE #__bsms_admin ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }       
  $query = "ALTER TABLE #__bsms_comments ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 
  $query = "ALTER TABLE #__bsms_folders ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
 $query = "ALTER TABLE #__bsms_locations ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }  
$query = "ALTER TABLE #__bsms_media ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 
$query = "ALTER TABLE #__bsms_mediafiles ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_message_type ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }

$query = "ALTER TABLE #__bsms_mimetype ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_podcast ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_series ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_servers ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_share ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_studies ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 
 
 $query = "ALTER TABLE #__bsms_teachers ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_templates ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

$query = "ALTER TABLE #__bsms_topics ADD COLUMN asset_id INT(10) NOT NULL, ADD COLUMN access INT(10) NOT NULL";
 $msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             } 

//Get all the study records

$query = "UPDATE #__bsms_studies SET `access` = '1' WHERE `show_level` = '0'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }

$query = "UPDATE #__bsms_studies SET `access` = '2' WHERE `show_level` = '18'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }

$query = "UPDATE #__bsms_studies SET `access` = '2' WHERE `show_level` = '19'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }

$query = "UPDATE #__bsms_studies SET `access` = '2' WHERE `show_level` = '20'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
$query = "UPDATE #__bsms_studies SET `access` = '3' WHERE `show_level` = '22'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
$query = "UPDATE #__bsms_studies SET `access` = '3' WHERE `show_level` = '23'";
$msg = $this->performdb($query);
 if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }
        
$query = "UPDATE #__bsms_studies SET `access` = '3' WHERE `show_level` = '24'";
$msg = $this->performdb($query);
  if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }       

$query = 'DROP TABLE #__bsms_books';
$msg = $this->performdb($query);
              if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }   
             
$query = "CREATE TABLE IF NOT EXISTS `#__bsms_books` (
					  `id` int(3) NOT NULL AUTO_INCREMENT,
					  `bookname` varchar(250) DEFAULT NULL,
					  `booknumber` int(5) DEFAULT NULL,
					  `published` tinyint(1) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
$msg = $this->performdb($query);
              if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }       
             
$query = "INSERT INTO `#__bsms_books` (`id`, `bookname`, `booknumber`, `published`) VALUES 
		 (1, 'JBS_BBK_GENESIS', 101, 1),
		 (2, 'JBS_BBK_EXODUS', 102, 1),
		 (3, 'JBS_BBK_LEVITICUS', 103, 1),
		 (4, 'JBS_BBK_NUMBERS', 104, 1),
		 (5, 'JBS_BBK_DEUTERONOMY', 105, 1) ,
		 (6, 'JBS_BBK_JOSHUA', 106, 1) ,
		 (7, 'JBS_BBK_JUDGES', 107, 1) ,
		 (8, 'JBS_BBK_RUTH', 108, 1) ,
		 (9, 'JBS_BBK_1SAMUEL', 109, 1) ,
		 (10, 'JBS_BBK_2SAMUEL', 110, 1) ,
		 (11, 'JBS_BBK_1KINGS', 111, 1) ,
		 (12, 'JBS_BBK_2KINGS', 112, 1) ,
		 (13, 'JBS_BBK_1CHRONICLES', 113, 1) ,
		 (14, 'JBS_BBK_2CHRONICLES', 114, 1) ,
		 (15, 'JBS_BBK_EZRA', 115, 1) ,
		 (16, 'JBS_BBK_NEHEMIAH', 116, 1) ,
		 (17, 'JBS_BBK_ESTHER', 117, 1) ,
		 (18, 'JBS_BBK_JOB', 118, 1) ,
		 (19, 'JBS_BBK_PSALM', 119, 1) ,
		 (20, 'JBS_BBK_PROVERBS', 120, 1) ,
		 (21, 'JBS_BBK_ECCLESIASTES', 121, 1) ,
		 (22, 'JBS_BBK_SONG_OF_SOLOMON', 122, 1) ,
		 (23, 'JBS_BBK_ISAIAH', 123, 1) ,
		 (24, 'JBS_BBK_JEREMIAH', 124, 1) ,
		 (25, 'JBS_BBK_LAMENTATIONS', 125, 1) ,
		 (26, 'JBS_BBK_EZEKIEL', 126, 1) ,
		 (27, 'JBS_BBK_DANIEL', 127, 1) ,
		 (28, 'JBS_BBK_HOSEA', 128, 1) ,
		 (29, 'JBS_BBK_JOEL', 129, 1) ,
		 (30, 'JBS_BBK_AMOS', 130, 1) ,
		 (31, 'JBS_BBK_OBADIAH', 131, 1) ,
		 (32, 'JBS_BBK_JONAH', 132, 1) ,
		 (33, 'JBS_BBK_MICAH', 133, 1) ,
		 (34, 'JBS_BBK_NAHUM', 134, 1) ,
		 (35, 'JBS_BBK_HABAKKUK', 135, 1) ,
		 (36, 'JBS_BBK_ZEPHANIAH', 136, 1),
		 (37, 'JBS_BBK_HAGGAI', 137, 1),
		 (38, 'JBS_BBK_ZECHARIAH', 138, 1),
		 (39, 'JBS_BBK_MALACHI', 139, 1),
		 (40, 'JBS_BBK_MATTHEW', 140, 1),
		 (41, 'JBS_BBK_MARK', 141, 1),
		 (42, 'JBS_BBK_LUKE', 142, 1),
		 (43, 'JBS_BBK_JOHN', 143, 1),
		 (44, 'JBS_BBK_ACTS', 144, 1),
		 (45, 'JBS_BBK_ROMANS', 145, 1),
		 (46, 'JBS_BBK_1CORINTHIANS', 146, 1),
		 (47, 'JBS_BBK_2CORINTHIANS', 147, 1),
		 (48, 'JBS_BBK_GALATIANS', 148, 1),
		 (49, 'JBS_BBK_EPHESIANS', 149, 1),
		 (50, 'JBS_BBK_PHILIPPIANS', 150, 1),
		 (51, 'JBS_BBK_COLOSSIANS', 151, 1),
		 (52, 'JBS_BBK_1THESSALONIANS', 152, 1),
		 (53, 'JBS_BBK_2THESSALONIANS', 153, 1),
		 (54, 'JBS_BBK_1TIMOTHY', 154, 1),
		 (55, 'JBS_BBK_2TIMOTHY', 155, 1),
		 (56, 'JBS_BBK_TITUS', 156, 1),
		 (57, 'JBS_BBK_PHILEMON', 157, 1),
		 (58, 'JBS_BBK_HEBREWS', 158, 1),
		 (59, 'JBS_BBK_JAMES', 159, 1),
		 (60, 'JBS_BBK_1PETER', 160, 1),
		 (61, 'JBS_BBK_2PETER', 161, 1),
		 (62, 'JBS_BBK_1JOHN', 162, 1),
		 (63, 'JBS_BBK_2JOHN', 163, 1),
		 (64, 'JBS_BBK_3JOHN', 164, 1),
		 (65, 'JBS_BBK_JUDE', 165, 1),
		 (66, 'JBS_BBK_REVELATION', 166, 1),
		 (67, 'JBS_BBK_TOBIT', 167, 1),
		 (68, 'JBS_BBK_JUDITH', 168, 1),
		 (69, 'JBS_BBK_1MACCABEES', 169, 1),
		 (70, 'JBS_BBK_2MACCABEES', 170, 1),
		 (71, 'JBS_BBK_WISDOM', 171, 1),
		 (72, 'JBS_BBK_SIRACH', 172, 1),
		 (73, 'JBS_BBK_BARUCH', 173, 1)";
$msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }      

//Fix studies params
$query = "SELECT `id`, `params` FROM #__bsms_studies";
$db->setQuery($query);
$db->query();
$results = $db->loadObjectList();
if ($results)
{
    foreach ($results AS $result)
    {
        //Update the params to json
        $params = new JParameter($result->params);
        $params2	= $params->toObject();
        $params2 = json_encode($params2);
        $query = "UPDATE #__bsms_studies SET `params` = '$params2' WHERE `id` = $result->id LIMIT 1";
        $msg = $this->performdb($query);
    }
}

//Fix share params
$query = "SELECT `id`, `params` FROM #__bsms_share";
$db->setQuery($query);
$db->query();
$results = $db->loadObjectList();
if ($results)
{
    foreach ($results AS $result)
    {
        //Update the params to json
        $params = new JParameter($result->params);
        $params2	= $params->toObject();
        $params2 = json_encode($params2);
        $query = "UPDATE #__bsms_share SET `params` = '$params2' WHERE `id` = $result->id LIMIT 1";
        $msg = $this->performdb($query);
    }
}


//Fix template params
$query = "SELECT `id`, `params` FROM #__bsms_templates";
$db->setQuery($query);
$db->query();
$results = $db->loadObjectList();
if ($results)
{
    foreach ($results AS $result)
    {
        //Update the params to json
        $params = new JParameter($result->params);
        $params2	= $params->toObject();
        $params2 = json_encode($params2);
        $query = "UPDATE #__bsms_templates SET `params` = '$params2' WHERE `id` = $result->id LIMIT 1";
        $msg = $this->performdb($query);
    }
}             
$application = JFactory::getApplication();
$application->enqueueMessage( ''. JText::_('Upgrading to build 700') .'' ) ;
$results = array('build'=>'700','messages'=>$messages);
    
    return $results;
        
    }

  function performdb($query)
    {
        $db = JFactory::getDBO();
        $results = false;
        $db->setQuery($query);
        $db->query();
           		if ($db->getErrorNum() != 0)
					{
						$results = JText::_('JBS_EI_DB_ERROR').': '.$db->getErrorNum()."<br /><font color=\"red\">";
						$results .= $db->stderr(true);
						$results .= "</font>";
                        return $results;
					}
				else
					{
						$results = false; return $results;
					}	
    }
}