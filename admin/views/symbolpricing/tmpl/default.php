<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Redirect Access
defined('_JEXEC') or die('Restricted access');
foreach ($this->data as $data) {
	$user_all = $data->is_all_user;
	$symbol_pricing_id = $data->symbol_pricing_id;
}
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id')); ?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped" width="50%">
	<tr class="row0">
		<td colspan="2"><?php
		if ($user_all != "0") {
			$total_pricing = count($this->model->symbolDetails($symbol_pricing_id));
			if ($total_pricing > 0) {
				$label = 'Edit';
			} else {
				$label = 'New';
			}
			$pricing_id = $symbol_pricing_id;
		} else {
			$label = 'New';
			$pricing_id = $symbol_pricing_id;
		}
		?> <input type="hidden" name="pricing_id"
			value="<?php echo $pricing_id;?>"> <input type="checkbox"
			name="all_users" value="1" <?php if ($user_all) { ?>
			checked="checked" <?php } ?>>&nbsp;&nbsp;All Registered Users</td>
	</tr>
	<tr class="row1">
		<td width="150">Symbol Pricing</td>
		<td><input type="hidden" name="symbol_pricing_id"
			value="<?php echo $pricing_id; ?>"> <a
			href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&presentation_id=' . JRequest::getVar('presentation_id') . '&pricing_id=' . $pricing_id . '&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo $label; ?></td>
		</td>
	</tr>
</table>
<table class="table table-striped">
	<tr>
		<thead>
			<th colspan="10" align="center">
			<h4>Selected Registered users only</h4>
			</th>
	
	</tr>

	<tr style="text-align:center; background-color:#CCCCCC">
		<!-- 
		<th width="20"><input type="checkbox" name="toggle" value=""
			onclick="checkAll(<?php echo count($this->data); ?>);" /></th>
		 -->
		<th width="20" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
		<th align="center">User Group</th>
		<th align="center">Symbol Pricing</th>
		<th align="center">Publish</th>
	</tr>
	</thead>
	<?php
	foreach ($this->data as $i => $data) {
		if ($data->is_all_user == "0") {
			$total_pricing = count($this->model->symbolDetails($data->symbol_pricing_id));
			?>
	<tr class="row<?php echo $i; ?>">
		<td><?php echo JHtml::_('grid.id', $i, $data->symbol_pricing_id); ?></td>
		<td align="center"><?php
		$totalUserGroup = count($this->model->getPricingGroup($data->symbol_pricing_id));
		$link = 'index.php?option=com_awardpackage&view=symbolusergroup&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id') . '&symbol_pricing_id=' . $data->symbol_pricing_id;
		if($totalUserGroup<1){
			$label ='New';
		}  else {
			$label ='Edit';
		}
		?> <a href="<?php echo JRoute::_($link); ?>"><?php echo $label;?></a>
		</td>
		<td align="center"><?php
		if ($total_pricing < 1) {
			$label = 'New';
		} else {
			$label = 'Edit';
		}
		?> <a
			href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . $data->symbol_pricing_id) . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id'); ?>"><?php echo $label; ?></a>
		</td>
		<td align="center"><?php echo JHtml::_('jgrid.published', $data->is_publish, $i, 'symbolpricing.', 'cb'); ?>
		</td>
	</tr>
	<?php
		}
	}
	?>
</table>
<div><input type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id'); ?>"> <input
	type="hidden" name="presentation_id"
	value="<?php echo JRequest::getVar('presentation_id'); ?>"> <input
	type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="<?php echo $_REQUEST['view']; ?>" /> <input
	type="hidden" name="filter_order" value="<?php echo $listOrder ?>" /> <input
	type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
<input type="hidden" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
