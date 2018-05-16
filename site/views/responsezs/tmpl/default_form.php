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

require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/formfields.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';

$user = JFactory::getUser();
$document = JFactory::getDocument();
$itemid = CJFunctions::get_active_menu_id();

$page_id = $this->params->get('hide_toolbar_responses', 0) == 1 ? -1 : 8;
CJFunctions::load_jquery(array('libs'=>array('validate')));

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME);
$bbcode = $this->params->get('default_editor', 'bbcode') == 'bbcode' ? true : false;
$content = $this->params->get('process_content_plugins', 0) == 1;


$formfields = new SurveyFormFields($wysiwyg, $bbcode, $content);

?>

<div id="cj-wrapper">
	<div <?php echo $this->hide_template == 1 ? ' class="full-screen"' : '';?>>		
		<table width="100%">
			<tr>
				<td width="10%" valign="top">
					<?php include_once JPATH_COMPONENT.'/'.'helpers'.'/'.'main_header.php';?>
				</td>
				<td valign="top">
					<?php include_once JPATH_COMPONENT.'/'.'helpers'.'/'.'headersz.php';?>
		
					<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
					<div class="custom-pageheader"><?php echo $this->item->custom_header; ?></div>
					
					<?php if($this->item->display_progress == 1):?>
					<div class="row-fluid margin-bottom-20 margin-top-20">
						<div class="span4 pull-center">
							<div class="margin-bottom-10" style="text-align: right;"><?php echo JText::sprintf('LBL_PAGE_NO', $this->item->pageno, count($this->item->pages))?></div>
							<div class="progress progress-info no-margin-bottom<?php echo ($this->item->current == count($this->item->pages)) ? '' : ' progress-striped active';?>">
								<div class="bar" style="width: <?php echo count($this->item->pages) > 0 ? round($this->item->current * 100 / count($this->item->pages)) : 0;?>%"></div>
							</div>
						</div>
					</div>
					<?php endif;?>
					
					<form id="response-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.save_response&id='.$this->item->id)?>" method="post">
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
								case 13:
									echo $formfields->get_user_name_question($question, $class);
									break;
								case 14:
									echo $formfields->get_user_email_question($question, $class);
									break;
								case 15:
									echo $formfields->get_calendar_question($question, $class);
									break;
								case 16: 
									echo $formfields->get_address_question($question, $class);
									break;
								default: break;
							}
							}
						}
						?>
						<div class="alert alert-error hide" id="txt_errors_alert"><i class="icon-warning-sign"></i> <?php echo JText::_('TXT_RESPONSE_CONTAINS_ERRORS');?></div>
						
						<div class="well center">
							<div class="form-inline">
								<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_latest_surveys'.$itemid)?>"><?php echo JText::_('LBL_CANCEL');?></a>
								
								<?php if(!$this->item->start && $this->item->backward_navigation == '1'):?>
								<button class="btn" type="button" id="btn-previous" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
									<i class="icon icon-hand-left"></i> <?php echo JText::_('LBL_PREVIOUS');?>
								</button>
								<?php endif;?>
								
								<button class="btn btn-primary" type="button" id="btn_submit" data-loading-text="<?php echo JText::_('TXT_LOADING');?>">
									<i class="icon icon-hand-right icon-white"></i> <?php echo $this->item->finalize ? JText::_('LBL_FINISH') : JText::_('LBL_NEXT');?>
								</button>
							</div>
						</div>
						
						<input name="id" type="hidden" value="<?php echo $this->item->id;?>">
						<input name="rid" type="hidden" value="<?php echo $this->item->response_id;?>">
						<input name="pid" type="hidden" value="<?php echo $this->item->pid;?>">
						<input name="key" type="hidden" value="<?php echo !empty($this->item->key) ? $this->item->key : '';?>">
						<input name="current" type="hidden" value="<?php echo $this->item->current;?>">
						<input name="finalize" type="hidden" value="<?php echo $this->item->finalize;?>">
						<input name="pageno" type="hidden" value="<?php echo $this->item->pageno;?>">
					</form>
					
					<input type="hidden" id="cjpageid" value="survey_response">
					<div class="hide" style="display: none">
						<span id="default_error_required"><?php echo JText::_('MSG_QUESTION_MANDATORY')?></span>
						<span id="msg_validation_min_answers_required"><?php echo JText::_('COM_COMMUNITYSURVEYS_MIN_ANSWERS_REQUIRED');?></span>
						<span id="msg_validation_max_answers_required"><?php echo JText::_('COM_COMMUNITYSURVEYS_MAX_ANSWERS_REQUIRED');?></span>
						<span id="url_previous_page"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.previous_page&id='.$this->item->id)?></span>
					</div>
				</td>
			</tr>
		</table>
		
	</div>
</div>