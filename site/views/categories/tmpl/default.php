<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
     	return false;
     	return true;
 }
</script>
<input type="hidden" value="<?php echo JRequest::getVar('package_id');?>" name="package_id" >
<?php if(count($this->items)>0){?>

<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'?option=com_awardpackage&controller=donation&package_id='.JRequest::getVar('package_id').'&task=preview';?>" name="adminForm" class="form-validate" id="user-form">
	<h1><?php echo JText::_('Make donation');?></h1>
	<div class="clear"></div>
	<div class="tableright">
		<label for="payment_gateway">
			<?php echo JText::_('Payment Options');?>
		</label>
		<select name="payment_gateway" class="select">
			<option value="paypal"><?php echo JText::_('PAYPAL');?></option>
		</select>
	</div>
	<br class="clear"/>
	<div class="hr"></div>
	<br class="clear"/>
	<?php foreach($this->items as $i => $item): ?>
	<input type="hidden" value="<?php echo $item->setting_id;?>" name="id_item[]">
	<input type="hidden" value="<?php echo $item->category_id;?>" name="category_id[]">
	<div style="float:left;width:70px">
		<div align="center" style="margin:5px;width:50px;background-color:<?php echo $item->colour_code;?>">
			<font color="white" size="6"><b><?php echo $item->category_id; ?></b></font>
		</div>
	</div>
	<div align="center" valign="middle" style="float:left;margin:15px;width:200px;">
		<b>donate <?php echo $this->iscent($item->donation_amount); ?></b>
	</div>
	<div style="float:left;margin:12px;width:70px;">
		<input type="text" style="text-align:right" size="10" name="quantity[]" onkeypress="return isNumberKey(event)" class="hasTip" title="Number Only">
	</div>
	<div style="float:left;margin:15px;width:70px;">
		times
	</div>
	<div class="clear"></div>
	<?php endforeach; ?>
	
	<div class="clear"></div>
		<input type="submit" value="NEXT" name="submit" class="btn">
	<div>
		<input type="hidden" name="option" value="com_awardpackage" />
		<input type="hidden" name="controller" value="donation" />				
		<input type="hidden" name="task" value="preview" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php }else{?>
	you have no access to this page
<?php };?>