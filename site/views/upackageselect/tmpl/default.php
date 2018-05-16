<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">

<div class="login ">
<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=upackageselect&task=upackageselect.doSelectPackage");?>"
	method="post" class="form-horizontal">
<fieldset class="well">
<div class="control-group">

<div class="control-group">
<div class="control-label"><label id="password-lbl" for="password"
	>Package</label></div>
<div class="controls">
<select name="package">
	<?php foreach ($this->packages as $package){ ?>
	<option value="<?php echo $package->package_id; ?>"><?php echo $package->package_name; ?></option>
	<?php } ?>
</select>
</div>
</div>

<div class="controls">
<button type="submit" class="btn btn-primary">Select Package</button>

</div>

</fieldset>
</form>

</div>
</div>


