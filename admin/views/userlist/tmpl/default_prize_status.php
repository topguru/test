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
				<table>
					<tr>
						<td width="35%" valign="top" style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
							<div class="span3" >
								<table width="100%">
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('Date from');?>:</label>&nbsp;
											<?php echo JHtml::_('calendar', null, 'fromDate', 'fromDate', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>&nbsp;											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to');?>:</label>&nbsp;
											<?php echo JHtml::_('calendar', null, 'toDate', 'toDate', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>&nbsp;
										</td>
									</tr>									
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('Value from');?>:</label>&nbsp;
											<input type="text" name="valueFrom" value="" style="width:150px;" />											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to');?>:</label>&nbsp;
											<input type="text" name="valueTo" value="" style="width:150px;" />
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="3">
											<label><?php echo JText::_('Prize status');?>:</label>&nbsp;
											<select name="prizeStatus">
												<option value="locked">Locked</option>
												<option value="win">Win</option>
												<option value="claimed">Claimed</option>
											</select>
										</td>
									</tr>
								</table>
							</div>							
						</td>
						<td width="2%"style="">&nbsp;</td>
						<td valign="top">
							<div class="span12">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
											<th><?php echo JText::_('Status'); ?></th>																	
											<th><?php echo JText::_('From'); ?></th>
											<th><?php echo JText::_('To'); ?></th>
											<th><?php echo JText::_('Duration'); ?></th>											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="hidden-phone"><?php echo JText::_('Locked'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>											
										</tr>										
										<tr>
											<td class="hidden-phone"><?php echo JText::_('Unlocked'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>											
										</tr>
										<tr>
											<td class="hidden-phone"><?php echo JText::_('Won'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>											
										</tr>
										<tr>
											<td class="hidden-phone"><?php echo JText::_('Claim'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>
											<td class="hidden-phone"><?php echo JText::_(''); ?></td>											
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