<?php
$groups = $this->ug->getNameUserGroupPresentation($this->package_id, 'gender');
$group = null;
if(!empty($groups)){
	$group = $groups[0];
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&package_id='.$this->package_id); ?>" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
	<div class="span12">
		<div class="clearfix">
			<label><?php echo JText::_('Group Name');?></label>
			<input name=jform[group_name] type="text" value="<?php echo $this->escape($group->group_name);?>" placeholder="<?php echo JText::_('Enter a group name');?>" required="" aria-required="true">
		</div>		
	</div>
    <div style="border:1px solid #fff;">

    <table width="100%" cellpadding="1" cellspacing="1"
		class="table-striped"
		style="border: 1px solid #cccccc; font-size: 10pt;">
		<thead>
			<tr style="text-align: center; background-color: #CCCCCC">
				<td colspan="2" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></strong></td>
			</tr>
			<tr>
				<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
				<th><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="left" style="padding-left:15px;"><input type="text" name="jform[population]"
					id="jform_population" value="<?php echo $this->population ?>"
					class="inputbox" size="5" style="width: 50px;" required=""
					aria-required="true" aria-invalid="false">%</td>
				<td align="center"><select name="jform[gender]" style="width: 300px;">
					<?php
					foreach ($this->genders as $rows) {
					?>
					<option value="<?php echo $rows->gender;?>"
					<?php echo ($rows->gender == $rows->gender ? "selected=selected" : ""); ?>>
					<?php echo $rows->gender;?></option>
					<?php
					}
					?>              
				</select></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:15px;">
					<button value="Save" class="btn-add"
					<?php echo (empty($parents) && $command == '1' ? "" : ""); ?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</tbody>
	</table>
    <br/>
    <br/>
    </div>
    <table width="100%" cellpadding="1" cellspacing="1"	class="table-striped" style="border: 1px solid #cccccc; font-size: 10pt;">
        <thead>
			<tr>
				<td colspan="3" align="center" height="50" class="td-group"
					style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></strong></td>
			</tr>
			<tr>
				<th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
				<th><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></th>
				<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
			</tr>
		</thead>
        <?php
        $rows 	= $this->itemmodel->getItems();
        ?>
        <tbody>
            <?php
            foreach ($rows as $row):
				if($row->field =='gender'){
                ?>
                <tr>
                    <td align="center"><?php echo $row->population;?> %</td>
                    <td align="center"><?php echo $row->gender;?>&nbsp;</td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=packageuser&field=gender&task=edit&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=packageuser&task=delete&field=gender&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
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
            <input type="hidden" value="gender" name="jform[field]">	
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>