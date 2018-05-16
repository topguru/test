<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$par = null;
if(!empty($parents)){
	$par = $parents[0];
}


?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onNext(){
		jQuery('#task').val('user.registration_next');
		jQuery('form#adminForm').submit();
}
</script>
<div id="cj-wrapper">
<div class="login ">
<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=user&task=user.save");?>"
	method="post" id="adminForm" name="adminForm" class="form-horizontal">
    
    <input type="hidden" name="email" value="<?php echo @$this->email; ?>" />
    <input type="hidden" name="id" value="<?php echo @$this->data['id']; ?>" />
<fieldset class="well">

<div class="control-group">
														<div class="control-label">Username</div>

							<div class="controls">
								<input type="text" name="username" id="username" value="<?php echo @$this->data['username']; ?>" class="validate-username required" size="30" required aria-required="true" />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
                        <div class="control-group">
														<div class="control-label">Email</div>

							<div class="controls">
								<input type="text" name="email" id="email" value="<?php echo @$this->data['email']; ?>" class="validate-username required" size="30" required aria-required="true" />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
																				<div class="control-group">
														<div class="control-label">Password</div>

							<div class="controls">
								<input type="password" name="password1" id="password1" value="" autocomplete="off" class="validate-password required" size="30" maxlength="99" required aria-required="true" />	<span style="color:#FF0000">&nbsp;*</span>						</div>
						</div>
																				<div class="control-group">
							<div class="control-label">Confirm Password</div>

							<div class="controls">
								<input type="password" name="confirmpasw" id="confirmpasw" value="" autocomplete="off" class="validate-password required" size="30" maxlength="99" required aria-required="true" />		<span style="color:#FF0000">&nbsp;*</span>					</div>
						</div>

<br/><br/>
<div class="control-group">
<div class="control-label">First name</div>
<div class="controls"><input type="text" name="firstname" id="firstname"
	value="<?php echo @$this->data['firstname']; ?>" size="25"  required="" aria-required="true"/><span style="color:#FF0000">&nbsp;*</span></div>
    
</div>

<div class="control-group">
<div class="control-label">Last name</div>
<div class="controls"><input type="text" name="lastname" id="lastname"
	value="<?php echo @$this->data['lastname']; ?>" size="25" /><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Gender</div>
<div class="controls"><select name="gender" size="1">
	<option value="M"
	<?php echo (@$this->data['gender'] == 'M' ? 'selected=selected' : ''); ?>> <?php echo JText::_('MALE');?></option>
	<option value="F"
	<?php echo (@$this->data['gender'] == 'F' ? 'selected=selected' : ''); ?>> <?php echo JText::_('FEMALE');?></option>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Country</div>
<div class="controls"><select name="country" size="1" aria-required="true">
<?php foreach (@$this->countries as $country) { ?>
	<option value="<?php echo $country; ?>"
	<?php echo (@$this->data['country'] == $country ? 'selected=selected' : ''); ?>><?php echo $country; ?></option>
	<?php } ?>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<div class="control-group">
<div class="control-label">Paypal Account</div>
<div class="controls"><input type="text" name="paypal_account" id="paypal_account"
	value="<?php echo @$this->data['paypal_account']; ?>" size="25" /><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<br />
<br />
<div class="controls">
<button type="submit" class="btn btn-primary" >Next</button>
</div>
</fieldset>
</form>
</div>
