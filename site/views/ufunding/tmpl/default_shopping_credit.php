<?php
defined('_JEXEC') or die();
foreach ($this->paypals as $paytos){		
		$payto = $paytos->business; }
		
?>
<script type="text/javascript">
function gotoPaypal(){	
	jQuery('form#adminForm').attr('action','https://www.paypal.com/cgi-bin/webscr');
	jQuery('form#adminForm').attr('target','_blank');
	jQuery('form#adminForm').submit();
	return true;
}

function onNext(){
	jQuery('#task').val('ufunding.addShoppingCreditConfirm');
	jQuery('form#adminForm').submit();
}


function onConfirmPaypal(){
	jQuery('#task').val('ufunding.confirm');
	var paratemers = jQuery('form#adminForm').serialize();
	jQuery.ajax({
        type: "POST",
        url: "index.php?option=com_awardpackage&view=ufunding",
        data: paratemers,
        success:function(response){
        	jQuery('#message').html('Successful add new fund, please complete the process using paypal');
			jQuery('form#instantpaypal').submit();
			document.getElementById('btn_confirm').style.visibility = "hidden";
			document.getElementById('btn_more').style.visibility = "visible";

        },
        error: function (request, status, error) {
            
        }                  
    });  
}

function onBack(){
	window.location = "index.php?option=com_awardpackage&view=ufunding&task=ufunding.addFunds";
}

function onMore(){
	window.location = "index.php?option=com_awardpackage&view=ufunding&task=ufunding.addFunds";
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

<form id="adminForm" name="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=ufunding')?>"
	method="post"><!-- 
<input type="hidden" name="business" value="sf6684@yahoo.com" />
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="item_name" value="" />							  	
<input type="hidden" name="currency_code" value="USD" /> 
<input type="hidden" name="lc" value="US" />
<input type="hidden" name="charset" value="utf-8" />
 --> 
<input type="hidden" name="task" id="task" value=""/>
<input type="hidden" name="amount" value="<?php echo $this->amount; ?>">
<input type="hidden" name="difference" value="<?php echo $this->amount; ?>">
<input type="hidden" name="user_id" value="<?php echo $this->userId;?>">
<input type="hidden" name="package_id" value="<?php echo $this->package_id; ?>">
<div id="cj-wrapper">
<div
	class="container-fluid no-space-left no-space-right surveys-wrapper">
<div class="row-fluid">
<table>
	<tr>
		<td valign="top"><?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
		</td>
		<td valign="top">
		<div class="span12">
		<div class="well">
        <h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Add Funds'); ?>
								</h2>	
        <table>
        <tr><td>Total funds</td><td>: $</td>
        <td><?php echo number_format($this->amount,2); ?></td>
        </tr>
        <tr><td>Shopping credits</td><td>: $</td>
        <td><?php echo number_format($this->totalsc,2); ?></td>
        </tr>
        <?php $this->difference = $this->amount - $this->totalsc;?>
        <?php if ($this->totalsc < $this->amount) {?>
        <tr><td>The difference</td><td>: $ </td>
        <td><?php echo number_format($this->difference,2); ?></td>
        </tr>
        <?php } ?>
        </table>
		<?php if ( $this->difference > 0) {?>
		<span id="message" style="color:red;">
         You do not have enough shopping credits,
please select one of the following options:</span>
<?php }?>
		</div>
		</div>
		<div class="span12">
		<fieldset>
		<table border="0">
				<?php if ($this->totalsc < $this->amount) {?>
			<tr>
				<td>
                <input type="radio" name="rChoice" value="1" onclick="onConfirmSC()"/>&nbsp;Use paypal to pay for the difference of <?php echo '$ '.number_format($this->difference,2); ?><br/>   
                <input type="hidden" name="difference" id="difference" value="<?php echo $this->difference; ?>" />
            
				</td>
			</tr>
            <?php } ?>
			<tr>
				<td>
                <input type="radio" name="rChoice" value="1" onclick="onBack()"/>&nbsp;Take me back to the add funds page<br/>                
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		</fieldset>
		<br />
		<br />
		</div>
		<div class="span12" style="padding:20px;height:30px;text-align:right;">

		 <button type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn" onclick="onNext();"> <?php echo JText::_('Next');?></button>
						</div>
		</td>
	</tr>
</table>
</div>
</div>
</div>
</form>

