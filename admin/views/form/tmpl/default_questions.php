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
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form'), 'theme'=>'none'));
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper_quiz.php';
?>
<style>
<!--
.submenu-box, div.m {background-color: #fff}
<?php if(APP_VERSION < 3):?>
body {font-size: 80%;}
<?php endif;?>
-->
</style>
<div id="cj-wrapper">	
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="fa fa-info-circle"></i> <?php echo JText::_('TXT_QUESTIONS_FORM_HELP');?>
	</div>

	<div class="container-fluid no-space-left no-space-right margin-top-10">
		<div class="row-fluid">
			
			<div class="span10">
				<!-- 
				<div class="quiz-toolbox">
					<ul class="nav nav-tabs nav-stacked">
						<li><a href="#" onclick="return false;" id="qntype-header"><i class="fa fa-magnet"></i> <?php echo JText::_('TXT_PAGE_HEADER');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-radio"><i class="fa fa-dot-circle-o"></i> <?php echo JText::_('TXT_CHOICE_RADIO');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-checkbox"><i class="fa fa-check-square-o"></i> <?php echo JText::_('TXT_CHOICE_CHECKBOX');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-select"><i class="fa fa-hand-o-up"></i> <?php echo JText::_('TXT_CHOICE_SELECT');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-grid-radio"><i class="fa fa-th-large"></i> <?php echo JText::_('TXT_GRID_RADIO');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-singleline"><i class="fa fa-minus"></i> <?php echo JText::_('TXT_FREETEXT_SINGLE_LINE');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-multiline"><i class="fa fa-align-justify"></i> <?php echo JText::_('TXT_FREETEXT_MULTI_LINE');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-password"><i class="fa fa-qrcode"></i> <?php echo JText::_('TXT_FREETEXT_PASSWORD');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-richtext"><i class="fa fa-file"></i> <?php echo JText::_('TXT_FREETEXT_RICHTEXT');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-image"><i class="fa fa-picture-o"></i> <?php echo JText::_('TXT_CHOOSE_IMAGE');?></a></li>
						<li><a href="#" onclick="return false;" id="qntype-images"><i class="fa fa-film"></i> <?php echo JText::_('TXT_CHOOSE_IMAGES');?></a></li>
					</ul>
				</div>
				 -->
				<div class="quiz-toolbox">
					<div data-spy="affix" data-offset-top="200">
						<div class="navbar navbar-static-top">
							<div class="navbar-inner">
			                	<a class="brand" href="#"><i class="fa fa-wrench"></i></a>
			                	
			                	<ul class="nav">
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_HEADER');?> <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="return false;" id="qntype-header"><i class="fa fa-magnet"></i> <?php echo JText::_('TXT_PAGE_HEADER');?></a></li>
										</ul>
									</li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_CHOICE');?> <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="return false;" id="qntype-radio"><i class="fa fa-dot-circle-o"></i> <?php echo JText::_('TXT_CHOICE_RADIO');?></a></li>
											<li><a href="#" onclick="return false;" id="qntype-checkbox"><i class="fa fa-check-square-o"></i> <?php echo JText::_('TXT_CHOICE_CHECKBOX');?></a></li>
											<li><a href="#" onclick="return false;" id="qntype-select"><i class="fa fa-hand-o-up"></i> <?php echo JText::_('TXT_CHOICE_SELECT');?></a></li>
										</ul>
									</li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_GRID');?> <b class="caret"></b></a>
										<ul class="dropdown-menu">
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
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('TXT_TYPE_IMAGE');?> <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="return false;" id="qntype-image"><i class="fa fa-picture-o"></i> <?php echo JText::_('TXT_CHOOSE_IMAGE');?></a></li>
											<li><a href="#" onclick="return false;" id="qntype-images"><i class="fa fa-film"></i> <?php echo JText::_('TXT_CHOOSE_IMAGES');?></a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br />
			<br />
			<br />			
			<div class="span10">		
				<div class="panel-container panel-group quiz-form" id="quiz-questions">
					<?php if(!empty($this->item->questions)):?>
					<?php foreach($this->item->questions as $question):?>
					<div class="panel panel-default question-item">
						<div class="panel-heading">
							<a data-parent="#quiz-questions" data-toggle="panel-collapse" href="#qn-<?php echo $question->id?>" onclick="return false;" style="display: block;">								
								<span class="pull-right tooltip-hover btn-delete-question margin-right-10" title="<?php echo JText::_('LBL_DELETE_QUESTION')?>">
									<i class="fa fa-trash-o"></i>
								</span>
								<i class="<?php echo QuizHelper::get_question_icon($question->question_type);?>"></i>&nbsp;<span class="qn-title"><?php echo $this->escape($question->title);?></span>
							</a>
						</div>
						<div id="qn-<?php echo $question->id?>" class="panel-body hide">
							<form class="question-form" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.save_question&id='.$this->item->id);?>" method="post">
								<input type="hidden" name="uniq_id" value="<?php echo JRequest::getVar('uniq_id'); ?>" />
								<input type="hidden" name="id" value="<?php echo JRequest::getVar('id');?>" >
								<input type="hidden" name="question_id" value="<?php echo JRequest::getVar('questionSelectedId'); ?>" />
								<input type="hidden" name="page_number" value="<?php echo JRequest::getVar('pageId'); ?>"/>	
								<?php if(in_array($question->question_type, array(2,3,4,5,6,11,12))):?>
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basicinfo-<?php echo $question->id;?>" data-toggle="tab"><?php echo JText::_('LBL_BASIC_INFORMATION');?></a></li>
									<li><a href="#answers-<?php echo $question->id;?>" data-toggle="tab"><?php echo JText::_('LBL_ANSWERS');?></a></li>
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
										
										<?php
										 if(in_array($question->question_type, array(2,3,4,5,6,11,12))):?>
										<div class="clearfix">
											<label><?php echo JText::_('LBL_ANSWER_EXPLANATION');?>:</label>
											<span class="help-block"><?php echo JText::_('Enter detailed explanation about the corret answer(s). This will not be shown while taking quiz rather shown while displaying results.');?></span>
											<?php echo CJFunctions::load_editor($editor, 'explanation-'.$question->id, 'explanation', $question->answer_explanation, '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
										</div>
										<?php endif;?>
										
										<?php if(!in_array($question->question_type, array(1))):?>
										<div class="well well-small">
											
											<label class="checkbox">
												<input type="checkbox" name="mandatory" value="1"<?php echo $question->mandatory == 1 ? ' checked="checked"' : ''?>> <?php echo JText::_('LBL_MANDATORY')?>
											</label>
											
											<?php if(in_array($question->question_type, array(2,3,4,5,6))):?>
											<label class="checkbox">
												<input type="checkbox" name="custom_answer" value="1"<?php echo $question->include_custom == 1 ? ' checked="checked"' : ''?>> 
												<span><?php echo JText::_('LBL_CUSTOM_CHOICE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_CUSTOM_CHOICE');?>"></i></span>
											</label>
											<?php endif;?>
											
											<?php if(in_array($question->question_type, array(2,3,11,12))):?>
											<label class="checkbox">
												<input type="checkbox" name="orientation" value="1"<?php echo $question->orientation != 'H' ? ' checked="checked"' : ''?>> 
												<span><?php echo JText::_('LBL_DISPLAY_ANSWERS_IN_LINE')?> <i class="fa fa-info-circle tooltip-hover" title="<?php echo JText::_('HLP_ORIENTATION');?>"></i></span>
											</label>
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
															<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
														</p>
														<p class="center">
															<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->title);?>" class="required input-answer span8">
														</p>
													</div>
													<input type="hidden" name="image-src" value="<?php echo $answer->image;?>">
												</div>
												<?php else:?>
												<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->title);?>" class="required input-answer input-xlarge">
												<?php endif;?>
												&nbsp;<input type="text" class="required input-mini center text-center input-marks" value="<?php echo $answer->marks;?>" name="marks-<?php echo $answer->id?>" 
													placeholder="<?php echo JText::_('LBL_MARKS');?>" title="<?php echo JText::_('LBL_MARKS');?>" data-trigger="tooltip">&nbsp;
												
												<?php if(in_array($question->question_type, array(5,6))):?>
												<select name="correct-answer[]" size="1" class="correct-answer">
													<option value=""><?php echo JText::_('-- Select Correct Answer --');?></option>
													<?php if(empty($question->answers)){ ?>
													<option value="0">New Column</option>
													<?php } ?>
													<?php foreach ($question->answers as $column):?>
													<?php if($column->answer_type == 'y'):?>
													<option value="<?php echo $column->id?>"<?php echo $answer->correct_answer == $column->id ? ' selected="selected"' : '';?>>
														<?php echo $this->escape($column->title);?>
													</option>
													<?php endif;?>
													<?php endforeach;?>
												</select>
												<?php else:?>
												<input type="<?php echo in_array($question->question_type, array(2,4,11)) ? 'radio' : 'checkbox'; ?>" name="correct-answer[]" class="checkbox correct-answer tooltip-hover"  
													value="<?php echo $answer->id?>" <?php echo $answer->correct_answer > 0 ? ' checked="checked"' : ''?> title="<?php echo JText::_('LBL_CORRECT_ANSWER');?>">
												<?php endif;?>
												
												<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>											
												
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
												<input type="text" name="answer-<?php echo $answer->id?>" value="<?php echo $this->escape($answer->title);?>" class="required input-answer input-xlarge">&nbsp;
												<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>												
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
						<p><?php echo JText::_('');?></p>
						<select size="1" name="pid">
							<?php foreach($this->item->pages as $page):?>
							<?php if($page->id != $this->item->pid):?>
							<option value="<?php echo $page->id;?>"><?php echo $page->sort_order . '('. $page->title . ')'; ?></option>
							<?php endif;?>
							<?php endforeach;?>
						</select>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						<button class="btn btn-primary btn-save-pid"><i class="fa fa-share fa fa-white"></i> <?php echo JText::_('LBL_CONTINUE');?></button>
					</div>
				</div>

			</div>
		</div>
	</div>
				
	<div style="display: none;">
		<?php echo CJFunctions::load_editor($editor, 'dummy-editor', 'description', '', '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
		<input type="hidden" id="cjpageid" value="quiz_form">
		<input type="hidden" id="page_id" value="<?php echo $this->pid;?>">
		<span id="url_delete_qn"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.delete_question');?></span>
		<span id="url_update_ordering"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=update_order&id='.$this->item->id);?></span>
		<span id="url_move_page"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.move_question&id='.$this->item->id);?></span>
		
		<form id="file-upload-form" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.upload_answer_image');?>" enctype="multipart/form-data" method="post">
			<input name="input-attachment" class="input-file-upload" type="file">
		</form>
		
		<div id="tpl-question">
			<div class="panel panel-default question-item">
				<div class="panel-heading">
					<a data-toggle="panel-collapse" data-parent="#quiz-questions" href="#qn-qid">
						<span class="pull-right tooltip-hover btn-delete-question margin-right-10" title="<?php echo JText::_('LBL_DELETE_QUESTION')?>">
							<i class="fa fa-trash-o"></i>
						</span>
						<i class="icon"></i>&nbsp;<span class="qn-title"><?php echo JText::_('TXT_LOADING');?></span>
					</a>
				</div>
				<div id="qn-qid0" class="panel-body hide">
					<form class="question-form" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.save_question&id='.$this->item->id);?>" method="post">
						<input type="hidden" name="uniq_id" value="<?php echo JRequest::getVar('uniq_id'); ?>" />
						<input type="hidden" name="id" value="<?php echo JRequest::getVar('id');?>" >
						<input type="hidden" name="question_id" value="<?php echo JRequest::getVar('questionSelectedId'); ?>" />
						<input type="hidden" name="page_number" value="<?php echo JRequest::getVar('pageId'); ?>"/>	
						<ul class="nav nav-tabs">
							<li class="active"><a href="#basicinfo-qid" data-toggle="tab"><?php echo JText::_('LBL_BASIC_INFORMATION');?></a></li>
							<li><a href="#answers-qid" data-toggle="tab"><?php echo JText::_('LBL_ANSWERS');?></a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane active" id="basicinfo-qid">
								<div class="clearfix">
									<label><?php echo JText::_('LBL_QUESTION_TITLE');?><sup>*</sup>:</label>
									<input type="text" name="title" value="<?php echo JText::_('TXT_ENTER_QUESTION_TITLE');?>" class="input-title input-xlarge required">
								</div>
								
								<div class="clearfix">
									<label><?php echo JText::_('Question Text');?>:</label>
									<textarea rows="5" cols="40" name="description" id="desc-qid" style="width: 99%;"></textarea>
								</div>
								
								<div class="clearfix blk-explanation">
									<label><?php echo JText::_('LBL_ANSWER_EXPLANATION');?>:</label>
									<span class="help-block"><?php echo JText::_('Enter detailed explanation about the corret answer(s). This will not be shown while taking quiz rather shown while displaying results.');?></span>
									<textarea rows="5" cols="40" name="explanation" id="explanation-qid" style="width: 99%;"></textarea>
								</div>
								
								<div class="well well-small blk-options">
									
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
													<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
												</p>
												<p class="center">
													<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer span8">
												</p>
											</div>
											<input type="hidden" name="image-src" value="">
										</div>

										<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer input-xlarge input-answer-2">&nbsp;
										<input type="text" class="required input-mini center text-center input-marks" value="0" name="marks-0" 
												placeholder="<?php echo JText::_('LBL_MARKS');?>" title="<?php echo JText::_('LBL_MARKS');?>" data-trigger="tooltip">&nbsp;
													
										<select name="correct-answer[]" size="1" class="correct-answer correct-answer-select">
											<option value=""><?php echo JText::_('-- Select Correct Answer --');?></option>
										</select>
										
										<input type="radio" name="correct-answer[]" class="checkbox correct-answer tooltip-hover correct-answer-radio" value="1" title="<?php echo JText::_('LBL_CORRECT_ANSWER');?>">
										<input type="checkbox" name="correct-answer[]" class="checkbox correct-answer tooltip-hover correct-answer-checkbox" value="1" title="<?php echo JText::_('LBL_CORRECT_ANSWER');?>">
										
										<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>										
										
									</div>
									
									<div class="margin-top-10 margin-bottom-10">
										<button type="button" class="btn btn-primary btn-add-answer"><i class="fa fa-plus fa fa-white"></i> <?php echo JText::_('LBL_ADD_ANSWER');?></button>
									</div>
									
								</div>
								
								<div class="columns margin-bottom-20" id="columns-qid">
								
									<div class="margin-top-10 form-inline answer-item">
										<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_COLUMN');?>" class="required input-answer input-xlarge">&nbsp;
										<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>										
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
					<img class="img-answer" src="<?php echo CQ_IMAGES_URI_2.'blank_image.png';?>" alt="">
					<p class="center">
						<button type="button" class="btn-change-image btn margin-top-10"><i class="fa fa-picture"></i> <?php echo JText::_('LBL_CHANGE_IMAGE');?></button>
					</p>
					<p class="center">
						<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer span8">
					</p>
				</div>
				<input type="hidden" name="image-src" value="">
			</div>
			
			<input type="text" name="answer-aid" value="<?php echo JText::_('TXT_NEW_ANSWER');?>" class="required input-answer input-xlarge input-answer-2">&nbsp;
			<input type="text" class="required input-mini center text-center input-marks" value="0" name="marks-0" 
				placeholder="<?php echo JText::_('LBL_MARKS');?>" title="<?php echo JText::_('LBL_MARKS');?>" data-trigger="tooltip">&nbsp;
			
			<select name="correct-answer[]" size="1" class="correct-answer correct-answer-select">
				<option value=""><?php echo JText::_('-- Select Correct Answer --');?></option>
			</select>
			
			<input type="radio" name="correct-answer[]" class="checkbox correct-answer tooltip-hover correct-answer-radio" value="1" title="<?php echo JText::_('LBL_CORRECT_ANSWER');?>">
			<input type="checkbox" name="correct-answer[]" class="checkbox correct-answer tooltip-hover correct-answer-checkbox" value="1" title="<?php echo JText::_('LBL_CORRECT_ANSWER');?>">
			
			<a class="btn-delete-answer tooltip-hover" href="#" onclick="return false;" title="<?php echo JText::_('LBL_DELETE_ANSWER');?>"><i class="fa fa-trash-o"></i></a>			
		</div>
		
	</div>
</div>