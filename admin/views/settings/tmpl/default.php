<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

?>
<div id="j-main-container" class="span10">
<form method="POST"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=donation');?>"
	name="adminForm">
<table align="center" width="100%" class="" border="0">
	<input type="hidden" value="paypal" name="payment_gateway[]">
	<tr>
		<td>Currency</td>
		<td><select name="currency_code">
			<option <?php if($this->currency_code=='AUD') echo ' selected';?>>AUD</option>
			<option <?php if($this->currency_code=='USD') echo ' selected';?>>USD</option>
			<option <?php if($this->currency_code=='EUR') echo ' selected';?>>EUR</option>
		</select></td>
	</tr>
	<tr>
		<td>Amount unit</td>
		<td><input type="checkbox" value="1" name="currency_unit"
		<?php echo $this->checked;?>> in cents.</td>
	</tr>
</table>
<div><input type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?></div>
</form>
</div>
