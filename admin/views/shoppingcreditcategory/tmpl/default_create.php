<?php

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=shoppingcreditcategory" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
	<div class="col100">
		<table class="table table-hover table-striped">	
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->shopping->name;?>" /></td>
			</tr>			
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->shopping->id;?>">
    <input type="hidden" name="view" value="shoppingcreditcategory">
    <input type="hidden" name="task" value="shoppingcreditcategory.save_create">   
</form>