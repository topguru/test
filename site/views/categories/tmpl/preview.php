<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');

?>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'?option=com_awardpackage&controller=donation&task=confirm'; ?>" method="post" name="adminForm">
		
		<h1><?php echo JText::_('Preview donation');?></h1>
		<input type="hidden" value="<?php echo JRequest::getVar('package_id');?>" name="package_id">
		<div style="float:right;width:100px">
				<?php echo $this->payment_gateway;?>
				<input type="hidden" value="<?php echo $this->payment_gateway;?>" name="payment_gateway">
		</div>
		<div class="tableright" align="right">
				<label>Payment Option = </label>
		</div>
		<div class="clear"></div>
		<div class="hr"></div>
		<table width="100%" cellpadding="1" cellspacing="1" class="symbollist">
		<?php 
		$i = 0;
		$subtotal = 0;
		foreach($this->row as $k => $v){
		$j++;
		$subtotal = $subtotal+($v['donation_amount']*$v['quantity']);
		?>
		<tr class="row<?php echo $j%2;?>">
				<td>
				<input type="hidden" value="<?php echo $v['setting_id'];?>" name="setting_id[<?php echo $k;?>]">
				<input type="hidden" value="<?php echo $k; ?>" name="category_id[<?php echo $k; ?>]">
				<div style="float:left;width:70px">
						<div align="center" style="margin:5px;width:50px;background-color:<?php echo $v['colour_code'];?>">
				<font color="white" size="6"><b><?php echo $k; ?></b></font>
				</div>
				</div>
				<div align="center" valign="middle" style="float:left;margin:15px;width:150px;">
						<input type="hidden" value="<?php echo $v['donation_amount']; ?>" name="donation_amount[<?php echo $k; ?>]">		
						donate <?php echo $this->iscent($v['donation_amount']);?>
					</div>
				<div style="float:left;margin:12px;width:10px;">
				<input type="hidden" value="<?php echo $v['quantity']; ?>" name="quantity[<?php echo $k; ?>]">				
						<?php echo $v['quantity'];?>
				</div>
				<div style="float:left;margin-left:5px;margin-top:11px;width:50px;">times =</div>
				<div style="float:left;margin-left:5px;margin-top:11px;width:150px;"><?php echo $this->iscent($v['donation_amount']*$v['quantity']);?></div>
				<div style="clear:both;"></div>
				</td>
		</tr>
		<?php }; ?>
		<tr>
				<td>
				<div class="hr"></div>
				<div style="float:left;margin-left:236px;"><strong>Total donations = </strong></div>
				<div style="float:left;margin-left:10px;width:150px"><strong><?php echo $this->iscent($subtotal);?></strong></div>
				<div class="clear"></div>
				<div class="hr"></div>
				<div align="right" style="margin-top:25px;width:370px">
						<input type="submit" value="NEXT" name="submit" class="btn">
				</div>
				<div>
					<input type="hidden" name="option" value="com_awardpackage" />
					<input type="hidden" name="controller" value="donation" />	
					<input type="hidden" name="task" value="confirm" />
					<input type="hidden" name="boxchecked" value="0" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
				</td>
		</tr>		
		</table>
</form>