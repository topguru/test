<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
foreach ($this->paypals as $paytos){		
		$payto = $paytos->business; }
?>
<script type="text/javascript">
function open_modal_window(e, index){		
	var row = jQuery(e).parent().parent().parent().parent().parent().parent().parent().parent().parent().index();
	jQuery('#rowQuestionText').val(row);
	jQuery('#prizevalue').val(index);
	jQuery('#loadClaim_'+index).modal('show');	
}

function close_modal_window(){	
	jQuery('#category').val(category);
	jQuery('#task').val('prize.save_symbol');
	jQuery('form#adminForm').submit();	
}

function openModalSymbolPrice(){
	jQuery('#presentationModalWindow').modal('show');
}

function onCloseSymbolPrize(e){			
		jQuery('#presentationModalWindow').modal('toggle');				    			    
}

function NotConfirm(index){			
		jQuery('#loadClaim_'+index).modal('toggle');				    			    
}

function onConfirm(index){
	    jQuery('#amount').val(index);
		jQuery('#loadClaim_'+index).modal('toggle');				    			    

	jQuery.ajax({
        type: "POST",
        url: "index.php?option=com_awardpackage&view=prize&task=prize.confirm",
        data: paratemers,
        success:function(response){
        	jQuery('#message').html('Prize will be paid in cash by PayPal');
			jQuery('form#instantpaypal').submit();
        },
        error: function (request, status, error) {
            
        }                  
    });  
}
</script>



<form id="adminForm" name="adminForm" action="index.php?option=com_awardpackage&view=prize&task=prize.getPiecePrize" method="post">
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top"><div class="span12"><div class="well">

								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Award symbol pieces'); ?>
								</h2>			

							</div>

					
						<table class="table table-striped table-hover table-bordered" >
								<thead>
                                <tr>
<td style="text-align:right;" colspan="3"><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
									<tr>
										<th style="text-align:center"><?php echo JText::_( 'Prize' ); ?></th>
                                   		<th style="text-align:center"><?php echo JText::_( 'Prize value' ); ?></th>
										<th style="text-align:center"><?php echo JText::_( 'Symbol set <br/>(symbol piece to collect)' ); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php $k = 0; 
								$tts =0;
								$total = 0; 
									for ($i=0, $n=count( $this->symbolPrizes ); $i < $n; $i++){
										$row =& $this->symbolPrizes[$i]; 
//echo SYMBOL_IMAGES_PIECES_URI;
								?>
								<tr class="row<?echo $k ?>">
									<td>
                                    <img
										src="<?php echo PRIZE_IMAGES_URI . $row->prize_image; ?>"
										style="width: 150px;" /></td>
                                        <td>
                                        </td>
									<td style="text-align:center;font-weight:bold;font-size:14px;"><?php 
									echo $row->pieces;
									if($row->cols > 0 && $row->rows > 0){ ?>
									<table border="1px">
									<?php
									$segment_width = 150/$row->cols; //Determine the width of the individual segments
									$segment_height = 150/$row->rows; //Determine the height of the individual segments
									$show = '';
									for( $rownya = 0; $rownya < $row->rows; $rownya++){
										$show .= '<tr>';
										for( $colnya = 0; $colnya < $row->cols; $colnya++){
						
											$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
											$file = SYMBOL_IMAGES_PIECES_URI .$filename;
											$show .= '<td style="padding:3px;">';
											$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
											$show .= '</td>';
										}
										$show .= '</tr>';
									}
									echo $show;
									?>
									</table>
									<?php } ?>
									</td>
                                   
									
								</tr>
								<?php
									$k = 1 - $k;
									$total = $total - (count($this->total_symbol));
								}
								?>
                                                                  <tr><td colspan="3" style="text-align:right;">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
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
</form>
