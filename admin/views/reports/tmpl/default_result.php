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

$page_id = 8;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? true : false;
$bbcode =  $this->params->get('default_editor', 'bbcode') == 'bbcode';
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT.DS.'helpers'.DS.'qnresults.php';
$generator = new QuizQuestionResults($wysiwyg, $bbcode, $content);
?>

<div id="cj-wrapper" class="container-fluid no-space-left no-space-right">
	
	<div>
		<?php if(!$this->print):?>
		<div class="well">
			<a class="btn pull-right" 
				onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
				href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=results&id='.$this->item->id.':'.$this->item->alias.'&rid='.$this->response_id.'&tmpl=component&print=1'.$itemid);?>">
				<i class="icon-print"></i> <?php echo JText::_('JGLOBAL_PRINT');?>
			</a>
		</div>
		<?php else:?>
		<script type="text/javascript">window.print();</script>
		<?php endif;?>
		
		<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
		<div class="quiz-description"><?php echo CJFunctions::process_html($this->item->description, $bbcode, $content)?></div>
		
		<div class="well well-transperant">
			<h4 class="page-header no-margin-top margin-bottom-10"><?php echo JText::_('LBL_LEGEND');?></h4>
			<span class="margin-right-20"><i class="icon-hand-up"></i> <?php echo JText::_('LBL_YOUR_ANSWER');?></span>
			<span class="margin-right-20"><i class="icon-check"></i> <?php echo JText::_('LBL_CORRECT_ANSWER');?></span>
			<span class="margin-right-20"><i class="icon-ok"></i> <?php echo JText::_('LBL_SELECTED_CORRECT');?></span>
			<span class="margin-right-20"><i class="icon-remove"></i> <?php echo JText::_('LBL_SELECTED_WRONG');?></span>
			<span class="margin-right-20"><i class="icon-thumbs-down"></i> <?php echo JText::_('LBL_NOT_SELECTED_CORRECT');?></span>
		</div>
		
		<div class="results-wrapper">
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
				case 11:
				case 12:
					echo $generator->get_image_question($item, $class, CQ_IMAGES_URI);
					break;
			}
		}
		?>
		</div>
		
		<div class="table-responsive" id="quiz-score">
			<table class="table table-striped table-hover table-bordered table-condensed">
				<thead>
					<tr>
						<th colspan="2"><h3><?php echo JText::_('COM_COMMUNITYQUIZ_YOUR_FINAL_REPORT');?></h3></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYQUIZ_TOTAL_MARKS')?></th>
						<td><label class="label label-info"><?php echo $generator->get_total();?></label></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYQUIZ_MARKS_SECURED')?></th>
						<td><label class="label label-success"><?php echo $generator->get_score();?></label></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYQUIZ_PERCENTAGE_SECURED')?></th>
						<td><label class="label label-success"><?php echo $generator->get_percentage().' %';?></label></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYQUIZ_CORRECT_QUESTIONS');?></th>
						<td><label class="label label-info"><?php echo $generator->get_count();?></label></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYQUIZ_SUCCESS_RATIO')?></th>
						<td><label class="label label-info"><?php echo $generator->get_success_ratio().' %';?></label></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="well center margin-top-20">
			<?php if($this->item->multiple_responses == 1):?>
			<a class="btn" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=respond&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
				<?php echo JText::_('LBL_TAKE_AGAIN');?>
			</a>
			<?php endif;?>
			<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid)?>">
				<?php echo JText::_('LBL_HOME');?>
			</a>
		</div>
	</div>
</div>