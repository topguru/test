<?php
defined('_JEXEC') or die();

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
function onSelectPrizeStatus(){
	jQuery('form#adminForm').submit();
}
</script>
<div id="cj-wrapper">
<div
	class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
<div class="row-fluid">
<div class="span12">
<table class="table table-hover table-striped" >
<thead>
	<tr>
		<th valign="top"><?php echo JText::_('Presentation group'); ?></th>
		<th valign="top"><?php echo JText::_('Presentation users'); ?></th>
		<th valign="top"><?php echo JText::_('% of each $0.01 from all user groups to fund prize'); ?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</tbody>
</table>
</div>
		<div class="span12">

<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist');?>"
	method="post" name="adminForm">
	<input type="hidden" name="task" id="task" value="apresentationlist.fundPrizeHistory" />
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">	
<table>
	<tr>		
		<td valign="top">
		<table class="table table-hover table-striped">
			<thead>
				<tr>
					<th valign="top"><?php echo JText::_('Prize Value'); ?></th>
					<th valign="top"><?php echo JText::_('% of each $0.01 from all user groups to fund prize'); ?></th>
					<th valign="top"><?php echo JText::_('Value funded'); ?></th>
					<th valign="top"><?php echo JText::_('Shortfall'); ?></th>
					<th valign="top"><?php echo JText::_('% funded'); ?></th>
					<th valign="top"><?php echo JText::_('Prize status'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->rows as $row):?>
				<tr>
					<td class="hidden-phone"><?php echo JText::_('$' . number_format($row->prize_value, 2, '.', '')); ?></td>					
					<td class="hidden-phone"><?php echo JText::_(number_format($row->each_fund_prize, 2, '.', '') . '%'); ?></td>
					<td class="hidden-phone"><?php echo JText::_(number_format($row->value_funded, 2, '.', '')); ?></td>
					<td class="hidden-phone"><?php echo JText::_(number_format($row->shortfall, 2, '.', '')); ?></td>
					<td class="hidden-phone"><?php echo JText::_(number_format($row->percent_funded, 2, '.', '') . '%'); ?></td>
					<td class="hidden-phone"><?php echo JText::_($row->status); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="11"><?php echo (!empty($this->pagination) ? $this->pagination->getListFooter() : '') ?></td>
				</tr>
			</tfoot>
		</table>
		</div>
		</td>
	</tr>
</table>
</form>
</div>
</div>
</div>

