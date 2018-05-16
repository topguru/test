<?php
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title('Prize Claim List');
$items = $this->prize_model->getClaimPrize($this->package_id);
?>
<div id="j-main-container" class="span10">
<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('#');?></th>
			<th><?php echo JText::_('Prize Name');?></th>
			<th><?php echo JText::_('Prize Value');?></th>
			<th><?php echo JText::_('Prize Winner');?>
			<th><?php echo JText::_('Status');?></th>
		
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($items as $i=>$item){
		$j++;
		?>
		<tr class="row<?php echo $i;?>">
			<td align="center"><?php echo $j;?></td>
			<td><?php echo $item->prize_name;?></td>
			<td>$ <?php echo $item->prize_value;?></td>
			<td><?php echo $item->username;?></td>
			<td align="center"><?php if($item->send_status=='1'){?> Prize sent
			success <?php }else { ?> <a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&layout=send_prize&package_id='.$this->package_id.'&winner_id='.$item->winner_id);?>">Send
			via paypal</a> <?php }?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</div>
