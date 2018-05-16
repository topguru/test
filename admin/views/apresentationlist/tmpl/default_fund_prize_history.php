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
<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
<div class="row-fluid">
<div class="span12">
<table class="table table-hover table-striped table-bordered" style="width:50%">
<thead>
	<tr>
		<th valign="top"><?php echo JText::_('Presentation group'); ?></th>
		<th valign="top"><?php echo JText::_('Symbol Queues'); ?></th>
		<th valign="top"><?php echo JText::_('Award Funds Plan'); ?></th>
	</tr>
</thead>
<tbody>
 <?php foreach ( $this->usergrouplistId as $rows):
						?>
	<tr>
		<td><?php echo (!empty($rows->name) ? $rows->name : 0 );?></td>
		<td><?php echo (!empty($rows->symbol_name) ? $rows->symbol_name : 0 );?></td>
		<td><?php echo (!empty($rows->fund_amount) ? $rows->fund_amount : 0 );?> %</td>
	</tr>
							<?php endforeach;?>
</tbody>
</table>


<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist');?>"
	method="post" name="adminForm">
	<input type="hidden" name="task" id="task" value="apresentationlist.fundPrizeHistory" />
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">	

<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th valign="top"><?php echo JText::_('Prize value'); ?></th>
					<th valign="top"><?php echo JText::_('Total fund receivers'); ?></th>
					<th valign="top"><?php echo JText::_('Total prize unlocked'); ?></th>
					<th valign="top"><?php echo JText::_('Total prize won'); ?></th>
					<th valign="top"><?php echo JText::_('Total prize claimed'); ?></th>
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
				</tr>
				<?php endforeach; ?>
			</tbody>
			
		</table>
		
</form>
</div>
</div>
</div>

