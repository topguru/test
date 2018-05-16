<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form name="_xclick" action="<?php echo JRequest::getVar('destination');?>" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo JRequest::getVar('bussiness'); ?>">
    <input type="hidden" name="currency_code" value="<?php echo strtoupper(JRequest::getVar('currency_code')); ?>">
    <input type="hidden" name="custom" value="<?php echo JRequest::getVar('custom'); ?>">
    <input type="hidden" name="item_name" value="symbol order">
    <input type="hidden" name="item_number" value="<?php echo JRequest::getVar('ordernumber');?>">
    <input type="hidden" size="100" name="return" value="<?php echo JRequest::getVar('return_url'); ?>">
    <input type="hidden" size="100" name="notify_url" value="<?php echo JRequest::getVar('notify_url'); ?>">
    <input type="hidden" name="amount" size="10" value="<?php echo JRequest::getVar('amount');?>">
</form>
Please wait to complate your payment 
<script>
document._xclick.submit();
</script>