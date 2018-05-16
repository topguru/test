<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
 
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
//$categories = JHtml::_('category.categories', S_APP_NAME);

if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');
} else {
	JHTML::_('behavior.mootools');
}

?>
<script type="text/javascript">
function numbersOnly(e) {
	e.value = e.value.replace(/[^0-9\.]/g,'');
	var col = jQuery(e).parent();
	var row = jQuery(e).parent().parent();
	var price = jQuery(col).find("input[type='hidden']").val();
	var quantity = jQuery(e).val();
	var costPerResponse = price * quantity;
	jQuery(row).find("td:eq(4)").find("input[type='text']").val(costPerResponse);
}
function calculateCostPerResponse(e){
	var col = jQuery(e).parent();
	var row = jQuery(e).parent().parent();
	var price = jQuery(col).find("input[type='hidden']").val();
	var quantity = jQuery(e).val();
	var costPerResponse = price * quantity;
	jQuery(row).find("td:eq(4)").find("input[type='text']").val(costPerResponse);
	 
}

function open_modal_window(e, index){		
	var row = jQuery(e).parent().parent().parent().parent().parent().parent().parent().parent().parent().index();
	jQuery('#rowQuestionText').val(row);
	jQuery('#colQuestionText').val(index);
	jQuery('#loadSurveyCategory').modal('show');	
}
function close_modal_window(){	
	if(jQuery("input[type='radio'].radioSurveyCategoryClass").is(':checked')) {
	    var id = parseInt(jQuery("input[type='radio'].radioSurveyCategoryClass:checked").val()) + 1;			    
	    var tr = jQuery("#surveyCategoryTable tr:eq("+id+")");
	    var name = jQuery(tr).find("td:eq(2)").text();			    
	    var quantity = jQuery(tr).find("td:eq(3) input").val();
	    var costPerResponse = jQuery(tr).find("td:eq(4) input").val();
	    var cpr = costPerResponse;
	    
	    var row = jQuery("#rowQuestionText").val();
	    var col = jQuery("#colQuestionText").val();
	    if (col == 0)
	    {
	    	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(1)").html(
				   	'<input type="hidden" name="categorySurveyComplete[]" id="categorySurveyComplete" value="'+name+'" /><a href="#" onclick="open_modal_window(this, '+col+'); return false;" ' +
								' >'+name+'</a>');
	    } else if (col == 1) 
	    {
	    	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(1)").html(
				   	'<input type="hidden" name="categorySurveyIncomplete[]" id="categorySurveyIncomplete" value="'+name+'" /><a href="#" onclick="open_modal_window(this, '+col+'); return false;" ' +
								' >'+name+'</a>');
	    }
	    

		jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(2) input").val(quantity);
	   	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(3) input").val(cpr);
	   	
	    
	};
	jQuery('#loadSurveyCategory').modal('toggle');	
	jQuery('#task').val('formzs.save_giftcode');
	jQuery('form#adminForm').ajaxSubmit({
		url: 'index.php?option=com_awardpackage&view=formsz',
		type: 'post'					
	});
	jQuery('#task').val('formzs.save_survey');	
}
function onAdd(){	
	var lastID = jQuery('#questionTable tbody>tr:first-child').find('span a').text();
	lastID = parseInt(lastID.substring("Question ".length, lastID.length));
	lastID += 1; 
	jQuery('#questionSelectedId').val(lastID);
	var page =  jQuery('select[name=surveyPages]').val();
	jQuery('#task').val('formzs.config_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=formzs&page='+parseInt(page));
	jQuery('form#adminForm').submit();			
}
function onDelete(){	
	jQuery('#task').val('formzs.delete_giftcode');
	jQuery('#questionTable').find('input:checkbox:checked').each(function(){
		jQuery('#questionToDelete').val(jQuery(this).parent().find('#questionId').val());
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&view=formzs',
			type: 'post',
			success: function(data) {
				console.log(data);
				if(data == 'SUCCESS'){
	            	jQuery('#questionTable').find('input:checkbox:checked').parent().parent().remove();
	            } else {
		            alert('Can\'t delete. At least one question should be existed.');
	            }
	        }						
		});					
	});	
	jQuery('#task').val('surveysetting.save_survey');	
}
function show_page_title(){
	jQuery('#page-rename-modal').modal('show');
}
function update_page_title(){
	var id =  jQuery('select[name=surveyPages]').val();
	var title = jQuery('#pageTitle').val();
	jQuery('#task').val('formzs.update_page_title');
	jQuery('select[name=surveyPages] :selected').text(title);
	jQuery('#page-rename-modal').modal('toggle');
	jQuery('form#adminForm').ajaxSubmit({
		url: 'index.php?option=com_awardpackage&view=formzs',
		type: 'post'					
	});	
	jQuery('#task').val('formzs.save_survey');		
}
function show_reorder_page(){
	jQuery('#reorder-pages-modal').modal('show');			
}
function reorder_page(){
	var package_id = jQuery('#package_id').val();
	var uniq_id = jQuery('#uniq_id').val();
	var id = jQuery('#id').val();	
	var ordering = new Array();
	jQuery('#page-ordering-list').find('li').each(function(index){
		ordering.push(jQuery(this).attr('id').substring(7));
	});	
	jQuery('#reorder-pages-modal').modal('toggle');	
	jQuery.ajax({
		url: 'index.php?option=com_awardpackage&view=formzs&task=formzs.reorder_pages',
		data: {order: ordering},
		type: 'post',
		dataType: 'json',
		success: function(data, statusText, xhr, form){			
		}
	});
	if(parseInt(id) > 0) {
		window.location = 'index.php?option=com_awardpackage&view=formzs&task=formzs.do_edit&package_id='+package_id+'&id='+id;
	} else {
		window.location = 'index.php?option=com_awardpackage&view=formzs&task=formzs.do_first&package_id='+package_id+'&uniq_id='+uniq_id;
	}
	
}
function onNewPage(){
	jQuery('#questionSelectedId').val('1');
	var page = jQuery('select[name=surveyPages]').val();
	jQuery('#task').val('formzs.new_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=formzs&page=xxx');
	jQuery('form#adminForm').submit();			
}
function pageChange(){		
	var page = jQuery('select[name=surveyPages]').val();
	jQuery('#questionSelectedId').val('1');
	jQuery('#task').val('formzs.config_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=formzs&page='+parseInt(page));
	jQuery('form#adminForm').submit();
}
function onDeletePage(){
	jQuery('#task').val('formzs.remove_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=formzs');
	jQuery('form#adminForm').submit();
}
function open_question(e){
	var questionId = jQuery(e).text();
	questionId = questionId.substring("Question ".length, questionId.length);
	jQuery('#questionSelectedId').val(questionId);
	jQuery('#task').val('formzs.question');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=question');
	jQuery('form#adminForm').submit();						
}
function onPreview(){
	jQuery('#task').val('formzs.preview');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=formzs');
	jQuery('form#adminForm').attr('target', '_blank');
	jQuery('form#adminForm').submit();
			
}
</script>
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=formzs&task=formzs.save_survey')?>" method="post">
<input type="hidden" name="task" id="task" value="formzs.save_survey">
<input type="hidden" name="uniq_id" id="uniq_id" value="<?php echo $this->answers->uniq_id; ?>"> 
<input type="hidden" name="package_id" id="package_id" value="<?php echo $this->package_id; ?>" />
<input type="hidden" name="questionSelectedId" id="questionSelectedId" value="<?php echo $this->answers->questionSelectedId; ?>"> 
<input type="hidden" name="rowQuestionText" id="rowQuestionText" value=""> 
<input type="hidden" name="colQuestionText" id="colQuestionText" value=""> 
<input type="hidden" name="selectedQuestion" id="selectedQuestion" value="" />
<input type="hidden" name="questionToDelete" id="questionToDelete" value=""/>
<input type="hidden" name="published" value="<?php echo $this->answers->published;?>"> 
<input type="hidden" name="id" id="id" value="<?php echo $this->answers->id;?>">
<table>
	<tr>
		<td width="10%" valign="top">
			<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
		</td>
		<td valign="top">
			<div id="cj-wrapper">
				<div
					class="container-fluid no-space-left no-space-right surveys-wrapper">
				<div class="row-fluid">
				<div style="border: 1px solid #ccc; padding: 10px;font-size:11px; width:670px;"><br>
				<div id="accordion">
                <div class="tab-content">
												
                  </div>                                  
				<?php  
$i=0;
foreach ($this->answers as $questionId) { $i=$i+1;?>
<div style="border: 1px solid #ccc;
 margin-bottom: 2px;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	background: #f0f0f0;	
 ">
 <a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=formzs&task=formzs.edit_questions&id='.$questionId->id.'&uniq_id='.$questionId->uniq_key.'&questionSelectedId='.$questionId->survey_id.'&$questionId='.$questionId->question_id)?>" 
												class="tooltip-hover" title="<?php echo JText::_('LBL_EDIT_QUESTIONS');?>">
<?php echo '<h4 href="#" id="expanderHead_'.$i.'" style="text-align:center;cursor:pointer;"> Question #'.$i.' '.$questionId->title; ?>
											</a>
                                            
</div>
<?php } ?>
				
				</div>
				</div>
				</div>
		</td>
	</tr>
</table>
</form>
