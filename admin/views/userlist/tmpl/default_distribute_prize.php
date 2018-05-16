<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_('Start date'); ?></th>																	
							<th><?php echo JText::_('End date'); ?></th>
							<th><?php echo JText::_('Duration'); ?></th>
							<th><?php echo JText::_('Distribute prize queue number'); ?></th>											
							<th><?php echo JText::_('Distribute prize queue score'); ?></th>
							<th><?php echo JText::_('Distribute prize queue criteria'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->items as $row):?>
						<tr>
							<td class="hidden-phone"><?php echo JText::_($row->startDate); ?></td>
							<td class="hidden-phone"><?php echo JText::_($row->endDate); ?></td>
							<td class="hidden-phone"><?php echo JText::_($row->duration); ?></td>
							<td class="hidden-phone"><?php echo JText::_($row->distributePrizeQueueNumber); ?></td>											
							<td class="hidden-phone"><?php echo JText::_($row->distributePrizeQueueScore); ?></td>
							<td class="hidden-phone"><?php echo JText::_($row->distributePrizeQueueCriteria); ?></td>
						</tr>
						<?php endforeach;?>
					</tbody>									
				</table>							
			</form>
		</div>
	</div>
</div>