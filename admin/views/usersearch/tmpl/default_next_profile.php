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
		jQuery('#task').val('usersearch.registration_next');
		jQuery('form#adminForm').submit();
}

function onBack(index){
		jQuery('#unlock').val(index);
 	    jQuery('#task').val('usersearch.get_profile');
		jQuery('form#adminForm').submit();
}

function onSave(index){
		jQuery('#unlock').val(index);
 	    jQuery('#task').val('usersearch.save_next_profile');
		jQuery('form#adminForm').submit();
}
</script>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">						
        							<?php foreach ($this->result as $users):?>
			<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=usersearch&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')." ");?>"
	method="post" id="adminForm" name="adminForm" class="form-horizontal">
    
    <input type="hidden" name="email" value="<?php echo $users->email; ?>" />
    <input type="hidden" name="id" value="<?php echo JRequest::getVar('accountId'); ?>" />
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>" />
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
<input type="checkbox" name="toc" id="toc" required="" aria-required="true"/>&nbsp; I accept the terms above <span style="color:#FF0000">&nbsp;*</span>
</div>



<div class="control-group">
<div class="control-label">Birthday</div>
<div class="controls"><?php echo JHtml::_('calendar', $users->birthday, 'birthdate', 'birthdate', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
</div>
</div>



<div class="control-group">
<div class="control-label">Phone</div>
<div class="controls"><input type="text" name="phone" id="phone"
	value="<?php echo $users->phone; ?>" size="25" /> </div>

<div class="control-group"></div>
<div class="control-label">Address</div>
<div class="controls">
<?php echo $this->editor->display("street", $users->street, "15", "3", "25", "10", 10, null, null, null, array('mode' => 'extended'));?>

<br />
<div class="controls">
<button type="button" class="btn btn-primary" onclick="onBack(1);">Back</button>

<button type="button" class="btn btn-primary" onclick="onSave(0);">Save</button>
</div>
</fieldset>
<input type="hidden" name="unlock" id="unlock" value=""  />
<input type="hidden" name="task" id="task" value=""  />
</form>
							<?php endforeach;?>
						
				</div>
		</div>
	</div>
</div>