<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.view_symbolqueue&groupId='.JRequest::getVar('groupId').'&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id").'');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>		    								                    <input type="hidden" name="groupId" value="<?php echo  JRequest::getVar("groupId"); ?>"/>	                                       		
					<table class="table table-striped table-hover table-bordered" style="width:90%;">
						<thead>
                        <td colspan="7" style="text-align:right;">                                    <?php echo $this->pagination->getLimitBox(); ?>

                                    </td> 
							<tr>
								<th width="10%" style="text-align:center;"><?php echo JText::_( 'Symbol queue number' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Symbol piece' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Symbol piece type' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Symbol piece price' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Prize value' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Symbol piece status' ); ?></th>								
                                <th style="text-align:center;"><?php echo JText::_( 'Giftcode' ); ?></th>								
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($this->fundprizes as $i=>$row):?>
							<tr>
								<td style="text-align:center;"><?php echo $i+1;?></td>
								<td style="text-align:center;">
								 <img src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $row->symbol_pieces_image;?>?>" style="width:30px;" />

                                </td>
								<td style="text-align:center;"></td>
								<td style="text-align:center;">
                                <?php 
								$prize_piece = ($row->prize_value/$this->jumlah);
								echo '$'.number_format($prize_piece,0); ?>
                                </td>
								<td style="text-align:center;"><?php echo '$'.$row->prize_value;?></td>
								<td style="text-align:center;"></td>
								<td style="text-align:center;"></td>
                                
							</tr>
							<?php endforeach;?>
                             <tr>
<td colspan="7" style="text-align:right;">                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>

                                    </td>                                   
    </tr>
						</tbody>
						
					</table>
					
									
				</form>
			</div>
		</div>
	</div>
</div>