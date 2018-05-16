<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function onRate(adminForm){
	//var rate = document.getElementById('rate');
	//var total = document.getElementById('total');
		var rate = parseInt(jQuery('#rate').val());
		var total = parseInt(jQuery('#totalP').val());
	var persen = total + rate;

	//alert (totalDonations);
	if( persen > 100){
		alert("Total Percentage exceed");
		adminform.rate.focus();
		return false;
	} 
			return true;

}
</script>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=awardfundplan" method="post" onsubmit="return onRate(this);">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
    <input type="text" name="totalP" id="totalP" value="<?php echo $this->totalP; ?>"/>
	<div class="col100">
					<table class="table table-striped table-hover table-bordered" style="width:60%;">
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td colspan="3"><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->awardfund->name;?>" /> </td>
			</tr>	
            <tr>
				<th width="150px" style="border-right:none;"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Funding Rate'); ?></label></th>
<td><input class="text_area" type="text" name="rate" id="rate" onchange="onRate();" size="32" maxlength="3" value="<?php echo $this->awardfund->rate;?>" />
</td>
<td colspan="2" style="border-left:none;">
Percent of total award funds</td>			</tr>	
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Funding Speed'); ?></label></th>
				<td><input class="text_area" type="text" name="speed" id="speed" size="32" maxlength="250" value="<?php echo $this->awardfund->speed;?>" /></td>
                <th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Per'); ?></label></th>
				<td><select id="duration" name="duration">
                <option value="1">Second</option>
                <option value="2">Minute</option>
                <option value="3">Hour</option>
                </select>
                </td>
			</tr>
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award Funds Amount'); ?></label></th>
				<td colspan="3"><input class="text_area" type="text" name="amount" id="amount" size="32" maxlength="250" value="<?php echo $this->awardfund->amount;?>" /></td>
			</tr>
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Description'); ?></label></th>
				<td colspan="3"><textarea class="" id="description" name="description" ><?php echo $this->awardfund->description;?></textarea>
               </td>
			</tr>			
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->awardfund->id;?>">
    <input type="hidden" name="view" value="awardfundplan">
    <input type="hidden" name="task" value="awardfundplan.save_create">   
</form>