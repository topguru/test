<style>
td,th {
	text-align: center;
}
</style>
<div id"j-main-container" class="span10">
<form action="index.php" method="post" name="adminForm">
<center>
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!--
			<th width="1"><input type="checkbox" name="checkall-toggle"
				value="on" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
				onclick="Joomla.checkAll(this)" /></th> 
			 -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			<th>#</th>
			<th><?php echo JText::_('Color Code');?></th>
			<th><?php echo JText::_('Category Name');?></th>
			<th><?php echo JText::_('Published');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ($this->data as $data) {
		if($data->package_id == JRequest::getVar('package_id')):
		$i++;
		?>
		<tr>
			<td width="1"><?php echo JHtml::_('grid.id', $i, $data->id); ?></td>
			<td width="40px" height="30px" align="center" style="background-color: <?php echo $data->color_code ?>;">
			<font color="white" size="6"><b><?php echo $data->category_id; ?></b></font>
			</td>
			<td><input type="text" value="<?php echo $data->color_code; ?>"
				style="text-align: center;"
		<?php echo $locked = $data->locked == 1 ? "disabled" : "" ?>
				name="color[]" /></td>
			<td><input type="text" value="<?php echo $data->name; ?>"
				style="text-align: center;"
		<?php echo $locked = $data->locked == 1 ? "disabled" : "" ?>
				name="name[]" /></td>
			<td><?php echo JHtml::_('jgrid.published', $data->published, $i, '', 1,'cb'); ?>
			</td>
		</tr>
		<?php
		endif;
	}
	?>
	</tbody>
</table>
</center>
<input type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="create" /> <input type="hidden" name="controller"
	value="giftcodecategory" /></form>
</div>
