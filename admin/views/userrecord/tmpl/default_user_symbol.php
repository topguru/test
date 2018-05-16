<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
?>
<div id="j-main-container" class="span10">
	<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th>Selected User</th>
				<th>Symbol Queue</th>
				<th>Symbol Pieces</th>
				<th>Shuffled</th>
				<th>Prize Range Value</th>
			</tr>
		</thead>	
		<tbody>	
			<?php
				$i = 0; 
				foreach ($this->items as $item) { 
			?>
			<tr>
				<td><?php echo $item->selectedUser;?></td>
				<!-- <td><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&task=userrecord.user_symbol_detail&layout=user_symbol_detail&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$item->id);?>">View</a></td> -->
				<td><a href="#">View</a></td>
				<td><?php echo $item->total;?></td>
				<td>1</td>
				<td><?php echo $item->prizeValueRange;?></td>
			</tr>
			<?php
				} 
			?>
		</tbody>
	</table>
</div>