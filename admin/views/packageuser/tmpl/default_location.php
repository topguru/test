<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');

$groups = $this->ug->getNameUserGroupPresentation($this->package_id, 'location');
$group = null;
if(!empty($groups)){
	$group = $groups[0];
}
$parents = $this->ug->getParentUserGroup($this->package_id,'location');
$par = null;
if(!empty($parents)){
	$par = $parents[0];
}
?>
<form OnSubmit="return validNumber('loc');" action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&package_id='.$this->package_id); ?>" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
	<div class="span12">
		<div class="clearfix">
			<label><?php echo JText::_('Group Name');?></label>
			<input name="jform[group_name]" type="text" value="<?php echo $this->escape($group->group_name);?>" placeholder="<?php echo JText::_('Enter a group name');?>" required="" aria-required="true">
		</div>		
	</div>
        <div style="border:1px solid #fff;">

    <table width="100%" cellpadding="1" cellspacing="1"
		class="table-striped"
		style="border: 1px solid #cccccc; font-size: 10pt;">
		<thead>
			<tr style="text-align: center; background-color: #CCCCCC">
				<td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
			</tr>
			<tr>
				<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
				<th align="center"><?php echo $this->form->getLabel('state'); ?></th>
				<th align="center"><?php echo $this->form->getLabel('city'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="left" style="padding-left:15px;"><input type="text" name="jform[population]"
					id="jform_population" value="<?php echo $this->population; ?>"
					class="inputbox" size="5" style="width: 50px;" required=""
					aria-required="true" aria-invalid="false">%</td>
				<td align="center"><input type="text" name="jform[state]"
					id="jform_state" value="<?php echo ($par->state != '' ? $par->state : $this->state); ?>"
					class="inputbox" size="5" style="width: 100px;" aria-invalid="false" <?php echo ($par->state != '' ? 'readonly=readonly' : ''); ?> ></td>
				<td align="center"><input type="text" name="jform[city]"
					id="jform_city" value="<?php echo ($par->city != '' ? $par->city : $this->city); ?>" class="inputbox"
					size="5" style="width: 130px;" aria-invalid="false" <?php echo ($par->city != '' ? 'readonly=readonly' : ''); ?>></td>
			</tr>
			<tr>
				<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('street'); ?></th>
				<th align="center"><?php echo $this->form->getLabel('post_code'); ?></th>
				<th>Country</th>
			</tr>
			<tr>
				<td align="left" style="padding-left:15px;"><input type="text" name="jform[street]"
					id="jform_street" value="<?php echo ($par->street != '' ? $par->street : $this->street); ?>"
					class="inputbox" size="5" style="width: 100px;" aria-invalid="false" <?php echo ($par->street != '' ? 'readonly=readonly' : ''); ?>>
				</td>
				<td align="center"><input type="text" name="jform[post_code]"
					id="jform_post_code" value="<?php echo ($par->post_code != '' ? $par->post_code : $this->post_code); ?>"
					class="hasTip" title="number only" size="5" style="width: 100px;"
					onkeypress="return isNumberKey(event)" <?php echo ($par->post_code != '' ? 'readonly=readonly' : ''); ?> /> 
				</td>
				<td align="center"><select name="jform[country]"
					style="width: 150px;">
					<?php
					foreach ($this->countries as $country) {
						?>
					<option value="<?php echo $country;?>"
					<?php echo ($this->country == $country ? "selected=selected" : ""); ?>>
					<?php echo $country;?></option>
					<?php
					}
					?>
				</select></td>
			</tr>
			<tr>
				<td align="left" style="padding-left: 15px;">
					<button value="Save" class="btn-add"
					<?php echo (empty($parents) && $command == '1' ? "" : ""); ?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</tbody>
	</table>
    <br/>
    <br/>
    </div>
    <table width="100%" cellpadding="1" cellspacing="1"	class="table-striped"style="border: 1px solid #cccccc; font-size: 10pt;">
        <thead>
			<tr>
				<td colspan="7" align="center" height="50" class="td-group"
					style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
			</tr>
			<tr>
				<th align="left" style="padding-left:15px;"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
				<th><?php echo JText::_('Address'); ?></th>
				<th><?php echo JText::_('COM_REFUND_LBL_CITY'); ?></th>
				<th><?php echo JText::_('Zip'); ?></th>
				<th><?php echo JText::_('COM_REFUND_LBL_STATE'); ?></th>
				<th><?php echo JText::_('Country'); ?></th>			
				<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
			</tr>
		</thead>
        <?php
        $rows 	= $this->itemmodel->getItems();
        ?>
        <tbody>
            <?php
            foreach ($rows as $row):
				if($row->field =='location'){
                ?>
                <tr>
                    <td align="center"><?php echo $row->population;?> %</td>
                    <td align="center"><?php echo $row->street;?>&nbsp;</td>
                    <td align="center"><?php echo $row->city;?>&nbsp;</td>
                    <td align="center"><?php echo $row->post_code;?>&nbsp;</td>
                    <td align="center"><?php echo $row->state;?>&nbsp;</td>
                    <td align="center"><?php echo $row->country;?>&nbsp;</td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=packageuser&field=location&task=edit&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=packageuser&task=delete&field=name&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                    </td>
                </tr>
                <?php
				}
            endforeach;
            ?>
        </tbody>
    </table>
    <div>
         <input type="hidden" name="jform[package_id]" value="<?php echo $this->package_id; ?>" />	
         <input type="hidden" name="jform[category_id]" value="<?php echo $this->category_id; ?>" />
		 <?php echo $this->form->getInput('id');?>	
         <input type="hidden" name="option" value="com_awardpackage" />
         <input type="hidden" name="task" value="save_packageuser" />
         <input type="hidden" name="controller" value="packageuser" />	
         <input type="hidden" value="location" name="jform[field]">	
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>