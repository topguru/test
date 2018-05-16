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
$wysiwyg = true;
$bbcode = $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/formfieldss.php';
$formfields = new QuizFormFields($wysiwyg, $bbcode, $content);
?>

<div id="cj-wrapper">
	
	<h2 class="page-header"><?php echo $this->escape($this->quiz->title);?></h2>
	
	<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=approval')?>" method="post">
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
		<?php 
		$class = '';
		foreach ($this->quiz->questions as $qid=>$question){
			$question->responses = array();			
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
		}
		?>
		
		<input type="hidden" name="cid[]" value="<?php echo $this->quiz->id;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="1" />
	</form>
</div>