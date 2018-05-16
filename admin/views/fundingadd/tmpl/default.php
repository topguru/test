<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

$funding_data = $this->model->CheckFunding(JRequest::getVar('funding_id'));

if($funding_data->funding_desc == "" || $funding_data->funding_desc =='-' || $funding_data->funding_session =='-' || $funding_data->funding_session ==""){
	$funding_data->funding_desc 	= JRequest::getVar('funding_desc');
	$funding_data->funding_session	= JRequest::getVar('funding_session');
}
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post"
	name="adminForm" id="adminForm" class="form-validate">
<fieldset class="adminform"><legend>Revenue to prize fund</legend>
<ul class="adminformlist">
	<label id="jform_title-lbl" class="hasTip required" title=""
		for="jform_title">Funding Session</label>
	<input id="jform_title" class="inputbox required" type="text" size="30"
		value="<?php echo $funding_data->funding_session;?>"
		name="funding_session" aria-required="true" required="required">
	<br />
	<label id="jform_title-lbl" class="hasTip required" title=""
		for="jform_title" name="funding_desc">Description</label>
	<textarea name="funding_desc"><?php echo $funding_data->funding_desc;?></textarea>
	<br />
</ul>
</fieldset>
<fieldset class="adminform"><legend>Details</legend></fieldset>
<div id="j-main-container" class="span10">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th width="5"><?php echo JText::_('ID'); ?></th>
			<!--
			<th width="20">
				<input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->items); ?>);" /></th>  
			-->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			<th><?php echo JHtml::_('grid.sort', '% of each dollar of revenue', 'revenue_percentage', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'From Prize Value', 'revenue_fromprize', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'To Prize Value', 'revenue_toprize', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'Allocation Strategy', 'revenue_strategy', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->items as $i => $item): ?>
	<?php

	$item->max_ordering = 0;

	$canChange	= $user->authorise('core.edit.state','com_awardpackage.funding.'.$item->funding_id);

	if($item->funding_id == JRequest::getVar('funding_id'))://check the funding id and display data
	?>
		<tr class="row<?php echo $i % 2; ?>">
			<td align="center"><?php echo $item->revenue_id; ?></td>
			<td align="center"><?php echo JHtml::_('grid.id', $i, $item->revenue_id); ?>

			<input type="hidden" name="revenue_id[]"
				value="<?php echo $item->revenue_id;?>"></td>
			<td align="center"><?php
			if($item->revenue_percentage==0 || $item->locked=='0'){
					
				echo'<input type="textbox" name="revenue_percentage[]" value="'.$item->revenue_percentage.'">';

			}else{
					
				echo $item->revenue_percentage;
					
				echo'<input type="hidden" name="revenue_percentage[]" value="'.$item->revenue_percentage.'">';
			}
			?></td>
			<td align="center"><?php
			if($item->locked==0 || $item->revenue_fromprize==0)
			{
				?> <input type="textbox" class="inputbox" name="revenue_fromprize[]"
				value="<?php echo $item->revenue_fromprize;  ?>" /> <?php
			}else
			{
				echo $item->revenue_fromprize;
					
				echo '<input type="hidden" name="revenue_fromprize[]" value="'.$item->revenue_fromprize.'">';
			}
			?></td>
			<td align="center"><?php
			if($item->locked==0 || $item->revenue_toprize==0)
			{
				?> <input type="textbox" class="inputbox" name="revenue_toprize[]"
				value="<?php echo $item->revenue_toprize;  ?>" /> <?php
			}else
			{
					
				echo'<input type="hidden" name="revenue_toprize[]" value="'.$item->revenue_toprize.'">';
					
				echo $item->revenue_toprize;
			}
			?></td>
			<td align="center"><?php
			if($item->locked=='0' || $item->revenue_toprize==0)
			{
				?> <select name="revenue_strategy[]">
				<?php
				switch($item->revenue_strategy)
				{
					case '1':
						?>
				<option value="1" selected="selected">High to Low</option>
				<option value="2">Low to High</option>
				<?php
				break;
case '2':
	?>
				<option value="1">High to Low</option>
				<option value="2" selected="selected">Low to High</option>
				<?php
				break;
				}
				?>
			</select> <?php
			}else
			{
				switch($item->revenue_strategy)
				{
					case '1':
						echo'<input type="hidden" value="1" name="revenue_strategy[]">';
						echo 'High to Low';
						break;
					case '2':
						echo'<input type="hidden" value="2" name="revenue_strategy[]">';
						echo 'Low to High';
						break;
				}
			}
			?></td>
		</tr>
		<?php endif ?>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>

<input type="hidden" name="presentation_id"
	value="<?php echo JRequest::getVar('presentation_id');?>"> <input
	type="hidden" name="funding_id"
	value="<?php echo JRequest::getVar('funding_id');?>"> <input
	type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="<?php echo $_REQUEST['view']; ?>" /> <input
	type="hidden" name="filter_order" value="<?php echo $listOrder?>" /> <input
	type="hidden" name="filter_order_Dir" value="<?php echo $listDirn?>" />
<input type="hidden" name="boxchecked" value="0" /></form>
