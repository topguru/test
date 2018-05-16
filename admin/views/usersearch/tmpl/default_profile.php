<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onNext(){
		jQuery('#task').val('usersearch.save_profile');
		jQuery('form#adminForm').submit();
}

function onEdit(index){
    jQuery('#unlock').val(index);
 	jQuery('#task').val('usersearch.get_profile');
	jQuery('form#adminForm').submit();
	 document.getElementById('btnEdit').style.visibility = "hidden";
	 document.getElementById('btnSave').style.visibility = "visible";
}

 function onSave(index){
//   	jQuery('#task').val('uaccount.edit');
	jQuery('#task').val('usersearch.save_profile');
	jQuery('#unlock').val(index);
	jQuery('form#adminForm').submit();

 }
</script>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">						
        							<?php 
if ($this->unlock == '0') {
$disabled = 'disabled';
}else {
$disabled = '';
}
									foreach ($this->result as $users):?>
			<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=usersearch&task=usersearch.save_profile&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')." ");?>"
	method="post" id="adminForm" name="adminForm" class="form-horizontal">
    
    <input type="hidden" name="email" value="<?php echo $users->email; ?>" />
    <input type="hidden" name="id" value="<?php echo JRequest::getVar('accountId'); ?>" />
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>" />
<fieldset class="well">

<div class="control-group">
														<div class="control-label">Username</div>

							<div class="controls">
								<input type="text" name="username" id="username" value="<?php echo $users->username; ?>" class="validate-username required" size="30" required aria-required="true" <?php echo $disabled;?> />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
                        <div class="control-group">
														<div class="control-label">Email</div>

							<div class="controls">
								<input type="text" name="email" id="email" value="<?php echo $users->email; ?>" class="validate-username required" size="30" required aria-required="true" <?php echo $disabled;?> />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
																				<div class="control-group">
														<div class="control-label">Password</div>

							<div class="controls">
								<input type="password" name="password1" id="password1" value="<?php echo $users->state; ?>" autocomplete="off" class="validate-password required" size="30" maxlength="99" aria-required="true" <?php echo $disabled;?>/>	<span style="color:#FF0000">&nbsp;*</span>						</div>
						</div>
																				<div class="control-group">
							<div class="control-label">Confirm Password</div>

							<div class="controls">
								<input type="password" name="confirmpasw" id="confirmpasw" value="<?php echo $users->state; ?>" autocomplete="off" class="validate-password required" size="30" maxlength="99" aria-required="true" <?php echo $disabled;?>/>		<span style="color:#FF0000">&nbsp;*</span>					</div>
						</div>

<br/><br/>
<div class="control-group">
<div class="control-label">First name</div>
<div class="controls"><input type="text" name="firstname" id="firstname"
	value="<?php echo $users->firstname; ?>" size="25"  required="" aria-required="true" <?php echo $disabled;?>/><span style="color:#FF0000">&nbsp;*</span></div>
    
</div>

<div class="control-group">
<div class="control-label">Last name</div>
<div class="controls"><input type="text" name="lastname" id="lastname"
	value="<?php echo $users->lastname; ?>" size="25" aria-required="true" <?php echo $disabled;?>/><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Gender</div>
<div class="controls"><select name="gender" size="1" <?php echo $disabled;?>>
	<option value="M"
	<?php echo ($users->gender == 'M' ? 'selected=selected' : ''); ?>> <?php echo JText::_('MALE');?></option>
	<option value="F"
	<?php echo ($users->gender == 'F' ? 'selected=selected' : ''); ?>> <?php echo JText::_('FEMALE');?></option>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Country</div>
<div class="controls"><select name="country" size="1" aria-required="true" <?php echo $disabled;?>>
<?php foreach ($this->countries as $country) { ?>
	<option value="<?php echo $country; ?>"
	<?php echo ($users->country == $country ? 'selected=selected' : ''); ?>><?php echo $country; ?></option>
	<?php } ?>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<div class="control-group">
<div class="control-label">Paypal Account</div>
<div class="controls"><input type="text" name="paypal_account" id="paypal_account"
	value="<?php echo $users->paypal_account; ?>" size="25" aria-required="true" <?php echo $disabled;?>/><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<br />
<br />
 <div class="controls">
<?php if ($this->unlock == '0') { ?>
<button type="button" class="btn btn-primary" onclick="onEdit(1);">Edit</button>
<?php } else { ?>
<button type="button" class="btn btn-primary" onclick="onSave(0);">Save</button>
<?php } ?>
</fieldset>
<input type="hidden" name="unlock" id="unlock" value=""  />
<input type="hidden" name="task" id="task" value=""  />
</form>
							<?php endforeach;?>
						
				</div>
		</div>
	</div>
</div>