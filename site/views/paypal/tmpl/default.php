<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

?>
<?php 
if($this->item->is_test){?>
    <div align="center"><h1><font color="red">THIS IS ON TEST MODE, ALL TRANSACTIONS WILL BE ONLY A FAKE</font></h1></div>
<?php };?>
<div align="center"><h1>PLEASE WAIT, YOU WILL BE REDIRECTED TO PAYPAL SERVER IN FEW SECONDS</h1></div>
<div align="center"><img src="<?php echo JURI::base().'components/com_awardpackage/assets/progress.gif';?>"></div>
    <form name="_xclick" action="<?php echo $this->destination;?>" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php echo $this->account;?>">
        <input type="hidden" name="currency_code" value="<?php echo strtoupper($this->currency_code);?>">
        <input type="hidden" name="custom" value="<?php echo $this->custom;?>">
        <input type="hidden" name="item_name" value="Donation no. <?php echo $this->deposit_number;?>"><!-- -->
        <input type="hidden" name="item_number" value="<?php echo $this->deposit_number;?>">	
        <input type="hidden" name="amount" value="<?php echo $this->amount;?>">
        <input type="hidden" size="100" name="return" value="<?php echo $this->return_url;?>">
        <input type="hidden" size="100" name="notify_url" value="<?php echo $this->item->notify_url;?>">
    <div align="center">
	<input type="image" src="<?php echo JURI::base().'components/com_awardpackage/assets/donate.jpg';?>" border="0" name="submit" title="Click this if too long!" alt="Click this if too long!">
    </div>
</form>
<script>
document._xclick.submit();
</script>