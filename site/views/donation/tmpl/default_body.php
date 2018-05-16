<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$balance += $item->credit-$item->debit;
	if($item->credit){
		$amount = '+ '.$this->iscent($item->credit,2);
	}else{
		$amount = '- '.$this->iscent($item->debit,2);
	}
?>
	<tr class="row<?php echo $i % 2; ?>">
		<td align="center">
			<?php echo $item->dated; ?>
		</td>		
		<td align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=donation&task=view&transaction_id='.$item->transaction_id);?>"><?php echo $item->transaction; ?></a>
		</td>
		<td align="center"><?php echo $item->payment_gateway;?></td>				
		<td align="right"><?php echo $amount;?></td>
		<td align="right"><?php echo $this->iscent($balance,2);?></td>		
		<td align="center">
			<?php echo $item->status; ?>
		</td>		
	</tr>
<?php endforeach; ?>
