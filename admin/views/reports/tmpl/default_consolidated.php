<?php 
/**
 * @version		$Id: default_consolidated.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
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

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'reports.php';
$generator = new QuizReports($wysiwyg, $bbcode, $content);

$doc = JFactory::getDocument();
$doc->addScript('https://www.google.com/jsapi');
$doc->addScriptDeclaration('google.load("visualization", "1", {packages:["corechart"]}); google.setOnLoadCallback(QuizFactory.draw_consolidated_charts);');
?>

<div id="cj-wrapper" class="container-fuild no-space-left no-space-right">
	
	<?php if(!$this->print):?>
	<div class="well">
		<a class="btn pull-right" 
			onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
			href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=consolidated&id='.$this->item->id.':'.$this->item->alias.'&tmpl=component&print=1'.$itemid);?>">
			<i class="icon-print"></i> <?php echo JText::_('JGLOBAL_PRINT');?>
		</a>
		<a class="btn" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&id='.$this->item->id.':'.$this->item->alias.$itemid);?>">
			<i class="icon-home"></i> <?php echo JText::_('LBL_REPORTS');?>
		</a>
	</div>
	<?php else:?>
	<script type="text/javascript">window.print();</script>
	<?php endif;?>

	<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	<div class="quiz-description"><?php echo CJFunctions::process_html($this->item->description, $bbcode, $content)?></div>
	
	<?php if(!$this->print):?>
	<div class="tags margin-bottom-20">
		<?php foreach($this->item->tags as $tag):?>
		<a title="<?php echo JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text)).' - '.$this->escape($tag->description);?>" class="tooltip-hover" 
			href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=tag&id='.$tag->tag_id.':'.$tag->alias.$itemid);?>">
			<span class="label"><?php echo $this->escape($tag->tag_text);?></span>
		</a>
		<?php endforeach;?>
	</div>
	<?php endif;?>
	
	<div class="reports-wrapper">
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
			case 11:
			case 12:
				echo $generator->get_choice_question($item, $class);
				break;
			case 5:
			case 6:
				echo $generator->get_grid_question($item, $class);
				break;
		}
	}
	?>
	</div>

</div>