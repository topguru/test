<?php
defined('_JEXEC') or die();
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$app = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$headerstuff = $document->getHeadData();
$jpath = JURI::root();
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
window.addEventListener("load", function() {
    makeTabs(".tabs")
});

	jQuery(function() {
		  jQuery( ".accordion" ).accordion({collapsible: true, active: 0, heightStyle: "content" });
	});
jQuery(function() {
		 jQuery( "#resizable" ).resizable();
		 jQuery( "#resizable_1" ).resizable();
		 jQuery( "#resizable_2" ).resizable();
		 jQuery( "#resizable_3" ).resizable();
		 jQuery( "#resizable_4" ).resizable();
		 jQuery( "#resizable_5" ).resizable();

		 jQuery( "#resizable_6" ).resizable();
		 jQuery( "#resizable_7" ).resizable();
		 jQuery( "#resizable_8" ).resizable();
		 jQuery( "#resizable_9" ).resizable();
		 
	});
function onAddExtractPieces(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid1"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.addExtractPieces');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}				
	}

	function onAddValuePieces(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid1"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.AddValuePieces');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}				
	}



	function onAddNumCloneVal(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid1"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.addNumCloneVal');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}		
	}
	
	function onAddNumCloneFree(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid1"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.addNumCloneFree');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}		
	}
	
	function onPriceOfExtractedPieces(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid2"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked) {
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);		
			jQuery('#task').val('aprocesspresentation.priceOfExtractedPieces');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
			jQuery('form#adminForm').submit();
					} else {
			alert('Please select at least one presentation');
		}
		
	}	
	function onRpcSelectedForPricing(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid2"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked) {
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.rpcSelectedForPricing');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();	
		} else {
			alert('Please select at least one presentation');
		}
			
	}
	function onPriceOfSelectedRPC(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid2"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked) {
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.priceOfSelectedRPC');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}
		
	}	
	function onInsertOfPricedRPC(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid3"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.insertOfPricedRPC');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}
		
	}	
	function onInsertOfFreeRPC(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid3"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.insertOfFreeRPC');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}
		
	}
	function onShufflePieceIntoEachSymbolQueue(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid3"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked){
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.shufflePieceIntoEachSymbolQueue');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}
		
	}	
	function onPercentEachFromAllUserGroupsToFundPrize(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid4"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked) {
			jQuery('#selectedPresentation').val(id);
			jQuery('#index').val(index);
			jQuery('#task').val('aprocesspresentation.percentEachFromAllUserGroupsToFundPrize');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}				
	}
	function onFundingQueue(id, index){
		var atLeastOneIsChecked = jQuery("input[name='cid4"+index+"[]']:checked").length > 0;
		if(atLeastOneIsChecked) {
			jQuery('#task').val('aprocesspresentation.fundingQueue');
			jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
			jQuery('form#adminForm').submit();
		} else {
			alert('Please select at least one presentation');
		}						
	}	

	function onFundQueue(){		
		window.location = 
			"index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.onFundQueue&" +
			"package_id=<?php echo JRequest::getVar('package_id'); ?>";
	}

	function onPrizeQueue(selectedPresentation, presentation_id){		
		window.location = 
			"index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.onPrizeQueue&" +
			"package_id=<?php echo JRequest::getVar('package_id'); ?>&" +
			"index=<?php echo JRequest::getVar('index'); ?>&" +
			"idUserGroupsId=<?php echo JRequest::getVar('idUserGroupsId'); ?>&" +
			"position=<?php echo JRequest::getVar('position'); ?>&" +
			"processPresentation=<?php echo JRequest::getVar('processPresentation'); ?>&" +
			"var_id=<?php echo JRequest::getVar('var_id'); ?>&" +
			"selectedPresentation=" + selectedPresentation + "&" +
			"presentation_id=" + presentation_id;
	}
	function onSelectPresentations(id, index){
		jQuery('#task').val('aprocesspresentation.doSelectPresentations');
		jQuery('#selectedPresentation').val(id);
		jQuery('#index').val(index);
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
		jQuery('form#adminForm').submit();
	}
	function numbersOnly(e) {
		e.value = e.value.replace(/[^0-9\.]/g,'');	
	}

function onAddNewProcessTitle(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_title');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewProcess_1(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_1');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewProcess_2(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_2');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewProcess_3(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_3');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewProcess_4(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_4');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewProcess_5(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.new_process_5');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onAddNewSymbolSet(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('anewpresentationlist.new_symbolset');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=anewpresentationlist');
	jQuery('form#adminForm').submit();
}
function openModalPrize(){
	jQuery('#prizeModalWindow').modal('show');
}
function openModalSymbol(){
	jQuery('#symbolModalWindow').modal('show');
}
function openModalReceiver(){
	jQuery('#receiverModalWindow').modal('show');
}
function openModalPresentation(){
	jQuery('#presentationModalWindow').modal('show');
}
function openModalSymbolQueue(){
	jQuery('#SymbolQueueModalWindow').modal('show');
}
function openModalSymbolFilled(){
	jQuery('#SymbolFilledModalWindow').modal('show');
}

function onCloseSymbolFilledModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var assigned = jQuery(tr).find("td:eq(1)").text();
		var piece = jQuery(tr).find("td:eq(2)").text();		
		var shufle = jQuery(tr).find("td:eq(3)").text();
		var created = jQuery(tr).find("td:eq(4)").text();		
		jQuery("#idSymbolFilledName").text(assigned);			   
		jQuery("#SymbolFilled").text(created);
		jQuery("#assignSymbolFilled").text(piece);
		jQuery("#idSymbolFilledID").text(id);		
		jQuery('#SymbolFilledModalWindow').modal('toggle');				    			    
	}	
}


function onCloseSymbolQueueModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var presentation = jQuery(tr).find("td:eq(1)").text();
		var name = jQuery(tr).find("td:eq(2)").text();		
		var symbol = jQuery(tr).find("td:eq(3)").text();		
		jQuery("#idPresentation").text(presentation);			   
		jQuery("#idPrizePres").text(name);
		jQuery("#idPresentationId").val(id);
		jQuery('#SymbolQueueModalWindow').modal('toggle');				    			    
	}	
}

function onClosePresentationModalWindow(e,prizeCount){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var presentation = prizeCount+1;//jQuery(tr).find("td:eq(1)").text();
		var name = prizeCount+1;//jQuery(tr).find("td:eq(2)").text();		
		jQuery("#idPresentation").text(presentation);			   
		jQuery("#idPrizePres").text(name);
		jQuery("#idPresID").val(id);
		jQuery('#presentationModalWindow').modal('toggle');				    			    
	}	
}

function onCloseReceiverModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(1)").text();
		var filter = jQuery(tr).find("td:eq(2)").text();		
		var status = jQuery(tr).find("td:eq(3)").text();		
		jQuery("#idReceiverName").text(name);			   
		jQuery("#idReceiverLimit").text(filter);
		jQuery("#idReceiverID").val(id);
		jQuery('#receiverModalWindow').modal('toggle');				    			    
	}	
}
function onClosePrizeModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(1)").text();
		var valuefrom = jQuery(tr).find("td:eq(2)").text();		
		var valueto = jQuery(tr).find("td:eq(3)").text();		
		jQuery("#idPrizeName").text(name);			   
		jQuery("#idPrizeValuefrom").text(valuefrom);
		jQuery("#idPrizeValueto").text(valueto);		
		jQuery("#idFundPrizePlan").val(id);
		jQuery('#prizeModalWindow').modal('toggle');				    			    
	}	
}
function onCloseSymbolModalWindow_2(e){
	var tr = jQuery(e).parent().parent();						
	var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();				
	var name = jQuery(tr).find("td:eq(1)").text();
	var symbolSet = jQuery(tr).find("td:eq(2)").find("input[type='hidden']").val();
	var symbolPieces = jQuery(tr).find("td:eq(3)").text();
	var rows = jQuery(tr).find("td:eq(4)").find("input[name='rows']").val();
	var cols = jQuery(tr).find("td:eq(4)").find("input[name='cols']").val();
	console.log(rows);
	console.log(cols);
	//jQuery("#idSymbolName").text(name);
	jQuery("#idSymbolSet").html(
			'<img ' +
			' src="./components/com_awardpackage/asset/symbol/'+symbolSet+'" '	+
			' style="width:100px;cursor:pointer;" onclick="openModalSymbol();"></img> '
		);
	jQuery("#idSymbolPieces").text(symbolPieces);
	var show = '';
	var segment_width = 80 / parseInt(cols);
	var segment_height = 80 / parseInt(rows);
	for(var i=0; i < parseInt(rows); i++ ){
		show += '<tr>';
		for(var j=0; j < parseInt(cols); j++){
			var filename = symbolSet.substr(0,symbolSet.length-4)+i+j+'.png';
			var file = './components/com_awardpackage/asset/symbol/pieces/'+filename;
			show += '<td style="padding:3px;">';
			show += '<img style="left: 0px; top: 0px; width: '+segment_width+'px;" alt="" src="'+file+'"/>';
			show += '</td>';
		}
		show += '</tr>';
	}
	jQuery("#idSymbolPiecesToCollect").html(show);
	jQuery("#idSymbolId").val(id);
	jQuery('#symbolModalWindow').modal('toggle');
}

function onDeleteStartFundPrize(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('aprocesspresentation.delete_startfundprizeplan');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation&package_id='+package_id);
	jQuery('form#adminForm').submit();	
}

function onCloseSymbolModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(1)").text();
		var rate = jQuery(tr).find("td:eq(2)").text();		
		var amount = jQuery(tr).find("td:eq(3)").text();		
		jQuery("#idFundName").text(name);			   
		jQuery("#idFundRate").text(rate);
		jQuery("#idFundAmount").text(amount);		
		jQuery("#AwardFundPlan").val(id);
		jQuery('#symbolModalWindow').modal('toggle');
	}
}
</script>
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation')?>" method="post" >
<input type="hidden" name="task" id="task" value="">
<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
<input type="hidden" name="process_id" id="process_id" value="<?php echo JRequest::getVar('process_id'); ?>">
<input type="hidden" name="presentation_id" value="<?php echo $this->presentation_id; ?>">
<div id="cj-wrapper">
	<div class="container-fluid">
		<div class="row-fluid">
		        	<?php  include('default_presentations.php'); ?>
					<?php include ('default_tabel.php');?>	
					<?php include ('default_selected.php');?>	


        </div>
        	<?php  include('default_process.php'); ?>
     </div>
</div>        
</form>
