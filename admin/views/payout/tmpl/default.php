<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=payout&task=payout.get_payout&package_id='.JRequest::getVar("package_id").'');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-striped table-hover table-bordered" style="width:100%;">
						<thead>
                        <tr><td colspan="10" style="text-align:right;">                                    
                                                                            
                                               <?php echo $this->pagination->getLimitBox(); ?>

                                    </td>                                   
    </tr>

							<tr>
								<th><?php echo JText::_( '#' ); ?></th>
								<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JText::_( 'Prize value' ); ?></th>								
								<th><?php echo JText::_( 'Prize' ); ?></th>
								<th><?php echo JText::_( 'Symbol set' ); ?></th>
                                <th><?php echo JText::_( 'Status' ); ?></th>				
							  <th><?php echo JText::_( 'Claimed date' ); ?></th>				 
                              <th><?php echo JText::_( 'Claimed by' ) ?></th>				 
                              	 
                              <th><?php echo JText::_( 'Prize payout status' ); ?></th>				 
                              <th><?php echo JText::_( 'Prize payout date' ); ?></th>				                                				
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($this->symbolPrizes as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><?php echo '$'.number_format($this->escape($row->prize_value),2); ?></td>
								<td class="hidden-phone">
                                <?php if ($row->claimed_status == '1') {?>
                                <img
										src="<?php echo PRIZE_IMAGES_URI . $row->prize_image; ?>"
										style="width: 150px;" />
                                        <?php }?>
                                </td>
								<td class="hidden-phone">
                                <img
										src="<?php echo SYMBOL_IMAGES_URI . $row->symbol_image; ?>"
										style="width: 150px;" />
                                </td>
								<td>
									<?php
									if ($row->claimed_status == '1'){
									echo 'Won';
									}else
									{
									echo 'Not Won';
									}
									?>
								</td>		
                               <td class="hidden-phone"><?php echo $row->claimed_date; ?></td>
						<td class="hidden-phone"><?php echo $this->escape($row->firstname).' '.$this->escape($row->lastname); ?></td>
                        
                        <td class="hidden-phone">
<?php
									if ($row->send_status == "1"){
									echo 'Paid';
									}else
									{
									echo 'Not Paid';
									}
									?>                        </td>
                        <td class="hidden-phone"><?php 
						if ($row->send_status == "1"){
						echo $row->claimed_date; 
                        } ?></td>
							</tr>
							<?php endforeach;?>
                                                                                                         <tr>
<td colspan="10" style="text-align:right;">                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>

                                    </td>                                   
    </tr>

						</tbody>
						
					</table>
					
					<input type="hidden" name="boxchecked" value="0" />	
									
				</form>
			</div>
		</div>
	</div>
</div>