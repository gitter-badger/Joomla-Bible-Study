<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php //$params = &JComponentHelper::getParams($option);  
$user =& JFactory::getUser();
global $mainframe, $option;
$params = $this->params;
$entry_user = $user->get('gid');
$user_submit_name = $user->name;
if ($user->name == ''){$user_submit_name = '';}
$entry_access = ($params->get('entry_access')) - 1;
$allow_entry = $params->get('allow_entry_study');
$templatemenuid = $params->get('teachertemplateid');
//dump ($templatemenuid);
if (!$templatemenuid) {$templatemenuid = JRequest::getVar('templatemenuid', 1, 'get', 'int');}
$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
$admin_params = $this->admin_params;
include_once($path1.'image.php');
//if (!$templatemenuid){$templatemenuid = 1;}

$listingcall = JView::loadHelper('teacher');

?>
<div id="biblestudy" class="noRefTagger">
  <table id="bsm_teachertable" cellspacing="0">
    <tbody>
      <tr class="titlerow">
        <td align="center" colspan="3" class="title" >
          <?php echo $this->params->get('teacher_title', JText::_('Our Teachers'));?>
        </td>
      </tr>
    </tbody>
  </table>
<?php if ($allow_entry > 0) {
if ($entry_access <= $entry_user){ ?>
<tr><td><a href="index.php?option=com_biblestudy&view=teacheredit&layout=form"><strong><?php echo JText::_('Add a Teacher');?></strong></a></td></tr><?php } }?>

<tr><td>
<?php 
  switch ($params->get('teacher_wrapcode')) {
      case '0':
        //Do Nothing
        break;
      case 'T':
        //Table
        echo '<table id="bsms_teachertable" width="100%">'; 
        break;
      case 'D':
        //DIV
        echo '<div>';
        break;
      }
  echo $params->get('teacher_headercode');
  
  
  foreach ($this->items as $row) { //Run through each row of the data result from the model
  $listing = getTeacherListExp($row, $params, $oddeven, $this->admin_params, $this->template);
	echo $listing;
 }
 
    switch ($params->get('teacher_wrapcode')) {
      case '0':
        //Do Nothing
        break;
      case 'T':
        //Table
        echo '</table>'; 
        break;
      case 'D':
        //DIV
        echo '</div>';
        break;
      }

?>
  
<div class="listingfooter" >
	<?php 
      echo $this->pagination->getPagesLinks();
      echo $this->pagination->getPagesCounter();
	 ?>
</div> <!--end of bsfooter div-->
</div>