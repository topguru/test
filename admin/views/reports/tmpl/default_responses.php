<?php 
/**
 * @version		$Id: default_responses.php 01 2012-04-30 11:37:09Z maverick $
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
<script type="text/javascript">
<!--
if(!Joomla) var Joomla = {};
Joomla.tableOrdering = function(order, order_dir, temp){
	document.adminForm.filter_order.value = order;
	document.adminForm.filter_order_Dir.value = order_dir;
	document.adminForm.submit();
};
Joomla.checkAll = function(global){
	jQuery('#adminForm').find('table').find('input[type="checkbox"]').attr('checked', global.checked);
};
//-->
</script>
<div id="cj-wrapper">
	
	<h2 class="page-header margin-bottom-10"><?php echo JText::_('LBL_RESPONSES').': '.$this->escape($this->item->title);?></h2>

	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid)?>" method="post">
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
		<div class="form-inline margin-bottom-20 well">
			<div class="pull-right">
				<select name="limit" size="1" onchange="document.adminForm.submit();" class="input-mini margin-bottom-10">
					<option value="5"<?php echo $this->lists['limit'] == 5 ? ' selected="selected"' : '';?>>5</option>
					<option value="10"<?php echo $this->lists['limit'] == 10 ? ' selected="selected"' : '';?>>10</option>
					<option value="20"<?php echo $this->lists['limit'] == 20 ? ' selected="selected"' : '';?>>20</option>
					<option value="30"<?php echo $this->lists['limit'] == 30 ? ' selected="selected"' : '';?>>30</option>
					<option value="50"<?php echo $this->lists['limit'] == 50 ? ' selected="selected"' : '';?>>50</option>
					<option value="100"<?php echo $this->lists['limit'] == 100 ? ' selected="selected"' : '';?>>100</option>
				</select>
				<select name="state" size="1" onchange="document.adminForm.submit();" class="input-medium margin-bottom-10">
					<option value="3"<?php echo $this->lists['state'] == 3 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_FILTER');?></option>
					<option value="1"<?php echo $this->lists['state'] == 1 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_COMPLETED');?></option>
					<option value="0"<?php echo $this->lists['state'] == 0 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_PENDING');?></option>
				</select>
				<input
					onchange="this.adminForm.submit();" 
					type="text" name="search" value="<?php echo $this->lists['search'];?>" 
					placeholder="<?php echo JText::_('LBL_SEARCH');?>" class="input-medium margin-bottom-10">
			</div>
			<a class="btn margin-bottom-10" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_reports&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id)?>">
				<i class="fa fa-reply"></i> <?php echo JText::_('LBL_REPORTS');?>
			</a>
			<a class="btn margin-bottom-10" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_csv_report&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>" target="_blank">
				<i class="fa fa-download"></i> <?php echo JText::_('Export To CSV');?>
			</a>
			<button class="btn btn-delete-responses margin-bottom-10" type="button"><i class="fa fa-times"></i> <?php echo JText::_('LBL_DELETE_SELECTED');?></button>
		</div>
		
		<div class="alert alert-error alert-no-selection hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_ROWS_TO_CONTINUE');?></div>
		
		<table class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th width="20"><?php echo JText::_( '#' ); ?></th>
					<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_USERNAME' ), 'username', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="15%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_COUNTRY' ), 'cr.country_name', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'Start Time' ), 'r.created', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'End Time' ), 'r.finished', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="15%"><?php echo JHTML::_( 'grid.sort', JText::_( 'Time Taken' ), 'time_taken', $this->lists['order_dir'], $this->lists['order']);?></th>
					<th width="5%"><?php echo JHTML::_( 'grid.sort', JText::_( 'Score' ), 'r.score', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="5%"><?php echo JHTML::_( 'grid.sort', JText::_( 'Status' ), 'r.completed', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="5%"><?php echo JText::_('Report');?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->responses as $i=>$row):?>
				<tr>
					<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
					<td><?php echo JHTML::_( 'grid.id', $i, $row->response_id );?></td>
					<td><?php echo $this->escape($row->username);?></td>
					<td><?php echo $this->escape($row->country_name);?></td>
					<td class="hidden-phone"><?php echo $row->responded_on;?></td>
					<td class="hidden-phone"><?php echo $row->finished?></td>
					<td><?php echo $row->completed == 1 ? CJFunctions::get_date_difference($row->responded_on, $row->finished) : JText::_('LBL_NA');?>
					<td class="center"><?php echo $row->score?></td>
					<td>
						<div class="center tooltip-hover" title="<?php echo $row->completed ? JText::_('LBL_COMPLETED') : JText::_('LBL_PENDING');?>">
							<i class="<?php echo $row->completed == 1 ? 'fa fa-check-circle success' : 'fa fa-spinner fa-spin';?>"></i>
						</div> 
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$row->response_id.$itemid)?>" target="_blank">
							<?php echo JText::_('LBL_VIEW');?>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<div class="row-fluid">
			<?php 
			echo CJFunctions::get_pagination(
					'index.php?option='.Q_APP_NAME.'&view=quiz&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid, 
					$this->pagination->get('pages.start'), 
					$this->pagination->get('pages.current'), 
					$this->pagination->get('pages.total'),
					$this->pagination->get('limit'),
					true
				);
			?>
		</div>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists['order_dir']; ?>" />
	</form>
	
	<div style="display: none;">
		<input type="hidden" name="cjpageid" id="cjpageid" value="report_responses">
		<span id="url_remove_responses"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=remove_responses&id='.$this->item->id.':'.$this->item->alias.$itemid)?></span>
	</div>
</div>