<?php
defined('_JEXEC') or die();

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div
		class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<h2>Prize Value = $2, Prize status = locked</h2>
				<form id="adminForm"
				action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>">	
                <input type="hidden" name="process_id" id="process_id" value="<?php echo JRequest::getVar('process_id'); ?>">				
				<table>
					<tr>
						<td valign="top">
							<div class="span12">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
											<th valign="top"><?php echo JText::_('Start date'); ?></th>
											<th valign="top"><?php echo JText::_('End date'); ?></th>
											<th valign="top"><?php echo JText::_('Duration'); ?></th>
											<th valign="top"><?php echo JText::_('Distribute prize queue number'); ?></th>
											<th valign="top"><?php echo JText::_('Distribute prize queue score'); ?></th>
											<th valign="top"><?php echo JText::_('Distribute prize queue criteria'); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_('1'); ?></td>
											<td><?php echo JText::_('1599'); ?></td>
											<td><?php echo JText::_('View'); ?></td>
										</tr>
										<tr>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_('3'); ?></td>
											<td><?php echo JText::_('1212'); ?></td>
											<td><?php echo JText::_('View'); ?></td>
										</tr>
										<tr>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_(''); ?></td>
											<td><?php echo JText::_('13'); ?></td>
											<td><?php echo JText::_('345'); ?></td>
											<td><?php echo JText::_('View'); ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>