<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function onContinue(){
	var type = jQuery('input[name=rChoice]:checked', '#myForm').val()
	if(type == "add_transfer") {
		window.location = "index.php?option=com_awardpackage&view=utransfer&task=utransfer.addtransfer";
	} else {
		window.location = "index.php?option=com_awardpackage&view=utransfer&task=utransfer.withdrawtransfer";
	}
}
</script>
<form id="myForm">
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
									<?php 									
									echo JText::_('Transfer'); ?>
								</h2>
                                <?php if (!empty($this->paycheck)) { ?>
								<span style="color:#000;"><i>Your transfer for Quiz and Survey only : $<?php echo number_format($this->remain,2); ?></i><br/></span>		
                                <?php } else { ?>
                                <span style="color:#000;"><a href="index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile">Update </a>your paypall account</i><br/></span>		
                                <?php } ?>

                                
							</div>
						
							<fieldset>
								<legend>Please select one of the following :</legend>					
								<table border="0">
									<tr>
										<td>
											<input type="radio" name="rChoice" value="add_transfer">&nbsp;Add transfer
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>
											<input type="radio" name="rChoice" value="withdraw_transfer">&nbsp;Withdraw transfer
										</td>
									</tr>
								</table>
							</fieldset>	
							<br/>
							<br/>			
						<div class="span12" style="text-align:center;padding-right:10px;">				
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
							id="btn"><i></i> <?php echo JText::_('Cancel');?></button>
							&nbsp;							
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onContinue();" ><i></i> <?php echo JText::_('Continue');?></button>														
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
