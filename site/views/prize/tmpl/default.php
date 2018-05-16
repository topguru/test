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


<?php

$mailer =& JFactory::getMailer();
foreach ($this->users as $user){
			$emails      = $user->email;
		    $mailer->setSender('system@award.com');
		    $mailer->addRecipient($emails);
		    $mailer->setSubject('Prize From Award');
		    $mailer->setBody('Prize Claimed');
		    $send =& $mailer->Send();
		    if ( $send !== true ) {
		    	echo 'Error sending email:'.$send->get('message');
		    } else {
		    	echo 'Mail sent';
		    }
}

	
	
?>
<form id="adminForm" name="adminForm" action="index.php?option=com_awardpackage&view=prize&task=prize.getMainPage" method="post">

<div id="message" name="message"></div>
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
<?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>								<div class="well">

								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Prizes'); ?>
								</h2>			

							</div>

					
						<table class="table table-striped table-hover table-bordered table-condensed">
								<thead>
                                <tr><td colspan="4">                                    
                                   
                                    </td>
<td><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
									<tr>
										<th style="text-align:center"><?php echo JText::_( 'Prize' ); ?></th>
										<th style="text-align:center"><?php echo JText::_( 'Symbol set <br/>(symbol piece to collect)' ); ?></th>
										<th style="text-align:center"><?php echo JText::_( 'Symbol pieces collected' ); ?></th>
										<th style="text-align:center"><?php echo JText::_( 'Prize status' ); ?></th>
										<th style="text-align:center"><?php echo JText::_( 'Action' ); ?></th>
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
									<td style="text-align:center;font-weight:bold;font-size:14px;">
                                    <?php 									
									$total_symbol = count($this->total_symbol);
									$total = $total_symbol - $row->pieces;
									echo '<a href="index.php?option=com_awardpackage&view=prize&task=prize.getPiecePrize">'.( $total > $pieces ? $row->pieces : $total ).'</a>';
									?>
									<ul style="width :180px;padding:0;margin:0;">
									<li style="padding:5px;float:right; list-style-type:none;">                                     
									<?php 
									$row =& $this->symbolPrizes[$i]; 
									$pieces = $row->pieces;
									for( $rownya = 0; $rownya < $row->rows; $rownya++){
										for( $colnya = 0; $colnya < $row->cols; $colnya++){

										if ($tts++ < $total_symbol) {
											$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
											$file = SYMBOL_IMAGES_PIECES_URI .$filename;
											echo '<a href="#" onclick="openModalSymbolPrice();">';
											echo '<img id="image'.$i.'" style="padding:3px; width: 50px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
											echo '</a>';
										}
											}
																				

									} ?>
                                    </li>

									</ul>
                                    
                                    </td>
									<td style="text-align:center">
                                    <?php if ($pieces <= $total ) {
	 								  echo "<p style='padding:8px;'>Not Won</p>";
	 								  echo "<p style='padding:8px;background-color:#0044cc;color:#fff;'>Won<br/></p>";
									} else
									{
	 								  echo "<p style='padding:8px;background-color:#0044cc;color:#fff;'>Not Won</p>";
	 								  echo "<p style='padding:8px;'>Won<br/></p>";
									}
									if ($row->unlocked_status == 1){
	 								  echo "<p style='padding:8px;background-color:#0044cc;color:#fff;'>Claimed<br/></p>";
									} else {
									echo "<p style='padding:8px;'>Claimed<br/></p>";

									}
									?>
                                    </td>
									<td>
                                    <input type="hidden" id="value_prize" name="value_prize" value="<?php echo $row->prize_value; ?>"  />
                                   <?php 
								   //if ($pieces == $total_symbol ){
								   echo '<button type="button" class="btn btn-primary" style="width:100px; " onclick="open_modal_window(this, '.$row->prize_value.' ); return false;">Claim Prize</button>';// }else {
								   //echo '<button disabled="disabled" type="button" class="btn btn-primary" style="width:100px; " onclick="open_modal_window(this, 1 ); return false;">Claim Prize</button>';}
									 
									 ?>
										
									</td>
								</tr>
								<?php
									$k = 1 - $k;
									$total = $total - (count($this->total_symbol));
								}
								?>
                                                                  <tr><td colspan="5" style="text-align:right;">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
