<?php
/**
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2012 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
if (BIBLESTUDY_CHECKREL)
{
	JHtml::_('bootstrap.tooltip');
	JHtml::_('dropdown.init');
	JHtml::_('formbehavior.chosen', 'select');
}
else
{
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.multiselect');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$archived  = $this->state->get('filter.published') == 2 ? true : false;
$trashed   = $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = $listOrder == 'study.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_biblestudy&task=message.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_biblestudy&view=messages'); ?>" method="post" name="adminForm"
      id="adminForm">
<?php if (!empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif; ?>

<div id="filter-bar" class="btn-toolbar">
    <div class="filter-search btn-group pull-left">
        <label for="filter_search"
               class="element-invisible"><?php echo JText::_('JBS_CMN_FILTER_SEARCH_DESC'); ?></label>
        <input type="text" name="filter_search" placeholder="<?php echo JText::_('JBS_CMN_FILTER_SEARCH_DESC'); ?>"
               id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
               title="<?php echo JText::_('JBS_CMN_FILTER_SEARCH_DESC'); ?>"/>
    </div>
    <div class="btn-group pull-left hidden-phone">
        <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i
                class="icon-search"></i></button>
        <button class="btn tip hasTooltip" type="button"
                onclick="document.id('filter_search').value='';this.form.submit();"
                title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
    </div>
    <div class="btn-group pull-right hidden-phone">
        <label for="limit"
               class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
		<?php echo $this->pagination->getLimitBox(); ?>
    </div>
    <div class="btn-group pull-right hidden-phone">
        <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
        <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
            <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JBS_CMN_ASCENDING'); ?></option>
            <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JBS_CMN_DESCENDING'); ?></option>
        </select>
    </div>
    <div class="btn-group pull-right">
        <label for="sortTable" class="element-invisible"><?php echo JText::_('JBS_CMN_SELECT_BY'); ?></label>
        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JBS_CMN_SELECT_BY'); ?></option>
			<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
        </select>
    </div>
	<?php if (!BIBLESTUDY_CHECKREL): ?>
    <div class="btn-group pull-right">
        <label for="filter_published" id="filter_published"
               class="element-invisible"><?php echo JText::_('JBS_CMN_SELECT_BY'); ?></label>
        <select name="filter_published" class="input-medium" onchange="this.form.submit()">
            <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
			<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true); ?>
        </select>
    </div>
	<?php endif; ?>
</div>
<div class="clearfix"></div>

<table class="table table-striped" id="articleList">
<thead>
<tr>
    <th width="1%" class="nowrap center hidden-phone">
		<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'study.ordering', $listDirn, $listOrder, null, 'desc', 'JGRID_HEADING_ORDERING');?>
    </th>
    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
               onclick="Joomla.checkAll(this)"/>
    </th>

    <th width="8%">
		<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'study.published', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_STUDY_DATE', 'study.studydate', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_TITLE', 'study.studytitle', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_SCRIPTURE', 'book.bookname', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_TEACHER', 'teacher.teachername', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_MESSAGE_TYPE', 'messageType.message_type', $listDirn, $listOrder); ?>
    </th>
    <th>
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_SERIES', 'series.series_text', $listDirn, $listOrder); ?>
    </th>
    <th align="center">
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_HITS', 'study.hits', $listDirn, $listOrder); ?>
    </th>
    <th align="center">
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_PLAYS', 'mediafile.plays', $listDirn, $listOrder); ?>
    </th>
    <th align="center">
		<?php echo JHtml::_('grid.sort', 'JBS_CMN_DOWNLOADS', 'mediafile.downloads', $listDirn, $listOrder); ?>
    </th>
    <th width="5%">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
    </th>
    <th width="1%" class="nowrap">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'mediafile.id', $listDirn, $listOrder); ?>
    </th>
</tr>
</thead>
<tbody>
<?php foreach ($this->items as $i => $item) :
	$item->max_ordering = 0; //??
	$ordering           = ($listOrder == 'study.ordering');
	$canCreate          = $user->authorise('core.create');
	$canEdit            = $user->authorise('core.edit', 'com_biblestudy.mediafile.' . $item->id);
	$canEditOwn         = $user->authorise('core.edit.own', 'com_biblestudy.mediafile.' . $item->id);
	$canChange          = $user->authorise('core.edit.state', 'com_biblestudy.mediafile.' . $item->id);
	?>
<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->series_id; ?>">
    <td class="order nowrap center hidden-phone">
		<?php if ($canChange) :
		$disableClassName = '';
		$disabledLabel    = '';

		if (!$saveOrder) :
			$disabledLabel    = JText::_('JORDERINGDISABLED');
			$disableClassName = 'inactive tip-top';
		endif; ?>
        <span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>"
              title="<?php echo $disabledLabel; ?>">
				                <i class="icon-menu"></i>
				            </span>
        <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>"
               class="width-10 text-area-order "/>
		<?php else : ?>
        <span class="sortable-handler inactive">
					            <i class="icon-menu"></i>
				            </span>
		<?php endif; ?>
    </td>
    <td class="center hidden-phone">
		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
    </td>
    <td class="center">
        <div class="btn-group">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'messages.', $canChange, 'cb', '', ''); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo JHtml::_('date', $this->escape($item->studydate, JText::_('DATE_FORMAT_LC4'))); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php if ($canEdit || $canEditOwn) : ?>
            <a href="<?php echo JRoute::_(
				'index.php?option=com_biblestudy&task=message.edit&id=' . (int) $item->id
			); ?>">
				<?php echo ($this->escape($item->studytitle) ? $this->escape(
				$item->studytitle
			) : 'ID: ' . $this->escape($item->id)); ?>
            </a>
			<?php else : ?>
			<?php echo ($this->escape($item->studytitle) ? $this->escape(
				$item->studytitle
			) : 'ID: ' . $this->escape($item->id)); ?>
			<?php endif; ?>
            <div class="pull-left">
				<?php
				if (BIBLESTUDY_CHECKREL)
				{
					// Create dropdown items
					JHtml::_('dropdown.edit', $item->id, 'message.');
					JHtml::_('dropdown.divider');

					if ($item->published)
					{
						JHtml::_('dropdown.unpublish', 'cb' . $i, 'messages.');
					}
					else
					{
						JHtml::_('dropdown.publish', 'cb' . $i, 'messages.');
					}

					JHtml::_('dropdown.divider');

					if ($archived)
					{
						JHtml::_('dropdown.unarchive', 'cb' . $i, 'messages.');
					}
					else
					{
						JHtml::_('dropdown.archive', 'cb' . $i, 'messages.');
					}

					if ($trashed)
					{
						JHtml::_('dropdown.untrash', 'cb' . $i, 'messages.');
					}
					else
					{
						JHtml::_('dropdown.trash', 'cb' . $i, 'messages.');
					}

					// Render dropdown list
					echo JHtml::_('dropdown.render');
				}
				?>
            </div>
			<?php if ($item->alias) : ?>
            <p class="smallsub">
				<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
			<?php endif; ?>
        </div>

    </td>


    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->bookname) . ' ' . $this->escape(
			$item->chapter_begin
		) . ':' . $this->escape($item->verse_begin); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->teachername); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->messageType); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->series_text); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->hits); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->totalplays); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo $this->escape($item->totaldownloads); ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php if ($item->language == '*'): ?>
			<?php echo JText::alt('JALL', 'language'); ?>
			<?php else: ?>
			<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
			<?php endif; ?>
        </div>
    </td>
    <td class="nowrap has-context">
        <div class="pull-left">
			<?php echo (int) $item->id; ?>
        </div>
    </td>
</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>
<?php //Load the batch processing form. ?>
<?php echo $this->loadTemplate('batch'); ?>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<?php echo JHtml::_('form.token'); ?>
</div>
</form>