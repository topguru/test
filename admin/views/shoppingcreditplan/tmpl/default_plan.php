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
<script type="text/javascript"><!--
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onAddContributionRange(){

	var min_amount = jQuery("input[name=min_amount]").val();
	var max_amount = jQuery("input[name=max_amount]").val();
	var local_ranges = ranges.slice();
	var MAX_SAFE_INTEGER = Math.pow(2, 53) - 1;

	if(min_amount == '' || max_amount == '') {
		alert("Min and max amount is required");
		return false;
	}

	// Only one "Over $n range is allowed"
	if(max_amount == 0) {
		for (var i = ranges.length - 1; i >= 0; i--) {
			if(ranges[i][1] == 0) {
				alert('You can\'t have more then 1 open range');
				return false;
			}
		};
	};

	// Max amount must be greater than min amount unless Max amount is 0
	if(min_amount != '' && max_amount != '') {
		if( (parseInt(max_amount) <= parseInt(min_amount)) && (parseInt(max_amount) != 0) ) {
			alert('Max Amount must be greater than Mix Amount');
			return false;
		}
	}

	// check for overlapping ranges
	for (var i = ranges.length - 1; i >= 0; i--) {
		if(ranges[i][1] == 0) {
			ranges[i][1] = MAX_SAFE_INTEGER;
		}
	};
	
	local_ranges.push([parseInt(min_amount), (max_amount == 0) ? MAX_SAFE_INTEGER : parseInt(max_amount)]);

	var checkOverlap = multipleRangeOverlaps.apply(this, [].concat.apply([], local_ranges));

	if(checkOverlap) {
		alert('Overlapping ranges detected, Please fix');
		return false;
	}

	jQuery('#task').val('shoppingcreditplan.add_contribution_range');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onDeleteContributionRange(){
	jQuery('#task').val('shoppingcreditplan.delete_contribution_range');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onAddProgressCheck(){
	var check_every = jQuery("input[name=check_every]").val();
	if(check_every != ''){
		jQuery('#task').val('shoppingcreditplan.add_progress_check');
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
		jQuery('form#adminForm').submit();
	} else {
		alert("Please insert check every");
		return false;
	}	
}
function onDeleteProgressCheck(){
	jQuery('#task').val('shoppingcreditplan.delete_progress_check');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onDonationFeeOpen(){
	jQuery('#donation-fee-page-modal').modal('show');	
}
function on_donation_fee(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_donation_fee');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();		
}
function onDonationRefundedOpen(){
	jQuery('#donation-refunded-page-modal').modal('show');
}
function on_donation_refunded(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_donation_refunded');	
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onDonationUnlockOpen(){
	jQuery('#donation-unlock-page-modal').modal('show');
}
function on_donation_unlock(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_donation_unlock');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onDonationExpiryOpen(){
	jQuery('#donation-expiry-page-modal').modal('show');
}
function on_donation_expiry(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_donation_expiry');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onAwardFeeOpen(){
	jQuery('#award-fee-page-modal').modal('show');
}
function on_award_fee(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_award_fee');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onAwardRefundedOpen(){
	jQuery('#award-refunded-page-modal').modal('show');
}
function on_award_refunded(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_award_refund');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onAwardUnlockOpen(){
	jQuery('#award-unlock-page-modal').modal('show');
}
function on_award_unlock(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_award_unlock');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onAwardExpiryOpen(){
	jQuery('#award-expiry-page-modal').modal('show');
}
function on_award_expiry(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_award_expire');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function onGiftcodeOpen(ids){
	jQuery('#giftcode_id').val(ids);
	jQuery('#giftcode-page-modal').modal('show');
}
function on_giftcode(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();	
	jQuery('#task').val('shoppingcreditplan.on_giftcode');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
function on_giftcode_fee_open(){
	jQuery('#giftcode-fee-page-modal').modal('show');
}
function on_giftcode_fee(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();
	jQuery('#task').val('shoppingcreditplan.on_giftcode_fee');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=shoppingcreditplan');
	jQuery('form#adminForm').submit();
}
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
function rangeOverlaps(a_start, a_end, b_start, b_end) {
    if (a_start <= b_start && b_start <= a_end) return true; // b starts in a
    if (a_start <= b_end   && b_end   <= a_end) return true; // b ends in a
    if (b_start <  a_start && a_end   <  b_end) return true; // a in b
    return false;
}
function multipleRangeOverlaps() {
    var i, j;
    if (arguments.length % 2 !== 0)
        throw new TypeError('Arguments length must be a multiple of 2');
    for (i = 0; i < arguments.length - 2; i += 2) {
        for (j = i + 2; j < arguments.length; j += 2) {
            if (
                rangeOverlaps(
                    arguments[i], arguments[i+1],
                    arguments[j], arguments[j+1]
                )
            ) return true;
        }
    }
    return false;
}
</script>
<script type="text/javascript">
ranges = [];
<?php foreach ($this->contribution_ranges as $range): ?>
ranges.push([<?php echo $range->min_amount ?>, <?php echo $range->max_amount ?>]);
<?php endforeach; ?>
</script>
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan')?>" method="post">
	<input type="hidden" name="task" id="task" value="shoppingcreditplan.create_update_plan">
	<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
	<input type="hidden" name="id" id="id" value="<?php echo JRequest::getVar('id'); ?>">
	<input type="hidden" name="uniq_id" id="uniq_id" value="<?php echo $this->uniq_id; ?>">
	<input type="hidden" name="giftcode_id" id="giftcode_id" value="">	
	<input type="hidden" name="plan_id" value="<?php echo $this->plan_id; ?>">
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
													<td align="right">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
														onclick="onAddContributionRange();" id="addBtn"><i></i> <?php echo JText::_('Add');?></button>
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
														onclick="onDeleteContributionRange();"><i></i> <?php echo JText::_('Delete');?></button>
													</div>
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
																			<td style="border: 1px solid #ccc;">
																				<?php
																					if($range->max_amount == 0) {
																						$span_txt = 'Over $' . ($range->min_amount - 1);
																						$span_class = 'opened-range'; 
																					}
																					else {
																						$span_txt = '$' . $range->min_amount . ' to ' . '$' . $range->max_amount; 	
																						$span_class = 'closed-range';
																					}
																					echo "<span class=\"$span_class\">$span_txt</span>";
																				?>
																			</td>
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
													<td align="right">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
														onclick="onAddProgressCheck();" id="addBtn"><i></i> <?php echo JText::_('Add');?></button>
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
														onclick="onDeleteProgressCheck();"><i></i> <?php echo JText::_('Delete');?></button>
													</div>
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
				<?php if( !empty($this->progress_check_value)     and 
						  !empty($this->contribution_range_value) and
						  !empty($this->start_date) and
					      !empty($this->end_date)
					): ?>
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
																			<!--<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Unlock');?></th>-->
																			<th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Expire');?></th>
																		</tr>																		
																	</thead>
																	<tbody>
																		<?php if (!empty($this->donations)) { ?>
																		<?php foreach ($this->donations as $donation) { ?>
																		<tr>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationFeeOpen();"><?php echo $donation->fee; ?>%</a></td>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationRefundedOpen();"><?php echo $donation->refund; ?>%</a></td>
																			<!--<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationUnlockOpen();"><?php echo $donation->unlock; ?> days</a></td>-->
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationExpiryOpen();"><?php echo $donation->expire; ?> days</a></td>
																		</tr>
																		<?php } ?>
																		<?php } else { ?>
																		<tr>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationFeeOpen();">0$</a></td>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationRefundedOpen();">0%</a></td>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationUnlockOpen();">0 days</a></td>
																			<td style="border: 1px solid #ccc;"><a href="#" onclick="onDonationExpiryOpen();">0 days</a></td>
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
														<td align="center">&nbsp;</td>
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
														<td align="center">&nbsp;</td>
											  </tr>
												</tbody>
										</table>											
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<div id="donation-fee-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Fee');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">&nbsp;<input
						name="donation-fee-percent" id="donation-fee-percent" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;">%</span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_donation_fee();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="donation-refunded-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('% Refunded');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix"><input
						name="donation-refunded-percent" id="donation-refunded-percent" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('Percent');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_donation_refunded();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="donation-unlock-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Unlock');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">
						<sup><span style="font-size:12px;"><?php echo JText::_('Unlock');?></span></sup>&nbsp;
						<input
						name="donation-refunded-unlock" id="donation-refunded-unlock" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('days after been awarded');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_donation_unlock();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="donation-expiry-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Expire');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">
						<sup><span style="font-size:12px;"><?php echo JText::_('Expire');?></span></sup>&nbsp;
						<input
						name="donation-refunded-expire" id="donation-refunded-expire" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('days after been unlocked');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_donation_expiry();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<!-- ----------------------- -->
	<div id="award-fee-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('% Fee');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix"><input
						name="award-fee-percent" id="award-fee-percent" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('Percent');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_award_fee();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="award-refunded-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('% Refunded');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix"><input
						name="award-refunded-percent" id="award-refunded-percent" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('Percent');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_award_refunded();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="award-unlock-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Unlock');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">
						<sup><span style="font-size:12px;"><?php echo JText::_('Unlock');?></span></sup>&nbsp;
						<input
						name="award-refunded-unlock" id="award-refunded-unlock" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('days after been awarded');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_award_unlock();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="award-expiry-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Expire');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">
						<sup><span style="font-size:12px;"><?php echo JText::_('Expire');?></span></sup>&nbsp;
						<input
						name="award-refunded-expire" id="award-refunded-expire" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('days after been unlocked');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_award_expiry();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="giftcode-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('Others Benefit - Giftcode');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix">
						<sup><span style="font-size:12px;"><?php echo JText::_('Quantity');?></span></sup>&nbsp;
						<input
						name="giftcode_quantity" id="giftcode_quantity" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">
						</div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_giftcode();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
	
	<div id="giftcode-fee-page-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('% Fee');?></h3>
		</div>
		<table class="table table-striped" id="table"
			style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<div class="clearfix"><input
						name="giftcode-fee-percent" id="giftcode-fee-percent" type="text" class="input-large"
						value="" onkeyup="numbersOnly(this);">&nbsp;<sup><span style="font-size:12px;"><?php echo JText::_('Percent');?></span></sup></div>					
					</td>				
				</tr>
			</tbody>
		</table>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Cancel');?></button>
			<button type="button" class="btn btn-primary" onclick="on_giftcode_fee();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('Save & Close');?></button>
		</div>
	</div>
</form>
