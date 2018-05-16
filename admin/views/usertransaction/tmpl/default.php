<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>
<script type="text/javascript">
function showHide(div){
  if(document.getElementById(div).style.display == 'none'){
    document.getElementById(div).style.display = 'block';
  }else{
    document.getElementById(div).style.display = 'none'; 
  }
}

$(document).ready(function(){
	$("#expanderHead").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead2").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead3").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead4").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
});

</script>

<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">						
			<form id="adminForm" 
				action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usertransaction&task=usertransaction.doGetUserTransaction');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<table>
					<tr>
						<td width="30%" valign="top" style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">							
							<div class="span3" >
								<table width="100%">
									<tr>
										<td width="50%" valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('Start Date');?>:										
												</label>
												<div class="controls"><?php echo JHtml::_('calendar', null, 'start_date', 'start_date', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
												</div>
											</div>
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('End Date');?>:										
												</label>
												<div class="controls"><?php echo JHtml::_('calendar', null, 'end_date', 'end_date', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
												</div>									
											</div>
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('User name');?>:										
												</label>
												<div class="controls">
													<input name="user_name" style="width:150px" type="text" value="">
												</div>
											</div>
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('Transaction Type');
													//-All -Funds -Quiz -Survey -Award symbol purchase -Prize claimed -Shopping credit
													?>:										
												</label>
												<div class="controls">
													<select name="transaction_type">
                                                    <option value="">All</option>
                                                    <option value="funding">Funds</option>
														<option value="quiz">Quiz</option>
														<option value="survey">Survey</option>
														<option value="symbol">Award symbol purchase</option>
														<option value="prize">Prize Claimed</option>
                                                        <option value="shopping">Shopping Credit</option>
													</select>
												</div>									
											</div>
										</td>
									</tr>								
									<tr>
										<td width="50%" valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('Amount from');?>:										
												</label>
												<div class="controls">
													<input name="amount_from" style="width:150px" type="text" value="">
												</div>
											</div>
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('to');?>:										
												</label>
												<div class="controls">
													<input name="amount_to" style="width:150px" type="text" value="">
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<label class="control-label">
												<?php echo JText::_('User Action');?>:										
											</label>
											<div class="control-group">
												<select name="user_action">
                                                <option value="">All</option>
													<option value="FUNDING">Add fund</option>
													<option value="WITHDRAW">Withdraw fund</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="control-group">
												<input type="submit" value="Search">
											</div>
										</td>
									</tr>
								</table>														
							</div>
						</td>
						<td width="2%" style="">&nbsp;</td>
						<td valign="top">

                            <table class="table table-hover table-striped table-bordered" border="1">
                            <tr><td>
                                                                        <?php echo '<h4 href="#" id="expanderHead" style="text-align:center;cursor:pointer;">'.JText::_( 'Transaction list - Pending only').'</h4>'; ?>
                                                                        </td>
                                                                        <td style="text-align:right;">
                                                                        <?php echo $this->pagination->getLimitBox(); ?>
                                                                        </td>
                                                                        
                                                                        </tr></table>

                                                                    <div id="expanderContent" style="display:none">
                  <table class="table table-hover table-striped table-bordered">
									<tr>
											<th><?php echo JText::_('Date'); ?></th>							
											<th><?php echo JText::_('Transaction'); ?></th>																	
											<th><?php echo JText::_('Amount'); ?></th>
											<th><?php echo JText::_('Total <+> only '); ?></th>											
											<th><?php echo JText::_('Total + only'); ?></th>
											<th><?php echo JText::_('Grand Total'); ?></th>
                                            
										</tr>
									<tbody>

										<?php				
											if (!empty($_POST)) {							 
											foreach ($this->pending_transactions as $row):
											$trans = null;
										?>										
											<?php
												//if(!empty($row->transaction)){
												//	$trans = $row->transaction;
												//	$tran = $trans[0];
											?>
											<tr>	
											<td class="hidden-phone"><?php  
											echo $row->created_date;  ?></td>
											<td class="hidden-phone"><?php  
											echo $row->description;  ?></td>
											<td class="hidden-phone" align="right">
												<?php
					switch($row->transaction_type){
							case 'FUNDING':
								echo '<+> $' . number_format((float)$row->credit, 2) ;							
								break;
							case 'REFUND':
								echo '<+> $' . number_format((float)$row->credit, 2) ;							
								break;	
							case 'WITHDRAW':
								echo '- $' . number_format((float)$row->debit, 2) ;
								break;
							default:
								echo '+ $' . number_format((float)$row->credit, 2) ;
								break;
							}	
												?>
											</td>
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'REFUND':
							    $totala = $totala + $row->credit;
								echo '<+> $' . number_format((float)$totala, 2) ;							
								break;
							case 'FUNDING':
							    $totala = $totala + $row->credit;
								echo '<+> $' . number_format((float)$totala, 2) ;							
								break;
								
							case 'WITHDRAW':
								break;
							default:
								break;
							}	
												*/
							echo '<+> $' .number_format($row->total_pending,2);					?>
											</td>
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'REFUND':
							   	$totalb = $totalb - $row->debit;
								//echo ' $' . number_format((float)$total, 2) ;
								break;
							case 'FUNDING':
							   	$totalb = $totalb - $row->debit;
								//echo ' $' . number_format((float)$total, 2) ;
								break;

							case 'WITHDRAW':
							    $totalb = $totalb - $row->debit;
								echo ' $' . number_format((float)$totalb, 2) ;
								break;
							default:
								$totalb = $totalb + $row->credit;
								echo ' $' . number_format((float)$totalb, 2) ;
								break;
							}	*/
														echo ' $' . number_format($row->total_plus,2);					?>

											</td>	
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'FUNDING':
							   	$total = $total + $row->credit;
								echo ' $' . number_format((float)$total, 2) ;
								break;		
								break;
							case 'WITHDRAW':
							    $total = $total - $row->debit;
								echo ' $' . number_format((float)$total, 2) ;
								break;
							default:
								$total = $total + $row->credit;
								echo ' $' . number_format((float)$total, 2) ;
								break;
							}	
												*/ 
																			echo ' $' . number_format($row->grand_total,2);					?>
											</td>                                            										
											</tr>																					
											<?php 
												//} 
											?>									
										<?php endforeach;
										} ?>
									</tbody>
								</table>

						
							</div>
							<br/>
						<table class="table table-hover table-striped table-bordered">
									<thead>
										<tr>
											<th colspan="5" style="text-align:center;">
												<b><u><?php echo JText::_('Transaction list - All Users (Admin account)'); ?></u></b>
											</th>
										</tr>
										<tr>
											<th><?php echo JText::_('Date'); ?></th>							
											<th><?php echo JText::_('Transaction'); ?></th>																	
											<th><?php echo JText::_('Amount'); ?></th>
											<th><?php echo JText::_('Total <+> only '); ?></th>											
											<th><?php echo JText::_('Total + only'); ?></th>
											<th><?php echo JText::_('Grand Total'); ?></th>
                                            
										</tr>
									</thead>
									<tbody>
										<?php				
											if (!empty($_POST)) {							 
											foreach ($this->transactions as $row):
											$trans = null;
										?>										
											<?php
												//if(!empty($row->transaction)){
												//	$trans = $row->transaction;
												//	$tran = $trans[0];
											?>
											<tr>	
											<td class="hidden-phone"><?php  
											echo $row->created_date;  ?></td>
											<td class="hidden-phone"><?php  
											echo $row->description;  ?></td>
											<td class="hidden-phone" align="right">
												<?php
					switch($row->transaction_type){
							case 'FUNDING':
								echo '<+> $' . number_format((float)$row->credit, 2) ;							
								break;
							case 'REFUND':
								echo '<+> $' . number_format((float)$row->credit, 2) ;							
								break;	
							case 'WITHDRAW':
								echo '- $' . number_format((float)$row->debit, 2) ;
								break;
							default:
								echo '+ $' . number_format((float)$row->credit, 2) ;
								break;
							}	
												?>
											</td>
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'REFUND':
							    $totala = $totala + $row->credit;
								echo '<+> $' . number_format((float)$totala, 2) ;							
								break;
							case 'FUNDING':
							    $totala = $totala + $row->credit;
								echo '<+> $' . number_format((float)$totala, 2) ;							
								break;
								
							case 'WITHDRAW':
								break;
							default:
								break;
							}	
												*/
							echo '<+> $' .number_format($row->total_pending,2);					?>
											</td>
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'REFUND':
							   	$totalb = $totalb - $row->debit;
								//echo ' $' . number_format((float)$total, 2) ;
								break;
							case 'FUNDING':
							   	$totalb = $totalb - $row->debit;
								//echo ' $' . number_format((float)$total, 2) ;
								break;

							case 'WITHDRAW':
							    $totalb = $totalb - $row->debit;
								echo ' $' . number_format((float)$totalb, 2) ;
								break;
							default:
								$totalb = $totalb + $row->credit;
								echo ' $' . number_format((float)$totalb, 2) ;
								break;
							}	*/
														echo ' $' . number_format($row->total_plus,2);					?>

											</td>	
											<td class="hidden-phone" align="right">
												<?php /*
					switch($row->transaction_type){
							case 'FUNDING':
							   	$total = $total + $row->credit;
								echo ' $' . number_format((float)$total, 2) ;
								break;		
								break;
							case 'WITHDRAW':
							    $total = $total - $row->debit;
								echo ' $' . number_format((float)$total, 2) ;
								break;
							default:
								$total = $total + $row->credit;
								echo ' $' . number_format((float)$total, 2) ;
								break;
							}	
												*/ 
																			echo ' $' . number_format($row->grand_total,2);					?>
											</td>                                            										
											</tr>																					
											<?php 
												//} 
											?>									
										<?php endforeach;
										} ?>
                                         <tr><td colspan="6" style="text-align:right;">                                        
                                   
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>                                   
    </tr>
									</tbody>
								</table>
						</td>
					</tr>
				</table>				
				
				<input type="hidden" name="task" value="usertransaction.doGetUserTransaction" />
				<!--input type="hidden" name="filter_order" value="<?php //if($this->lists['order']) echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php //if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" -->
                <input type="hidden" name="filter_order" value="" />
				<input type="hidden" name="filter_order_Dir" value="" />								
			</form>			
		</div>
	</div>
</div>