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

$page_id = 7;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_ROOT.DS.'components'.DS.S_APP_NAME.DS.'helpers'.DS.'qnresults.php';
$generator = new SurveyQuestionResults($wysiwyg, $bbcode, $content);
?>

<div id="cj-wrapper">
	
	<?php if(!$this->print):?>
	<div class="well">
		<a class="btn pull-right" 
			onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
			href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$this->response_id.'&tmpl=component&print=1'.$itemid);?>">
			<i class="icon-print"></i> <?php echo JText::_('COM_COMMUNITYSURVEYS_PRINT');?>
		</a>
	</div>
	<?php else:?>
	<script type="text/javascript">window.print();</script>
	<?php endif;?>
	
	<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	<div class="survey-description"><?php echo CJFunctions::process_html($this->item->introtext, $bbcode, $content)?></div>
	
	<div class="results-wrapper margin-top-20">
	<?php 
	$class = '';
	foreach($this->item->questions as $item){
		switch ($item->question_type){
			case 1:
				echo $generator->get_page_header_question($item, $class);
				break;
			case 2:
			case 3:
			case 4:
				echo $generator->get_choice_question($item, $class);
				break;
			case 5:
			case 6:
				echo $generator->get_grid_question($item, $class);
				break;
			case 7:
			case 8:
			case 9:
				echo $generator->get_text_question($item, $class);
				break;
			case 10:
				echo $generator->get_text_question($item, $class, false);
				break;
			case 11:
			case 12:
				echo $generator->get_image_question($item, $class, S_IMAGES_URI);
				break;
			case 13: // name
				echo $generator->get_user_name_question($item, $class);
				break;
			case 14: // email
				echo $generator->get_email_question($item, $class);
				break;
			case 15: // calendar
				echo $generator->get_calendar_question($item, $class);
				break;
			case 16: // address
				echo $generator->get_address_question($item, $class);
				break;				
		}
	}
	?>
	</div>
	
	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid)?>" method="post">
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
		<input type="hidden" name="task" value="responses">
	</form>
</div>