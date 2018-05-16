<?php
$groups = $this->ug->getNameUserGroupPresentation($this->package_id, 'name');
$group = null;
if(!empty($groups)){
	$group = $groups[0];
}
$parents = $this->ug->getParentUserGroup($this->package_id,'name');
$par = null;
if(!empty($parents)){
	$par = $parents[0];
}
?>
<form action="index.php?option=com_awardpackage&package_id=<?php echo $this->package_id; ?>&task=save_packageuser" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
	<div class="span12">
		<div class="clearfix">
			<label><?php echo JText::_('Group Name');?></label>
			<input name="jform[group_name]" type="text" value="<?php echo $this->escape($group->group_name);?>" placeholder="<?php echo JText::_('Enter a group name');?>" required="" aria-required="true">
		</div>		
	</div>
    <table width="100%" cellpadding="1" cellspacing="1" class="table-striped"
		style="border: 1px solid #cccccc; font-size:10pt;">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
			</tr>
			<tr>
				<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
				<th><?php echo $this->form->getLabel('firstname'); ?></th>
				<th><?php echo $this->form->getLabel('lastname'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="left"  style="padding-left:15px;"><input type="text" name="jform[population]" id="jform_population" value="100" class="inputbox" size="5" required="" aria-required="true" style="width:40px;">%</td>
				<td align="center"><input type="text" name="jform[firstname]" id="jform_firstname" 
					value="<?php echo ($par->firstname != '' ? $par->firstname : $this->firstname); ?>" class="inputbox" size="5" style="width:130px;" required="" aria-required="true"
					<?php echo ($par->firstname != '' ? 'readonly=readonly' : ''); ?>></td>
				<td align="center"><input type="text" name="jform[lastname]" id="jform_lastname" 
					value="<?php echo ($par->lastname != '' ? $par->lastname : $this->lastname); ?>" class="inputbox" size="5" style="width:130px;" required="" aria-required="true"
					<?php echo ($par->lastname != '' ? 'readonly=readonly' : ''); ?>></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:15px;">
					<button value="Save" class="btn-add" <?php echo (empty($parents) && $command == '1' ? "" : ""); ?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
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
    <table width="100%" cellpadding="1" cellspacing="1" class="table-striped" style="border: 1px solid #cccccc;font-size:10pt;">
        <thead>
			<tr>
				<td colspan="4" align="center" height="50" class="td-group" style="text-align:center; background-color:#CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
			</tr>
			<tr>
				<th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
				<th><?php echo JText::_('Firstname'); ?></th>
				<th><?php echo JText::_('Lastname'); ?></th>
				<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
			</tr>
		</thead>
        <tbody>
            <?php
			$items 	= $this->itemmodel->getItems();
            foreach ($items as $i=>$row):
			if($row->field =='name'){
                ?>
                <tr class="row<?php echo $i;?>">
                    <td align="center"><?php echo $row->population; ?> %</td>
                    <td align="center"><?php echo $row->firstname; ?></td>
                    <td align="center"><?php echo $row->lastname; ?></td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=packageuser&field=name&task=edit&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=packageuser&task=delete&field=name&package_id='.$this->package_id.'&category_id='.$row->category_id); ?>&id=<?php echo $row->id;?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                        <span style="text-decoration:underline;cursor:pointer; color:blue;" onclick="openModalUserName();">
							<?php echo JText::_('Select User'); ?>				
						</span>
                    </td>
                </tr>
                <?php
				}
            endforeach;
            ?>
        </tbody>
    </table>
    <input type="hidden" name="jform[package_id]" value="<?php echo $this->package_id; ?>" />	
    <input type="hidden" name="jform[category_id]" value="<?php echo $this->category_id; ?>" />
    <?php echo $this->form->getInput('id');?>	
    <input type="hidden" name="option" value="com_awardpackage" />    
    <input type="hidden" name="controller" value="packageuser" />	
    <input type="hidden" value="name" name="jform[field]">
<div id="userNameModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User List');?></h3>
	</div>	
	<div style="overflow:scroll; height:550px; width:100%;">
		<br/>
		&nbsp;&nbsp;&nbsp;<input type="Button" value="Select" onclick="onSelectUserName();"/>
		<br/>
		<br/>
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th><u><?php echo JText::_('Firstname')?></u></th>
					<th><u><?php echo JText::_('Lastname')?></u></th>
					<th><u><?php echo JText::_('Birthday')?></u></th>
					<th><u><?php echo JText::_('Email')?></u></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->users as $user){ ?>
				<tr>
					<td>
						<input type="checkbox" name="nameaccountid" id="nameaccountid" 
						value="<?php echo $user->ap_account_id; ?>" />						
					</td>
					<td>
						<?php echo (empty($user->firstname) ? '' : JText::_($user->firstname)); ?>
					</td>
					<td>
						<?php echo (empty($user->lastname) ? '' : JText::_($user->lastname)); ?>						
					</td>
					<td>					
						<?php echo (empty($user->birthday) ? '' : JText::_($user->birthday)); ?>
					</td>
					<td>					
						<?php echo (empty($user->email) ? '' : JText::_($user->email)); ?>
					</td>
				</tr>		
				<?php				
				} 
				?>
			</tbody>
		</table>
	</div>
</div>	
</form>