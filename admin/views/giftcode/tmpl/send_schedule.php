<?php
JHtml::_('behavior.tooltip');
?>
<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcode&layout=send_scedule');?>"
	name="adminForm" id="adminForm">
<center>
<div style="margin-top: 10px;">
<table class="table table-striped">
	<tbody>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!--
			<th width="1">
			<center><input type="checkbox" id="checkall-toggle"
				onclick="checkAll(this)" />
			
			</th>  
			-->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			<th><strong><?php echo JText::_('Send Every');?></strong></th>
		</tr>
		<?php
		$days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
		foreach ($days as $day) {
			$schedule = $this->model->checkSchedule($this->package_id,$this->category_id);
			?>
		<tr>
			<td align="center"><input type="checkbox" id="cb3"
				name="<?php echo $day;?>" value="1"
				onclick="Joomla.isChecked(this.checked);" title="<?php echo $day;?>"
				class="hasTip"
				<?php
				if($schedule->$day==1){
					echo'checked="checked"';
				}
				?> /></td>
			<td><?php echo ucwords($day); ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
</div>
</center>
<input type="hidden" name="category_id"
	value="<?php echo $this->category_id; ?>" /> <input type="hidden"
	name="package_id" value="<?php echo $this->package_id;?>" /> <input
	type="hidden" name="controller" value="giftcode"> <input type="hidden"
	name="boxchecked" value="1" /> <input type="hidden" name="task"
	value="" /></form>
</div>
