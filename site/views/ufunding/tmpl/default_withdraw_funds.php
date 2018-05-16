<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onCancel(){
	window.location = "index.php?option=com_awardpackage&view=ufunding&task=ufunding.getMainPage";
}
function onContinue(){
	jQuery('#task').val('ufunding.withdrawFundsConfirm');
	jQuery('form#adminForm').submit();	
}
</script>
<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=ufunding')?>" method="post">
<input type="hidden" name="task" id="task" value=""/>
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
									<?php echo JText::_('Withdraw Funds'); ?>
								</h2>
								<br/>
								<label>Funds remain: $ <?php echo number_format($this->remain,2); ?></label>		
							</div>
						</div>		
						<div class="span12">
							<fieldset>
								<legend>Please enter amount to withdraw :</legend>					
								<table border="0">
									<tr>
										<td>
											<input type="text" name="amount" style="width:200px;" onkeyup="numbersOnly(this);"/>
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
										id="btn" onclick="onCancel();"><i></i> <?php echo JText::_('Cancel');?></button>				
							&nbsp;
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onContinue();"><i></i> <?php echo JText::_('Continue');?></button>				
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
