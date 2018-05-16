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
<input type="hidden" name="uniq_id" id="uniq_id" value="<?php echo $this->item->uniq_id; ?>"> 
<input type="hidden" name="package_id" id="package_id" value="<?php echo $this->package_id; ?>" />
<input type="hidden" name="questionSelectedId" id="questionSelectedId" value="<?php echo $this->item->questionSelectedId; ?>"> 
<input type="hidden" name="rowQuestionText" id="rowQuestionText" value=""> 
<input type="hidden" name="colQuestionText" id="colQuestionText" value=""> 
<input type="hidden" name="selectedQuestion" id="selectedQuestion" value="" />
<input type="hidden" name="questionToDelete" id="questionToDelete" value=""/>
<input type="hidden" name="published" value="<?php echo $this->item->published;?>"> 
<input type="hidden" name="id" id="id" value="<?php echo $this->item->id;?>">
<div id="cj-wrapper">
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php'; ?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>
				<table width="100%">
					<tr>
						<td width="51%" valign="top">
							<div id="cj-wrapper"
								style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
									<div class="container-fluid no-space-left no-space-right surveys-wrapper">					
										<fieldset>										
											<legend><?php echo JText::_('LBL_BASIC_INFORMATION');?></legend>											
											<div class="row-fluid">
												<div class="span12">	
                                                					<div class="clearfix">
									<select name="catid" size="1">
										<option><?php echo JText::_('Select Category');?></option>
                                        <?php 
										foreach ($this->scategories as $row){ ?>
										<option value="<?php echo $row->id; ?>" <?php echo ($this->item->catid == $row->id) ? ' selected="selected"' : ''?>><?php echo $row->title; ?></option>
                                        <?php } ?>
									</select>
								</div>	
													<div class="clearfix">
														<label>
															<?php echo JText::_('LBL_TITLE');?><sup>*</sup>: 
															<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_TITLE');?>"></i>
														</label>
														<input name="title" type="text" class="input-large" value="<?php echo $this->escape($this->item->title);?>" placeholder="<?php echo JText::_('Survey Title');?>" required="" aria-required="true">
													</div>
								
													<div class="clearfix">
														<label>
															<?php echo JText::_('LBL_ALIAS');?>:
															<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_ALIAS');?>"></i>
														</label>
														<input name="alias" type="text" class="input-large" value="<?php echo $this->escape($this->item->alias);?>">
													</div>
													
													
													
													<div class="clearfix margin-top-20">
														<label>
															<?php echo JText::_('Introduction Message');?>:
															<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Introduction Message');?>"></i>
														</label>
														<?php echo CJFunctions::load_editor($editor, 'introtext', 'introtext', $this->item->introtext, '5', '40', '99%', '200px', '', 'width: 80%;'); ?>
													</div>
													
													<div class="clearfix margin-top-20">
														<label>
															<?php echo JText::_('End of Survey Message');?>:
															<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('End of Survey Message');?>"></i>
														</label>
														<?php echo CJFunctions::load_editor($editor, 'endtext', 'endtext', $this->item->endtext, '5', '40', '99%', '200px', '', 'width: 80%;'); ?>
													</div>
													
													<div class="clearfix margin-top-20">
														<label>
															<?php echo JText::_('Custom Page Header');?>:
															<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Custom Page Header');?>"></i>
														</label>
														<?php echo CJFunctions::load_editor($editor, 'custom_header', 'custom_header', $this->item->custom_header, '5', '40', '99%', '200px', '', 'width: 80%;'); ?>
													</div>
												</div>
											</div>
											
											<legend><?php echo JText::_('Survey Options');?></legend>
											
											<div class="row-fluid">
												<div class="form-horizontal">
													
													<div class="span12">
														<div class="clearfix">
															<label>
																<?php echo JText::_('Survey Type');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Survey Type');?>"></i>
															</label>
															<select name="survey-type" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->private_survey == '1' ? 'selected="selected"':'';?>><?php echo JText::_('Private Survey');?></option>
																<option value="0" <?php echo $this->item->private_survey == '0' ? 'selected="selected"':'';?>><?php echo JText::_('Public Survey');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Responses Type');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Responses Type');?>"></i>
															</label>
															<select name="response-type" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->anonymous == '1' ? 'selected="selected"':'';?>><?php echo JText::_('Anonymous');?></option>
																<option value="0" <?php echo $this->item->anonymous == '0' ? 'selected="selected"':'';?>><?php echo JText::_('Onymous');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Display Report');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Display Report');?>"></i>
															</label>
															<select name="show-result" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->public_permissions == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->public_permissions == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Use Site Styles');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Use Site Styles');?>"></i>
															</label>
															<select name="show-template" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->display_template == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->display_template == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Skip Intro');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Skip Intro');?>"></i>
															</label>
															<select name="skip-intro" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->skip_intro == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->skip_intro == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Confidentiality Notice');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Confidentiality Notice');?>"></i>
															</label>
															<select name="display-notice" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->display_notice == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->display_notice == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Notification');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Notification');?>"></i>
															</label>
															<select name="notification" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->notification == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->notification == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Back Navigation');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Back Navigation');?>"></i>
															</label>
															<select name="backward-navigation" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->backward_navigation == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
																<option value="0" <?php echo $this->item->backward_navigation == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Progress Bar');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Progress Bar');?>"></i>
															</label>
															<select name="display-progress" size="1" class="input-medium">
																<option value="1" <?php echo $this->item->display_progress == '1' ? 'selected="selected"':'';?>><?php echo JText::_('Display');?></option>
																<option value="0" <?php echo $this->item->display_progress == '0' ? 'selected="selected"':'';?>><?php echo JText::_('Hide');?></option>
															</select>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Start Date');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Start Date');?>"></i>
															</label>
															<?php echo JHtml::_('calendar', $this->item->publish_up, 'publish-up', 'publish-up', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('End Date');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('End Date');?>"></i>
															</label>
															<?php echo JHtml::_('calendar', $this->item->publish_down, 'publish-down', 'publish-down', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Max Responses');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Max Responses');?>"></i>
															</label>
															<input name="max-responses" type="text" class="input-medium" value="<?php echo $this->escape($this->item->max_responses);?>">															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Redirect URL');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Redirect URL');?>"></i>
															</label>
															<input name="redirect-url" type="text" class="input-medium" value="<?php echo $this->escape($this->item->redirect_url);?>">															
														</div>
														<br/>
														<div class="clearfix">
															<label>
																<?php echo JText::_('Restriction');?><sup>*</sup>:
																<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('Restriction');?>"></i>
															</label>
															<label class="checkbox">
																<input name="restriction[]" type="checkbox" value="cookie"
																	<?php echo strpos($this->item->restriction, 'cookie') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('Cookies')?>
															</label>
															<label class="checkbox">
																<input name="restriction[]" type="checkbox" value="username"
																	<?php echo strpos($this->item->restriction, 'username') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('LBL_USERNAME')?>
															</label>
															<label class="checkbox">
																<input name="restriction[]" type="checkbox" value="ip"
																	<?php echo strpos($this->item->restriction, 'ip') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('IP Address')?>
															</label>															
														</div>
													</div>											
												</div>
											</div>									
										</fieldset>										
										<input name="id" type="hidden" value="<?php echo $this->item->id;?>">
										<input name="userid" type="hidden" value="<?php echo $this->item->created_by;?>">									
									
									<div style="display: none;">
										<input type="hidden" name="cjpageid" id="cjpageid" value="create_edit_survey">
									</div>
								</div>
							</div>								
						</td>
						<td width="1%"></td>
						<td width="48%" valign="top">
							<div id="cj-wrapper">
								<div
									class="container-fluid no-space-left no-space-right surveys-wrapper">
									<div class="row-fluid">
										<div class="span12" style="text-align: right;">
											<table width="100%" border="0" cellpadding="1" cellspacing="2">
												<tr>
													<td width="50%" align="left">&nbsp;&nbsp; 
														<select name="surveyPages"
															id="surveyPages" style="width: 100px;" onchange="pageChange();">
															<?php for($i=0; $i < count($this->item->surveyPages); $i++){
																$pages = $this->item->surveyPages[$i];
															?>
															<option value="<?php echo $pages->id; ?>"
																<?php echo ($pages->id == $this->item->surveyPage ? "selected=selected" : "" ); ?>
																><?php echo ($pages->title != '' ? 'Page '.$pages->sort_order.' ('.$pages->title.')' : 'Page '.$pages->sort_order); ?></option>
															<?php } ?>
														</select> &nbsp;
														<div class="page-actions pull-right margin-left-10">
															<div class="dropdown">
																<button type="button" class="dropdown-toggle btn btn-small" data-toggle="dropdown">
																	<i class="fa fa-cogs"></i> <span class="caret"></span>
																</button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
																	<li>
																		<a href="#" onclick="show_page_title(); return false;">
																			<i class="fa fa-edit"></i> <?php echo JText::_('LBL_RENAME');?>
																		</a>
																	</li>
																	<li>
																		<a class="btn-reorder-page" href="#" onclick="show_reorder_page(); return false;"><i class="fa fa-edit"></i> <?php echo JText::_('LBL_REORDER_PAGES');?></a>
																	</li>
																	<li>
																		<a href="#" onclick="onNewPage(); return false;">
																			<i class="fa fa-plus-circle"></i> <?php echo JText::_('LBL_NEW');?>
																		</a>
																	</li>
																	<li>
																		<a href="#" onclick="onDeletePage(); return false;">
																			<i class="fa fa-minus-circle text-error"></i> <?php echo JText::_('LBL_REMOVE');?>
																		</a>
																	</li>
																</ul>						
															</div>
														</div>
													</td>
													<td width="50%" align="right">
														<button type="button" class="btn btn-primary btn-invite-reg-groups"
															onclick="onPreview();" id="previewBtn"><i></i> <?php echo JText::_('Preview');?></button>
														</td>
													</td>
												</tr>
											</table>
											<br />
											<br />
											<div class="span12" style="border: 1px solid #ccc; padding: 10px;">
												<table width="100%">
													<tr>
														<td align="right">
														<button type="button" class="btn btn-primary btn-invite-reg-groups"
															onclick="onAdd(); return false;" id="addBtn"><i></i> <?php echo JText::_('Add');?></button>
														<button type="button" class="btn btn-primary btn-invite-reg-groups"
															onclick="onDelete(); return false;"><i></i> <?php echo JText::_('Delete');?></button>
														</div>
														</td>
													</tr>
												</table>
												<br>
												<table class="table table-hover table-striped" width="100%"
													id="questionTable">
													<tbody>
														<?php 
														foreach ($this->item->questionId as $questionId) { 
														?>
                                                        
														<tr>
															<td width="1%" align="center"><input type="hidden"
																name="questionId[]" id="questionId"
																value="<?php echo $questionId->question_id; ?>" /> <?php echo JHtml::_('grid.id',$i,$questionId->question_id); ?>
															</td>
															<td align="center">
															<div style="border: 1px solid #ccc; padding: 10px; height: 210px;">
															<div
																style="border: 1px solid #ccc; padding: 2px; float: left; text-align: left; width: 372px; margin-top: 2px;">
															<div style="width: 260px; float: left;"><b><span><?php echo JText::_('Survey :	');?><a
																href="#" onclick="open_question(this);">Question <?php echo $questionId->question_id; ?></a></span></div>
															<div style="width: 30px; float: right;">
																<a 
																	class="btn btn-mini <?php echo (!empty($questionId->questions) > 0 ? 'btn-success' : 'btn-danger');?> tooltip-hover btn-publish" 
																	href="#"
																	onclick="return false;">
																	<i class="icon <?php echo (!empty($questionId->questions) > 0 ? 'icon-ok' : 'icon-remove'); ?> icon-white"></i>
																</a>
															</div>
															</div>
															<div style="width: 260px; float: left;">
															<table class="table table-striped" style="border: 1px solid #ccc;">
																<thead>
																	<tr>
																		<th width="40%"><u><?php echo JText::_('Answer')?></u></th>
																		<th><u><?php echo JText::_('Giftcode')?></u></th>
																		<th><u><?php echo JText::_('Giftcode Quantity')?></u></th>
																		<th><u><?php echo JText::_('Total Giftcode Cost')?></u></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td><span style="color: blue;">Complete</span></td>
																		<td><input type="hidden" name="categorySurveyComplete[]"
																			id="categorySurveyComplete"
																			value="<?php echo $questionId->complete_giftcode; ?>" /> <a
																			href="#" onclick="open_modal_window(this, 0); return false;">
																			<span> 
																			<?php echo $questionId->complete_giftcode; ?>
																			</span>
																			</a> </td>
																		<td><input type="text" name="giftcodeQuantityComplete[]"
																			style="width: 80px;"
																			value="<?php echo $questionId->complete_giftcode_quantity; ?>" readonly="readonly" /></td>
																		<td><input type="text" name="costPerResponseComplete[]"
																			style="width: 80px;"
																			value="<?php echo $questionId->complete_giftcode_cost_response; ?>" readonly="readonly" /></td>
																	</tr>
																	<tr>
																		<td><span style="color: red;">Incomplete</span></td>
																		<td><input type="hidden" name="categorySurveyIncomplete[]"
																			id="categorySurveyIncomplete"
																			value="<?php echo $questionId->incomplete_giftcode; ?>" /> <a
																			href="#loadSurveyCategory"
																			onclick="open_modal_window(this, 1); return false;"> <?php echo $questionId->incomplete_giftcode; ?></a></td>
																		<td><input type="text" name="giftcodeQuantityIncomplete[]"
																			style="width: 80px;"
																			value="<?php echo $questionId->incomplete_giftcode_quantity; ?>" readonly="readonly" /></td>
																		<td><input type="text" name="costPerResponseIncomplete[]"
																			style="width: 80px;"
																			value="<?php echo $questionId->incomplete_giftcode_cost_response; ?>" readonly="readonly"/></td>
																	</tr>
																</tbody>
															</table>
															</div>
															</div>
															</td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</td>						
					</tr>
					<tr>
						<td colspan="3">
							<div class="row-fluid">
								<div class="span12">
									<div class="well center">
										<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_latest_surveys'.$surveys_itemid)?>"><?php echo JText::_('LBL_CANCEL');?></a>
										<button type="button" class="btn-submit-form btn btn-primary"><i class="icon-arrow-right icon-white"></i> <?php echo JText::_('LBL_SAVE');?></button>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>				
			</td>
		</tr>
	</table>	
</div>
</form>
<div id="loadSurveyCategory" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Survey Category Giftcode');?></h3>
	</div>
	<table class="table table-striped" id="surveyCategoryTable"
		style="border: 1px solid #ccc;">
		<thead>
			<tr style="background-color:#CCCCCC">
				<th width="5%">&nbsp;</th>
				<th><u><?php echo JText::_('Category')?></u></th>
				<th width="19%"><u><?php echo JText::_('Category Name');?></u></th>
				<th><u><?php echo JText::_('Giftcode Quantity');?></u></th>
				<th><u><?php echo JText::_('Total Giftcode Cost');?></u></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->categories as $i=>$category) { ?>
			<tr class="row<?php echo $i%2;?>">
				<td><input type="radio" class="radioSurveyCategoryClass"
					value="<?php echo $i; ?>" name="surveyCategory"
					onclick="close_modal_window();"></td>
				<td valign="center">
					<div style="padding-top:14px;width:50px;height:40px;text-align:center;background-color:<?php echo $category->colour_code;?>"><font color="white" size="5"><b><?php echo $category->category_id; ?></b></font></div>
				</td>						
				<td><?php echo $category->category_name; ?></td>				
				<td align="right">
				<input type="text" value="1" name="giftCodeTotal" onkeyup="numbersOnly(this);">
				<input type="hidden" value="<?php echo ($category->survey_price*100); ?>">
				</td>
				<td align="right">
				<input type="text" value="<?php echo ($category->survey_price*100); ?>" name="giftCodePrice" readonly="readonly">								
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div id="page-rename-modal" class="modal hide fade" style="height:250px; width:700px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('LBL_RENAME_PAGE');?></h3>
	</div>
	<table class="table table-striped" id="surveyCategoryTable"
		style="border: 1px solid #ccc;" >
		<thead>
			<tr>
				<th width="100%"></th>				
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="100%" align="left" valign="center">
					<div class="clearfix"><label><?php echo JText::_('LBL_NAME_TITLE');?>: <i
					class="icon-info-sign tooltip-hover"
					title="<?php echo JText::_('Title');?>"></i> </label><input
					name="pageTitle" id="pageTitle" type="text" class="input-xxlarge"
					value="<?php echo $this->escape($this->item->pageTitle);?>"></div>
					<input type="hidden" name="pageId" value="<?php echo $this->escape($this->item->pageId);?>" />
				</td>				
			</tr>
		</tbody>
	</table>
	<br>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
		<button type="button" class="btn btn-primary btn-save-page-title"
					id="updateTitleBtn" onclick="update_page_title(); return false;"><i></i> <?php echo JText::_('UPDATE TITLE');?></button>
	</div>		
</div>
<div id="reorder-pages-modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('LBL_REORDER_PAGES');?></h3>
	</div>
	<div class="modal-body">
		<table class="table table-striped" id="surveyCategoryTable"
		style="border: 1px solid #ccc;" width="80%">
			<thead>
				<tr>
					<th width="100%"></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100%" align="left" valign="center">
						<ul id="page-ordering-list" class="nav nav-pills nav-stacked">
							<?php foreach($this->item->surveyPages as $num=>$page):?>
							<li id="pageid-<?php echo $page->id;?>" class="">
								<a href="#"><i class="fa fa-move"></i><?php echo JText::sprintf('LBL_PAGE', ($num + 1)).(!empty($page->title) ? ': '.$this->escape($page->title) : '');?></a>
							</li>
							<?php endforeach;?>
						</ul>	
					</td>				
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
		<button type="button" class="btn btn-primary btn-save-page-order" id="reorderPageBtn" onclick="reorder_page();"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('REORDER PAGE');?></button>
	</div>
</div>
