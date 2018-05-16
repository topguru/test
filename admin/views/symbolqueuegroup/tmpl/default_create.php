<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">

function openModalUserList(e){
	jQuery('#UserListModalWindow').modal('show');
	//alert('UserListModalWindow_'+e);
}

function onCloseUserListModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(2)").text();
		var name2 = jQuery(tr).find("td:eq(3)").text();		
		var gender = jQuery(tr).find("td:eq(4)").text();
		jQuery("#idUser").val(id);			   		
		jQuery("#idUserName").text(name);			   
		jQuery('#UserListModalWindow').modal('toggle');		
	    jQuery('form#adminForm').submit();			

	}	
	
}

</script>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=symbolqueuegroup" method="post" >
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
    
	<div class="col100">
					<table class="table table-striped table-hover table-bordered" style="width:50%;">
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td colspan="3"><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->symbolqueue->name;?>" /> </td>
			</tr>	
            <tr>
				<th width="150px" style="border-right:none;"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Create'); ?></label></th>
<td><input class="text_area" type="text" name="amount" id="amount" size="32"  value="<?php echo $this->symbolqueue->amount;?>" />&nbsp;&nbsp;symbol queues
</td>

           
            <tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Description'); ?></label></th>
				<td colspan="3"><textarea class="" id="description" name="description" ><?php echo $this->symbolqueue->description;?></textarea>
               </td>
			</tr>			
            
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->symbolqueue->id;?>">
    <input type="hidden" name="view" value="symbolqueuegroup">
    <input type="hidden" name="task" value="symbolqueuegroup.save_create">   
</form>
