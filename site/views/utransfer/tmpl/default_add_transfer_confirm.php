<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onCancel(){
	window.location = "index.php?option=com_awardpackage&view=ufunding&task=utransfer.addTransfer";
}
function onContinue(){
	jQuery('#task').val('utransfer.addTransferComplete');
	jQuery('form#adminForm').submit();
	//window.location = "index.php?option=com_awardpackage&view=utransfer&task=utransfer.addFundsConfirm";
}
</script>
<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=utransfer')?>" method="post">
<input type="hidden" name="task" id="task" value="">
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">	
			<table>
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<div class="span12">
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Transfer to personal'); ?>
								</h2>		
                                <br/>
								<h4>Total shopping credit:  <?php echo number_format($this->totalsc,2); ?></h4>	
							</div>
						</div>		
						<div class="span12">
							<fieldset>
                            <p>Please click 'Next' to transfer the following amount to your<br/>
personal shopping credit account
                            <p>
								<h4>Transfer to personal :	<?php echo number_format($this->totalsc,2); ?></h4>				
								<table border="0">
									<tr>
										<td>
											<input type="hidden" name="amount" style="width:200px;" onkeyup="numbersOnly(this);"/>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>						
								</table>
							</fieldset>	
							<br/>
							<br/>			
						</div>
						<div class="span12" style="text-align:center;padding-right:10px;">
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onCancel();"><i></i> <?php echo JText::_('Back');?></button>
							&nbsp;
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onContinue();"><i></i> <?php echo JText::_('Next');?></button>				
							<br/>
							<br/>
						</div>
					</td>
				</tr>
			</table>	
		</div>
	</div>
</div>
</form>
