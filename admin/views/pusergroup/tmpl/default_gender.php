<form
	action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&package_id='.$this->package_id); ?>&task=save_usergroup"
	method="post" name="adminForm" id="refundpackagelist-form"
	class="form-validate"><?php $command = JRequest::getVar('command'); ?>
<input type="Hidden" name="criteria_id" value="<?php echo JRequest::getVar('criteria_id');?>"/>
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="var_id" value="<?php echo JRequest::getVar('var_id'); ?>">
<?php
//$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'gender');
/* Above code line changed to below code by Sushil on 30-11-2015 */
$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'gender',JRequest::getVar('criteria_id'));
$presentationGroup = null;
if(!empty($presentationGroups)){
	$presentationGroup = $presentationGroups[0];
}

$parents = $this->ug->getParentUserGroup($this->package_id,'name');
$parent = null;
if(!empty($parents)){
	$parent = $parents[0];
}
?>

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
			<td align="center">
				<select name="jform[gender]" style="width: 300px;" <?php //echo ($command == '1' && $this->p_gender != '' ? "disabled=disabled" : ""); ?>>
				<?php if($command == '1'){ ?>
					<?php foreach ($this->genders as $rows){ ?>
					<option value="<?php echo $rows->gender; ?>" <?php echo ($this->p_gender == 'M' ? 'selected=selected' : ''); ?>><?php echo $rows->gender; ?></option>
					<?php } ?>
				<?php } else { ?>
					<option value="M"
					<?php echo (@$this->gender == 'M' ? "selected=selected" : ""); ?><?php echo ($this->p_gender == 'M' ? 'selected=selected' : ''); ?> >Male</option>
					<option value="F"
					<?php echo (@$this->gender == 'F' ? "selected=selected" : ""); ?><?php echo ($this->p_gender == 'F' ? 'selected=selected' : ''); ?>>Female</option>
				<?php } ?>
				</select>
			</td>
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
<br />
<br />
<table width="100%" cellpadding="1" cellspacing="1"
	class="table-striped"
	style="border: 1px solid #cccccc; font-size: 10pt;">
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
	$rows = $this->ug->filter_field($this->package_id,'gender');
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population;?> %</td>
			<td align="center"><?php echo $row->gender;?>&nbsp;</td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=usergroup&field=gender&task=edit&package_id=' . $this->package_id . '&criteria_id=' . $row->criteria_id  . '&command=' . JRequest::getVar('command')); ?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
			<a
				href="<?php
           if(JRequest::getVar('command') == '1'){$deltask = "deletepres";} else {$deltask = "delete";} 
           echo JRoute::_('index.php?option=com_awardpackage&controller=usergroup&task='.$deltask.'&field=gender&package_id=' . $this->package_id . '&criteria_id=' . $row->criteria_id  . '&command=' . JRequest::getVar('command')); ?>"
				onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
			<?php if( JRequest::getVar('command') == '1' ) { ?>
      &nbsp;&nbsp;
			<?php
              if($row->is_presentation!="1"){
                $linkcheckbox = JRoute::_("index.php?option=com_awardpackage&task=usergrouplocation.centang&field=gender&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="";
              } else {
                $linkcheckbox = JRoute::_("index.php?option=com_awardpackage&task=usergrouplocation.uncentang&field=gender&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="checked"; 
              };
              echo "&nbsp;&nbsp;<input type=checkbox id='cbl{$row->criteria_id}' name='cbl[]' value='1' onchange='javascript:window.location=\"$linkcheckbox\"' $cs>";
      ?>
      <!--
      <a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.$this->package_id.'&idUserGroupsId='.$row->criteria_id.'&processPresentation='.JRequest::getVar('processPresentation')); ?>"
				style="color:blue;text-decoration:underline;"><?php echo JText::_('Select'); ?></a>
			-->
      <?php } ?>
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<div><input type="hidden" name="jform[package_id]"
	value="<?php echo $this->package_id; ?>" /> <input type="hidden"
	name="jform[criteria_id]" value="<?php echo $this->criteria_id; ?>" />
<input type="hidden" name="option" value="com_awardpackage" /><input
	type="hidden" name="controller" value="usergroup" /> <input
	type="hidden" value="gender" name="jform[field]"> <input type="hidden"
	name="command" value="<?php echo ($command == '1' ? '1' : '0'); ?>"> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
