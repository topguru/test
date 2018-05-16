<?php 
/**
 * @version		$Id: default_responses.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
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

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
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
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>

				<h2 class="page-header margin-bottom-10"><?php echo JText::_('LBL_DEVICES').': '.$this->escape($this->item->title);?></h2>
			
				<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_device_report&id='.$this->item->id)?>" method="post">
				
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
							<input
								onchange="this.adminForm.submit();" 
								type="text" name="search" value="<?php echo $this->lists['search'];?>" 
								placeholder="<?php echo JText::_('LBL_SEARCH');?>" class="input-medium margin-bottom-10">
						</div>
						<a class="btn margin-bottom-10" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_survey_reports&id='.$this->item->id)?>">
							<i class="fa fa-reply"></i> <?php echo JText::_('LBL_REPORTS');?>
						</a>
					</div>
					
					<div class="alert alert-error alert-no-selection hide"><i class="fa fa-warning"></i> <?php echo JText::_('MSG_SELECT_ROWS_TO_CONTINUE');?></div>
					
					<table class="table table-striped table-condensed table-hover">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_BROWSER' ), 'a.browser', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_RESPONSES' ), 'responses', $this->lists['order_dir'], $this->lists['order']); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->devices as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo $this->escape($row->browser);?></td>
								<td class="center"><?php echo $row->responses?></td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<div class="row-fluid">
						<?php 
						echo CJFunctions::get_pagination(
								'index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_device_report&id='.$this->item->id, 
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
					<input type="hidden" name="cjpageid" id="cjpageid" value="report_devices">
				</div>
			</td>
		</tr>
	</table>
		
</div>