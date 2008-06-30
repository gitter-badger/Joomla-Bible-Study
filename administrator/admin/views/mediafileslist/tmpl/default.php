<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">

<?php //echo $this->lists['studyid']; ?>
<div id="editcell">
	<table class="adminlist">
      <thead>
        <tr> 
       
          <th width="5"> <?php echo JText::_( 'Row' ); ?> </th>
          <th width="20"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /> </th>
          
          <th width="20" align="center"> <?php echo JHTML::_('grid.sort',  'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th width = "20"> <?php echo JHTML::_('grid.sort',  'I.D.', 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
          <th align="left">  <?php echo JHTML::_('grid.sort',  'File Name', 'filename', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th> <?php  echo JHTML::_('grid.sort',  'Study Title', 'studytitle', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
          <th>  <?php echo JHTML::_('grid.sort',  'Media Type', 'media_image_name', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th>  <?php echo JHTML::_('grid.sort',  'Create Date', 'createdate', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          
        </tr>
      </thead>
      <?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_biblestudy&controller=mediafilesedit&task=edit&cid[]='. $row->id );
		$published 	= JHTML::_('grid.published', $row, $i );
		$ordering = ($this->lists['order'] == 'ordering');
		?>
      <tr class="<?php echo "row$k"; ?>"> 
        <td> <?php echo $this->pagination->getRowOffset( $i ); ?> </td>
        <td width="20"> <?php echo $checked; ?> </td>
        <td align="center" width="20"> <?php echo $published; ?> </td>
        <td width="20"><?php echo $row->id; ?> </td>
        
        <td width="8%" class="order">
				<span><?php echo $this->pagination->orderUpIcon( $i, ($row->study_id == @$this->items[$i-1]->study_id),'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->study_id == @$this->items[$i+1]->study_id), 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
        <td> <a href="<?php echo $link; ?>"><?php echo $row->filename; ?></a> </td>
        <td> <?php echo $row->studytitle; ?> </td>
        <td> <?php echo $row->media_image_name; ?> </td>
        <td> <?php echo $row->createdate; ?> </td>
        
      </tr>
      <?php
		$k = 1 - $k;
	}
	?>
    
      <tfoot>
      <td colspan="10"> <?php echo $this->pagination->getListFooter(); ?> </td></tfoot>
    </table>


</div>
    <!--<table>    <tr><td>Pagination: <?php //print_r($this->pagination);?></td></tr></table>-->
<input type="hidden" name="option" value="com_biblestudy" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="mediafileslist" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
