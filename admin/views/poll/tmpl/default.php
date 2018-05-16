<?php
/**
 * version 1.0
 * create by Yasa I Kade
 * Bali - Indonesia
 */

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
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=poll&package_id='.JRequest::getVar("package_id"));?>"
	name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->categories); ?>);" /></th> -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>		
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_PRICE_PER_POLL');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_PRICE_PER_UNIT');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_GIFTCODE_QUANTITY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_TYPE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_PER_GIFTCODE');?>
			</th>
			<th><?php echo JText::_('COM_SYMBOL_PUBLISH');?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->categories as $i=>$category) { ?>
		<tr class="row<?php echo $i %2;?>">
			<td><?php echo JHtml::_('grid.id', $i, $category->setting_id); ?></td>
			<td style="width:40px;height:30px;text-align:center;background-color:<?php echo $category->colour_code;?>">
			<font color="white" size="6"> <b><?php echo $category->category_id; ?></b>
			</font></td>
			<td><?php echo $category->category_name; ?></td>
			<td><input type="text" class="hasTip" title="number only"
				onkeypress="return isNumberKey(event)"
				value="<?php echo ($category->poll_price*100); ?>"
				name="poll_price_<?php echo $category->setting_id ?>" /></td>
			<td align="center"><?php echo JText::_('Cents');?></td>
			<td align="center"><?php 
			$giftcodeTotal = $this->model->getGiftCode($category->setting_id);
			echo $giftcodeTotal->total;
			?></td>
			<td><?php echo JText::_('Unique Symbol');?></td>
			<td align="center"><?php 
			$awardSymbolTotal = $this->model->getAwardSymbol($category->setting_id);
			echo $awardSymbolTotal->total;
			?></td>
			<td align="center"><?php echo JHtml::_('jgrid.published', $category->published, $i, '', 1,'cb'); ?>
			<input type="hidden" name="last_queue"
				value="<?php echo $category->setting_id; ?>" /></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div><input type="hidden" id="task" name="task" value="" /> <input
	type="hidden" id="package_id" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>" /> <input
	type="hidden" id="controller" name="controller" value="poll" /> <input
	type="hidden" id="boxchecked" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
