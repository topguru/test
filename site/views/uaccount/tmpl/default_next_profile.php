<?php
defined('_JEXEC') or die();
JHTML::_('behavior.modal');

?>

<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');	
}

function onNext(index){
       /* jQuery('#unlock').val(index);
		jQuery('#task').val('uaccount.next_profile');
		jQuery('form#adminForm').submit();*/
		window.location = 'index.php?option=com_awardpackage&view=uaccount&task=uaccount.next_profile';
}

function onBack(index){
				jQuery('#unlock').val(index);
				window.location = 'index.php?option=com_awardpackage&view=uaccount&task=uaccount.get_profile';

}

function onSave(index){
		jQuery('#unlock').val(index);
 	    jQuery('#task').val('uaccount.updateInfo');
		jQuery('form#adminForm2').submit();
}

function onEdit(index){
    jQuery('#unlock').val(index);
 	jQuery('#task').val('uaccount.next_profile');
	jQuery('form#adminForm2').submit();
	 document.getElementById('btnEdit').style.visibility = "hidden";
	 document.getElementById('btnSave').style.visibility = "visible";
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
$disabledc =  array('placeholder' => 'YYYY-MM-DD', 'disabled' => '' ) ;
$disableda = '<textarea disabled>'.$this->user->street.'</textarea>	 ';
}else {
$disabled = '';
$disabledc =  array('placeholder' => 'YYYY-MM-DD' ) ;
$disableda = $this->editor->display("street", $this->user->street, "15", "3", "25", "10", 10, null, null, null, array('mode' => 'extended','readonly'=>'readonly'));

}

?>							

<div class="login">
							<form
								action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount");?>"
								method="post" class="form-horizontal" id="adminForm2">
    
    <input type="hidden" name="accountId" value="<?php echo $this->user->id; ?>" />
    <input type="hidden" name="package_id" value="<?php echo $this->user->package_id; ?>" />
	<input type="hidden" name="unlock" id="unlock" value=""  />
	<input type="hidden" name="task" id="task" value=""  />
	
<fieldset class="well">

<div class="control-group">
 <span style="color:#FF0000">Read the following terms before you proceed!!<br/></span>
<ul  style="text-align:justify;padding:10px;">
<li>You do NOT require to input your birthday, phone or address to participate in our surveys, quizzes and other activities on our website </li>

<li>We will require your birthday, phone and address ONLY when you need to claim any prizes that you have won on our website</li>

<li>To prevent identity fraud, you will NOT be allowed to claim the prizes that you have won if the personal information you have provided to us on our website  is incorrect or fake. </li>
<li> Your personal information refers to your first name, last name, email, gender, birthday, phone and address.</li>
<li>Incorrect or fake personal information refers to information that do not match the true and correct personal information on your drivers license or passport.</li>
</ul>
<input type="checkbox" name="toc" id="toc" required="" aria-required="true" <?php echo $disabled;?> />&nbsp; I accept the terms above <span style="color:#FF0000">&nbsp;*</span>
</div>



<div class="control-group">
<div class="control-label">Birthday</div>
<div class="controls">
<?php echo JHtml::_('calendar', $this->user->birthday, 'birthdate', 'birthdate', '%Y-%m-%d', $disabledc ); ?>
</div>
</div>



<div class="control-group">
<div class="control-label">Phone</div>
<div class="controls"><input type="text" name="phone" id="phone"
	value="<?php echo $this->user->phone; ?>" size="25"  <?php echo $disabled;?> />	 
</div>
</div>

<div class="control-group">
<div class="control-label">Address</div>
<div class="controls">
<?php echo $disableda ;?>
</div>
</div>

<br />
<div class="controls">
<button type="button" class="btn btn-primary" onclick="onBack(1);">Back</button>
<?php if ($this->unlock == '0') { ?>
<button type="button" class="btn btn-primary" onclick="onEdit(1);">Edit</button>
<?php } else { ?>
<button type="button" class="btn btn-primary" onclick="onSave(0);">Save</button>
<?php } ?>
</div>
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

