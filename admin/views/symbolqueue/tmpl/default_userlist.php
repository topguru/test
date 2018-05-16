<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function onClosePrizeModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		
		jQuery('#prizeModalWindow').modal('toggle');	
		jQuery('#task').val('symbolqueue.save_userlist');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=symbolqueue&package_id='+<?php echo JRequest::getVar('package_id'); ?>);
	jQuery('form#adminForm').submit();			    			    
	}	
}
</script>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueue');?>" method="post" name="adminForm">
					<table class="table table-striped table-hover table-bordered" style="width:95%;">
<thead>
    <tr>
    <th>#</th>
    <th>ID</th>
    <th>Name</th>
    <th>Username</th>
    </tr>
    </thead>
    <?php 
	$i = 0; 
	foreach ($this->users as $rows) : ?>
    <tr>
    <td>
						<input type="radio" name="radio_prize" class="radioPrizeClass" 
						value="<?php echo $i; ?>" onclick="onClosePrizeModalWindow(this);"/>
						<input type="hidden" name ="userid" value="<?php echo JText::_($rows->id); ?>">
					</td>
    <td><?php echo $rows->id; ?></td>
    <td><?php echo $rows->name; ?></td>
    <td><?php echo $rows->username; ?></td>
    </tr>
    <?php endforeach; ?>
    </table>
    					<input type="hidden" name="groupId" value="<?php echo  JRequest::getVar("groupId"); ?>"/>	                    
						<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
						<input type="hidden" name="id" value="<?php echo JRequest::getVar("id"); ?>"/>

					<input type="hidden" name="task" value="symbolqueue.save_userlist" />
                    <input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>