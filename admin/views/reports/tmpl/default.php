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
	
	<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	<div class="quiz-description"><?php echo CJFunctions::process_html($this->item->description, $bbcode, $content)?></div>
	
	<table class="table table-bordered table-striped table-hover">
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COMPLETED_RESPONSES');?></td>
			<td width="15%"><?php echo $this->item->stats->completed;?></td>
			<td width="30%">
				<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_consolidated_report&package_id='.JRequest::getVar("package_id").'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_CONSOLIDATE_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_RESPONSES');?></td>
			<td><?php echo $this->item->stats->responses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_responses_list&package_id='.JRequest::getVar("package_id").'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_ALL_RESPONSES');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COUNTRIES_PARTICIPATED');?></td>
			<td><?php echo $this->item->stats->countries;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_location_report&package_id='.JRequest::getVar("package_id").'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_COUNTRY_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_BROWSERS_USED');?></td>
			<td><?php echo $this->item->stats->browsers;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_device_report&package_id='.JRequest::getVar("package_id").'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_BROWSER_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_OS_USED');?></td>
			<td><?php echo $this->item->stats->oses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_os_report&package_id='.JRequest::getVar("package_id").'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_OS_REPORT');?>
				</a>
			</td>
		</tr>
	</table>
	
	<h3 class="page-header no-margin-bottom"><?php echo JText::_('Top Scorers');?></h3>
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('LBL_USERNAME');?></th>
				<th><?php echo JText::_('LBL_COUNTRY');?></th>
				<th><?php echo JText::_('Start Time');?></th>
				<th><?php echo JText::_('Time Taken')?></th>
				<th><?php echo JText::_('Score');?></th>
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
</div>