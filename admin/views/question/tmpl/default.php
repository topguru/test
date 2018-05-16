<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.survey
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();

$page_id = 6;
$itemid = '';
$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
?>
<style>
<!--
#element-box .m {background-color: #fff;}
-->
</style>
<div id="cj-wrapper">	
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="fa fa-info-circle"></i> <?php echo JText::_('TXT_QUESTIONS_FORM_HELP');?>
	</div>

	<div class="container-fluid no-space-left no-space-right margin-top-10">
		<div class="row-fluid">
			<div style="width:1090px;">
				<div class="survey-toolbox">
					<div data-spy="affix" data-offset-top="200">
						<div class="navbar navbar-static-top">
							<div class="navbar-inner">
				                <div class="container-fluid">
				                	<a class="brand" href="#"><i class="fa fa-wrench"></i></a>									
				                	<ul class="nav">
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_CHOICE');?> <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li class="nav-header"><?php echo JText::_('TXT_TYPE_MULTIPLE_CHOICE');?></li>
												<li><a href="#" onclick="return false;" id="qntype-radio"><i class="fa fa-dot-circle-o"></i> <?php echo JText::_('TXT_CHOICE_RADIO');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-checkbox"><i class="fa fa-check"></i> <?php echo JText::_('TXT_CHOICE_CHECKBOX');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-select"><i class="fa fa-chevron-down"></i> <?php echo JText::_('TXT_CHOICE_SELECT');?></a></li>
												<li class="divider"></li>
												<li class="nav-header"><?php echo JText::_('TXT_TYPE_IMAGE');?></li>
												<li><a href="#" onclick="return false;" id="qntype-image"><i class="fa fa-picture-o"></i> <?php echo JText::_('TXT_CHOOSE_IMAGE');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-images"><i class="fa fa-film"></i> <?php echo JText::_('TXT_CHOOSE_IMAGES');?></a></li>
												<li class="divider"></li>
												<li class="nav-header"><?php echo JText::_('TXT_TYPE_GRID');?></li>
												<li><a href="#" onclick="return false;" id="qntype-grid-radio"><i class="fa fa-th-large"></i> <?php echo JText::_('TXT_GRID_RADIO');?></a></li>
											</ul>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_FREETEXT');?> <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#" onclick="return false;" id="qntype-singleline"><i class="fa fa-minus"></i> <?php echo JText::_('TXT_FREETEXT_SINGLE_LINE');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-multiline"><i class="fa fa-align-justify"></i> <?php echo JText::_('TXT_FREETEXT_MULTI_LINE');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-password"><i class="fa fa-qrcode"></i> <?php echo JText::_('TXT_FREETEXT_PASSWORD');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-richtext"><i class="fa fa-file"></i> <?php echo JText::_('TXT_FREETEXT_RICHTEXT');?></a></li>
											</ul>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_SPECIAL');?> <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li class="nav-header"><?php echo JText::_('TXT_TYPE_HEADER');?></li>
												<li><a href="#" onclick="return false;" id="qntype-header"><i class="fa fa-magnet"></i> <?php echo JText::_('TXT_PAGE_HEADER');?></a></li>
												<li class="divider"></li>
												<li class="nav-header"><?php echo JText::_('TXT_TYPE_OTHER');?></li>
												<li><a href="#" onclick="return false;" id="qntype-name"><i class="fa fa-user"></i> <?php echo JText::_('TXT_SPECIAL_NAME');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-email"><i class="fa fa-envelope-o"></i> <?php echo JText::_('TXT_SPECIAL_EMAIL');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-calendar"><i class="fa fa-calendar"></i> <?php echo JText::_('TXT_SPECIAL_CALENDAR');?></a></li>
												<li><a href="#" onclick="return false;" id="qntype-address"><i class="fa fa-building-o"></i> <?php echo JText::_('TXT_SPECIAL_ADDRESS');?></a></li>
											</ul>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div> 
			</div>
		
			<div class="span10">
				
				<div class="survey-pages clearfix">
					<div class="pull-right">
						<ul class="unstyled inline">
							<li>
	
								<form name="adminForm" id="adminForm" action=""<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.edit&id='.$this->item->id.':'.$this->item->alias)?>" method="get">
									<input type="hidden" name="option" value="<?php echo S_APP_NAME;?>">
									<input type="hidden" name="view" value="form">
									<input type="hidden" name="task" value="edit_questions">
									<input type="hidden" name="id" value="<?php echo $this->item->id;?>">
									
									<!-- 
									<a class="btn btn-small btn-danger margin-left-10 pull-right" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.finalize_survey&id='.$this->item->id.$itemid)?>">
										<i class="fa fa-hdd-o"></i> <?php echo JText::_('LBL_FINISH');?>
									</a>
									-->
									<!-- 									
									<div class="page-actions pull-right margin-left-10">
										<div class="dropdown">
											<button type="button" class="dropdown-toggle btn btn-small" data-toggle="dropdown">
												<i class="fa fa-cogs"></i> <span class="caret"></span>
											</button>
											<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
												<li>
													<a class="btn-rename-page" href="#" onclick="return false;"><i class="fa fa-edit"></i> <?php echo JText::_('LBL_RENAME');?></a>
												</li>
												<li>
													<a class="btn-reorder-page" href="#" onclick="return false;"><i class="fa fa-edit"></i> <?php echo JText::_('LBL_REORDER_PAGES');?></a>
												</li>
												<li>
													<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.new_page&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
														<i class="fa fa-plus-circle"></i> <?php echo JText::_('LBL_NEW');?>
													</a>
												</li>
												<li>
													<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.remove_page&id='.$this->item->id.':'.$this->item->alias.'&pid='.$this->pid.$itemid)?>">
														<i class="fa fa-minus-circle text-error"></i> <?php echo JText::_('LBL_REMOVE');?>
													</a>
												</li>
											</ul>
										</div>
									</div>
									 -->
									<!-- 
									<select id="page-id" name="pid" size="1" onchange="form.submit();" class="input-xlarge">
										<?php foreach($this->item->pages as $num=>$page):?>
										<option value="<?php echo $page->id;?>"<?php echo $this->pid == $page->id ? ' selected="selected"' : '';?>>
											<?php echo JText::sprintf('LBL_PAGE', ($num + 1)).(!empty($page->title) ? ': '.$this->escape($page->title) : '');?>
										</option>
										<?php endforeach;?>
									</select>
									 -->
								</form>
							</li>
						</ul>
					</div>
				</div>
				
				<div class="accordion survey-form" id="survey-questions">
					
					<?php if(!empty($this->item->questions)):?>
					<?php foreach($this->item->questions as $question):?>
					<div class="accordion-group question-item">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#survey-questions" href="#qn-<?php echo $question->id?>">
								<!-- 
								<span class="pull-right tooltip-hover btn-sort-question" title="<?php echo $question->question_type != 1 ? JText::_('LBL_DRAG_SORT') : ''?>">
									<i class="<?php echo $question->question_type == 1 ? 'fa fa-screenshot' : 'fa fa-arrows';?> qn-btn-drag"></i>
								</span>
								 -->
								 <!-- 
								<?php if($question->question_type != 1):?>
								<span class="pull-right tooltip-hover btn-copy-question margin-right-10" title="<?php echo JText::_('LBL_COPY_QUESTION')?>">
									<i class="fa fa-file"></i>
								</span>
								<?php endif;?>
								 -->
								 <!-- 
								<span class="pull-right tooltip-hover btn-move-question margin-right-10" title="<?php echo JText::_('LBL_MOVE_TO_PAGE')?>">
									<i class="fa fa-share"></i>
								</span>
								 -->
								<span class="pull-right tooltip-hover btn-delete-question margin-right-10" title="<?php echo JText::_('LBL_DELETE_QUESTION')?>">
									<i class="fa fa-trash-o"></i>
								</span>
								<i class="<?php echo SurveyHelper::get_question_icon($question->question_type);?>"></i>&nbsp;<span class="qn-title"><?php echo $this->escape($question->title);?></span>
							</a>
						</div>
						<div id="qn-<?php echo $question->id?>" class="accordion-body collapse">
							<div class="accordion-inner">
								<form class="question-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.save_question&id='.$this->item->id);?>" method="post">
									<input type="hidden" name="uniq_id" value="<?php echo $this->uniq_id; ?>" />	
									<input type="hidden" name="questionSelectedId" value="<?php echo $this->questionSelectedId; ?>"/>
									<?php if($question->question_type != 1):?>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#basicinfo-<?php echo $question->id;?>" data-toggle="tab"><?php echo JText::_('LBL_BASIC_INFORMATION');?></a></li>
										<?php if(in_array($question->question_type, array(2,3,4,5,6,11,12))):?>
										<li><a href="#answers-<?php echo $question->id;?>" data-toggle="tab"><?php echo JText::_('LBL_ANSWERS');?></a></li>
										<?php endif;?>
										<li><a href="#rules-<?php echo $question->id;?>" data-toggle="tab"><?php echo JText::_('LBL_CONDITIONAL_RULES');?></a></li>
									</ul>
									<?php endif;?>
									
									<div class="tab-content">
										<div class="tab-pane active" id="basicinfo-<?php echo $question->id;?>">
											<div class="clearfix">
												<label><?php echo JText::_('LBL_QUESTION_TITLE');?><sup>*</sup>:</label>
												<input type="text" name="title" value="<?php echo $this->escape($question->title);?>" class="input-title input-xlarge required">
											</div>
											
											<div class="clearfix">
												<label><?php echo JText::_('Question Text');?>:</label>
												<?php echo CJFunctions::load_editor($editor, 'desc-'.$question->id, 'description', $question->description, '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
											</div>
											
											<?php if(!in_array($question->question_type, array(1))):?>
											<div class="well well-small margin-top-20">
												
												<label class="checkbox">
													<input type="checkbox" name="mandatory" value="1"<?php echo $question->mandatory == 1 ? ' checked="checked"' : ''?>> <?php echo JText::_('LBL_MANDATORY')?>
												</label>
												
												<?php if(in_array($question->question_type, array(2,3,4,5,6))):?>
												<label class="checkbox">
													<input type="checkbox" name="custom_answer" value="1"<?php echo $question->custom_choice == 1 ? ' checked="checked"' : ''?>> 
													<span><?php echo JText::_('LBL_CUSTOM_CHOICE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_CUSTOM_CHOICE');?>"></i></span>
												</label>
												<?php endif;?>
												
												<?php if(in_array($question->question_type, array(2,3,11,12))):?>
												<label class="checkbox">
													<input type="checkbox" name="orientation" value="1"<?php echo $question->orientation != 'H' ? ' checked="checked"' : ''?>> 
													<span><?php echo JText::_('LBL_DISPLAY_ANSWERS_IN_LINE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_ORIENTATION');?>"></i></span>
												</label>
												<?php endif;?>
												
												<?php if(in_array($question->question_type, array(3, 12))):?>
												<br/>
												<label><?php echo JText::_('COM_COMMUNITYSURVEYS_MIN_SELECTABLE_ANSWERS');?>:</label>
												<select name="min_selections" size="1"  class="input-small">
													<option value="0">0</option>
													<?php for($i = 1; $i <= count($question->answers); $i++):?>
													<option value="<?php echo $i;?>"<?php echo $question->min_selections == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
													<?php endfor;?>
												</select>
												<br/>
												<label><?php echo JText::_('COM_COMMUNITYSURVEYS_MAX_SELECTABLE_ANSWERS');?>:</label>
												<select name="max_selections" size="1"  class="input-small">
													<option value="0"><?php echo JText::_('JALL');?></option>
													<?php for($i = 1; $i <= count($question->answers); $i++):?>
													<option value="<?php echo $i;?>"<?php echo $question->max_selections == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
													<?php endfor;?>
												</select>
												<?php endif;?>
											</div>
											<?php endif;?>
											
										</div>
										
										<?php if(in_array($question->question_type, array(2,3,4,5,6,11,12))):?>
										<div class="tab-pane" id="answers-<?php echo $question->id;?>">

											<div class="answers margin-bottom-20">
												<div class="file-upload-error hide alert alert-error">
													<i class="fa fa-warning-sign"></i> <span class="error-msg"></span>
												</div>

												<?php foreach ($question->answers as $answer):?>
												<?php if($answer->answer_type == 'x'):?>
												<div class="margin-top-10 form-inline answer-item clearfix">													
													<?php if(in_array($question->question_type, array(11,12))):?>
													<div class="span3 img-answer-wrapper">
														<div class="thumbnail">
															<img class="img-answer" src="<?php echo !empty($answer->image) ? CQ_IMAGES_URI_2.$answer->image : CQ_IMAGES_URI_2.'blank_image.png'?>" alt="">
															<p class="center">
																<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture-o"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
															</p>
															<p class="center">
																<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->answer_label);?>" class="required input-answer span8">
															</p>
														</div>
														<input type="hidden" name="image-src" value="<?php echo $answer->image;?>">
													</div>
													<?php else:?>
													<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->answer_label);?>" class="required input-answer input-xlarge">
													<?php endif;?>
													&nbsp;
													<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>
													
													<a href="#" onclick="return false;" class="btn-sort-answer tooltip-hover" title="<?php echo JText::_('LBL_DRAG_SORT');?>"><i class="fa fa-arrows"></i></a>
													
												</div>
												<?php endif;?>
												<?php endforeach;?>
												
												<div class="margin-top-10 margin-bottom-10">
													<button type="button" class="btn btn-primary btn-add-answer"><i class="fa fa-plus fa fa-white"></i> <?php echo JText::_('LBL_ADD_ANSWER');?></button>
												</div>
												
											</div>
											
											<?php if(in_array($question->question_type, array(5,6))):?>
											<div class="tab-pane columns margin-bottom-10" id="columns-<?php echo $question->id;?>">
											
												<?php foreach ($question->answers as $answer):?>
												<?php if($answer->answer_type == 'y'):?>
												<div class="margin-top-10 form-inline answer-item">
													<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->answer_label);?>" class="required input-answer input-xlarge">&nbsp;
													<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>
													<a href="#" onclick="return false;" class="btn-sort-answer tooltip-hover" title="<?php echo JText::_('LBL_DRAG_SORT');?>"><i class="fa fa-arrows"></i></a>
												</div>
												<?php endif;?>
												<?php endforeach;?>
												
												<div class="margin-top-10 margin-bottom-10">
													<button type="button" class="btn btn-primary btn-add-column"><i class="fa fa-plus fa fa-white"></i> <?php echo JText::_('LBL_ADD_COLUMN');?></button>
												</div>
												
												<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo JText::_('TXT_COLUMN_FIELD_HELP');?></div>
												
											</div>
											<?php endif;?>
											
										</div>
										<?php endif;?>
										
										<?php if($question->question_type != 1):?>
										<div class="tab-pane rules-wrapper" id="rules-<?php echo $question->id;?>">
											<table class="table table-striped table-hover rules-list">
												<thead>
													<tr>
														<th><?php echo JText::_('LBL_RULE_DESCRIPTION');?></th>
														<th><?php echo JText::_('LBL_DELETE');?></th>
													</tr>
												</thead>
												<tbody>
													<?php if(!empty($question->rules)):?>
													<?php foreach($question->rules as $rule):?>
													<tr>
														<td><?php echo $rule->rulecontent;?></td>
														<td>
															<a class="btn-remove-rule" onclick="return false;" href="#"><i class="fa fa-trash-o"></i>&nbsp;</a>
															<input name="rule_id" type="hidden" value="<?php echo $rule->id;?>"/>
														</td>
													</tr>
													<?php endforeach;?>
													<?php endif;?>
												</tbody>
											</table>
											
											<h4 class="page-header margin-bottom-10"><?php echo JText::_('LBL_ADD_NEW_RULE');?></h4>
											<div class="conditional-rules-form">
												
												<div class="invalid-form-alert alert alert-error hide">
													<i class="fa fa-warning-sign"></i> <?php echo JText::_('MSG_SELECT_REQUIRED');?>
												</div>
												
												<label class="radio"><input type="radio" name="rule-name" value="1"> <?php echo JText::_('LBL_RULE_IF_ANSWERED');?></label>
												<label class="radio"><input type="radio" name="rule-name" value="2"> <?php echo JText::_('LBL_RULE_IF_NOT_ANSWERED');?></label>
												<?php if(in_array($question->question_type, array(2,3,4,5,6,11,12))):?>
												<label class="radio"><input type="radio" name="rule-name" value="3"> <?php echo JText::_('LBL_RULE_IF_SELECTED');?></label>
												<label class="radio"><input type="radio" name="rule-name" value="4"> <?php echo JText::_('LBL_RULE_IF_NOT_SELECTED');?></label>
												<?php endif;?>
												
												<div class="rule-criteria form-horizontal margin-top-20 margin-bottom-10 hide">
													<div class="control-group rule-answer">
														<label class="control-label"><?php echo JText::_('LBL_ANSWER');?></label>
														<div class="controls">
															<select name="rule-answer" size="1">
																<?php foreach ($question->answers as $answer):?>
																<?php if($answer->answer_type == 'x'):?>
																<option value="<?php echo $answer->id?>"><?php echo $this->escape($answer->answer_label);?></option>
																<?php endif;?>
																<?php endforeach;?>
															</select>
														</div>
													</div>
													
													<?php if($question->question_type == 5 || $question->question_type == 6):?>
													<div class="control-group rule-column">
														<label class="control-label"><?php echo JText::_('LBL_COLUMN');?></label>
														<div class="controls">
															<select name="rule-column" size="1">
																<?php foreach ($question->answers as $answer):?>
																<?php if($answer->answer_type == 'y'):?>
																<option value="<?php echo $answer->id?>"><?php echo $this->escape($answer->answer_label);?></option>
																<?php endif;?>
																<?php endforeach;?>
															</select>
														</div>
													</div>
													<?php endif;?>
													
													<div class="control-group">
														<label class="control-label"><?php echo JText::_('LBL_WHAT_YOU_WANT_TO_DO');?></label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="rule-outcome" value="1"> <?php echo JText::_('LBL_SKIP_TO_PAGE');?>:
															</label>
															<select name="rule-page" size="1" class="margin-bottom-10">
																<?php foreach($this->item->pages as $num=>$page):?>
																<option value="<?php echo $page->id?>"><?php echo JText::sprintf('TXT_PAGE_AND_ID', $num + 1, $page->id)?></option>
																<?php endforeach;?>
															</select> 
															<label class="radio"><input type="radio" name="rule-outcome" value="2"> <?php echo JText::_('LBL_FINALIZE_SURVEY_RESPONSE');?></label>
														</div>
													</div>
												</div>
												
												<button class="btn-save-rule btn btn-primary margin-top-10" type="button"><?php echo JText::_('LBL_SAVE_RULE');?></button>
												
											</div>
											
										</div>
										<?php endif;?>
										
									</div>
									
									<hr/>
									<button type="button" class="btn btn-success btn-submit-form" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
										<i class="fa fa-ok fa fa-white"></i> <?php echo JText::_('LBL_SAVE_QUESTION');?>
									</button>
									<input type="hidden" name="qid" value="<?php echo $question->id;?>"/>
									<input type="hidden" name="qtype" value="<?php echo $question->question_type;?>"/>
								</form>
							</div>
						</div>
					</div>
					<?php endforeach;?>
					<?php endif;?>
					
				</div>
				
				<div id="message-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i> <?php echo JText::_('LBL_CLOSE');?></button>
					</div>
				</div>
				
				<div id="confirm-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="confirmModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
					</div>
					<div class="modal-body"><?php echo JText::_('MSG_CONFIRM')?></div>
					<div class="modal-footer">
						<button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i> <?php echo JText::_('LBL_CLOSE');?></button>
						<button class="btn btn-primary btn-confirm" aria-hidden="true"><i class="fa fa-thumbs-up fa fa-white"></i> <?php echo JText::_('LBL_CONFIRM');?></button>
					</div>
				</div>
				
				<div id="remove-page-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="confirmModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
					</div>
					<div class="modal-body"><?php echo JText::_('MSG_CONFIRM')?></div>
					<div class="modal-footer">
						<button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						<a class="btn btn-primary btn-confirm" href=""><i class="fa fa-remove fa fa-white"></i> <?php echo JText::_('LBL_CONFIRM');?></a>
					</div>
				</div>
				
				<div id="page-modal" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3><?php echo JText::_('LBL_MOVE_TO_PAGE');?></h3>
					</div>
					<div class="modal-body">
						<select name="pid" id="move-to-pageid" class="input-xlarge">
							<?php foreach($this->item->pages as $num=>$page):?>
							<option value="<?php echo $page->id;?>"<?php echo $this->pid == $page->id ? ' selected="selected"' : '';?>>
								<?php echo JText::sprintf('LBL_PAGE', ($num + 1)).(!empty($page->title) ? ': '.$this->escape($page->title) : '');?>
							</option>
							<?php endforeach;?>
						</select>
						<br><br><br><br><br><br><br><br><br><br>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						<button class="btn btn-primary btn-save-pid"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('LBL_CONTINUE');?></button>
					</div>
				</div>
				
				<div id="reorder-pages-modal" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3><?php echo JText::_('LBL_REORDER_PAGES');?></h3>
					</div>
					<div class="modal-body">
						<ul id="page-ordering-list" class="nav nav-pills nav-stacked">
							<?php foreach($this->item->pages as $num=>$page):?>
							<li id="pageid-<?php echo $page->id;?>" class="">
								<a href="#"><i class="fa fa-move"></i> <?php echo JText::sprintf('LBL_PAGE', ($num + 1)).(!empty($page->title) ? ': '.$this->escape($page->title) : '');?></a>
							</li>
							<?php endforeach;?>
						</ul>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						<button class="btn btn-primary btn-save-page-order"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('LBL_CONTINUE');?></button>
					</div>
				</div>
				
				<div id="page-rename-modal" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3><?php echo JText::_('LBL_RENAME_PAGE');?></h3>
					</div>
					<div class="modal-body">
						<p><?php echo JText::_('LBL_NAME_TITLE');?>:</p>
						<input type="text" name="page_title" value="">
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						<button class="btn btn-primary btn-save-page-title"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('LBL_CONTINUE');?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<form id="file-upload-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.upload_answer_image'.$itemid);?>" 
		enctype="multipart/form-data" method="post" style="position:absolute; top:-100px;">
		<input name="input-attachment" class="input-file-upload" type="file">
		<input name="id" value="<?php echo $this->item->id;?>" type="hidden">
	</form>
	
	<div style="display: none;">
		<?php echo CJFunctions::load_editor($editor, 'dummy-editor', 'dummy-editor', '', '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
		<input type="hidden" id="cjpageid" value="survey_form">
		<input type="hidden" id="page_id" value="<?php echo $this->pid;?>">
		<span id="url_delete_qn"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.delete_question&id='.$this->item->id.$itemid);?></span>
		<span id="url_update_ordering"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.update_ordering&id='.$this->item->id.$itemid);?></span>
		<span id="url_move_page"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.move_question&id='.$this->item->id.$itemid);?></span>
		<span id="url_save_rule"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.save_conditional_rule&id='.$this->item->id.$itemid);?></span>
		<span id="url_remove_rule"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.remove_conditional_rule&id='.$this->item->id.$itemid);?></span>
		<span id="url_rename_page"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.rename_page&id='.$this->item->id.$itemid);?></span>
		<span id="url_reorder_pages"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.reorder_pages&id='.$this->item->id.$itemid);?></span>
		
		<div id="tpl-question">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#survey-questions" href="#qn-qid">
					<!-- 
					<span class="pull-right tooltip-hover btn-sort-answer" title="<?php echo JText::_('LBL_DRAG_SORT')?>">
						<i class="fa fa-arrows qn-btn-drag"></i>
					</span>
					<span class="pull-right tooltip-hover btn-copy-question margin-right-10" title="<?php echo JText::_('LBL_COPY_QUESTION')?>">
						<i class="fa fa-file"></i>
					</span>
					<span class="pull-right tooltip-hover btn-move-question margin-right-10" title="<?php echo JText::_('LBL_MOVE_TO_PAGE')?>">
						<i class="fa fa-share"></i>
					</span>
					 -->
					<span class="pull-right tooltip-hover btn-delete-question margin-right-10" title="<?php echo JText::_('LBL_DELETE_QUESTION')?>">
						<i class="fa fa-trash-o"></i>
					</span>
					<i class="icon"></i>&nbsp;<span class="qn-title"><?php echo JText::_('TXT_LOADING');?></span>
				</a>
			</div>
			<div id="qn-qid0" class="accordion-body collapse">
				<div class="accordion-inner">
					<form class="question-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=question.save_question&id='.$this->item->id);?>" method="post">
						<input type="hidden" name="uniq_id" value="<?php echo $this->uniq_id; ?>" />	
						<input type="hidden" name="questionSelectedId" value="<?php echo $this->questionSelectedId; ?>"/>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#basicinfo-qid" data-toggle="tab"><?php echo JText::_('LBL_BASIC_INFORMATION');?></a></li>
							<li><a href="#answers-qid" data-toggle="tab"><?php echo JText::_('LBL_ANSWERS');?></a></li>
							<li><a href="#rules-qid" data-toggle="tab"><?php echo JText::_('LBL_CONDITIONAL_RULES');?></a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane active" id="basicinfo-qid">
								<div class="clearfix">
									<label><?php echo JText::_('LBL_QUESTION_TITLE');?><sup>*</sup>:</label>
									<input type="text" name="title" value="<?php echo JText::_('TXT_ENTER_QUESTION_TITLE');?>" class="input-title input-xlarge required">
								</div>
								
								<div class="clearfix">
									<label><?php echo JText::_('Question Text');?>:</label>
									<textarea rows="5" cols="40" name="description" id="desc-qid" style="width: 98%;"></textarea>
								</div>
								
								<div class="well well-small blk-options margin-top-20">
									
									<label class="checkbox">
										<input type="checkbox" name="mandatory" value="1"> <?php echo JText::_('LBL_MANDATORY')?>
									</label>
									
									<label class="checkbox blk-custom-answer">
										<input type="checkbox" name="custom_answer" value="1"> 
										<span><?php echo JText::_('LBL_CUSTOM_CHOICE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_CUSTOM_CHOICE');?>"></i></span>
									</label>
									
									<label class="checkbox blk-orientation">
										<input type="checkbox" name="orientation" value="1"> 
										<span><?php echo JText::_('LBL_DISPLAY_ANSWERS_IN_LINE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_ORIENTATION');?>"></i></span>
									</label>
									
									<div class="blk-selection-range">
										<br/>
										<label><?php echo JText::_('COM_COMMUNITYSURVEYS_MIN_SELECTABLE_ANSWERS');?>:</label>
										<select name="min_selections" size="1"  class="input-small">
											<option value="0"><?php echo JText::_('JALL');?></option>
											<option value="1">1</option>
										</select>
										<br/>
										<label><?php echo JText::_('COM_COMMUNITYSURVEYS_MAX_SELECTABLE_ANSWERS');?>:</label>
										<select name="max_selections" size="1"  class="input-small">
											<option value="0">0</option>
											<option value="1">1</option>
										</select>
									</div>
								</div>
								
							</div>
							
							<div class="tab-pane" id="answers-qid">
								<div class="file-upload-error hide alert alert-error">
									<i class="fa fa-warning-sign"></i> <span class="error-msg"></span>
								</div>

								<div class=" answers margin-bottom-20">
								
									<div class="margin-top-10 form-inline answer-item clearfix">
									
										<div class="span3 img-answer-wrapper">
											<div class="thumbnail">
												<img class="img-answer" src="<?php echo CQ_IMAGES_URI_2.'blank_image.png';?>" alt="">
												<p class="center">
													<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture-o"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
												</p>
												<p class="center">
													<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer span8">
												</p>
											</div>
											<input type="hidden" name="image-src" value="">
										</div>

										<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer input-xlarge input-answer-2">&nbsp;
										
										<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>
										<a href="#" onclick="return false;" class="btn-sort-answer tooltip-hover" title="<?php echo JText::_('LBL_DRAG_SORT');?>"><i class="fa fa-arrows"></i></a>
										
									</div>
									
									<div class="margin-top-10 margin-bottom-10">
										<button type="button" class="btn btn-primary btn-add-answer"><i class="fa fa-plus fa fa-white"></i> <?php echo JText::_('LBL_ADD_ANSWER');?></button>
									</div>
									
								</div>
								
								<div class="columns margin-bottom-20" id="columns-qid">
								
									<div class="margin-top-10 form-inline answer-item">
										<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_COLUMN');?>" class="required input-answer input-xlarge">&nbsp;
										<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>
										<a href="#" onclick="return false;" class="btn-sort-answer tooltip-hover" title="<?php echo JText::_('LBL_DRAG_SORT');?>"><i class="fa fa-arrows"></i></a>
									</div>
									
									<div class="margin-top-10 margin-bottom-10">
										<button type="button" class="btn btn-primary btn-add-column"><i class="fa fa-plus fa fa-white"></i> <?php echo JText::_('LBL_ADD_COLUMN');?></button>
									</div>
									
									<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo JText::_('TXT_COLUMN_FIELD_HELP');?></div>
									
								</div>
							</div>
						</div>
						
						<hr/>
						<button type="button" class="btn btn-success btn-submit-form" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
							<i class="fa fa-ok fa fa-white"></i> <?php echo JText::_('LBL_SAVE_QUESTION');?>
						</button>
						<input type="hidden" name="qid" value="0">
						<input type="hidden" name="qtype" value="0">
					</form>
				</div>
			</div>
		</div>
		
		<div id="tpl-new-answer">
									
			<div class="span3 img-answer-wrapper">
				<div class="thumbnail">
					<img class="img-answer" src="<?php echo S_MEDIA_URI.'images/blank_image.png';?>" alt="">
					<p class="center">
						<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture-o"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
					</p>
					<p class="center">
						<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer span8">
					</p>
				</div>
				<input type="hidden" name="image-src" value="">
			</div>
			
			<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer input-xlarge input-answer-2">&nbsp;
			
			<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>
			<a href="#" onclick="return false;" class="btn-sort-answer tooltip-hover" title="<?php echo JText::_('LBL_DRAG_SORT');?>"><i class="fa fa-arrows"></i></a>
		</div>
		
		<div id="tpl-rules-tab">
			<div class="tab-pane rules-wrapper" id="rules-qid">
				<table class="table table-striped table-hover rules-list">
					<thead>
						<tr>
							<th><?php echo JText::_('LBL_RULE_DESCRIPTION');?></th>
							<th><?php echo JText::_('LBL_DELETE');?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
				<h4 class="page-header margin-bottom-10"><?php echo JText::_('LBL_ADD_NEW_RULE');?></h4>
				<div class="conditional-rules-form">
					
					<div class="invalid-form-alert alert alert-error hide">
						<i class="fa fa-warning-sign"></i> <?php echo JText::_('MSG_SELECT_REQUIRED');?>
					</div>
					
					<label class="radio"><input type="radio" name="rule-name" value="1"> <?php echo JText::_('LBL_RULE_IF_ANSWERED');?></label>
					<label class="radio"><input type="radio" name="rule-name" value="2"> <?php echo JText::_('LBL_RULE_IF_NOT_ANSWERED');?></label>
					<label class="radio"><input type="radio" name="rule-name" value="3"> <?php echo JText::_('LBL_RULE_IF_SELECTED');?></label>
					<label class="radio"><input type="radio" name="rule-name" value="4"> <?php echo JText::_('LBL_RULE_IF_NOT_SELECTED');?></label>
					
					<div class="rule-criteria form-horizontal margin-top-20 margin-bottom-10 hide">
						<div class="control-group rule-answer">
							<label class="control-label"><?php echo JText::_('LBL_ANSWER');?></label>
							<div class="controls">
								<select name="rule-answer" size="1">
								</select>
							</div>
						</div>
						
						<div class="control-group rule-column">
							<label class="control-label"><?php echo JText::_('LBL_COLUMN');?></label>
							<div class="controls">
								<select name="rule-column" size="1">
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('LBL_WHAT_YOU_WANT_TO_DO');?></label>
							<div class="controls">
								<label class="radio">
									<input type="radio" name="rule-outcome" value="1"> <?php echo JText::_('LBL_SKIP_TO_PAGE');?>:
								</label>
								<select name="rule-page" size="1" class="margin-bottom-10">
									<?php foreach($this->item->pages as $num=>$page):?>
									<option value="<?php echo $page->id?>"><?php echo JText::sprintf('TXT_PAGE_AND_ID', $num + 1, $page->id)?></option>
									<?php endforeach;?>
								</select> 
								<label class="radio"><input type="radio" name="rule-outcome" value="2"> <?php echo JText::_('LBL_FINALIZE_SURVEY_RESPONSE');?></label>
							</div>
						</div>
					</div>
					<button class="btn-save-rule btn btn-primary margin-top-10" type="button"><?php echo JText::_('LBL_SAVE_RULE');?></button>
				</div>
			</div>
		</div>
		
	</div>
</div>
