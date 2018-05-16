<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$result = null;
if(!empty($this->results)) {
	$result = $this->results[0];	
}
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<table>
					<tr>
						<td width="35%" valign="top" style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
							<div class="span3" >
								<table width="100%">
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('From');?>:</label>&nbsp;
											<?php echo JHtml::_('calendar', null, 'fromDate', 'fromDate', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>&nbsp;											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('To');?>:</label>&nbsp;
											<?php echo JHtml::_('calendar', null, 'toDate', 'toDate', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('Symbol pieces purchased value from');?>:</label>&nbsp;
											<input type="text" name="purchaseFrom" value="" style="width:150px;"/>											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to');?>:</label>&nbsp;
											<br/>
											<input type="text" name="purchaseTo" value="" style="width:150px;"/>
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('Number of symbol pieces purchase from');?>:</label>&nbsp;
											<input type="text" name="purchaseSymbolFrom" value="" style="width:150px;"/>											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to');?>:</label>&nbsp;
											<br/>
											<input type="text" name="purchaseSymbolTo" value="" style="width:150px;"/>
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('User with prizes that have');?>:</label>&nbsp;
											<input type="text" name="userCollectSymbolFrom" value="" style="width:150px;"/>											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to, total symbol pieces to collect');?>:</label>&nbsp;
											<input type="text" name="userCollectSymbolTo" value="" style="width:150px;"/>
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('User with prizes that have');?>:</label>&nbsp;
											<input type="text" name="userNotCollectSymbolFrom" value="" style="width:150px;"/>											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to, total symbol pieces not collected');?>:</label>&nbsp;
											<input type="text" name="userNotCollectSymbolTo" value="" style="width:150px;"/>
										</td>
									</tr>
									<tr>
										<td width="50%" valign="top">
											<label><?php echo JText::_('User with prizes that have');?>:</label>&nbsp;
											<input type="text" name="userSymbolPiecesFrom" value="" style="width:150px;"/>											
										</td>
										<td width="2%">&nbsp;</td>
										<td valign="top">
											<label><?php echo JText::_('to, symbol pieces collected');?>:</label>&nbsp;
											<input type="text" name="userSymbolPiecesTo" value="" style="width:150px;"/>
										</td>
									</tr>
								</table>
							</div>							
						</td>
						<td width="2%"style="">&nbsp;</td>
						<td valign="top">
							<div class="span12">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
											<th><?php echo JText::_('Symbol queue number'); ?></th>																	
											<th><?php echo JText::_('Symbol piece'); ?></th>
											<th><?php echo JText::_('Symbol piece type'); ?></th>
											<th><?php echo JText::_('Symbol piece price'); ?></th>
											<th><?php echo JText::_('Prize'); ?></th>
											<th><?php echo JText::_('Symbol piece status'); ?></th>											
										</tr>
									</thead>
									<tbody>
										<?php
											$i = 0; 
											foreach ($result->symbol_queue as $row):
											$i++;
										?>
										<tr>
											<td class="hidden-phone"><?php echo JText::_($i); ?></td>
											<td class="hidden-phone"><img
												src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $row->symbol_pieces_image; ?>"
												style="width: 100px;" /></td>
											<td class="hidden-phone"><?php echo JText::_('Clone'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(!empty($row->price) ?  '$'.$row->price : 'Free'); ?></td>
											<td class="hidden-phone"><img
												src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>"
												style="width: 100px;" /></td>											
											<td class="hidden-phone"><a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_symbol_queue_detail&accountId=<?php echo JRequest::getVar('accountId'); ?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo JText::_('View');?></a>	</td>
										</tr>
										<?php endforeach;?>
									</tbody>									
								</table>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>