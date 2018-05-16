<?php
defined('_JEXEC') or die();
if(!empty($this->results)){
	$result = $this->results[0];
}
								
?>
<script type="text/javascript">
function onNext(){
	jQuery('form#adminForm').submit();	
}
</script>
<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=udonation')?>" method="post">
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">			
			<table>
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<div class="span12">
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php 
									$i = 0;
									$total = 0; 
						
										foreach ($this->results as $item) {
											$total = $total + $item['donation_amount'];
											$i++; 
											}
									echo JText::_('Calculate Total Donation: $ ');
									echo number_format($total,2);
									; ?>
								</h2>		
							</div>
							<br/>
							<br/>
						</div>		
						<div class="span12">
							<table class="table table-hover table-striped">
								<tbody>
									<?php 
									$i = 0;
									$total = 0; 
										foreach ($this->results as $item) {
											$total = $total + $item['donation_amount'];
											$i++; 
									?>
									<tr>
										<td width="10%" valign="top">
											<table>
												<tr>
													<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $item[color];?>" valign="center">
													<font color="white" size="5"><b><?php echo $item['category_id']; ?></b></font>
													</td>
												</tr>
											</table>
										</td>
										<td valign="top">
											<?php echo JText::_('Donate ' . ((float)$item['donate'] * 100) . ' cents ' . $item['quantity'] . ' times = '); ?>
										</td>							
										<td valign="top" style="text-align:right">
											<?php echo JText::_(($item['donation_amount'] < 1 ? $item['donation_amount'] * 100 . ' cents' : $item['donation_amount'] . ' dollar')); ?>
										</td>
									</tr>
									<?php } ?>						
									<tr>
										<td colspan="3" style="text-align:right;">
											<h2> <?php echo ($total < 1 ? ($total * 100) . ' cents' : $total . ' dollar');?></h2>
										</td>
									</tr>
								</tbody>
							</table>		
						</div>
						<div class="span12" style="text-align:right;padding-right:10px;">
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onNext();"> <?php echo JText::_('Next');?></button>				
							<br/>
							<br/>
						</div>
					</td>
				</tr>
			</table>			
		</div>
	</div>
</div>
<input type="hidden" name="task" value="udonation.selectPayment"/>
<input type="hidden" name="amount" value="<?php echo $total; ?>"/>
<input type="hidden" name="user_id" value="<?php echo $this->userId;?>">
<input type="hidden" name="package_id" value="<?php echo $this->package_id;?>">
</form>
