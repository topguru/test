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
			
			<form id="adminForm"
				action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>">	
                <input type="hidden" name="process_id" id="process_id" value="<?php echo JRequest::getVar('process_id'); ?>">
			
				<table>
					<tr>
						<td valign="top">
							<div class="span12">
					<table class="table table-striped table-hover table-bordered" >
									<thead>
										<tr>
											<th valign="top"><?php echo JText::_('No'); ?></th>
											<th valign="top"><?php echo JText::_('From'); ?></th>
											<th valign="top"><?php echo JText::_('To'); ?></th>
											<th valign="top"><?php echo JText::_('Duration'); ?></th>
											<th valign="top"><?php echo JText::_('Status'); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $this->i; ?></td>
											<td><?php echo $this->valuefrom; ?></td>
											<td><?php echo $this->valueto; ?></td>
											<td><?php echo JText::_('1'); ?></td>
											<td><?php echo JText::_('off'); ?></td>
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