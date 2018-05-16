<?php
defined('_JEXEC') or die();
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');	
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');

$app = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$headerstuff = $document->getHeadData();
$jpath = JURI::root();
//$document->addStyleSheet($jpath . 'templates/beez3/css/personal.css', 'text/css');
$customstyle = "#main-container .panel h3.pane-toggler a {
    padding-bottom: 5px;
    padding-top: 6px;
}";
$document->addStyleDeclaration($customstyle);
// Add Javascript
$document->addScriptDeclaration("jQuery(document).ready(function($){
        $(function() {
            $('#main-container').find('.title').each( function () {
                var _t = $(this);
                _t.on('click', function(){
                    if( _t.hasClass('pane-toggler-down') ){
                        _t.removeClass('pane-toggler-down');
                    }
                    else{
                        _t.addClass('pane-toggler-down');
                    }
                    var container = $(this).next();
                    container.toggle();
                });
            });
            $('#collapse-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').hide();
                $(this).parents('#main-container').find('.title').removeClass('pane-toggler-down');
            });
            $('#expand-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').show();
                $(this).parents('#main-container').find('.title').addClass('pane-toggler-down');
            });
        });
    });");
?>
<script type="text/javascript">

function onAddPresentationUserGroup(){
	var package_id = jQuery('#package_id').val();	
	jQuery('#task').val('apresentationlist.addrow');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='+package_id);
	jQuery('form#adminForm').submit();
}

function onSavePresentationUserGroup(){
	var package_id = jQuery('#package_id').val();	
	jQuery('#task').val('apresentationlist.save');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='+package_id);
	jQuery('form#adminForm').submit();
}

function onCancel(){
	var package_id = jQuery('#package_id').val();	
window.location = "index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id="+package_id;	
}

function onDelPresentationUserGroup(){
var package_id = jQuery('#package_id').val();	
jQuery('#task').val('apresentationlist.deleteusergroup');
jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='+package_id);
jQuery('form#adminForm').submit();
}

function openModalUserGroups(id,package_id){
   var baris = jQuery("input[name='cid[]']:checked").val();
   var title = jQuery("#idUserGroupsId_"+baris).val();		
   var groupId = jQuery("#usergroupId_"+baris).val();		
   
	if (groupId == "0"){
	window.location = "index.php?option=com_awardpackage&view=usergroup&task=create&criteria_id="+id+"&package_id="+package_id+"&command=1&var_id=" + id;	
	}else{
	window.location = "index.php?option=com_awardpackage&view=usergroup&task=edit&criteria_id="+id+"&package_id="+package_id+"&command=1&title="+title+"&usergroup="+groupId+"&var_id=" + id;	
	}
}

function openModalSymbolQueue(baris){
	jQuery('#baris').val(baris);
	jQuery('#SymbolQueueModalWindow').modal('show');
}

function onCloseSymbolQueueModalWindow(e){
   var baris = jQuery("input[name='cid[]']:checked").val();

	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var piece = jQuery(tr).find("td:eq(1)").text();	
		var shufle = jQuery(tr).find("td:eq(2)").text();	
		jQuery("#idSymbolQueueID_"+baris).val(id);	
		jQuery("#idShufle_"+baris).text(shufle);		
		
		jQuery('#SymbolQueueModalWindow').modal('toggle');	
		}			    			    
}


function openModalAwardFundPlan(baris){
	jQuery('#baris').val(baris);
	jQuery('#AwardFundPlanModalWindow').modal('show');
}

function onCloseAwardFundPlanModalWindow(e){
   var baris = jQuery("input[name='cid[]']:checked").val();
		if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var rate = jQuery(tr).find("td:eq(2)").text();	
		var name = jQuery(tr).find("td:eq(1)").text();	
		jQuery("#idAwardFundName_"+baris).text(name);
		jQuery("#idAwardFundID_"+baris).val(id);		
		jQuery("#idAwardFundRate_"+baris).text(rate);		
		
		jQuery('#AwardFundPlanModalWindow').modal('toggle');	
		}			    			    
}

