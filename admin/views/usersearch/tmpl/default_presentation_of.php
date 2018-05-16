<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
				
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				
						<table class="table table-hover table-striped table-bordered">
									<thead>
                                    <tr><td colspan="12" style="text-align:right;">      
                                   
                                   <?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
										<tr>
											<th style="text-align:center;"><?php echo JText::_('Prize value'); ?></th>																	
											<th style="text-align:center;"><?php echo JText::_('Prize'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol set'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol pieces to collect'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Extracted pieces (EP)'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Value pieces (VP)'); ?></th>											
											<th style="text-align:center;"><?php echo JText::_('Free pieces (FP)'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Pieces already collected'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Pieces not collected'); ?></th>
                                            <th style="text-align:center;"><?php echo JText::_('Prize status'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Extracted pieces insert into symbol queue'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol queue number for extracted pieces'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($this->symbolprize as $presentation):
											/*$presentations = $this->model->getPresentationData($result->presentation_id);
											$presentation = $presentations[0];											
											$extracteds = $this->present_model->get_extracted_pieces($result->presentation_id, $presentation->symbol_id);
											$remain=$presentation->pieces - count($extracteds);*/
										?>
										<tr>
											<td class="hidden-phone"><?php echo JText::_('$'.$presentation->prize_value); ?></td>
											<td class="hidden-phone">
												<img
												src="./components/com_awardpackage/asset/prize/<?php echo $presentation->prize_image; ?>"
												style="width: 100px;" />
											</td>
											<td class="hidden-phone">
												<img
												src="./components/com_awardpackage/asset/symbol/<?php echo $presentation->symbol_image; ?>"
												style="width: 100px;" />
											</td>
											<!--td class="hidden-phone">
												<table border="1px">
													<?php															
													$segment_width = 100/$presentation->cols; //Determine the width of the individual segments
													$segment_height = 100/$presentation->rows; //Determine the height of the individual segments
													$show = '';
													for( $rownya = 0; $rownya < $presentation->rows; $rownya++){
														$show .= '<tr>';
														for( $colnya = 0; $colnya < $presentation->cols; $colnya++){
																
															$filename = substr($presentation->symbol_image,0,strlen( $presentation->symbol_image) - 4).$rownya.$colnya.".png";
															$file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
															$show .= '<td style="padding:3px;">';
															$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
															$show .= '</td>';
														}
														$show .= '</tr>';
													}
													echo $show;															
													?>
												</table>
											</td-->
                                            <td class="hidden-phone"><?php echo $presentation->pieces; ?></td>											
											<td class="hidden-phone"><?php echo JText::_(count($extracteds)); ?></td>											
											<td class="hidden-phone"><?php echo JText::_($remain); ?></td>
											<td class="hidden-phone"><?php echo JText::_('0'); ?></td>
											<td class="hidden-phone"><?php echo $this->countprize; ?></td>
											<td class="hidden-phone"><?php $remains = $presentation->pieces - $this->countprize; 
											echo $remains;
											?></td>
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            
											
										</tr>
										<?php endforeach;?>
                                        <tr><td colspan="12" style="text-align:right;">                                      
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
						
			</form>
		</div>
	</div>
</div>