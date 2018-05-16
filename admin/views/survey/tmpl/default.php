<style>
table tbody tr td, table thead tr th, a {
	text-align: center;
	font-size:90%;
}
</style>
<?php
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
<?php
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.DS.'shared'.DS.'button.php');
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.DS.'shared'.DS.'button_survey.php');

?>
<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar("package_id"));?>"
	name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">			
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>					
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY')?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></th>
			<th><?php echo JText::_('Price Per Giftcode');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_PRICE_PER_UNIT');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_TYPE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_PER_GIFTCODE');?></th>
			<th><?php echo JText::_('COM_SYMBOL_PUBLISH');?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->categories as $i=>$category) { 
	if($category->unlocked==0){
		$this->readonly = ' disabled';
	}else{
		$this->readonly = ' ';
	}
	?>
		<tr class="row<?php echo $i%2;?>">
			<td><?php echo JHtml::_('grid.id', $i, $category->setting_id); ?></td>
			<td width="60px" align="center" height="40px">
				<table>
					<tr>
						<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $category->colour_code;?>" valign="center">
						<font color="white" size="5"><b><?php echo $category->category_id; ?></b></font>
						</td>
					</tr>
				</table>
			</td>
			<td><?php echo $category->category_name; ?></td>
			<td><input type="text" onkeypress="return isNumberKey(event)"
				value="<?php echo ($category->survey_price*100); ?>"  
           				name="survey_price[]" <?php echo $this->readonly;?> /></td>
			<td><?php echo JText::_('Cents');?></td>			
			<td><?php echo JText::_('Unique Symbol');?></td>
			<td><?php echo $category->category_id; ?></td>
			<td><?php echo JHtml::_('jgrid.published', $category->published, $i, '', 1,'cb'); ?></td>
			<input type="hidden" name="last_queue"  <?php echo $this->readonly;?>
				value="<?php echo $category->setting_id; ?>" />
		</tr>
		<?php } ?>
	</tbody>
	<input type="hidden" id="controller" name="controller" value="survey" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="task" value="" />
</table>
</form>


</div>
