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

function onFilter(){
	var from = jQuery('#from').val();
	var to = jQuery('#to').val();
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize&from='+from+'&to='+to);
	jQuery('form#adminForm').submit();	
}

</script>	
<form name="adminForm" action="index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize" id="adminForm" method="post">

<div id="cj-wrapper">
<div class="container-fluid no-space-left no-space-right surveys-wrapper">
<div class="row-fluid">
			<table>
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<div class="span12" style="margin:0;">
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Account'); ?>
								</h2>				
								
								<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile");?>">Profile</a></li>
                               <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getFunds");?>">Funds</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getDonation");?>">Donation</a>	</li>
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getAwardSymbol");?>">Award Symbol</a>	</li>                                
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getShoppingCredit");?>">Shopping Credit</a>	</li>
                                <li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize");?>">Prize Claimed</a>		</li>	<br />
</ul>
</nav>                                             
							</div>
						</div>

<?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12" style="margin:0;">';
} ?>

<br/><table class="table table-hover table-striped" width="100%" >
                                <tr><td>            
                                   <div class="control-label"> From :  <input type="text" name="from" id="from" alt="date" class="IP_calendar" title="Y-m-d">          To    <input type="text" name="to" id="to" alt="date" class="IP_calendar" title="Y-m-d">       
                                   <button type="button" class="btn btn-primary" onclick="onFilter();">Go</button>
								</div>
                                    </td>
                                    <td><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>
    </tr>
    <tr>
										<td align="center" colspan="2">
											<div style="border: 1px solid #ccc; padding: 10px;">
												<b><?php echo 'Transaction List - '.$this->user->firstname.' '.$this->user->lastname.' '; ?></b>
											</div>
                                            </td>
</tr>
    <tr>
    <td colspan="2">
    <div id="tabs">
  <ul>
    <li><a href="#tabs-1"><span>Pending</span></a></li>
    <li><a href="#tabs-2"><span>Completed</span></a></li>
  </ul>
  
 <div id="tabs-1">
  <table class="table table-striped" style="border: 1px solid #ccc;">
													<thead>
														<tr>
															<th><u><?php echo JText::_('Date')?></u></th>
															<th><u><?php echo JText::_('Transaction')?></u></th>
															<th><u><?php echo JText::_('Amount')?></u></th>
															<th><u><?php echo JText::_('Total prize claimed')?></u></th>
														</tr>
													</thead>
                                                   <tbody>
                                                   

										<?php								
		 
											foreach ($this->pending_histories as $row):
										?>										
											<?php
												//if(!empty($row->transaction)){
													//$trans = $row->transaction;
													//$tran = $trans[0];
											?>
                                            
											<tr>	
											<td class="hidden-phone"><?php echo ($row->credit > 0 ? $row->created_date : ''); ?></td>
											<td class="hidden-phone"><?php echo ($row->credit > 0 ? $row->description : ''); ?></td>
											<td class="hidden-phone" align="right">
												<?php													
												echo  ($row->credit > 0 ? '$'.number_format((float)$row->credit, 2) : '');

												?>
											</td>
<td>
											<?php
 												    $pending_total = $pending_total + $row->credit;
													echo  ($row->total_pending > 0 ? '$'.number_format((float)$row->total_pending, 2) : '');
											?>
										</td>											</tr>																					
											<?php 
												//} 
											?>									
										<?php endforeach;?>
                                       
									</tbody>
												</table>
  </div>
  
  <div id="tabs-2">
  <table class="table table-striped" style="border: 1px solid #ccc;">
													<thead>
														<tr>
															<th><u><?php echo JText::_('Date')?></u></th>
															<th><u><?php echo JText::_('Transaction')?></u></th>
															<th><u><?php echo JText::_('Amount')?></u></th>
															<th><u><?php echo JText::_('Total prize claimed')?></u></th>
														</tr>
													</thead>
													<tbody>
										<?php 
									foreach($this->completed_histories as $history) { ?>
									<tr>
										<td><?php echo ($history->credit > 0 ? $history->created_date : ''); ?></td>
										<td><?php echo ($history->credit > 0 ? $history->description : '' ); ?></td>
										<td class="hidden-phone" align="right">
												<?php													
														echo  ($history->credit > 0 ? '$'.number_format((float)$history->credit, 2) : '');
												?>
											</td>
										<td>
											<?php
 												    $completed_total = $completed_total + $history->credit;
													echo  ($history->total_plus > 0 ? '$'.number_format((float)$history->total_plus, 2) : '');
											?>
										</td>
									</tr>
									<?php } ?>
                                    
									</tbody>
												</table>
  </div>
</div>
    </td>
    
    </tr>
    
    <tr><td colspan="2" style="text-align:right;" >                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
                                   
    </tr>
    </table>
</div>
</td>
</tr>
</table>						


                                    
                                    
                                    
									
</div>
</div>
</div>
</form>
