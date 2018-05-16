<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.
DS.'shared'.DS.'button.php');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
	 }
	 
</script>
<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'));?>"
	name="adminForm" id="adminForm">
<table align="center" border="0" class="table table-striped" width="70%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->items); ?>);" /></th> -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th>#</th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_DONATION_AMOUNT');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_UNIT');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_GIFTCODE_QUANTITY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_TYPE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_PER_GIFTCODE');?>
			</th>
			<th><?php echo JText::_('COM_GIFT_CODE_PUBLISHED');?></th>
		</tr>
	</thead>
	<?php
	foreach($this->items as $i => $item):
	if($item->unlocked==0){

		$this->readonly = ' disabled';
	}else{
		$this->readonly = ' ';
	}

	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td align="center"><input type="hidden"
			value="<?php echo $item->setting_id;?>" name="setting_id[]"> <?php echo JHtml::_('grid.id', $i, $item->setting_id); ?>
		</td>
		<td width="60px" align="center" height="40px">
		<table>
			<tr>
				<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $item->colour_code;?>" valign="center">
				<font color="white" size="5"><b><?php echo $item->category_id; ?></b></font>
				</td>
			</tr>
		</table>
		</td>
		<td align="center"><?php echo $item->category_name;?></td>
		<td align="center"><input type="text" style="text-align: right"
			value="<?php echo $this->iscent_raw($item->donation_amount);?>"
			name="donation_amount[]" <?php echo $this->readonly;?>
			onkeypress="return isNumberKey(event)" class="hasTip"
			title="number only"></td>
		<td align="center"><?php echo $this->unit;?></td>
		<td align="center"><?php  
		//$giftcodeTotal = $this->poll_model->getGiftCode($category->setting_id);
		//echo $giftcodeTotal->total;
		echo '1';
		?></td>
		<td align="center"><?php echo JText::_('Unique Symbol');?></td>
		<td align="center"><?php echo $item->category_id; ?></td>
		<td align="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, '', 1,'cb'); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<div><input type="hidden" id="task" name="task" value="" /> <input
	type="hidden" id="package_id" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>" /> <input
	type="hidden" id="controller" name="controller" value="donation" /> <input
	type="hidden" id="boxchecked" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
