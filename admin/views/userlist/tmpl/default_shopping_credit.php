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
											<label><?php echo JText::_('From date');?>:</label>&nbsp;
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
											<label><?php echo JText::_('Shopping credit status');?>:</label>&nbsp;
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
							<table class="table table-hover table-striped table-bordered">
									<thead>
										<tr>
											<th><?php echo JText::_('Shopping credits'); ?></th>																	
											<th><?php echo JText::_('Awarded on'); ?></th>
											<th><?php echo JText::_('Unlocked on'); ?></th>
											<th><?php echo JText::_('Days to unlock'); ?></th>											
											<th><?php echo JText::_('Expired on'); ?></th>
											<th><?php echo JText::_('Days to expire'); ?></th>
											<th><?php echo JText::_('Status'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($this->items as $row):?>
										<tr>
											<td class="hidden-phone"><?php echo JText::_($row->name).' $'.JText::_($row->fee); ?></td>
											<td class="hidden-phone"><?php echo $this->date_donation; ?></td>
											<td class="hidden-phone"><?php 
											$unlock_date = date('Y-m-d',strtotime($this->date_donation. ' + '.$row->unlock.' days'));

											echo JText::_($unlock_date); ?></td>
											<td class="hidden-phone"><?php echo JText::_($row->unlock); ?></td>											
											<td class="hidden-phone"><?php 
											$expired_date = date('Y-m-d',strtotime($this->date_donation. ' + '.$row->expire.' days'));

											echo JText::_($expired_date); ?></td>
											<td class="hidden-phone"><?php echo JText::_($row->expire); ?></td>
											<td class="hidden-phone"><?php echo JText::_($row->status); ?></td>
										</tr>
										<?php endforeach;?>
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