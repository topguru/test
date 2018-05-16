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
<script type="text/javascript">
function on_select_contribution_range(e){
	if(jQuery(e).is(':checked')) {
		jQuery('#task').val('shoppingcreditplan.on_select_contribution_range');
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
		jQuery('form#adminForm').submit();
	}
	
}
function on_select_progress_check(e){
	if(jQuery(e).is(':checked')) {
		jQuery('#task').val('shoppingcreditplan.on_select_contribution_range');
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
		jQuery('form#adminForm').submit();
	}	
}
</script>
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan')?>" method="post">
	<input type="hidden" name="task" id="task" value="shoppingcreditplan.create_update_plan">		
	<input type="hidden" name="id" id="id" value="<?php echo JRequest::getVar('id'); ?>">
	<input type="hidden" name="uniq_id" id="uniq_id" value="<?php echo $this->uniq_id; ?>">	
	<table width="100%">
		<tr>
			<td width="51%" valign="top">
				<div id="cj-wrapper"
					style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
					<div
						class="container-fluid no-space-left no-space-right surveys-wrapper">
						<div class="clearfix">
							<label><?php echo JText::_('Shopping Credit Plan Category');?></label>
							<select name="catid" size="1" disabled="disabled">
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
									<table width="100%" style="background-color:#CCC;" cellpadding="1" cellspacing="1">
										<tr>
											<td width="46%">
												<div class="clearfix">
													<label> <?php echo JText::_('From');?>:
													</label>
													<label><?php echo $this->start_date; ?> </label>																						
												</div>
											</td>
											<td width="1%">&nbsp;</td>
											<td>
												<div class="clearfix">
													<label> <?php echo JText::_('To');?>:
													</label>
													<label><?php echo $this->end_date; ?></label>																						
												</div>
											</td>
										</tr>
									</table>
								</div>
								<div class="clearfix">
									<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
										<table class="table table-hover" width="100%">
											<tbody>
												<tr>
													<td align="center">
														<div style="border: 1px solid #ccc; padding: 10px; height: auto; width:auto;">
															<table class="table table-hover table-striped" style="border: 1px solid #ccc;" id="tableContributionRange">
																<thead>
																	<tr>
																		<th colspan="2" align="center">
																		<?php echo JText::_( 'Contribution Range' ); ?>
																		</th>
																	</tr>																		
																</thead>
																<tbody>
																	<?php foreach ($this->contribution_ranges as $range){?>
																	<tr>
																		<td style="border: 1px solid #ccc;"><input type="radio" name="contrib_radio" value="<?php echo $range->id; ?>" 
																			<?php echo ((!empty($this->contrib_radio) && $this->contrib_radio == $range->id) ? "checked=checked" : ""); ?> onchange="on_select_contribution_range(this);" /></td>
																		<td style="border: 1px solid #ccc;"><?php echo '$' . $range->min_amount . ' to ' . '$' . $range->max_amount; ?></td>																		
																	</tr>
																	<?php } ?>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="2">
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
								<br />
								<div class="clearfix">
									<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
										<table class="table table-hover" width="100%">
											<tbody>
												<tr>
													<td align="center">
														<div style="border: 1px solid #ccc; padding: 10px; height: auto; width:auto;">
															<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																<thead>
																	<tr>
																		<th colspan="2" align="center">
																		<?php echo JText::_( 'Progress Check' ); ?>
																		</th>
																	</tr>																		
																</thead>
																<tbody>
																	<?php foreach ($this->progress_checkes as $pc){?>
																	<tr>
																		<td style="border: 1px solid #ccc;"><input type="radio" name="progress_radio" value="<?php echo $pc->id; ?>"
																			<?php echo ((!empty($this->progress_radio) && $this->progress_radio == $pc->id) ? "checked=checked" : ""); ?> onchange="on_select_progress_check(this)" /></td>
																		<td style="border: 1px solid #ccc;"><?php echo 'Every ' . $pc->every. ' ' .  $pc->type; ?></td>																		
																	</tr>
																	<?php } ?>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="2">
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
													</label>
								</div>
								<div class="clearfix">
									<label> <?php echo JText::_('From: ');?>
										<b><?php echo JText::_($this->start_date . ' to ' . $this->end_date); ?></b> 
														</label>
								</div>
								<div class="clearfix">
									<label> <?php echo JText::_('Progress Check: ');?>
										<b><?php echo JText::_($this->progress_check_value); ?></b> 
														</label>
								</div>
								<div class="clearfix">
									<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px; width:auto;">
										<table class="table table-hover" width="100%">
											<tbody>
													<tr>
														<td align="center">
															<div style="border: 1px solid #ccc; padding: 10px; height: auto; width:auto">
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
																			<td style="border: 1px solid #ccc;"><?php echo $donation->fee; ?>%</td>
																			<td style="border: 1px solid #ccc;"><?php echo $donation->refund; ?>%</td>
																			<td style="border: 1px solid #ccc;"><?php echo $donation->unlock; ?> days</td>
																			<td style="border: 1px solid #ccc;"><?php echo $donation->expire; ?> days</td>
																		</tr>
																		<?php } ?>
																		<?php } else { ?>
																		<tr>
																			<td style="border: 1px solid #ccc;">0%</td>
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
								<br/>
								<div class="clearfix">
									<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px; width:auto;">
										<table class="table table-hover" width="100%">
											<tbody>
													<tr>
														<td align="center">
															<div style="border: 1px solid #ccc; padding: 10px; height:auto; width:auto;">
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
																			<td style="border: 1px solid #ccc;"><?php echo $award->fee; ?>%</td>
																			<td style="border: 1px solid #ccc;"><?php echo $award->refund; ?>%</td>
																			<td style="border: 1px solid #ccc;"><?php echo $award->unlock; ?> days</td>
																			<td style="border: 1px solid #ccc;"><?php echo $award->expire; ?> days</td>
																		</tr>
																		<?php } ?>
																		<?php } else { ?>
																		<tr>
																			<td style="border: 1px solid #ccc;">0%</td>
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
								<br/>
								<div class="clearfix">
									<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px; height:auto; width:auto;">
										<table class="table table-hover" width="100%">
											<tbody>
													<tr>
														<td align="center">
															<div style="border: 1px solid #ccc; padding: 10px; height: auto; width:auto;">
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
																			<td><?php echo $fee; ?>%</td>
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