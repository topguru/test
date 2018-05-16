<?php 
/**
 * @version		$Id: default_reports.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 6;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

?>

<div id="cj-wrapper">	
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>

				<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
				<div class="quiz-description"><?php echo CJFunctions::process_html($this->item->description, $bbcode, $content)?></div>
				
				<div class="tags margin-bottom-20">
					<?php foreach($this->item->tags as $tag):?>		
					<a title="<?php echo JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text)).' - '.$this->escape($tag->description);?>" class="tooltip-hover" 
						href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=tag&id='.$tag->tag_id.':'.$tag->alias.$itemid);?>">
						<span class="label"><?php echo $this->escape($tag->tag_text);?></span>
					</a>
					<?php endforeach;?>
				</div>
				
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_COMPLETED_RESPONSES');?></td>
						<td width="15%"><?php echo $this->item->stats->completed;?></td>
						<td width="30%">
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_consolidated_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_CONSOLIDATE_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_RESPONSES');?></td>
						<td><?php echo $this->item->stats->responses;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_responses_list&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_ALL_RESPONSES');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_COUNTRIES_PARTICIPATED');?></td>
						<td><?php echo $this->item->stats->countries;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_location_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_COUNTRY_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_BROWSERS_USED');?></td>
						<td><?php echo $this->item->stats->browsers;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_device_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_BROWSER_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_OS_USED');?></td>
						<td><?php echo $this->item->stats->oses;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_os_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_OS_REPORT');?>
							</a>
						</td>
					</tr>
				</table>
				
				<h3 class="page-header no-margin-bottom"><?php echo JText::_('LBL_TOP_SCORERS');?></h3>
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo JText::_('LBL_USERNAME');?></th>
							<th><?php echo JText::_('LBL_COUNTRY');?></th>
							<th><?php echo JText::_('LBL_START_TIME');?></th>
							<th><?php echo JText::_('LBL_TIME_TAKEN')?></th>
							<th><?php echo JText::_('LBL_SCORE');?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->stats->topscorers as $i=>$topper):?>
						<tr>
							<td><?php echo $i + 1;?></td>
							<td><?php echo $topper->created_by > 0 ? $this->escape($topper->username) : JText::_('LBL_GUEST');?></td>
							<td><?php echo $this->escape($topper->country_name);?></td>
							<td><?php echo $topper->created;?></td>
							<td><?php echo CJFunctions::get_date_difference($topper->created, $topper->finished);?></td>
							<td><?php echo $topper->score;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>		
</div>