<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<script type="text/javascript">
function onSelectFilter(){
	var filter = jQuery('#cbfilter').val();
	jQuery('#filter').val(filter);
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=uaccount');
	jQuery('form#adminForm').submit();	
}
</script>	
<form name="adminForm" action="" id="adminForm" method="post">
	<input type="hidden" id="task" name="task" value="uaccount.getTransaction" />
	<input type="hidden" id="filter" name="filter" value="" />
</form>
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table>
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<?php echo '<div class="span12" style="margin:0;">'; ?>
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Account'); ?>
								</h2>				
								<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile");?>">Profile</a></li>
                               <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getFunds");?>">Funds</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getDonation");?>">Donation</a>	</li>
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getAwardSymbol");?>">Award Symbol</a>	</li>                                
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getShoppingCredit");?>">Shopping Credit</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize");?>">Prize Claimed</a>		</li>	<br />
</ul>
</nav>                                                  
							</div>
						</div>
						<div class="span12" style="text-align:right;padding-right:10px;">
							<select name="cbfilter" id="cbfilter" style="width:200px" onchange="onSelectFilter();">
								<option value="all">All</option>
								<option value="funding" <?php echo ($this->filter == "funding" ? "selected=selected" : ""); ?>>Funding</option>
								<option value="withdrawal" <?php echo ($this->filter == "withdrawal" ? "selected=selected" : ""); ?>>Withdrawal</option>
								<option value="donation" <?php echo ($this->filter == "donation" ? "selected=selected" : ""); ?>>Donation</option>
								<option value="awardSymbol" <?php echo ($this->filter == "awardSymbol" ? "selected=selected" : ""); ?>>Award Symbol</option>
								<option value="shoppingCredits" <?php echo ($this->filter == "shoppingCredits" ? "selected=selected" : ""); ?>>Shopping credits</option>
								<option value="survey" <?php echo ($this->filter == "survey" ? "selected=selected" : ""); ?>>Survey</option>
								<option value="quiz" <?php echo ($this->filter == "quiz" ? "selected=selected" : ""); ?>>Quiz</option>
							</select>
							<br/>
							<br/>
						</div>
						<?php echo '<div class="span12" style="margin:0;">'; ?>
							<table class="table table-hover table-striped" width="100%">
								<tbody>
									<tr>
										<td align="center">
											<div style="border: 1px solid #ccc; padding: 10px;">
												<b><?php echo 'Transaction List - '.$this->user->firstname.' '.$this->user->lastname.' - Pending Only'; ?></b>
											</div>
											<div style="width: 100%; float: left;">
												<table class="table table-striped" style="border: 1px solid #ccc;">
													<thead>
														<tr>
															<th width="40%"><u><?php echo JText::_('Date')?></u></th>
															<th><u><?php echo JText::_('Transaction')?></u></th>
															<th><u><?php echo JText::_('Amount')?></u></th>
															<th><u><?php echo JText::_('Status')?></u></th>
														</tr>
													</thead>
                                                   <tbody>

										<?php								
		 
											foreach ($this->histories as $row):
										?>										
											<?php
												//if(!empty($row->transaction)){
													//$trans = $row->transaction;
													//$tran = $trans[0];
											?>
											<tr>	
											<td class="hidden-phone"><?php echo $row->created_date;  ?></td>
											<td class="hidden-phone"><?php echo $row->description; ?></td>
											<td class="hidden-phone" align="right">
												<?php
													if((float)$row->debit > 0) {
														echo '+$' . number_format((float)$row->debit, 2);
													} else 
													if((float)$row->credit > 0) {
														echo '-$' . number_format((float)$row->credit, 2);
													} else {
														echo number_format(0,2);
													}
												
												//$amount = $row->credit - $row->debit;
												//echo '-$ ' . number_format((float)$amount, 2);
												?>
											</td>
											<td class="hidden-phone"><?php echo JText::_('Pending'); ?></td>
											</tr>																					
											<?php 
												//} 
											?>									
										<?php endforeach;?>
									</tbody>
												</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>		
						<?php echo '<div class="span12" style="margin:0;">'; ?>
							<table class="table table-hover table-striped" width="100%">
								<tbody>
									<tr>
										<td align="center">
											<div style="border: 1px solid #ccc; padding: 10px;">
												<b><?php echo 'Transaction List - '.$this->user->firstname.' '.$this->user->lastname.' '; ?></b>
											</div>
											<div style="width: 100%; float: left;">
												<table class="table table-striped" style="border: 1px solid #ccc;">
													<thead>
														<tr>
															<th width="20%"><u><?php echo JText::_('Date')?></u></th>
															<th><u><?php echo JText::_('Transaction')?></u></th>
															<th><u><?php echo JText::_('Amount')?></u></th>
															<th><u><?php echo JText::_('Total incl funds')?></u></th>
															<th><u><?php echo JText::_('Total exlc funds')?></u></th>
														</tr>
													</thead>
													<tbody>
										<?php											 
											foreach ($this->histories as $row):
										?>										
											<?php
												//if(!empty($row->transaction)){
												//	$trans = $row->transaction;
												//	$tran = $trans[0];
											?>
											<tr>	
											<td class="hidden-phone"><?php echo $row->created_date;  ?></td>
											<td class="hidden-phone"><?php echo $row->description; ?></td>
											<td class="hidden-phone" align="right">
												<?php
													if((float)$row->debit > 0) {
														echo '+$' . number_format((float)$row->debit, 2);
													} else 
													if((float)$row->credit > 0) {
														echo '-$' . number_format((float)$row->credit, 2);
													} else {
														echo number_format(0,2);
													}
													//$amount = $row->credit - $row->debit;
												//echo '-$ ' . number_format((float)$amount, 2);
												?>
											</td>
											<td class="hidden-phone" align="right">
												<?php
													
														if((float)$row->debit > 0) {
														echo '+$' . number_format((float)$row->debit, 2);
													} else 
													 {
														echo number_format(0,2);
													}
												?>
											</td>
											<td class="hidden-phone" align="right">
												<?php
														
													if((float)$row->credit > 0) {
														echo '-$' . number_format((float)$row->credit, 2);
													} else {
														echo number_format(0,2);
													}
												?>
											</td>											
											</tr>																					
											<?php 
												//} 
											?>									
										<?php endforeach;?>
									</tbody>
												</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>			
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

