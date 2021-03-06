<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<?php echo $this->view_buttons();?>
<h1>Donation Details</h1>
<div style="clear:both;height:30px"></div>
Donation ID : <?php echo $this->transaction_id;?><br>
Date : <?php echo $this->dated;?><br>
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=donation'); ?>" method="post" name="adminForm">
<input type="hidden" name="transaction_id" value="<?php echo $this->transaction_id;?>"/>
<table class="adminlist">
<thead>
<tr>
	<th>#</th>
	<th>Category name</th>
	<th>Donation Amount</th>
	<th>Quantity</th>	
	<th>Amount</th>
</tr>
</thead>
<?php
	$info = $this->info;
	for($i=0;$i<=count($info)-1;$i++){
	$amount += $info[$i]->donation_amount*$info[$i]->quantity;
	?>
		<tr class="row<?php echo $i % 2; ?>">
		<td align="center"><?php echo  $info[$i]->category_id;?></td>
		<td align="center"><?php echo $this->show_category_name($info[$i]->category_id);?></td>
		<td align="right"><?php echo $this->iscent($info[$i]->donation_amount);?></td>
		<td align="right"><?php echo $info[$i]->quantity;?></td>
		<td align="right"><?php echo $this->iscent($info[$i]->donation_amount*$info[$i]->quantity);?></td></tr>
	<?php
	}
?>
<tr><td class="row<?php echo ($i+1) % 2;?>" colspan="4" align="right">Total:</td><td align="right"><?php echo $this->iscent($amount);?></td></tr>
</table>
	<div>
		<input type="hidden" name="controller" value="donation" />	
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="1" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>