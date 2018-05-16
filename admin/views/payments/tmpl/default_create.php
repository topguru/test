<?php

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage" method="post">
	<div class="col100">
		<table class="table table-hover table-striped">	
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="payment"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td><input class="text_area required" type="text" name="opt" id="opt" size="32" maxlength="250" value="<?php echo $this->payment->option;?>" /></td>
			</tr>			
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->payment->id;?>">
    <input type="hidden" name="view" value="payments">
    <input type="hidden" name="task" value="payments.save_create">   
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
</form>