<td>
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
<?php for ($i=0, $n=count( $this->symbolPrizes ); $i < $n; $i++){
										$row =& $this->symbolPrizes[$i]; 
 ?>
<div id="loadClaim_<?php echo $row->prize_value; ?>" class="modal hide fade" style="height:300px; width:500px;padding:10px;">
<div class="modal-header">
	<h3>
 <form id="adminForm2" name="adminForm2" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&task=prize.confirm')?>" method="post">                
	<?php echo JText::_('Prize Name : ')?>
    <input type="text" id="prizename" name="prizename" value="<?php echo $row->prize_name; ?>" readonly /></br>
    	<?php echo JText::_('Prize Value : ')?>
    <input type="text" id="prizevalue" name="prizevalue" value="<?php echo $row->prize_value; ?>" readonly />

<br/>
    <?php  
	echo JText::_('Do you want to claim the prize ?');?></h3>
</div>
<div style="margin:50px;">
<input type="button" class="btn btn-default" style="width:90px;" id="button_check" name="button_check" value="No" onclick="NotConfirm(<?php echo $row->prize_value; ?>);" />
<input type="submit" class="btn btn-default" style="width:90px;" id="button_check" name="button_check" value="Yes" onclick="onConfirm(<?php echo $row->prize_value; ?>);" />
</div>
</div>
</div>
</form>

<?php } ?>
<form name="instantpaypal" id="instantpaypal" method="post" >
    
<input type="hidden" name="email" value="masanam@yahoo.com"><br>
<input type="hidden" name="message" value="You Win"><br>
</form>
<div id="presentationModalWindow" class="modal hide fade" style="height:500px; width:600px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Symbol Prize');?></h3>
	</div>
	<div style="overflow:scroll; height:450px; width:100%;">
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">#</th>
                    					<th ><?php echo JText::_( 'Prize Name' ); ?></th>													
								<th class="hidden-phone"><?php echo JText::_( 'Prize Image' ); ?></th>
								<th><?php echo JText::_( 'Prize Value' ); ?></th>	
                                <th><?php echo JText::_( 'Symbol Set' ); ?></th>								
								<th class="hidden-phone"><?php echo JText::_( 'Symbol Piece' ); ?></th>
                                <th class="hidden-phone"><?php echo JText::_( 'Symbol Price' ); ?></th>

				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->symbolPrizes as $symbolPrizes){ 
				?>
				<tr>
					<td>
<input type="radio" name="radio_receiver" class="radioReceiverClass" 
						value="<?php echo $i; ?>" onclick="onConfirm();"/>
						<input type="hidden" value="">
                        					</td>
					
                    <td>					
						<?php echo (empty($symbolPrizes->prize_name) ? '' : JText::_($symbolPrizes->prize_name )); ?>						
					</td>
					<td><img
				src="./administrator/components/com_awardpackage/asset/prize/<?php echo $symbolPrizes->prize_image; ?>"
				style="width: 100px;" />
					</td>
					<td>					
						<?php echo (empty($symbolPrizes->prize_value) ? '' : JText::_($symbolPrizes->prize_value )); ?>						
					</td>
                    <td><img
				src="./administrator/components/com_awardpackage/asset/symbol/<?php echo $symbolPrizes->symbol_image; ?>"
				style="width: 100px;" />
					</td>
                    <td>					
						<?php echo (empty($symbolPrizes->pieces) ? '' : JText::_($symbolPrizes->pieces )); ?>						
					</td>
                     <td>					
						<?php echo (empty($symbolPrizes->pieces) ? '' : number_format($symbolPrizes->prize_value/$symbolPrizes->pieces ,0)); ?>						
					</td>
				</tr>		
				<?php
				$i++;
				} 
				?>
			</tbody>
		</table>
	</div>		
</div>
