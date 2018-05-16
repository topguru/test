<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onNext(credit){
	var times = new Array();
	var donations = new Array();
	var category  = new Array();
	jQuery('input.times').each(function(index){
		times[index] = parseFloat(jQuery(this).val());		
	});

	jQuery('input.category').each(function(index){
		category[index] = parseFloat(jQuery(this).val());		
	});
	
	jQuery('input.donations').each(function(index){
		donations[index] = parseFloat(jQuery(this).val());		
	});

	var totalDonations = 0;
	for(var i = 0; i < donations.length-1; i++){
		totalDonations += times[i] << 0;
	}
	//alert (totalDonations);

		jQuery('#task').val('udonation.doCalculate');
		jQuery('form#adminForm').submit();
}
</script>
<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=udonation')?>" method="post">
<input type="hidden" name="task" id="task" value=""/>
<input type="hidden" name="package_id" value="<?php echo $this->package_id;?>">

<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table>
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
<?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>								<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Make a Donation'); ?>
								</h2>
                                <?php 
								/* Code commented by sushil on 30-11-2015 */
								/*if (!empty($this->paycheck)) { 
								/*?>		
								<span style="color:#000;"><i>Your Donations for Quiz and Survey only : $<?php echo number_format($this->remain,2); ?></i></span>
                                <?php } else { ?>
                                <span style="color:#000;"><a href="index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile">Update </a>your paypall account</i><br/></span>		
                                <?php } */?>

							</div>
							<br/>
							<br/>
						<div class="span12">
							<table class="table table-hover table-striped">
								<tbody>
									<?php 
										foreach ($this->categories as $item) { 
									?>
									<tr>
										<td width="20%" valign="top">
											<table>
												<tr>
													<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $item->colour_code;?>" valign="center">
													<font color="white" size="5"><b><?php echo $item->category_id; ?></b></font>
													</td>
												</tr>
											</table>
										</td>
										<td width="20%" valign="top">
											<?php echo JText::_('Donate ' . ((float)$item->donation_amount * 100) . ' cents'); ?>
										</td>
										<td>
											<input type="text" name="times[]" class="times" style="width:200px;" onkeyup="numbersOnly(this);"/>&nbsp;&nbsp;<?php echo JText::_('times'); ?>
											<input type="hidden" name="category[]" class="category" value="<?php echo $item->category_id; ?>" />
                                            <input type="hidden" name="settingId[]" class="settingId" value="<?php echo $item->setting_id; ?>" />
											<input type="hidden" name="donations[]" class="donations" value="<?php echo $item->donation_amount;  ?>">
											<input type="hidden" name="colors[]" class="colors" value="<?php echo $item->colour_code;  ?>">
                                            <input type="hidden" name="kredit[]" class="colors" value="<?php echo $this->remain;  ?>">
                                            <input type="hidden" name="setting[]" class="setting" value="<?php echo $item->setting_id; ?>" />
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>		
						</div>
						<div class="span12" style="text-align:right;padding-right:10px;">
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onNext(<?php echo (float) $this->remain ?>);"
										<?php //echo ((int)$this->remain <= 0 ? 'disabled=disabled' : ''); ?> ><i></i> <?php echo JText::_('Next');?></button>				
							<br/>
							<br/>
						</div>
					</td>
				</tr>
			</table>	
            						</div>		

		</div>
	</div>
</div>
</form>
