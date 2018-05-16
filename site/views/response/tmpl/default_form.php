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
$document = JFactory::getDocument();
$itemid = CJFunctions::get_active_menu_id();

$page_id = $this->params->get('hide_toolbar_responses', 0) == 1 ? -1 : 1;
CJFunctions::load_jquery(array('libs'=>array('validate', 'rating')));
$document->addStyleSheet(CQ_MEDIA_URI_2.'css/jquery.countdown.css');
$document->addScript(CQ_MEDIA_URI_2.'js/jquery.countdown.min.js');

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME);
$bbcode = $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT.DS.'helpers'.DS.'formfields.php';
$formfields = new QuizFormFields($wysiwyg, $bbcode, $content);

$created = JFactory::getDate($this->item->response_created);
$now = JFactory::getDate();
$timeleft = ($this->item->duration * 60) - ($now->toUnix() - $created->toUnix());
?>
<div id="cj-wrapper" class="container-fluid no-space-left no-space-right">

	<div class="<?php echo $this->hide_template == 1 ? 'full-screen' : 'row-fluid';?>">
		
		<table width="100%">
			<tr>
				<td width="10%" valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
				</td>
				<td valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
		
					<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
					
					<div class="well clearfix">
						<div class="row-fluid">
							<div class="span4">
								<div class="margin-bottom-20"><?php echo JText::sprintf('LBL_PAGE_NO', $this->item->current, count($this->item->pages))?></div>
								<div class="progress no-margin-bottom"><div class="bar" style="width: <?php echo count($this->item->pages) > 0 ? round($this->item->pageno * 100 / count($this->item->pages)) : 0;?>%"></div></div>
							</div>
							<?php if($this->item->duration > 0):?>
							<div class="span8">
								<div class="quiz-countdown pull-right">
									<div class="quiz-timer-label"><?php echo JText::_('LBL_TIME_LEFT');?>:</div>
									<div class="quiz-timer margin-top-10"></div>
								</div>
							</div>
							<?php endif;?>
						</div>
					</div>
					
						<form id="response-form" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.save_response&id='.$this->item->id)?>" method="post">
						<input type="hidden" name="rid" value="<?php echo $this->item->response_id;?>">
						
						<?php 
						$class = '';
						if (!empty($this->item->questions)) {
						foreach ($this->item->questions as $question){
							switch($question->question_type){
								case 1:
									echo $formfields->get_page_header_question($question, $class);
									break;
								case 2:
									echo $formfields->get_radio_question($question, $class);
									break;
								case 3:
									echo $formfields->get_checkbox_question($question, $class);
									break;
								case 4:
									echo $formfields->get_select_question($question, $class);
									break;
								case 5:
									echo $formfields->get_grid_radio_question($question, $class);
									break;
								case 6:
									echo $formfields->get_grid_checkbox_question($question, $class);
									break;
								case 7:
									echo $formfields->get_single_line_textbox_question($question, $class);
									break;
								case 8:
									echo $formfields->get_multiline_textarea_question($question, $class);
									break;
								case 9:
									echo $formfields->get_password_textbox_question($question, $class);
									break;
								case 10:
									echo $formfields->get_rich_textbox_question($question, $class);
									break;
								case 11:
									echo $formfields->get_image_radio_question($question, $class, CQ_IMAGES_URI_2);
									break;
								case 12:
									echo $formfields->get_image_checkbox_question($question, $class, CQ_IMAGES_URI_2);
									break;
								default: break;
							}
						}}
						?>
						
						<div class="alert alert-error hide" id="txt_errors_alert"><i class="icon-warning-sign"></i> <?php echo JText::_('TXT_RESPONSE_CONTAINS_ERRORS');?></div>
						
						<div class="well center">
							<?php if($this->item->finalize && $this->params->get('enable_ratings', 1) == 1):?>
							<div class="center margin-bottom-20">
								<p><?php echo JText::_('TXT_RATING_HELP');?></p>
								<span id="cj-star-rating" data-rating-score="<?php echo empty($item->rating) ? '0' : $item->rating;?>" style="width: auto;"></span>
							</div>
							<?php endif;?>
				
							<div class="form-inline">
								<a class="btn" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid)?>"><?php echo JText::_('LBL_CANCEL');?></a>
								
								<?php if(!$this->item->start):?>
								<button class="btn" type="button" id="btn-previous" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
									<i class="icon icon-hand-left"></i> <?php echo JText::_('LBL_PREVIOUS');?>
								</button>
								<?php endif;?>
								
								<button class="btn btn-primary" type="button" id="btn_submit" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
									<i class="icon icon-hand-right icon-white"></i> <?php echo $this->item->finalize ? JText::_('LBL_FINISH') : JText::_('LBL_NEXT');?>
								</button>
							</div>
						</div>
						
						<input name="rid" type="hidden" value="<?php echo $this->item->response_id;?>">
						<input name="pid" type="hidden" value="<?php echo $this->item->pid;?>">
						<input name="current" type="hidden" value="<?php echo $this->item->current;?>">
						<input name="finalize" type="hidden" value="<?php echo $this->item->finalize;?>">
						<input name="pageno" type="hidden" value="<?php echo $this->item->pageno;?>">
						<input name="quiz-rating" type="hidden" value="0">
					</form>
				</td>
			</tr>
		</table>
				
	</div>
	
	<input type="hidden" id="cjpageid" value="quiz_response">
	<div style="display:none;">
		<div id="time_left"><?php echo $timeleft;?></div>
		<span id="default_error_required"><?php echo JText::_('MSG_QUESTION_MANDATORY')?></span>
		<span id="url_previous_page"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.previous_page&id='.$this->item->id)?></span>
		<div id="data-rating-noratemsg"><?php echo JText::_('LBL_RATING_NORATE_HINT');?></div>
		<div id="data-rating-cancelhint"><?php echo JText::_('LBL_RATING_CANCEL_HINT');?></div>
		<div id="data-rating-hints"><?php echo JText::_('LBL_RATING_HINTS');?></div>
		<div id="date_labels1"><?php echo JText::_('LBL_DATE_LABELS1')?></div>
		<div id="date_labels2"><?php echo JText::_('LBL_DATE_LABELS2')?></div>
	</div>
</div>