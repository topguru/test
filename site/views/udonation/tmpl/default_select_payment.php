<?php
defined('_JEXEC') or die();
foreach ($this->paypals as $paytos){		
		$payto = $paytos->business; }
?>
<script type="text/javascript">
function onConfirmSC(){
	jQuery('#task').val('udonation.addShoppingCredit');
	jQuery('form#adminForm').submit();
}

function onConfirm(){
	jQuery('#task').val('udonation.confirm');
	var paratemers = jQuery('form#adminForm').serialize();
	jQuery.ajax({
        type: "POST",
        url: "index.php?option=com_awardpackage&view=udonation",
        data: paratemers,
        success:function(response){
        	jQuery('#message').html('Successful add new donation, please complete the process using paypal');
			jQuery('form#instantpaypal').submit();
			document.getElementById('btn_more').style.visibility = "visible";
        },
        error: function (request, status, error) {
            
        }                  
    });  
}
function onBack(){
	window.location = "index.php?option=com_awardpackage&view=udonation&task=udonation.getMainPage";
}

function onMore(){
	window.location = "index.php?option=com_awardpackage&view=udonation&task=udonation.getMainPage";
}

function onNext(){
 var selValue = jQuery('input[name=rChoice]:checked').val(); 
 if ( selValue == '0'){
	jQuery('#task').val('udonation.addShoppingCredit');
	jQuery('form#adminForm').submit();
	}else
	{
	jQuery('#task').val('udonation.confirm');
	var paratemers = jQuery('form#adminForm').serialize();
	jQuery.ajax({
        type: "POST",
        url: "index.php?option=com_awardpackage&view=udonation",
        data: paratemers,
        success:function(response){
        	jQuery('#message').html('Successful add new donation, please complete the process using paypal');
			jQuery('form#instantpaypal').submit();
			document.getElementById('btn_more').style.visibility = "visible";
        },
        error: function (request, status, error) {
            
        }                  
    }); 
	}	
}
</script>
<jdoc:include type="message" />
<form name="instantpaypal" id="instantpaypal"
	action="https://www.paypal.com/cgi-bin/webscr" method="get"
	target="_blank"><input type="hidden" name="business"
	value="<?php echo $payto;?>" /> <input type="hidden" name="cmd"
	value="_xclick" /> <input type="hidden" name="amount"
	value="<?php echo $this->amount; ?>" /> <input type="hidden"
	name="item_name" value="" /> <input type="hidden" name="currency_code"
	value="USD" /> <input type="hidden" name="lc" value="US" /> <input
	type="hidden" name="charset" value="utf-8" />
</form>

<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=udonation')?>" method="post">
<input type="hidden" name="task" id="task" value="udonation.confirm"/>
<input type="hidden" name="amount" value="<?php echo $this->amount; ?>">
<input type="hidden" name="difference" value="<?php echo $this->amount; ?>">
<input type="hidden" name="user_id" value="<?php echo $this->userId;?>">
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
						<div class="span12">
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Add Donation : $ ' ); 
									echo number_format($this->amount,2);
									?>
								</h2>	
								<br/>
								<span id="message" style="color:red;"></span>
							</div>
						</div>			
						<div class="span12">
							<fieldset>
								<table border="0">
		
			<tr>
				<td>
                <input type="radio" name="rChoice" id="rChoice" value="0" />&nbsp;Shopping Credits<br/>                
				</td>
			</tr>
			<tr>
				<td>
                <input type="radio" name="rChoice" id="rChoice" value="1" />&nbsp;Paypal<br/>                
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
							</fieldset>	
                            <div class="span12" style="padding-left:20px;height:30px;">
<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onNext();"> <?php echo JText::_('Next');?></button>
                                        
        <button style="visibility:hidden;" type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_more" onclick="onMore();"> <?php 
			echo JText::_('Add More Donation');?></button>
            
   
        <button style="visibility:hidden;" type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_more" onclick="onBack();"> <?php echo JText::_('Go Back');?></button>
            </div>
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
