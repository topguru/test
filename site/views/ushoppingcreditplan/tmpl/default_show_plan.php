<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');
?>
<div id="cj-wrapper">	
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td width="10%" valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<div class="span12">
							<div class="well" style="background-color:#8A8887;">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Shopping Credit Plan'); ?>
								</h2>		
							</div>
							<br/>
							<br/>
						</div>	
						<div class="span12" style="overflow: scroll;">
							<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=ushoppingcreditplan')?>" method="post">
								<input type="hidden" name="task" id="task" value="ushoppingcreditplan.showPlan">
								<input type="hidden" name="id" id="id" value="<?php echo JRequest::getVar('id'); ?>">	
								<table width="100%">
									<tr>
										<td width="51%" valign="top">
											<div id="cj-wrapper"
												style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
												<div
													class="container-fluid no-space-left no-space-right surveys-wrapper">
													<div class="clearfix">
														<label><?php echo JText::_('Shopping Credit Plan Category');?></label>
														<select name="catid" size="1">
															<?php foreach($this->categories as $category):?>
															<option value="<?php echo $category->id;?>" <?php echo ($category->id == $this->plan->category ? "selected=selected" : "") ?>>
																<?php echo $this->escape($category->name);?>
															</option>
															<?php endforeach;?>
														</select>
													</div>
													<br />
													<div class="row-fluid">
														<div class="span10">
															<div class="clearfix">
																<table width="100%">
																	<tr>
																		<td width="46%">
																			<div class="clearfix">
																				<label> <?php echo JText::_('From');?>:
																				<i class="icon-info-sign tooltip-hover"
																					title="<?php echo JText::_('From');?>"></i></label>
																				<div class="controls"><?php echo JHtml::_('calendar', $this->start_date, 'from', 'from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
																				</div>									
																			</div>
																		</td>
																		<td width="1%">&nbsp;</td>
																		<td>
																			<div class="clearfix">
																				<label> <?php echo JText::_('To');?>:
																				<i class="icon-info-sign tooltip-hover"
																					title="<?php echo JText::_('To');?>"></i></label>
																				<div class="controls"><?php echo JHtml::_('calendar', $this->end_date, 'to', 'to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
																				</div>									
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<div class="clearfix">
																<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;">											
																	<table width="100%">
																		<tr>
																			<td align="left" colspan="2">
																				<span style="font-weight:bold; font-size:18px;"><?php echo JText::_('Contribution Range');?></span>
																			</td>
																		</tr>
																		
																		<tr>
																			<td colspan="2">
																				<div class="clearfix">
																					<label> <?php echo JText::_('Min Amount');?>:
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('Min Amount');?>"></i></label>
																					<input type="text" class="input-medium"
																							name="min_amount" 
																							value="" onkeyup="numbersOnly(this);" />									
																				</div>
																				<div class="clearfix">
																					<label> <?php echo JText::_('Max Amount');?>:
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('Max Amount');?>"></i></label>
																					<input type="text" class="input-medium"
																							name="max_amount" 
																							value="" onkeyup="numbersOnly(this);" />									
																				</div>
																			</td>
																		</tr>
																	</table>
																	<table class="table table-hover" width="100%">
																		<tbody>
																			<tr>
																				<td align="center">
																					<div style="border: 1px solid #ccc; padding: 10px; height:auto; width:auto;">
																						<table class="table table-hover table-striped" style="border: 1px solid #ccc;" id="tableContributionRange">
																							<thead>
																								<tr>
																									<th colspan="3" align="center">
																									<?php echo JText::_( 'Contribution Range' ); ?>
																									</th>
																								</tr>																		
																							</thead>
																							<tbody>
																								<?php foreach ($this->contribution_ranges as $range){?>
																								<tr>
																									<td style="border: 1px solid #ccc;"><input type="checkbox" name="contrib_range[]" value="<?php echo $range->id; ?>"/></td>
																									<td style="border: 1px solid #ccc;"><?php echo '$' . $range->min_amount . ' to ' . '$' . $range->max_amount; ?></td>
																									<td style="border: 1px solid #ccc;"><input type="radio" name="contrib_radio" value="<?php echo $range->id; ?>" 
																										<?php echo ((!empty($this->contrib_radio) && $this->contrib_radio == $range->id) ? "checked=checked" : ""); ?> onchange="on_select_contribution_range(this);" /></td>
																								</tr>
																								<?php } ?>
																							</tbody>
																							<tfoot>
																								<tr>
																									<td colspan="3">
																									<?php echo $this->pagination_contribution_range->getListFooter(); ?>
																									</td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>															
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
															<div class="clearfix">
																<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;">											
																	<table width="100%">
																		<tr>
																			<td align="left" colspan="2">
																				<span style="font-weight:bold; font-size:18px;"><?php echo JText::_('Progress Check');?></span>
																			</td>
																		</tr>
																		
																		<tr>
																			<td colspan="2">
																				<div class="clearfix">
																					<label> <?php echo JText::_('Check Every');?>:
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('Check Every');?>"></i></label>
																					<input type="text" class="input-medium"
																							name="check_every" 
																							value="" />
																					&nbsp;
																					<select name="pc_type">
																						<option value="min">Min</option>
																						<option value="hour">Hour</option>
																						<option value="day">Day</option>
																					</select>									
																				</div>														
																			</td>
																		</tr>
																	</table>
																	<table class="table table-hover" width="100%">
																		<tbody>
																			<tr>
																				<td align="center">
																					<div style="border: 1px solid #ccc; padding: 10px; height:auto; width:auto;">
																						<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																							<thead>
																								<tr>
																									<th colspan="3" align="center">
																									<?php echo JText::_( 'Progress Check' ); ?>
																									</th>
																								</tr>																		
																							</thead>
																							<tbody>
																								<?php foreach ($this->progress_checkes as $pc){?>
																								<tr>
																									<td style="border: 1px solid #ccc;"><input type="checkbox" name="progress_checks[]" value="<?php echo $pc->id; ?>"/></td>
																									<td style="border: 1px solid #ccc;"><?php echo 'Every ' . $pc->every. ' ' .  $pc->type; ?></td>
																									<td style="border: 1px solid #ccc;"><input type="radio" name="progress_radio" value="<?php echo $pc->id; ?>"
																										<?php echo ((!empty($this->progress_radio) && $this->progress_radio == $pc->id) ? "checked=checked" : ""); ?> onchange="on_select_progress_check(this)" /></td>
																								</tr>
																								<?php } ?>
																							</tbody>
																							<tfoot>
																								<tr>
																									<td colspan="3">
																									<?php echo $this->pagination_progress_check->getListFooter(); ?>
																									</td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>															
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</td>
										<td width="1%"></td>
										<td width="48%" valign="top">
											<div id="cj-wrapper"
												style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
												<div
													class="container-fluid no-space-left no-space-right surveys-wrapper">
													<div class="row-fluid">
														<div class="span10">
															<div class="clearfix">
																<label><?php echo JText::_('Contribution Range: ');?>
																<b><?php echo JText::_($this->contribution_range_value); ?></b>
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('Contribution Range');?>"></i></label>
															</div>
															<div class="clearfix">
																<label> <?php echo JText::_('From: ');?>
																	<b><?php echo JText::_($this->start_date . ' to ' . $this->end_date); ?></b> 
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('From');?>"></i></label>
															</div>
															<div class="clearfix">
																<label> <?php echo JText::_('Progress Check: ');?>
																	<b><?php echo JText::_($this->progress_check_value); ?></b> 
																					<i class="icon-info-sign tooltip-hover"
																						title="<?php echo JText::_('Progress Check');?>"></i></label>
															</div>
															<div class="clearfix">
																<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;">
																	<table class="table table-hover" width="100%">
																		<tbody>
																				<tr>
																					<td align="center">
																						<div style="border: 1px solid #ccc; padding: 10px; height: 170px;">
																							<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																								<thead>
																									<tr>
																										<th colspan="4" align="center">
																										<?php echo JText::_( 'Shopping credit from donation' ); ?>
																										</th>
																									</tr>
																									<tr>
																										<th width="12%" style="border: 1px solid #ccc;"><?php echo JText::_('Fee');?></th>
																										<th style="border: 1px solid #ccc;"><?php echo JText::_('% Refund as shopping credits');?></th>
																										<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Unlock');?></th>
																										<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Expire');?></th>
																									</tr>																		
																								</thead>
																								<tbody>
																									<?php if (!empty($this->donations)) { ?>
																									<?php foreach ($this->donations as $donation) { ?>
																									<tr>
																										<td style="border: 1px solid #ccc;">$<?php echo $donation->fee; ?></td>
																										<td style="border: 1px solid #ccc;"><?php echo $donation->refund; ?>%</td>
																										<td style="border: 1px solid #ccc;"><?php echo $donation->unlock; ?> days</td>
																										<td style="border: 1px solid #ccc;"><?php echo $donation->expire; ?> days</td>
																									</tr>
																									<?php } ?>
																									<?php } else { ?>
																									<tr>
																										<td style="border: 1px solid #ccc;">0$</td>
																										<td style="border: 1px solid #ccc;">0%</td>
																										<td style="border: 1px solid #ccc;">0 days</td>
																										<td style="border: 1px solid #ccc;">0 days</td>
																									</tr>
																									<?php } ?>
																								</tbody>
																							</table>
																						</div>															
																					</td>
																				</tr>
																			</tbody>
																	</table>											
																</div>
															</div>
															<div class="clearfix">
																<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;">
																	<table class="table table-hover" width="100%">
																		<tbody>
																				<tr>
																					<td align="center">
																						<div style="border: 1px solid #ccc; padding: 10px; height: 170px;">
																							<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																								<thead>
																									<tr>
																										<th colspan="4" align="center">
																										<?php echo JText::_( 'Shopping credit from purchase of award symbols' ); ?>
																										</th>
																									</tr>																		
																								</thead>
																								<tr>
																										<th width="12%" style="border: 1px solid #ccc;"><?php echo JText::_('Fee');?></th>
																										<th style="border: 1px solid #ccc;"><?php echo JText::_('% Refund as shopping credits');?></th>
																										<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Unlock');?></th>
																										<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Expire');?></th>
																									</tr>
																								<tbody>
																									<?php if (!empty($this->awards)){ ?>
																									<?php foreach ($this->awards as $award){ ?>
																									<tr>
																										<td style="border: 1px solid #ccc;">$<?php echo $award->fee; ?></td>
																										<td style="border: 1px solid #ccc;"><?php echo $award->refund; ?>%</td>
																										<td style="border: 1px solid #ccc;"><?php echo $award->unlock; ?> days</td>
																										<td style="border: 1px solid #ccc;"><?php echo $award->expire; ?> days</td>
																									</tr>
																									<?php } ?>
																									<?php } else { ?>
																									<tr>
																										<td style="border: 1px solid #ccc;">0$</td>
																										<td style="border: 1px solid #ccc;">0%</td>
																										<td style="border: 1px solid #ccc;">0 days</td>
																										<td style="border: 1px solid #ccc;">0 days</td>
																									</tr>
																									<?php } ?>
																								</tbody>
																							</table>
																						</div>															
																					</td>
																				</tr>
																			</tbody>
																	</table>											
																</div>
															</div>
															<div class="clearfix">
																<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;">
																	<table class="table table-hover" width="100%">
																		<tbody>
																				<tr>
																					<td align="center">
																						<div style="border: 1px solid #ccc; padding: 10px; height: 360px;">
																							<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																								<thead>
																									<tr>
																										<th colspan="2" align="center">
																										<?php echo JText::_( 'Other benefits' ); ?>
																										</th>
																									</tr>
																									<tr>
																										<th width="20%"><?php echo JText::_( 'Fee' ); ?></th>
																										<th><?php echo JText::_( 'Free giftcode for the funder' ); ?></th>
																									</tr>																		
																								</thead>
																								<?php
																									$giftcode = empty($this->giftcodes) ? 0 : $this->giftcodes[0];
																									$fee = empty($giftcode->fee) ? 0 : $giftcode->fee;
																								 ?>
																								<tbody>
																									<tr>
																										<td>$<?php echo $fee; ?></td>
																										<td>
																											<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																												<thead>
																													<tr>
																														<td><?php echo JText::_( 'Giftcode' ); ?></td>
																														<td><?php echo JText::_( 'Quantity' ); ?></td>
																														<td><?php echo JText::_( 'To be Awarded' ); ?></td>
																													</tr>
																												</thead>
																												<tbody>
																												<?php foreach ($this->giftcodes  as $giftcode) { ?>
																													<tr>
																														<td><?php echo $giftcode->name; ?></td>
																														<td><?php echo $giftcode->quantity; ?></td>
																														<td>Daily</td>
																													</tr>
																												<?php } ?>
																												</tbody>
																											</table>
																										</td>																			
																									</tr>
																								</tbody>
																							</table>
																						</div>															
																					</td>
																				</tr>
																			</tbody>
																	</table>											
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</td>
				</tr>
			</table>				
		</div>
	</div>	
</div>

