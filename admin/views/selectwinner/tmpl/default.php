<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<form
	action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id);?>"
	method="post" name="adminForm" id="refundpackagelist-form"
	class="form-validate">
<table>
	<tr>
		<td width="45%">
		<div class="j-main-container" style="float: left;">
		<table width="100%" class="table table-striped" cellpadding="0"
			cellspacing="0">
			<thead>
				<tr style="text-align: center; background-color: #CCCCCC">
					<th><?PHP echo JText::_('Total unlocked prize');?></th>
					<th><?PHP echo JText::_('Total prizes awarded');?></th>
					<th><?PHP echo JText::_('Total prizes left award');?></th>
				</tr>
				<tr class="row0">
					<td align="center"><?PHP 
					$totalUnlockedPrize = count($this->model->getTotalUnlockedPrizePresentationID());
					echo $totalUnlockedPrize;
					?></td>
					<td align="center"><?PHP 
					$totalPrizeAward = count($this->model->getTotalPrizeAward());
					echo $totalPrizeAward;
					?></td>
					<td align="center"><?PHP 
					$leftAward = $totalUnlockedPrize-$totalPrizeAward;
					echo $leftAward;
					?></td>
				</tr>
			</thead>
		</table>
		</div>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="45%">
		<div class="j-main-container"><input type="checkbox"
			name="jform[is_same_person]" value="1" id="sameperson"
			<?php if($this->setting){?> checked="checked" <?php }?>>&nbsp; <label
			for="sameperson"><?PHP echo JText::_('Same person can win more than 1 prize');?></label>
		</div>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="4%"><a
			href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id); ?>"><?PHP echo JText::_('Potential winners ');?><?PHP echo count($this->model->checkPotentialWinner($this->presentation_id));?></a>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="4%">&nbsp;
		</td>
	</tr>
</table>
<br />
<fieldset><br />
<table width="100%" class="table table-striped" cellpadding="0" cellspacing="0">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- 
			<th align="center"><input type="checkbox" name="checkall-toggle"
				value="" onclick="checkAll(this)" /></th>
			 -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?> 
			<th><?PHP echo JText::_('Start unlocked prize value'); ?></th>
			<th><?PHP echo JText::_('End unlocked prize value'); ?></th>
			<th><?PHP echo JText::_('Total no of unlocked prizes'); ?></th>
			<th><?PHP echo JText::_('% Of Total to award'); ?></th>
			<th><?PHP echo JText::_('Selected Winners'); ?></th>
			<th><?PHP echo JText::_('Actual Winners');?></th>
		</tr>
	</thead>
	<tbody>
	<?PHP foreach($this->data as $i=>$item){?>
		<tr class="row<?PHP echo $i;?>">
			<td align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td align="center"><a href="<?PHP echo JRoute::_(''); ?>"><?PHP echo $item->start_unlocked_prize;?></a>
			</td>
			<td align="center"><a href="<?PHP echo JRoute::_(''); ?>"><?PHP echo $item->end_unlocked_prize;?></a>
			</td>
			<td align="center"><a
				href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=unlocked_prize&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&winner_id='.$item->id); ?>"><?PHP echo count($this->model->getTotalNoUnlockedPrize($item->id));?></a>
			</td>
			<td align="center"><a href="<?PHP echo JRoute::_(''); ?>"><?PHP echo $this->model->getPersenTotal($item->id,$this->package_id);?>%</a>
			</td>
			<td align="center"><a
				href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=winners&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&winner_id='.$item->id); ?>"><?PHP echo count($this->model->getSelectedWinners($item->id));?></a>
			</td>
			<td align="center"><a
				href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=actualwinners&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&winner_id='.$item->id); ?>"><?PHP echo count($this->model->getActualWinners($item->id));?></a>
			</td>
		</tr>
		<?PHP }?>
	</tbody>
</table>
</fieldset>
<div><input type="hidden" name="presentation_id"
	value="<?php echo $this->presentation_id; ?>"> <input type="hidden"
	name="package_id" value="<?php echo $this->package_id; ?>"> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="filter_order"
	value="<?php echo $listOrder; ?>" /> <input type="hidden"
	name="filter_order_Dir" value="<?php echo $listDirn; ?>" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