function openModalSymbolQueueGroup(){
	jQuery('#SymbolQueueGroupModalWindow').modal('show');
}

function onCloseSymbolQueueGroupModalWindow(e){
   var baris = jQuery("input[name='cid[]']:checked").val();

			if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var symbol = jQuery(tr).find("td:eq(3)").text();	
		var name = jQuery(tr).find("td:eq(1)").text();	
		jQuery("#idSymbolGroupName_"+baris).text(name);
		jQuery("#idSymbolGroupID_"+baris).val(id);		
		jQuery("#idSymbolQueue_"+baris).text(symbol);		
		
		jQuery('#SymbolQueueGroupModalWindow').modal('toggle');	
		}			    			    
}

function openModalPresentation(){
	jQuery('#PresentationModalWindow').modal('show');
}

function onClosePresentationModalWindow(e){
   var baris = jQuery("input[name='cid[]']:checked").val();

			if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var presname = jQuery(tr).find("td:eq(1)").text();
		var prize = jQuery(tr).find("td:eq(2)").text();	
		var award = jQuery(tr).find("td:eq(5)").text();
	
		jQuery("#idPresentationName_"+baris).text(presname);
		jQuery("#idPresentationID_"+baris).val(id);		
		jQuery("#idPrizeName_"+baris).text(prize);	
		jQuery("#idPrizeID_"+baris).val(prize);		
		jQuery("#idPrizeValue_"+baris).text(award);		
		jQuery('#PresentationModalWindow').modal('toggle');	
		}			    			    
}

  function onEditA(){
  var atLeastOneIsChecked = jQuery("input[name='cid[]']:checked").length > 0;
   var baris = jQuery("input[name='cid[]']:checked").val();

		if(atLeastOneIsChecked){
		
	document.getElementById('btn_save').style.visibility = "visible";
	 document.getElementById('btn_edit').style.visibility = "hidden";	
	 document.getElementById('UserGroupColumn['+baris+']').style.textDecoration = "underline";
	 document.getElementById('UserGroupColumn['+baris+']').style.cursor = "pointer";
	document.getElementById('UserGroupColumn['+baris+']').style.background = "#fff";

	 document.getElementById('UserGroupColumn1['+baris+']').style.textDecoration = "underline";
	 document.getElementById('UserGroupColumn1['+baris+']').style.cursor = "pointer";
	document.getElementById('UserGroupColumn1['+baris+']').style.background = "#fff";
	
		 document.getElementById('UserGroupColumn2['+baris+']').style.textDecoration = "underline";
	 document.getElementById('UserGroupColumn2['+baris+']').style.cursor = "pointer";
	document.getElementById('UserGroupColumn2['+baris+']').style.background = "#fff";
	
		 document.getElementById('UserGroupColumn3['+baris+']').style.textDecoration = "underline";
	 document.getElementById('UserGroupColumn3['+baris+']').style.cursor = "pointer";
	document.getElementById('UserGroupColumn3['+baris+']').style.background = "#fff";
	
	/*		 document.getElementById('UserGroupColumn5['+baris+']').style.textDecoration = "underline";
	 document.getElementById('UserGroupColumn5['+baris+']').style.cursor = "pointer";
	document.getElementById('UserGroupColumn5['+baris+']').style.background = "#fff";*/
	}else
	{
	  alert('no rows selected');
	}
   }

</script>
<?php require(JPATH_COMPONENT_ADMINISTRATOR . '/views/bpresentationlist/tmpl/default_left.php'); ?>

