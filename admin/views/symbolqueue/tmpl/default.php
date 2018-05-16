<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');

?>
<script type="text/javascript">
function onAddNewPrize(){
	var shufle = jQuery('#shufle').val();
	jQuery('#task').val('symbolqueue.save_shuffle');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=symbolqueue');
	jQuery('form#adminForm').submit();	
}
function prizeModalWindow(){
	jQuery('#prizeModalWindow').modal('show');
}

function onClosePrizeModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(1)").text();
		var prizeImage = jQuery(tr).find("td:eq(1)").find("input[type='hidden']").val();
		var value = jQuery(tr).find("td:eq(2)").text();		
		
		//jQuery("#idPrizeName").text(name);			   
		jQuery("#idPrizeValue").text(value);
		jQuery("#idPrizeId").val(id);
		jQuery('#prizeModalWindow').modal('toggle');				    			    
	}	
}

</script>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id").'')?>" method="post">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>	
					<input type="hidden" name="processId" value="<?php echo  JRequest::getVar("processId"); ?>"/>	                    
   					<input type="hidden" name="idUserGroupsId" value="<?php echo  JRequest::getVar("criteria_id"); ?>"/>
					<input type="hidden" name="groupId" value="<?php echo  JRequest::getVar("groupId"); ?>"/>	                    
                    							
					<table class="table table-striped table-hover table-bordered" style="width:80%;">
						<thead>
                        <tr>
                        <td colspan="4" style="border-right:none;"><b>Shuffle symbol pieces in symbol queue</b></td>
                        <td style="border-left:none;border-right:none;"><input class="text_area" type="text" name="shufle" id="shufle" style="width:100px;" size="20" maxlength="100" value="" required/>	</td>
                        <td colspan="3" style="border-left:none;">times <span style="margin:0 0 0 10px"><button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewPrize();" > <?php echo JText::_('Add');?></button></span></td>
                                                                        
<td style="text-align:right;">                                    <?php echo $this->pagination->getLimitBox(); ?>

                                    </td>  
                        </tr>
                                 
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>								
								<th style="text-align:center;"><?php echo JText::_( 'No' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Symbol queue' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Symbol piece' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Created' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Shuffle symbol piece' ); ?></th>								
                                <th style="text-align:center;"><?php echo JText::_( 'Status' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Assigned to user' ); ?>
                                </th>								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->symbols as $i=>$row):
							?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->queue_id );?></td>
								<td class="hidden-phone"><?php echo $i+1; ?></td>
								<td style="text-align:center;"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.view_symbolqueue&groupId='.JRequest::getVar('groupId').'&package_id='.JRequest::getVar('package_id').'&id='.$row->queue_id)?>">view</a></td>
								<td style="text-align:center;">
								<?php echo (!empty($row->firstname) ? $row->piece : '0'); ?>
                                <td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td style="text-align:center;"><?php echo $row->shufle; ?></td>
								<td class="hidden-phone"><?php 
								if (empty($row->firstname))
								{ ?>
								Not Assigned
					<?php 			} else { ?>
                    			Assigned
								<?php } ?></td>
								<td class="hidden-phone">
																							<span id="idPrizeValue">
                                                                                            <?php echo (!empty($row->firstname) ? $row->firstname.' '.$row->lastname : ''); ?>
                                                                                            </span>                                <input type="hidden" name="userid" id="userid" value="<?php echo $row->queue_id; ?>" />	
</td>
															
							</tr>
							<?php endforeach;?>
                            <tr>
<td colspan="9" style="text-align:right;">                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>

                                    </td>                                   
    </tr>
						</tbody>
						
					</table>
					
				<input type="hidden" name="task" id="task" value="symbolqueue.get_symbolqueue" />	
                           		<input type="hidden" name="boxchecked" value="0" />		

				</form>
			</div>
		</div>
	</div>
    <div id="prizeModalWindow" class="modal hide fade" style="height:600px; width:600px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User List');?></h3>
	</div>
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
						<input type="hidden" value="<?php echo JText::_($row->id); ?>">
					</td>
    <td><?php echo $rows->id; ?></td>
    <td><?php echo $rows->name; ?></td>
    <td><?php echo $rows->username; ?></td>
    </tr>
    <?php endforeach; ?>
    </table>
    </div>
</div>
				