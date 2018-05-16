<div style="display:inline;">

	<div style="width: 20%; float:left;padding:10px 0 0 0;">

								<table class="table table-striped table-hover table-bordered" width="100%">  
                                <thead>                              	
                                <tr>
		                                <th colspan="4"><span>Fund prize plan</span>
        		                        <span style="float:right;">
                                        <button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onDeleteStartFundPrize();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Delete');?></button>
										</span>	
                                       
                                        </th>
                                	</tr>       
                                    <th colspan="4">Start fund prize when award funds plan is above: $
									<?php echo (!empty($presentation->fund_amount) ? $presentation->fund_amount : 0 );	 ?>
                                        </th>
                                	</tr>                           	
                                    <tr>
		                                <th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        		                        </th>
                                        <th>No
        		                        </th>
                		                <th>Funding Value
                        		        </th>
                                        <th>Status
                        		        </th>
                                	</tr>
                                    </thead>
                                    <?php foreach ($this->startfundprize as $startfundprizes){
									$k++;
									?>
                                    <tbody>
                                    <tr>
                                    <td><?php echo JHTML::_( 'grid.id', $i, $startfundprizes->id );?></td>

		                                <td><?php echo $k; ?> </td>
                		                <td><?php echo $startfundprizes->value_from.' to '.$startfundprizes->value_to;?></td>
                                        <td><?php if ($startfundprizes->published=='1') { ?>
										<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showFundPrizeHistory&package_id='.JRequest::getVar('package_id').'&process_id='.$row->id.'&valuefrom='.$startfundprizes->value_from.'&valueto='.$startfundprizes->value_to)?>">On</a>
                                        <?php }else { ?> 
<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showFundPrizeHistory&package_id='.JRequest::getVar('package_id').'&process_id='.$row->id.'&valuefrom='.$startfundprizes->value_from.'&valueto='.$startfundprizes->value_to)?>">Off</a>										<?php } ?> </td>

                                	</tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
	</div>
    
    <div style="width: 79%; float:right;padding:10px 0 0 0;font-size:11px;">

<div class="scroller">				
                        <table class="table">
                        <tr><td>
                        <table class="table table-striped table-bordered table-condensed">
						<?php 
				$urutan = 0;
				foreach ($this->presentations as $rows) { 
				$urutan++; 
				$j = 0;  
//					echo '<h4>Selected Presentation # '.JText::_( $urutan ).'</h4>'; 
					echo '<h4>Selected Presentation </h4>'; ?>

						
												<input type="hidden" name="presentations[]" value="<?php echo $this->selectedPresentation; ?>">
                                                                    <thead>
																	<tr>
																		<th><?php echo JText::_( 'Selected presentation' ); ?></th>
																		<th><?php echo JText::_( 'Prize value range' ); ?></th>														
																		<th><?php echo JText::_( 'Report' ); ?></th>										
																	</tr>						
                                                                </thead>
                                                                <tbody>
																<tr>
																	<td><?php echo (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0 ? count($this->PrizeSelected) . ' prizes' : 'New' ); ?></td>
																	<td><?php echo '$'.$this->valuePrizeFrom.' to $'.$this->valuePrizeTo; ?></td>
																	<td><span style="cursor:pointer;">View</span></td>
																	
																</tr>	
                                                                <?php } ?>															
                                                                </tbody>
															</table>
                        </td></tr>
                        
                        <tr>
<!--test1-->
                        
                       <td valign="top">
                       <table class="table table-bordered table-condensed">
                        <tr><th>
                        <?php echo JText::_('Process Symbol');?>
                        </th></tr>
                        <tr><td>
                        <table class="table table-striped table-bordered table-condensed" style="height:200px;">
<tr>
<td><?php echo JText::_('Extract Pieces (EP)');?></td>
<td><input type="text" class="input-medium" name="extract_pieces[]" maxlength="4" value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddExtractPieces('<?php echo $selectedPresentation->id; ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td>
</td></tr>
<tr>
<td><?php echo JText::_('Value Pieces (VP)');?></td>
<td><input type="text" class="input-medium" name="value_pieces[]" maxlength="4" value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddValuePieces('<?php echo $selectedPresentation->id; ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td>
</td></tr>
<tr>
<td><?php echo JText::_('Set each VP to');?></td>
<td><input type="text" class="input-medium" name="num_clone_val[]" maxlength="4" value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddNumCloneVal('<?php echo $selectedPresentation->id ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td>
</tr>
<tr>
<td><?php echo JText::_('Set each FP to');?></td>
<td><input type="text" class="input-medium" name="num_clone_free[]" maxlength="4" value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddNumCloneFree('<?php echo $selectedPresentation->id ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td>
</tr></table>
                        </td></tr><tr><td>
                        <table class="table table-striped table-bordered table-condensed">
<tr style="height:80px;"> 
<td style="text-align:center;">	<?php echo JText::_( '#' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Prize value' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Prize' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Symbol set' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Symbol pieces to Collect' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Extract pieces (EP)' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Value pieces (VP) ' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Free pieces (FP) ' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Set each VP to ' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Set each FP to ' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Total VP' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Total FP' ); ?></td>
</tr>	                          
																
<?php 
foreach ($this->list_presentations as $rows) { 
?> 
<input type="hidden" id="presentationId" name="presentationId" value="<?php echo $rows->presentation; ?>" >
 <?php
	$symbol = $this->model->getSymbolPresentationList($rows->presentation,$rows->selected_presentation);		

		foreach ($symbol as $simbol){
							?>									
<tr>
<td style="text-align:center;height:30px;"	>

<input type="checkbox" id="cb<?php echo $j; ?>" name="cid1<?php echo $j; ?>[]" value="<?php echo $rows->selected_presentation; ?>" 																											onclick="Joomla.isChecked(this.checked);">  
</td>
<td style="text-align:center;"	>	<?php echo (!empty($simbol->prize_value) ? '$'. number_format($simbol->prize_value,2) : ''); ?></td>
<td>
<img src="./components/com_awardpackage/asset/prize/<?php echo $simbol->prize_image;?>?>" style="height:30px;" />
</td>		
<td>
<img src="./components/com_awardpackage/asset/symbol/<?php echo $simbol->symbol_image;?>?>" style="height:30px;" />
</td>
<td style="text-align:center;">	<?php echo $simbol->pieces; ?></td>
<td style="text-align:center;">	<?php echo $simbol->extra_from; ?></td>
<td style="text-align:center;">	<?php echo $simbol->extra_to; ?></td>
<td style="text-align:center;">	<?php echo $simbol->pieces - $simbol->extra_from - $simbol->extra_to; ?></td>
<td style="text-align:center;">	<?php echo $simbol->clone_from; ?></td>
<td style="text-align:center;">	<?php echo $simbol->clone_to; ?></td>
<td style="text-align:center;">	<?php echo $simbol->extra_to * $simbol->clone_from; ?></td>
<td style="text-align:center;">	<?php echo ($simbol->pieces - $simbol->extra_from - $simbol->extra_to )* $simbol->clone_to; ?></td>


</tr>		
<?php } 
	} ?>
</table>
                                                                                    </td></tr></table>
                        
                        
                        </td>
<!--test1-->
                        
<!--test2-->
                        
                        <td valign="top">
                      <table class="table table-bordered table-condensed" style="width:400px;">
                        <tr><th>
						<?php echo JText::_('Price Symbol');?>
                        </th></tr>
                        <tr><td>
                        <table class="table table-striped table-hover table-bordered table-condensed" style="margin:125px 0 0 0;">
<tr><td><?php echo JText::_('Price of each EP');?></td>
<td><input type="text" class="input-medium" name="price_of_each_extracted_pieces[]" maxlength="4" 																						value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onPriceOfExtractedPieces('<?php echo $selectedPresentation->id ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td></tr>									
<tr><td><?php echo JText::_('Price of each VP');?></td>
<td><input type="text" class="input-medium" name="price_of_selected_rpc[]" maxlength="4" 																						value="" onkeyup="numbersOnly(this);" /></td>
<td><button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onPriceOfSelectedRPC('<?php echo $selectedPresentation->id ?>', '<?php echo $j; ?>');" id="addBtn"><i></i> <?php echo JText::_('Add');?></button></td></tr></table>

                        </td></tr><tr><td>
                        <table class="table table-striped table-bordered table-condensed">
<tr style="height:80px;">
<td style="text-align:center;">	<?php echo JText::_( '#' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Price of each EP' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Price of each VP' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Total Price of EP' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Total Price of VP' ); ?></td>
</tr>																			
<?php 
  foreach ($this->list_presentations as $rows) { 
 ?> 
<input type="hidden" id="presentationId" name="presentationId" value="<?php echo $rows->presentation; ?>" >
 <?php
	$symbol = $this->model->getSymbolPresentationList($rows->presentation,$rows->selected_presentation);		
		foreach ($symbol as $simbol){

							?>
<td style="text-align:center;height:30px;"	>
<input type="checkbox" id="cb<?php echo $j; ?>" name="cid2<?php echo $j; ?>[]" value="<?php echo $simbol->selected_presentation; ?>" onclick="Joomla.isChecked(this.checked);"></td>
<td style="text-align:center;"	>	<?php echo (!empty($simbol->prize_value_from) ? '$' . number_format($simbol->prize_value_from,2) : '$0')  ; ?></td>
<td style="text-align:center;"	>	<?php echo (!empty($simbol->prize_value_to) ? '$' . number_format($simbol->prize_value_to,2) : '$0')  ; ?></td>
<td style="text-align:center;"	>	<?php $price_extract = $simbol->extra_from * $simbol->prize_value_from;
echo (!empty($price_extract) ? '$' . number_format($price_extract,2) : '$0')  ; ?></td>
<td style="text-align:center;"	>	<?php $price_vpc = $simbol->extra_to * $simbol->prize_value_to;
echo (!empty($price_vpc) ? '$' . number_format($price_vpc,2) : '$0')  ; ?></td>
</tr>
<?php } 
}?>
</table>
                                                            </td></tr></table>
                        
                        
                        </td>
<!--test2-->

<!--test3-->
                        
                                   <td valign="top">
                       <table class="table table-bordered table-condensed" style="width:300px;">
                        <tr><th>
						<?php echo JText::_('Distribute Symbol');?>
                        </th></tr>
                        <tr><td>
                        <table class="table table-striped table-bordered table-condensed" style="margin:163px 0 0 0;">
                                                                            <tr>
																			<th style="text-align:center;">
																				<?php echo JText::_('Filled symbol queue');?></th></tr>
																			<th style="text-align:center;">
																				<?php 
																				if (!empty($symbolName)){
																				$result = $this->model->getUserGroupName($presentationId);
																				if($result != null){
																						$usergroupname = $result->usergroup;
																								}
																				$symbolCount = count($this->model->getSymbolQueueCount($usergroupname));
																				echo (!empty($symbolCount) ? $symbolCount : 0 );}else{
																				echo 0;
																				}
																				?></th></tr>
                                                                                </table>
                                                                      </td></tr> 
                                                                      <tr><td>         
																			<!-- utk distributed symbol -->
<table class="table table-striped table-bordered table-condensed">
<tr style="height:80px;">
<td style="text-align:center;">	<?php echo JText::_( '#' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Total VP and FP for each symbol queue' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Symbol queue number to insert VP and FP' ); ?></td>
</tr>																		
<?php 
  foreach ($this->list_presentations as $rows) { 
 ?> 
<input type="hidden" id="presentationId" name="presentationId" value="<?php echo $rows->presentation; ?>" >
 <?php
	$symbol = $this->model->getSymbolPresentationList($rows->presentation,$rows->selected_presentation);		
		foreach ($symbol as $simbol){

							?>
<tr>
<td style="text-align:center;height:30px;"	>
																								<input type="checkbox" id="cb<?php echo $j; ?>" name="cid3<?php echo $j; ?>[]" value="<?php echo $simbol->selected_presentation; ?>" 
																											onclick="Joomla.isChecked(this.checked);">
																							</td>														
<td style="text-align:center;"	><?php 
$vpc = $simbol->extra_to * $simbol->clone_from; 
$fpc = ($simbol->pieces - $simbol->extra_from - $simbol->extra_to )* $simbol->clone_to;
$shuffle = $vpc + $fpc;
echo $shuffle; ?></td>
<td style="text-align:center;"	><?php echo '1-' . $shuffle; ?></td>															
</tr>
<?php } 
}?>
</table>

                                                            </td></tr></table>
                        
                        
                        </td>
<!--test3-->
<!--test3-->
                        
                                   <td valign="top">
                       <table class="table table-bordered table-condensed" style="width:250px;">
                        <tr><th>
						<?php echo JText::_('Fund Receiver');?>                      
                        </th></tr>
                        <tr><td>
                        <table border="0" style="margin:210px 0 0 0;">
                                                                            <tr>
																			<td>
                                                                                
                                                                                </td></tr>
                                                                                </table>
                                                                      </td></tr> 
                                                                      <tr><td>         
					<table class="table table-striped table-bordered table-condensed">
<tr style="height:80px;">
<td style="text-align:center;">	<?php echo JText::_( 'Fund receiver plan' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Prizes unlocked' ); ?></td>
<td style="text-align:center;">	<?php echo JText::_( 'Prizes unlocked value' ); ?></td>
</tr>																		
<?php 
  foreach ($this->list_presentations as $rows) { 
 ?> 
<input type="hidden" id="presentationId" name="presentationId" value="<?php echo $rows->presentation; ?>" >
 <?php
	$symbol = $this->model->getSymbolPresentationList($rows->presentation,$rows->selected_presentation);		
		foreach ($symbol as $simbol){

							

																								$claims = $this->model->getPrizeClaimed(JRequest::getVar('package_id'));
																								$prize_wons = $this->model->getPrizeWon(JRequest::getVar('package_id'), $simbol->id);
																								$getPrize = $this->model->getPrizeByStatus($simbol->prize_name, $status,JRequest::getVar('package_id'));
																								$getPrizeUnlock = $this->model->getPrizeByUnlock($simbol->prize_name, $status,JRequest::getVar('package_id'));
																								$prize_won = null;
																								if(empty($prize_wons)){
																									$prize_won = $prize_wons[0];
																								} 
																						?>
<tr>
<td style="text-align:center;height:30px;"	>
<a target='_blank' href="index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.onFundQueue&process_id=<?php echo JRequest::getVar('process_id');?>&value=<?php echo $simbol->prize_value; ?>&ep=<?php echo $simbol->extra_from;?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo count($getPrize); ?></a></span></td>
<td style="text-align:center;"	><?php echo count($getPrizeUnlock ); ?></td>
<td style="text-align:center;"	><?php echo ($simbol->prize_value != null ? '$' . $simbol->prize_value	: ''); ?></td>
</tr>
<?php 
} 
}?>
</table>

                                                            </td></tr></table>
                        
                        </td>
<!--test3-->

                        </tr>
                        
                        </table>								                                                             
                                                              


        
            
                                    </td>
                                    </tr>
                                    </table>    
                                    </div>    
                                    
                                    
                                    
                    </div>
                    
                                                                  
    </div>
    </div>
</div>
      
                      
