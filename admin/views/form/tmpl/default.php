<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();

$page_id = 6;
$editor = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
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
function open_modal_window(e, index){		
	var row = jQuery(e).parent().parent().parent().parent().parent().parent().parent().parent().parent().index();
	jQuery('#rowQuestionText').val(row);
	jQuery('#colQuestionText').val(index);
	jQuery('#loadQuizCategory').modal('show');		
}
function close_modal_window(){	
	if(jQuery("input[type='radio'].radioQuizCategoryClass").is(':checked')) {
	    var id = parseInt(jQuery("input[type='radio'].radioQuizCategoryClass:checked").val()) + 1;			    
	    var tr = jQuery("#quizCategoryTable tr:eq("+id+")");
	    var name = jQuery(tr).find("td:eq(2)").text();			    
	    var quantity = jQuery(tr).find("td:eq(3) input").val();
	    var costPerResponse = jQuery(tr).find("td:eq(4) input").val();
	    var cpr = costPerResponse;	    
	    var row = jQuery("#rowQuestionText").val();
	    var col = jQuery("#colQuestionText").val();
	    if (col == 0)
	    {
	    	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(1)").html(
				   	'<input type="hidden" name="categoryQuizComplete[]" id="categoryQuizComplete" value="'+name+'" /><a href="#" onclick="open_modal_window(this, '+col+'); return false;" ' +
								' >'+name+'</a>');
	    } else if (col == 1) 
	    {
	    	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(1)").html(
				   	'<input type="hidden" name="categoryQuizIncomplete[]" id="categoryQuizIncomplete" value="'+name+'" /><a href="#" onclick="open_modal_window(this, '+col+'); return false;" ' +
								' >'+name+'</a>');
	    }   
		jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(2) input").val(quantity);
	   	jQuery("#questionTable").find("tbody:first-child>tr:eq("+row+")").find("table tbody>tr:eq("+col+")").find("td:eq(3) input").val(cpr);   	
	};
	jQuery('#loadQuizCategory').modal('toggle');	
	jQuery('#task').val('form.save_giftcode');
	jQuery('form#adminForm').ajaxSubmit({
		url: 'index.php?option=com_awardpackage&view=form',
		type: 'post',
		success: function(data) {
            console.log(data); 
        }					
	});
	jQuery('#task').val('form.save_quiz');	
}
function onAdd(){
	var lastID = jQuery('#questionTable tbody>tr:first-child').find('span a').text();
	lastID = parseInt(lastID.substring("Question ".length, lastID.length));
	if(isNaN(lastID)) {
		lastID = 1;
	} else {
		lastID += 1;
	}	 
	jQuery('#questionSelectedId').val(lastID);
	var page =  jQuery('select[name=quizPages]').val();
	jQuery('#task').val('form.config_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form&page='+parseInt(page));
	jQuery('form#adminForm').submit();			
}
function onDelete(){	
	jQuery('#task').val('form.delete_giftcode');
	jQuery('#questionTable').find('input:checkbox:checked').each(function(){
		jQuery('#questionToDelete').val(jQuery(this).parent().find('#questionId').val());
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&view=form',
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
	jQuery('#task').val('quiz.save_quiz');	
}
function onNewPage(){
	jQuery('#questionSelectedId').val('1');
	var page = jQuery('select[name=quizPages]').val();
	jQuery('#task').val('form.new_page_2');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form&page=xxx');
	jQuery('form#adminForm').submit();			
}
function onDeletePage(){
	jQuery('#task').val('form.remove_page_2');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form');
	jQuery('form#adminForm').submit();
}
function pageChange(){		
	var page =  jQuery('select[name=quizPages]').val();
	jQuery('#questionSelectedId').val('1');
	jQuery('#task').val('form.config_page');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form&page='+parseInt(page));
	jQuery('form#adminForm').submit();
}
function show_page_title(){
	jQuery('#page-rename-modal').modal('show');
}
function update_page_title(){
	var id =  jQuery('select[name=quizPages]').val();
	var title = jQuery('#pageTitle').val();
	jQuery('#task').val('form.update_page_title');
	jQuery('select[name=quizPages] :selected').text(title);
	jQuery('#page-rename-modal').modal('toggle');
	jQuery('form#adminForm').ajaxSubmit({
		url: 'index.php?option=com_awardpackage&view=form',
		type: 'post',
		success: function(data) {
			console.log(data);
		}					
	});	
	jQuery('#task').val('form.save_quiz');		
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
		url: 'index.php?option=com_awardpackage&view=form&task=form.reorder_pages',
		data: {order: ordering},
		type: 'post',
		dataType: 'json',
		success: function(data, statusText, xhr, form){			
		}
	});
	if(parseInt(id) > 0) {
		window.location = 'index.php?option=com_awardpackage&view=form&task=form.do_edit&package_id='+package_id+'&id='+id;
	} else {
		window.location = 'index.php?option=com_awardpackage&view=form&task=form.create_edit_quiz&package_id='+package_id+'&uniq_id='+uniq_id;	
	}	
}
function open_question(e){
	var questionId = jQuery(e).text();
	questionId = questionId.substring("Question ".length, questionId.length);
	jQuery('#questionSelectedId').val(questionId);
	jQuery('#task').val('form.question');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form');
	jQuery('form#adminForm').submit();						
}
function onPreview(){
	jQuery('#task').val('form.preview');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form');
	jQuery('form#adminForm').submit();
			
}
</script>
<form id="adminForm" name="adminForm" class="quiz-form" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.save_quiz')?>" method="post">
<input type="hidden" name="package_id" id="package_id" value="<?php echo $this->item->package_id; ?>"/>
<input type="hidden" name="uniq_id" id="uniq_id" value="<?php echo $this->item->uniq_id; ?>"/>
<input type="hidden" name="questionSelectedId" id="questionSelectedId" value="<?php echo $this->item->questionSelectedId; ?>">
<input type="hidden" name="pageId" value="<?php echo $this->escape($this->item->pageId);?>" /> 
<input type="hidden" name="rowQuestionText" id="rowQuestionText" value=""> 
<input type="hidden" name="colQuestionText" id="colQuestionText" value=""> 
<input type="hidden" name="selectedQuestion" id="selectedQuestion" value="" />
<input type="hidden" name="questionToDelete" id="questionToDelete" value=""/>
<input type="hidden" name="task" id="task" value="form.save_quiz">
<input type="hidden" name="published" value="<?php echo $this->item->published;?>">
<input name="id" id="id" type="hidden" value="<?php echo $this->item->id;?>">					
<table width="100%">
	<tr>
		<td width="51%" valign="top">
			<div id="cj-wrapper" style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">	
				<div class="container-fluid no-space-left no-space-right quizzes-wrapper">		
						<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>" />
						<h3 class="page-title"><?php echo JText::_('Quiz Category'); ?></h3>
						<div class="row-fluid">
							<div class="span9">
								<div class="clearfix">
                               
									<label><?php echo JText::_('LBL_CATEGORY');?><sup>*</sup>:</label>
									<select name="catid" size="1">
										<option><?php echo JText::_('SELECT CATEGORY');?></option>
                                        
										<?php 
										foreach($this->kategori as $row):?>
										<option value="<?php echo $row->id;?>"<?php echo ($this->item->catid == $row->id) ? ' selected="selected"' : ''?>>
											<?php echo $this->escape($row->title);?>
										</option>
										<?php endforeach;?>
									</select>
								</div>	
							</div>
						</div>
						<h3 class="page-title"><?php echo JText::_('LBL_BASIC_INFORMATION');?></h3>			
						<div class="row-fluid">
							<div class="span9">
								<div class="clearfix">
									<label><?php echo JText::_('LBL_TITLE');?><sup>*</sup>:</label>
									<input name="title" type="text" class="input-xxlarge required" value="<?php echo $this->escape($this->item->title);?>" placeholder="<?php echo JText::_('Enter a descriptive title of the quiz');?>">
								</div>
								<div class="clearfix">
									<label><?php echo JText::_('LBL_ALIAS');?>:</label>
									<input name="alias" type="text" class="input-xxlarge" value="<?php echo $this->escape($this->item->alias);?>" placeholder="<?php echo JText::_('Enter an alias used for the quiz url, leave blank to let the system create it');?>">
								</div>				
								<div class="clearfix">
									<label><?php echo JText::_('LBL_DESCRIPTION1');?>:</label>
									<?php echo CJFunctions::load_editor($editor, 'description', 'description', $this->item->description, '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
									<span class="help-block"><?php echo JText::_('Enter the detailed description about your quiz');?></span>
								</div>
							</div>
						</div>			
						<h3 class="page-title"><?php echo JText::_('QUIZ OPTIONS');?></h3>			
						<div class="row-fluid">
							<div class="form-horizontal">
								<div class="span6">
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('LBL_TIME_DURATION');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('TXT_TIME_DURATION_HELP');?>"></i>
										</label>
										<div class="controls">
											<input name="duration" type="text" value="<?php echo $this->escape($this->item->duration);?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('LBL_MULTIPLE_RESPONSES');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('TXT_MULTIPLE_RESPONSES_HELP');?>"></i>
										</label>
										<div class="controls">
											<select name="multiple-responses" size="1">
												<option value="1" <?php echo $this->item->multiple_responses == '1' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_ALLOW');?></option>
												<option value="0" <?php echo $this->item->multiple_responses == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_DISALLOW');?></option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('LBL_SKIP_INTRO');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('TXT_SKIP_INTRO_HELP');?>"></i>
										</label>
										<div class="controls">
											<select name="skip_intro" size="1">
												<option value="0" <?php echo $this->item->skip_intro == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_NO');?></option>
												<option value="1" <?php echo $this->item->skip_intro == '1' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_YES');?></option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('LBL_SHOW_RESULT');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('TXT_SHOW_RESULT_HELP');?>"></i>
										</label>
										<div class="controls">
											<select name="show-result" size="1">
												<option value="1" <?php echo $this->item->show_answers == '1' ? 'selected="selected"':'';?>><?php echo JText::_('SHOW');?></option>
												<option value="0" <?php echo $this->item->show_answers == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_HIDE');?></option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('LBL_SHOW_TEMPLATE');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('TXT_SHOW_TEMPLATE_HELP');?>"></i>
										</label>
										<div class="controls">
											<select name="show-template" size="1">
												<option value="1" <?php echo $this->item->show_template == '1' ? 'selected="selected"':'';?>><?php echo JText::_('SHOW');?></option>
												<option value="0" <?php echo $this->item->show_template == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_HIDE');?></option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('Cutoff');?><sup>*</sup>:
											<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('COM_COMMUNITYQUIZ_HELP_CUTOFF');?>"></i>
										</label>
										<div class="controls">
											<input name="cutoff" type="text" value="<?php echo $this->escape($this->item->cutoff);?>">
										</div>
									</div>
								</div>				
							</div>
						</div>			
						<h3 class="page-title"><?php echo JText::_('TAGS');?></h3>
						<div class="row-fluid">
							<div class="span12">
								<span class="help-block"><?php echo JText::_('Enter few tags/keywords related to this quiz which can be used while searching/filtering the quizzes.');?></span>
								<input type="text" size="40" id="input-tags" name="input-tags" data-provide="typeahead" class="input-xlarge" autocomplete="off" placeholder="<?php echo JText::_('Type for suggestions. Enter to save tag');?>">
								<div class="quiz-tags">
									<ul class="clearfix unstyled">
										<?php foreach($this->item->tags as $tag):?>
										<?php if(!empty($tag)):?>
										<li class="label">
											<a href="#" class="btn-remove-tag" onclick="return false;"><i class="icon-remove icon-white"></i></a>&nbsp;
											<span class="tag-item"><?php echo $this->escape($tag)?></span>
										</li>
										<?php endif;?>
										<?php endforeach;?>
									</ul>
									<input type="hidden" name="tags" value="">
								</div>
							</div>
						</div>
					<div style="display: none;">
						<span id="url-get-tags"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=get_tags')?></span>
						<input type="hidden" name="cjpageid" id="cjpageid" value="create_edit_quiz">
					</div>
				</div>
			</div>				
		</td>
		<td width="1%"></td>
		<td width="48%" valign="top">
			<div id="cj-wrapper">
				<div
					class="container-fluid no-space-left no-space-right quizzes-wrapper">
					<div class="row-fluid">
						<div class="span12" style="text-align: right;">
							<table width="100%" border="0" cellpadding="1" cellspacing="2">
								<tr>
									<td width="50%" align="left">&nbsp;&nbsp;	
										<select name="quizPages"
											id="quizPages" style="width: 248px;" onchange="pageChange();">
											<?php for($i=0; $i < count($this->item->quizPages); $i++){
												$pages = $this->item->quizPages[$i];
											?>
											<option value="<?php echo $pages->id; ?>"
												<?php echo ($pages->id == $this->item->quizPage ? "selected=selected" : "" ); ?>
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
														<a href="#" onclick="show_page_title();">
															<i class="fa fa-edit"></i> <?php echo JText::_('LBL_RENAME');?>
														</a>
													</li>
													<li>
														<a class="btn-reorder-page" href="#" onclick="show_reorder_page();"><i class="fa fa-edit"></i> <?php echo JText::_('LBL_REORDER_PAGES');?></a>
													</li>
													<li>
														<a href="#" onclick="onNewPage();">
															<i class="fa fa-plus-circle"></i> <?php echo JText::_('LBL_NEW');?>
														</a>
													</li>
													<li>
														<a href="#" onclick="onDeletePage();">
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
											onclick="onAdd();" id="addBtn"><i></i> <?php echo JText::_('Add');?></button>
										<button type="button" class="btn btn-primary btn-invite-reg-groups"
											onclick="onDelete();"><i></i> <?php echo JText::_('Delete');?></button>
										</div>
										</td>
									</tr>
								</table>
								<br />
								<table class="table table-hover table-striped" width="100%"
									id="questionTable">
									<tbody>
										<?php  foreach ($this->item->questionId as $questionId) { ?>
										<tr>
											<td width="1%" align="center"><input type="hidden"
												name="questionId[]" id="questionId"
												value="<?php echo $questionId->question_id; ?>" /> <?php echo JHtml::_('grid.id',$i,$questionId->question_id); ?>
											</td>
											<td align="center">
											<div style="border: 1px solid #ccc; padding: 10px; height: 170px;">
											<div
												style="border: 1px solid #ccc; padding: 2px; float: left; text-align: left; width: 550px; margin-top: 2px;">
											<div style="width: 520px; float: left;"><b><span><?php echo JText::_('Quiz	 :	');?><a
												href="#" onclick="open_question(this);">Question <?php echo $questionId->question_id; ?></a></span></div>
											<div style="width: 30px; float: right;">
												<a 
													class="btn btn-mini <?php if($questionId->complete_giftcode_quantity !=''){echo 'btn-danger';}else{ echo 'btn-success';}
													 ?> tooltip-hover btn-publish" 
													href="#"
													onclick="return false;">
													<i class="icon <?php if($questionId->complete_giftcode_quantity !=''){echo 'icon-remove';}else{ echo 'icon-ok';} ?> icon-white"></i>
												</a>
											</div>
											</div>
											<div style="width: 556px; float: left;">
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
														<td><span style="color: blue;">Correct</span></td>
														<td><input type="hidden" name="categoryQuizComplete[]"
															id="categoryQuizComplete"
															value="<?php echo $questionId->complete_giftcode; ?>" /> <a
															href="#" onclick="open_modal_window(this, 0); return false;"> <?php echo $questionId->complete_giftcode; ?></a></td>
														<td><input type="text" name="giftcodeQuantityComplete[]"
															style="width: 80px;"
															value="<?php echo $questionId->complete_giftcode_quantity; ?>" readonly="readonly" /></td>
														<td><input type="text" name="costPerResponseComplete[]"
															style="width: 80px;"
															value="<?php echo $questionId->complete_giftcode_cost_response; ?>" readonly="readonly" /></td>
													</tr>
													<tr>
														<td><span style="color: red;">Incorrect</span></td>
														<td><input type="hidden" name="categoryQuizIncomplete[]"
															id="categoryQuizIncomplete"
															value="<?php echo $questionId->incomplete_giftcode; ?>" /> <a
															href="#loadQuizCategory"
															onclick="open_modal_window(this, 1);"> <?php echo $questionId->incomplete_giftcode; ?></a></td>
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
</table>
<div id="loadQuizCategory" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Quiz Category Giftcode');?></h3>
	</div>
	<table class="table table-striped" id="quizCategoryTable"
		style="border: 1px solid #ccc;">
		<thead>
			<tr style="background-color:#CCCCCC">
				<th width="5%">&nbsp;</th>
				<th><u><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY')?></u></th>
				<th width="19%"><u><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></u></th>
				<th><u><?php echo JText::_('Giftcode Quantity');?></u></th>
				<th><u><?php echo JText::_('Total Giftcode Cost');?></u></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->categories as $i=>$category) { ?>
			<tr class="row<?php echo $i%2;?>">
				<td><input type="radio" class="radioQuizCategoryClass"
					value="<?php echo $i; ?>" name="quizCategory"
					onclick="close_modal_window();"></td>
				<td valign="center">
					<div style="padding-top:14px;width:50px;height:40px;text-align:center;background-color:<?php echo $category->colour_code;?>"><font color="white" size="5"><b><?php echo $category->category_id; ?></b></font></div>
				</td>						
				<td><?php echo $category->category_name; ?></td>				
				<td align="right">
				<input type="text" value="1" name="giftCodeTotal" onkeyup="numbersOnly(this);">
				<input type="hidden" value="<?php echo ($category->quiz_price*100); ?>">
				</td>
				<td align="right">
				<input type="text" value="<?php echo ($category->quiz_price*100); ?>" name="giftCodePrice" readonly="readonly">								
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
	<table class="table table-striped" id="quizCategoryTable"
		style="border: 1px solid #ccc;" width="80%">
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
					id="updateTitleBtn" onclick="update_page_title();"><i></i> <?php echo JText::_('UPDATE TITLE');?></button>
	</div>		
</div>
<div id="reorder-pages-modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('LBL_REORDER_PAGES');?></h3>
	</div>
	<div class="modal-body">
		<table class="table table-striped" id="quizCategoryTable"
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
							<?php foreach($this->item->quizPages as $num=>$page):?>
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
</form>
