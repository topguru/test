<?php
defined('_JEXEC') or die();
JHTML::_('behavior.modal');

?>

<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}
function onNext(index){
        jQuery('#unlock').val(index);
		/*jQuery('#task').val('uaccount.next_profile');
		jQuery('form#adminForm').submit();*/
		window.location = 'index.php?option=com_awardpackage&view=uaccount&task=uaccount.next_profile';
}

function onEdit(index){
    jQuery('#unlock').val(index);
 	jQuery('#task').val('uaccount.get_profile');
	jQuery('form#adminForm').submit();
	 document.getElementById('btnEdit').style.visibility = "hidden";
	 document.getElementById('btnSave').style.visibility = "visible";
}

 function onSave(index){
//   	jQuery('#task').val('uaccount.edit');
	jQuery('#task').val('uaccount.save_profile');
	jQuery('#unlock').val(index);
	jQuery('form#adminForm').submit();

 }
</script>
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td width="10%" valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<?php echo '<div class="span12" style="margin:0;">'; ?>
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Account'); ?>
								</h2>				
								<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile");?>">Profile</a></li>
                               <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getFunds");?>">Funds</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getDonation");?>">Donation</a>	</li>
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getAwardSymbol");?>">Award Symbol</a>	</li>                                
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getShoppingCredit");?>">Shopping Credit</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize");?>">Prize Claimed</a>		</li>	<br />
</ul>
</nav>                                
						</div>
<?php 

if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12" style="margin:0;">';
} 

if ($this->unlock == '0') {
$disabled = 'disabled';
}else {
$disabled = '';
}

?>							

<div class="login">
							<form
								action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount");?>"
								method="post" class="form-horizontal" id="adminForm">
								
	<input type="hidden" name="email" value="<?php echo $this->user->email; ?>" />
    <input type="hidden" name="accountId" value="<?php echo $this->user->id; ?>" />
    <input type="hidden" name="package_id" value="<?php echo $this->user->package_id; ?>" />

<fieldset class="well">

<div class="control-group">
														<div class="control-label">Username</div>

							<div class="controls">
								<input type="text" name="username" id="username" value="<?php echo $this->user->username; ?>" class="validate-username required" size="30" aria-required="true" <?php echo $disabled;?> />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
                        <div class="control-group">
														<div class="control-label">Email</div>

							<div class="controls">
								<input type="text" name="email" id="email" value="<?php echo $this->user->email; ?>" class="validate-username required" size="30" aria-required="true" <?php echo $disabled;?> />				<span style="color:#FF0000">&nbsp;*</span>			</div>
						</div>
																				<div class="control-group">
														<div class="control-label">Password</div>

							<div class="controls">
								<input type="password" name="password1" id="password1" value="<?php echo $this->user->state; ?>" autocomplete="off" class="validate-password required" size="30" maxlength="99" <?php echo $disabled;?>/>	<span style="color:#FF0000">&nbsp;*</span>						</div>
						</div>
																				<div class="control-group">
							<div class="control-label">Confirm Password</div>

							<div class="controls">
								<input type="password" name="confirmpasw" id="confirmpasw" value="<?php echo $this->user->state; ?>" autocomplete="off" class="validate-password required" size="30" maxlength="99" <?php echo $disabled;?>/>		<span style="color:#FF0000">&nbsp;*</span>					</div>
						</div>

<br/><br/>
<div class="control-group">
<div class="control-label">First name</div>
<div class="controls"><input type="text" name="firstname" id="firstname"
	value="<?php echo $this->user->firstname; ?>" size="25"  required="" aria-required="true" <?php echo $disabled;?>/><span style="color:#FF0000">&nbsp;*</span></div>
    
</div>

<div class="control-group">
<div class="control-label">Last name</div>
<div class="controls"><input type="text" name="lastname" id="lastname"
	value="<?php echo $this->user->lastname; ?>" size="25" <?php echo $disabled;?>/><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Gender</div>
<div class="controls"><select name="gender" size="1" <?php echo $disabled;?>>
	<option value="M"
	<?php echo ($this->user->gender == 'M' ? 'selected=selected' : ''); ?>> <?php echo JText::_('MALE');?></option>
	<option value="F"
	<?php echo ($this->user->gender == 'F' ? 'selected=selected' : ''); ?>> <?php echo JText::_('FEMALE');?></option>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>

<div class="control-group">
<div class="control-label">Country</div>
<div class="controls"><select name="country" size="1" aria-required="true" <?php echo $disabled;?>>
<?php foreach ($this->countries as $country) { ?>
	<option value="<?php echo $country; ?>"
	<?php echo ($this->user->country == $country ? 'selected=selected' : ''); ?>><?php echo $country; ?></option>
	<?php } ?>
</select><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<div class="control-group">
<div class="control-label">Paypal Account</div>
<div class="controls"><input type="text" name="paypal_account" id="paypal_account"
	value="<?php echo $this->user->paypal_account; ?>" size="25"<?php echo $disabled;?> /><span style="color:#FF0000">&nbsp;*</span></div>
</div>
<br />
<br />
 <div class="controls">
<?php if ($this->unlock == '0') { ?>
<button type="button" class="btn btn-primary" onclick="onEdit(1);">Edit</button>
<?php } else { ?>
<button type="button" class="btn btn-primary" onclick="onSave(1);">Save</button>
<?php } ?>
<button type="button" class="btn btn-primary" onclick="onNext(1);">Next</button>

</fieldset>
<input type="hidden" name="unlock" id="unlock" value=""  />
<input type="hidden" name="task" id="task" value=""  />
</fieldset>
</form>

							</div>
						</div>	
					</td>
				</tr>
			</table>									
		</div>
	</div>
</div>

