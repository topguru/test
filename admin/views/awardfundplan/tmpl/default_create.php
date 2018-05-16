<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function onRate(){
        var rate = jQuery("#rate").val();
        var total = jQuery("#TotalAward").val();  
        var persen = jQuery("#totalP").val();  
        var speed = jQuery("#speed").val();  
		
        var amount = (rate/100) * total ;
        var totpersen = persen + rate ;
		 

		if ( rate > 100 ||  totpersen > speed )  {
		alert("Percentage exceed");
		adminform.rate.focus();
		return false;
	} else {
		jQuery("#amount").val(amount);
        var spent = jQuery("#spent").val();
        var amount = jQuery("#amount").val();  
        var remain = amount - spent ;
		var sisa = remain.toFixed(2);
			/*if ( spent > amount )  {
				alert("spent bigger");
				adminform.spent.focus();
				return false;
			} else {	*/
        		jQuery("#remain").val(sisa);
			/*}*/

		}
		
}

function onSpent(){
        var spent = jQuery("#spent").val();
        var amount = jQuery("#amount").val();  
        var remain = amount - spent ;
		
		if ( spent > amount )  {
		alert("spent bigger");
		adminform.spent.focus();
		return false;
	} else {	
        jQuery("#remain").val(remain);
		}
		
}
/*function onRate(adminForm){
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

}*/

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
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=awardfundplan" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
    <input type="hidden" name="totalP" id="totalP" value="<?php echo $this->totalP; ?>"/>
    
    <input type="hidden" name="TotalAward" id="TotalAward" value="<?php echo $this->awardfundtotal; ?>"/>
	<div class="col100">
					<table class="table table-striped table-hover table-bordered" style="width:50%;">
			<tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td><input class="text_area" type="text" name="sname" id="sname" size="32" maxlength="250" value="<?php echo $this->awardfund->name;?>" /> </td>
			</tr>	
            
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award Funds Total'); ?></label></th>
				<td><input class="text_area" type="text" name="total" id="total" size="32" maxlength="250" value="<?php echo number_format($this->awardfundtotal,2); ?>" readonly/></td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award Funds Rate'); ?></label></th>
				<td><input class="text_area" type="text" name="rate" id="rate"  onchange="onRate();" size="10" maxlength="3" value="<?php echo number_format($this->awardfund->rate,2);?>" />&nbsp;percent</td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award funds for Fund
Receiver Plan'); ?></label></th>
				<td><input class="text_area" type="text" name="amount" id="amount" size="32" maxlength="250" value="<?php echo number_format($this->awardfund->amount,2);?>" readonly/></td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award funds spent'); ?></label></th>
				<td><input class="text_area" type="text" name="spent" id="spent" onchange="onSpent();" size="32" maxlength="250" value="<?php echo number_format($this->spent,2);?>" readonly/></td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Award funds remain'); ?></label></th>
				<td><input class="text_area" type="text" name="remain" id="remain" size="32" maxlength="250" value="<?php echo number_format($this->remain,2);?>" readonly/></td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" ><?php echo JText::_('Presentation user group'); ?></label></th>
				<td><input class="text_area" type="text" name="published" id="published" size="32" maxlength="250" value="<?php echo $this->awardfund->usergroup;?>" readonly/></td>
			</tr>
            <tr>
				<th width="200px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('Description'); ?></label></th>
				<td><textarea class="" id="description" name="description" ><?php echo $this->awardfund->description;?></textarea>
               </td>
			</tr>			
            
		</table>
	</div>	
	<input type="hidden" name="id" value="<?php echo $this->awardfund->id;?>">
    <input type="hidden" name="view" value="awardfundplan">
    <input type="hidden" name="task" value="awardfundplan.save_create">   
</form>

<div id="UserListModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th ><?php echo JText::_( 'Id' ); ?></th>			
                    					<th ><?php echo JText::_( 'First Name' ); ?></th>													
                    					<th ><?php echo JText::_( 'Last Name' ); ?></th>													
								<th><?php echo JText::_( 'Gender' ); ?></th>	
                                <th><?php echo JText::_( 'Location' ); ?></th>								
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->userlist  as $userlist ){ 
				?>
				<tr>
					<td>
						<input type="radio" name="radio_prize" class="radioPrizeClass" 
						value="<?php echo $i; ?>" onclick="onCloseUserListModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($userlist->id); ?>">
					</td>
                    <td>
						<?php echo JText::_( $userlist->id ); ?>
					</td>
					<td>
						<?php echo JText::_( $userlist->firstname ); ?>
						<input type="hidden" name="firstName" value="<?php echo $userlist->firstname; ?>" />
					</td>
                    <td>					
						<?php echo JText::_( $userlist->lastname ); ?>

					</td>
                    <td>					
						<?php echo JText::_( $userlist->gender ); ?>

					</td>
                    <td>					
						<?php echo JText::_( $userlist->city ); ?>

					</td>
                    <td>					

					</td>
					
				</tr>		
				<?php
				$i++;
				} 
				?>
			</tbody>
		</table>
	</div>		
</div>