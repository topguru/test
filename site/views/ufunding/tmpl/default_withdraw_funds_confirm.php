<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function onBack(){
	window.location = "index.php?option=com_awardpackage&view=ufunding&task=ufunding.withdrawFunds";
}
</script>
<form id="myForm">
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
									<?php echo 'Funds remain : $ ' . number_format($this->remain,2); ?>
								</h2>	
							</div>
						</div>			
						<div class="span12">
							<fieldset>
								<table class="table table-hover table-striped">
									<!--thead>
										<tr style="text-decoration: underline;">
											<th>Giftcode</th>
											<th>Cost per giftcode</th>
											<th>How many giftcode can be awarded with my funds?</th>
										</tr>
									</thead-->
									<tbody>
									<?php foreach ($this->categories as $item) { ?>
									<tr>
										<td>
											<table>
												<tr>
													<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $item->colour_code;?>" valign="center">
													<font color="white" size="5"><b><?php echo $item->category_id; ?></b></font>
													</td>
												</tr>
											</table>
										</td>
										<td>
											<?php echo number_format(($item->donation_amount * 100),2) . ' cents'?>
										</td>
										<td>
											<?php echo number_format((round($this->remain / $item->donation_amount)),2); ?>
										</td>
									</tr>
									<?php } ?>	
									</tbody>							
								</table>
							</fieldset>	
							<br/>
							<br/>			
						</div>
						<div class="span12" style="text-align:center;padding-right:10px;">				
							<button type="button" class="btn btn-primary btn-invite-reg-groups"
										id="btn" onclick="onBack();"><i></i> <?php echo JText::_('Back');?></button>				
							<br/>
							<br/>
						</div>
					</td>
				</tr>
			</table>			
		</div>
	</div>
</div>
</form>
