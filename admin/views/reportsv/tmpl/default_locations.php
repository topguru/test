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

	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_location_report&id='.$this->item->id.':'.$this->item->alias.$itemid)?>" method="post">
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>"/>
		<div class="form-inline margin-bottom-20 well">
			<div class="pull-right">
				<input
					onchange="this.adminForm.submit();" 
					type="text" name="search" value="<?php echo $this->lists['search'];?>" 
					placeholder="<?php echo JText::_('LBL_SEARCH');?>" class="input-medium margin-bottom-10">
			</div>
		</div>
		
		<div class="alert alert-error alert-no-selection hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_ROWS_TO_CONTINUE');?></div>
		
		<table class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th width="20"><?php echo JText::_( '#' ); ?></th>
					<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_COUNTRY' ), 'c.country_name', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="30%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CITY' ), 'a.city', $this->lists['order_dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_RESPONSES' ), 'responses', $this->lists['order_dir'], $this->lists['order']); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->locations as $i=>$row):?>
				<tr>
					<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
					<td><?php echo $this->escape($row->country_name);?></td>
					<td><?php echo $row->city?></td>
					<td class="center"><?php echo $row->responses?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<div class="row-fluid">
			<?php echo $this->pagination->getListFooter(); ?>
		</div>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists['order_dir']; ?>" />
		<input type="hidden" name="task" value="reportsv.get_location_report"> 
	</form>
	
	<div style="display: none;">
		<input type="hidden" name="cjpageid" id="cjpageid" value="report_locations">
		<span id="url_remove_responses"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=remove_responses&id='.$this->item->id.':'.$this->item->alias.$itemid)?></span>
	</div>
</div>