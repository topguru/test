<?php

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=payout" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
	<div class="col100">
					<table class="table table-striped table-hover table-bordered" style="width:50%;">
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->shopping->name;?>" /></td>
			</tr>	
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Funding Values'); ?></label></th>
				<td></td>
			</tr>	
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" style="text-align:right;"><?php echo JText::_('From prize value'); ?></label></th>
				<td><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->shopping->name;?>" /></td>
			</tr>
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" style="text-align:right;"><?php echo JText::_('To prize value'); ?></label></th>
				<td><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->shopping->name;?>" /></td>
			</tr>
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Description'); ?></label></th>
				<td><textarea class="" id="" name="" ></textarea>
               </td>
			</tr>			
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->shopping->id;?>">
    <input type="hidden" name="view" value="payout">
    <input type="hidden" name="task" value="payout.save_create">   
</form>