<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='. JRequest::getVar('package_id'))?>" method="post">
<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
   	<input type="hidden" name="task" id="task" value="apresentationlist.initiate">
	<input type="hidden" name="processPresentation" id="processPresentation" value="<?php  echo (empty($this->processPresentation) ? '0' : $this->processPresentation); ?>">	
	<input type="hidden" name="symbolqueue" id="symbolqueue" value="<?php echo (JRequest::getVar('symbolqueue')!='' ?  JRequest::getVar('symbolqueue') :  0);?>">	
	<input type="hidden" name="funding" id="funding" value="<?php  echo (JRequest::getVar('funding')!=''? JRequest::getVar('funding') : 0);?>">	
	<input type="hidden" name="prizename" id="prizename" value="<?php echo (JRequest::getVar('prizename') !='' ? JRequest::getVar('prizename') : 0);?>">	
    
    
	<input type="hidden" name="pids" id="pids" value="<?php echo $this->pids; ?>">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="unlock" id="unlock" value="" />


<table class="table table-bordered" width="100%">
<tr>
    <th colspan="10" style="text-align:right;">
    														   <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="AddPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>	
                                                               <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDelPresentationUserGroup();" id="DelPresentationUserGroupBtn"><i></i> <?php echo JText::_('Delete');?></button>	
                                                                <button type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_edit" onclick="onEditA();"> <?php 
			echo 'Edit';?></button>
                                                                <button style="visibility:hidden;" type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_save" onclick="onSavePresentationUserGroup();"> <?php 
			echo JText::_('Save');?></button>
                                                                <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onCancel();" id="CancelPresentationUserGroupBtn"><i></i> <?php echo JText::_('Cancel');?></button>								
    </th>
