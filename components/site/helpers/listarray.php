<?php
defined('_JEXEC') or die();

function getListarray($params, $row) {

$path1 = JPATH_BASE.DS.'components'.DS.'com_biblestudy/helpers/';
include_once($path1.'scripture.php');
include_once($path1.'date.php');
include_once($path1.'filesize.php');
include_once($path1.'filepath.php');
include_once($path1.'duration.php');

$scripture1 = getScripture($params, $row, $esv);

$scripture2 = '';
if ($row->booknumber2){
		  $esv = 0;
		  $scripture2 = getScripture($params, $row, $esv);
		  }

$duration = getDuration($params, $row);

$date = getstudyDate($params, $row->studydate);

$file_size = getFilesize($row->filesize);

$a = array( array(  'element' => $scripture1,
     'position' => $params->get('position1'),
     'order' => $params->get('order1'),
     'islink' => $params->get('islink1'),
     'span' => $params->get('span1'),
     'isbullet' => $params->get('isbullet1')
  ),
  array( 'element' => $row->studytitle,
     'position' => $params->get('position2'),
     'order' => $params->get('order2'),
     'islink' => $params->get('islink2'),
     'span' => $params->get('span2'),
     'isbullet' => $params->get('isbullet2')
  ),
  array( 'element' => $duration,
     'position' => $params->get('position3'),
     'order' => $params->get('order3'),
     'islink' => $params->get('islink3'),
     'span' => $params->get('span3'),
     'isbullet' => $params->get('isbullet3')
  ),
  array( 'element' => $row->studyintro,
     'position' => $params->get('position4'),
     'order' => $params->get('order4'),
     'islink' => $params->get('islink4'),
     'span' => $params->get('span4'),
     'isbullet' => $params->get('isbullet4')
  ),
  array( 'element' => $date,
     'position' => $params->get('position5'),
     'order' => $params->get('order5'),
     'islink' => $params->get('islink5'),
     'span' => $params->get('span5'),
     'isbullet' => $params->get('isbullet5')
  ),
  array( 'element' => $row->teachername,
     'position' => $params->get('position6'),
     'order' => $params->get('order6'),
     'islink' => $params->get('islink6'),
     'span' => $params->get('span6'),
     'isbullet' => $params->get('isbullet6')
  ),
  array( 'element' => $row->teachername.' - '.$row->teachertitle,
     'position' => $params->get('position7'),
     'order' => $params->get('order7'),
     'islink' => $params->get('islink7'),
     'span' => $params->get('span7'),
     'isbullet' => $params->get('isbullet7')
  ),
  array( 'element' => $row->teachertitle.' - '.$row->teachername,
     'position' => $params->get('position10'),
     'order' => $params->get('order10'),
     'islink' => $params->get('islink10'),
     'span' => $params->get('span10'),
     'isbullet' => $params->get('isbullet10')
  ),
  array( 'element' => $file_size,
     'position' => $params->get('position8'),
     'order' => $params->get('order8'),
     'islink' => $params->get('islink8'),
     'span' => $params->get('span8'),
     'isbullet' => $params->get('isbullet8')
  ),
  array( 'element' => $row->series_text,
     'position' => $params->get('position9'),
     'order' => $params->get('order9'),
     'islink' => $params->get('islink9'),
     'span' => $params->get('span9'),
     'isbullet' => $params->get('isbullet9')
  ),
  array( 'element' => "<br />",
     'position' => $params->get('blank1'),
     'order' => $params->get('blankorder1'),
     'islink' => 0,
     'span' => 0,
     'isbullet' => 0,
  ),
  array( 'element' => "<br />",
     'position' => $params->get('blank2'),
     'order' => $params->get('blankorder2'),
     'islink' => 0,
     'span' => 0,
     'isbullet' => 0,
  ),
  array( 'element' => "<br />",
     'position' => $params->get('blank3'),
     'order' => $params->get('blankorder3'),
     'islink' => 0,
     'span' => 0,
     'isbullet' => 0,
  ),
  array( 'element' => "<br />",
     'position' => $params->get('blank4'),
     'order' => $params->get('blankorder4'),
     'islink' => 0,
     'span' => 0,
     'isbullet' => 0,
  ),
  array( 'element' => $row->secondary_reference,
     'position' => $params->get('position11'),
     'order' => $params->get('order11'),
     'islink' => $params->get('islink11'),
     'span' => $params->get('span11'),
     'isbullet' => $params->get('isbullet11'),
  ),
  array( 'element' => $scripture2,
     'position' => $params->get('position13'),
     'order' => $params->get('order13'),
     'islink' => $params->get('islink13'),
     'span' => $params->get('span13'),
     'isbullet' => $params->get('isbullet13'),
  ),
  array( 'element' => $row->user_name,
     'position' => $params->get('position14'),
     'order' => $params->get('order14'),
     'islink' => $params->get('islink14'),
     'span' => $params->get('span14'),
     'isbullet' => $params->get('isbullet14')
  ),
  array( 'element' => $params->get('hits_label').': '.$row->hits,
     'position' => $params->get('position15'),
     'order' => $params->get('order15'),
     'islink' => $params->get('islink15'),
     'span' => $params->get('span15'),
     'isbullet' => $params->get('isbullet15')
  ),
  array( 'element' => $row->studynumber,
     'position' => $params->get('position16'),
     'order' => $params->get('order16'),
     'islink' => $params->get('islink16'),
     'span' => $params->get('span16'),
     'isbullet' => $params->get('isbullet16')
  ),
  array( 'element' => $row->topic_text,
     'position' => $params->get('position17'),
     'order' => $params->get('order17'),
     'islink' => $params->get('islink17'),
     'span' => $params->get('span17'),
     'isbullet' => $params->get('isbullet17')
  ),
  array( 'element' => $row->location_text,
     'position' => $params->get('position18'),
     'order' => $params->get('order18'),
     'islink' => $params->get('islink18'),
     'span' => $params->get('span18'),
     'isbullet' => $params->get('isbullet18')
  ),
  array( 'element' => $row->message_type,
     'position' => $params->get('position12'),
     'order' => $params->get('order12'),
     'islink' => $params->get('islink12'),
     'span' => $params->get('span11'),
     'isbullet' => $params->get('isbullet12'),
  )
  );
//dump ($a, '$a: ');
return $a;
}