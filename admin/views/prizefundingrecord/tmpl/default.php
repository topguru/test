<?php
/**
 * version 1.0
 * create by Yasa I Kade
 * Bali - Indonesia
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

//get user
$user		= JFactory::getUser();

//show user id
$userId		= $user->get('id');

$listOrder = $this->state->get('list.ordering');

$listDirn = $this->state->get('list.direction');
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prizefundingrecord&package_id='.JRequest::getVar('package_id')); ?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">

			<th width="5"><?php echo JText::_('ID'); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_FUNDING_SESSION', 'prize_funding_session_id', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_PRIZE', 'prize_id', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_VALUE', 'value', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_FUNDING', 'funding', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_SHORTFALL', 'shortfall', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_FUNDED', 'pct_funded', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_STATUS', 'created', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_DATE_UNLOCKED', 'unlocked_date', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($this->items as $item){
		$i++;
		$mod = $i%2;
		?>
		<tr class="row<?php echo $mod;?>">
			<td align="center"><?php echo $item->id;?></td>
			<td><?php echo $item->funding_session;?></td>
			<td><?php echo $item->prize_name;?></td>
			<td align="center"><?php echo '$'.number_format($item->prize_value,2,'.','.');?>
			</td>
			<td align="center"><?php echo $item->funding;?></td>
			<td align="center"><?php echo $item->shortfall;?></td>
			<td align="center"><?php echo $item->pct_funded;?> %</td>
			<td align="center"><?php
			if($item->status=='0'){
				echo'Unlocked';
			}else{
				echo'Locked';
			}
			?></td>
			<td align="center"><?php echo $item->unlocked_date;?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="presentation_id"
	value="<?php echo JRequest::getVar('presentation_id');?>"> <input
	type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="<?php echo $_REQUEST['view']; ?>" /> <input
	type="hidden" name="filter_order" value="<?php echo $listOrder?>" /> <input
	type="hidden" name="filter_order_Dir" value="<?php echo $listDirn?>" />
<input type="hidden" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</form>
</div>