</tr>
<tr>
    <th colspan="10" style="text-align:right;">
    <?php echo $this->pagination->getLimitBox(); ?>
    </th>
    </tr>
	<tr>															
    
							<!-- <th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th> -->
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>	
							<th style="text-align:center;"><?php echo JText::_( 'Presentation User Group' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Process Presentation' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Award Funds Plan' ); ?></th>	
							<th style="text-align:center;"><?php echo JText::_( 'Symbol Queues Group' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Prizes' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Symbol Queues ' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Assigned Symbol Queue ' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Fund Prize History' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Fund Receiver List Queue' ); ?></th>
						</tr>

                        <?php foreach ( $this->usergrouplist as $i=>$rows):
						?>
	<tr>
								<td><?php echo JHTML::_( 'grid.id', $i, $rows->id );?>
                                <input type="hidden" name="id" id="id" value="<?php echo $rows->id; ?>">
                                 <input type="hidden" name="usergroupId" id="usergroupId" value="<?php echo $rows->usergroup_id; ?>">
                                  <input type="hidden" name="usergroupId" id="usergroupId" value="<?php echo $rows->usergroup_id; ?>">
                                </td>
                                
   <td id="UserGroupColumn[<?php echo $rows->id; ?>]" style="text-decoration:none;cursor:auto;background:#f3f3f3;">    
   
    <span id="idUserGroups" 
    onclick="openModalUserGroups(<?php echo $rows->id; ?>,<?php echo JRequest::getVar('package_id'); ?>);">
																		<?php 
															echo (!empty($rows->usergroup) ? $rows->usergroup : $this->title);
																		?>
																</span>                                       
   <input type="hidden" name="idUserGroupsId_<?php echo $rows->id; ?>" id="idUserGroupsId_<?php echo $rows->id; ?>" value="<?php															echo (!empty($rows->usergroup) ? $rows->usergroup : $this->title);	?>">
    <input type="hidden" name="usergroupId_<?php echo $rows->id; ?>" id="usergroupId_<?php echo $rows->id; ?>" value="<?php															echo (!empty($rows->usergroup_id) ? $rows->usergroup_id : 0);	?>">

   </td>
   <td id="UserGroupColumn1[<?php echo $rows->id; ?>]" style="text-decoration:none;cursor:auto;background:#f3f3f3;">    
   <span id="idPresentationName_<?php echo $rows->id; ?>" onclick="openModalPresentation([<?php echo $rows->id; ?>]);"><?php echo (!empty($rows->name) ? $rows->name : 0 );?></span>
   <input type="hidden" name="idPresentationID_<?php echo $rows->id; ?>" id="idPresentationID_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->presentation_id) ? $rows->presentation_id : 0 );?>">   
   </td>
   <td id="UserGroupColumn2[<?php echo $rows->id; ?>]" style="text-align:center;text-decoration:none;cursor:auto;background:#f3f3f3;">    
   <span id="idAwardFundRate_<?php echo $rows->id; ?>" onclick="openModalAwardFundPlan([<?php echo $rows->id; ?>]);"><?php echo (!empty($rows->rate) ? $rows->rate : 0 );?></span>%
   <input type="hidden" name="idAwardFundID_<?php echo $rows->id; ?>" id="idAwardFundID_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->funds) ? $rows->funds : 0 );?>">   
   
   </td>
   <td id="UserGroupColumn3[<?php echo $rows->id; ?>]" style="text-align:center;text-decoration:none;cursor:auto;background:#f3f3f3;">    
   <span id="idSymbolGroupName_<?php echo $rows->id; ?>" onclick="openModalSymbolQueueGroup([<?php echo $rows->id; ?>]);"><?php echo (!empty($rows->symbolqueue) ? $rows->symbolqueue : 0 );?></span>
   <input type="hidden" name="idSymbolGroupID_<?php echo $rows->id; ?>" id="idSymbolGroupID_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->symbol) ? $rows->symbol : 0 );?>">   

   </td>
   <td style="text-align:center;">
	<span id="idPrizeName_<?php echo $rows->id; ?>"><?php 
	$presentationId = $rows->presentation_id;
	$this->PrizeSelected = $this->model->getProcessPresentationDetailPrize($presentationId ,JRequest::getVar('package_id'));
//	echo (!empty($rows->prize_name) ? count($rows->prize_name) : 0 );
 echo (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0 ? count($this->PrizeSelected) : 0 ); 
?></span>
      <input type="hidden" name="idPrizeName_<?php echo $rows->id; ?>" id="idPrizeName_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->prize_name) ? $rows->prize_name : 0 );?>">   
      <input type="hidden" name="idPrizeValue_<?php echo $rows->id; ?>" id="idPrizeValue_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->prize_value) ? $rows->prize_value : 0 );?>">     
   </td>
   <td style="text-align:center;">
	<span id="idSymbolQueue_<?php echo $rows->id; ?>"><?php 
	if (!empty($rows->symbolqueue)){
		$symbolCount = $rows->symbol;
    		if (!empty($symbolCount)){
				$jumlah = $this->model->getSymbolCount($symbolCount);
		}
	echo (!empty($jumlah) ? $jumlah : 0 );
	} ?></span>
      <input type="hidden" name="idSymbolQueue_<?php echo $rows->id; ?>" id="idSymbolQueue_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->amount) ? $rows->amount : 0 );?>">      
   </td>		
   <td id="UserGroupColumn5[<?php echo $rows->id; ?>]" style="text-align:center;text-decoration:none;cursor:auto;">    
   <span id="idShufle_<?php echo $rows->id; ?>"><?php 
	if (!empty($rows->symbolqueue)){
			$usergroupname =$rows->usergroup;
			$symbolCount = count($this->model->getSymbolQueueCount($usergroupname));
			echo (!empty($symbolCount) ? $symbolCount : 0 );
			}
																				?></span>
   <input type="hidden" name="idSymbolQueueID_<?php echo $rows->id; ?>" id="idSymbolQueueID_<?php echo $rows->id; ?>" value="<?php echo (!empty($rows->prize) ? $rows->prize : 0 );?>">   
   </td>  
<td style="text-align:center;">
								<a target="_blank" href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.fundPrizeHistory&processId=<?php echo $rows->presentation_id; ?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><b>View</b></a></td>
<td style="text-align:center;">
								<a target="_blank" href="index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundReceiverList&process_id=<?php echo $rows->presentation_id; ?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><b>View</b></a></td>                                   
   
   
	</tr>
							<?php endforeach;?>
                <tr><td colspan="10" style="text-align:right;">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
                                   
    </tr>

</table>
</form>
		        	<?php  include('default_modal.php'); ?>

    
    
					
</div>


