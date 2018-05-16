<?php

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage" method="post">
	<div class="col100">
		<table class="table table-hover table-striped">	
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('Business Email'); ?>" for="paypals"><?php echo JText::_('Business Email'); ?></label></th>
				<td><input class="text_area required" type="text" name="business" id="business" size="32" maxlength="250" 
					value="<?php echo $this->paypals->business;?>" /></td>
			</tr>			
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('Currency Code'); ?>" for="paypals"><?php echo JText::_('Currency Code'); ?></label></th>
				<td><input class="text_area required" type="text" name="currency_code" id="currency_code" size="32" maxlength="250" 
					value="<?php echo $this->paypals->currency_code;?>" /></td>
			</tr>
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LC'); ?>" for="paypals"><?php echo JText::_('LC'); ?></label></th>
				<td><input class="text_area required" type="text" name="lc" id="lc" size="32" maxlength="250" 
					value="<?php echo $this->paypals->lc;?>" /></td>
			</tr>
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('Is Active'); ?>" for="paypals"><?php echo JText::_('Is Active'); ?></label></th>
				<td><input type="checkbox" name="is_active" id="is_active" <?php echo (!empty($this->paypals->is_active) && $this->paypals->is_active == 1 ? "checked=checked" : ""); ?> /></td>
			</tr>
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->paypals->id;?>">
    <input type="hidden" name="view" value="paypals">
    <input type="hidden" name="task" value="paypals.saveCreate">   
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
</form>