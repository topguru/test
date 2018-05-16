<?php
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$test_mode = $this->setting_model->invar('test_mode','0');
$sand_box = $this->setting_model->invar('sandbox','0');
$bussiness = $this->setting_model->invar('business','0');
$currency_code = $this->setting_model->invar("currency_code",'0');
$id = JRequest::getVar('id');
$url =JURI::base().'index.php?option=com_awardpackage&view=prize&layout=finish_send&package_id='.$this->package_id.'&winner_id='.JRequest::getVar('winner_id');
$notify_url = JURI::base() . 'index.php?option=com_awardpackage&view=refundrecord&layout=listen';
if($test_mode){
     $destination = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	 $account = $sand_box;
	 $test=1;
}else{
     $destination = 'https://www.paypal.com/cgi-bin/webscr';
     $account = $model->invar('business', '');
     $test = 0;
}
$prizedata = $this->prize_model->get_winner_info(JRequest::getVar('winner_id'));
?>
<form name="_xclick" action="<?php echo $destination;?>" method="post" class="form-validate" id="user-form">
	<input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $prizedata->paypal_account; ?>">
    <input type="hidden" name="currency_code" value="<?php echo strtoupper($currency_code); ?>">
    <input type="hidden" name="custom" value="<?php echo $user_id; ?>">
    <input type="hidden" name="item_name" value="Prize won">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" size="100" name="return" value="<?php echo $url; ?>">
    <input type="hidden" size="100" name="notify_url" value="<?php echo $notify_url; ?>">
    <label>Prize Won Amount</label>&nbsp;&nbsp;&nbsp;<input type="text" name="amount" size="10" readonly="readonly" style="font-size:12px;" value="<?php echo $prizedata->prize_value;?>">
    <div>
    <input type="submit" value="Send" name="send">
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>