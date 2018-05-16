<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');	
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');
?>

<script type="text/javascript">
function onAddNewPrize(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('anewpresentationlist.new_prize');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.new_prize&package_id='+package_id);
	jQuery('form#adminForm').submit();
}
function onAddNewSymbolSet(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('anewpresentationlist.new_symbolset');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.new_symbolset&package_id='+package_id);
	jQuery('form#adminForm').submit();
}
function openModalPrize(){
	jQuery('#prizeModalWindow').modal('show');
}
function openModalSymbol(){
	jQuery('#symbolModalWindow').modal('show');
}
function onClosePrizeModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();
		var name = jQuery(tr).find("td:eq(1)").text();
		var prizeImage = jQuery(tr).find("td:eq(1)").find("input[type='hidden']").val();
		var value = jQuery(tr).find("td:eq(2)").text();		
		jQuery("#prizeImage").html(
				'<img ' +
				' src="./components/com_awardpackage/asset/prize/'+prizeImage+'" '	+
				' style="width:100px;cursor:pointer;" onclick="openModalPrize();"></img> '		
			);
		//jQuery("#idPrizeName").text(name);			   
		jQuery("#idPrizeValue").text(value);
		jQuery("#idPrizeId").val(id);
		jQuery('#prizeModalWindow').modal('toggle');				    			    
	}	
}
function onCloseSymbolModalWindow_2(e){
	var tr = jQuery(e).parent().parent();						
	var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();				
	var name = jQuery(tr).find("td:eq(1)").text();
	var symbolSet = jQuery(tr).find("td:eq(2)").find("input[type='hidden']").val();
	var symbolPieces = jQuery(tr).find("td:eq(3)").text();
	var rows = jQuery(tr).find("td:eq(4)").find("input[name='rows']").val();
	var cols = jQuery(tr).find("td:eq(4)").find("input[name='cols']").val();
	console.log(rows);
	console.log(cols);
	//jQuery("#idSymbolName").text(name);
	jQuery("#idSymbolSet").html(
			'<img ' +
			' src="./components/com_awardpackage/asset/symbol/'+symbolSet+'" '	+
			' style="width:100px;cursor:pointer;" onclick="openModalSymbol();"></img> '
		);
	jQuery("#idSymbolPieces").text(symbolPieces);
	var show = '';
	var segment_width = 80 / parseInt(cols);
	var segment_height = 80 / parseInt(rows);
	for(var i=0; i < parseInt(rows); i++ ){
		show += '<tr>';
		for(var j=0; j < parseInt(cols); j++){
			var filename = symbolSet.substr(0,symbolSet.length-4)+i+j+'.png';
			var file = './components/com_awardpackage/asset/symbol/pieces/'+filename;
			show += '<td style="padding:3px;">';
			show += '<img style="left: 0px; top: 0px; width: '+segment_width+'px;" alt="" src="'+file+'"/>';
			show += '</td>';
		}
		show += '</tr>';
	}
	jQuery("#idSymbolPiecesToCollect").html(show);
	jQuery("#idSymbolId").val(id);
	jQuery('#symbolModalWindow').modal('toggle');
}
function onCloseSymbolModalWindow(e){
	if(jQuery(e).is(':checked')) {
		var tr = jQuery(e).parent().parent();						
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();				
		var name = jQuery(tr).find("td:eq(1)").text();
		var symbolSet = jQuery(tr).find("td:eq(2)").find("input[type='hidden']").val();
		var symbolPieces = jQuery(tr).find("td:eq(3)").text();
		var rows = jQuery(tr).find("td:eq(4)").find("input[name='rows']").val();
		var cols = jQuery(tr).find("td:eq(4)").find("input[name='cols']").val();
		jQuery("#rows").val(rows);
		jQuery("#cols").val(cols);
		console.log(rows);
		console.log(cols);
		//jQuery("#idSymbolName").text(name);
		jQuery("#idSymbolSet").html(
				'<img ' +
				' src="./components/com_awardpackage/asset/symbol/'+symbolSet+'" '	+
				' style="width:100px;cursor:pointer;" onclick="openModalSymbol();"></img> '
			);
		jQuery("#idSymbolPieces").text(symbolPieces);
		var show = '';
		var segment_width = 80 / parseInt(cols);
		var segment_height = 80 / parseInt(rows);
		for(var i=0; i < parseInt(rows); i++ ){
			show += '<tr>';
			for(var j=0; j < parseInt(cols); j++){
				var filename = symbolSet.substr(0,symbolSet.length-4)+i+j+'.png';
				var file = './components/com_awardpackage/asset/symbol/pieces/'+filename;
				show += '<td style="padding:3px;">';
				show += '<img style="left: 0px; top: 0px; width: '+segment_width+'px;" alt="" src="'+file+'"/>';
				show += '</td>';
			}
			show += '</tr>';
		}
		jQuery("#idSymbolPiecesToCollect").html(show);
		jQuery("#idSymbolId").val(id);
		jQuery('#symbolModalWindow').modal('toggle');
	}
}
</script>
<form name="adminForm" id="adminForm" class="survey-form" 
action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id').''); ?>"  method="post">

<input type="hidden" name="task" id="task" value="anewpresentationlist.initiate">
<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
<input type="hidden" name="presentation_id" value="<?php echo $this->presentation_id; ?>">
<table width="100%">
	<tr>
		<td width="20%" valign="top">
			<div id="cj-wrapper"
				style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
				<div
					class="container-fluid no-space-left no-space-right surveys-wrapper">
					<div class="row-fluid">
						<div class="span10">
							<div class="clearfix">								
								<table width="100%">
									<tr>
										<td width="46%">
											
										</td>										
										<td width="1%">&nbsp;</td>
										<td align="right">	
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewPrize();" id="addNewPrizeBtn"><i></i> <?php echo JText::_('Add');?></button>										
										</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">
												<div style="overflow:auto;">										
												<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
												<thead>
													<tr>
														<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Prize' ); ?></th>																						
														<th width="50%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Prize Value' ); ?></th>																							
													</tr>
												</thead>
												<tbody>
													<?php if(!empty($this->prizeSelected)){ ?>
													<?php foreach ($this->prizeSelected as $prize){ ?>
													<tr>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
															<!-- 
															<span style="text-decoration:underline;cursor:pointer" id="idPrizeName" onclick="openModalPrize();">
																<?php echo $prize->prize_name; ?>
															</span>
															 -->
															 <span id="prizeImage">
															 <img src="./components/com_awardpackage/asset/prize/<?php echo $prize->prize_image; ?>" style="width:100px;cursor:pointer;" onclick="openModalPrize();" />
															 </span>
														</td>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
															<span id="idPrizeValue"><?php echo $prize->prize_value; ?></span>
															<input type="hidden" name="idPrizeId" id="idPrizeId" value="<?php echo $prize->id; ?>">
														</td>
													</tr>
													<?php }?>														
													<?php } else { ?>
													<tr>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
															<!-- <span style="text-decoration:underline;cursor:pointer" id="idPrizeName" onclick="openModalPrize();">New</span> -->
															<span style="text-decoration:underline;cursor:pointer" id="prizeImage" onclick="openModalPrize();">New</span>
														</td>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
															<span id="idPrizeValue">&nbsp;</span>
															<input type="hidden" name="idPrizeId" id="idPrizeId" value="">
														</td>
													</tr>														
													<?php } ?> 													
												</tbody>																									
											</table>
											</div>																																		
										</td>										
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>					
			</div>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="40%" valign="top">
			<div id="cj-wrapper"
				style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
				<div
					class="container-fluid no-space-left no-space-right surveys-wrapper">
					<div class="row-fluid">
						<div class="span10">
							<div class="clearfix">
								<table width="100%">
									<tr>
										<td width="46%">
											
										</td>
										<td width="1%">&nbsp;</td>
										<td align="right">
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewSymbolSet();" id="addNewSymbolSetBtn"><i></i> <?php echo JText::_('Add');?></button>											
										</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">
											<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
												<thead>
													<tr>
														<!-- <th width="20%"><?php echo JText::_( 'Symbol Name' ); ?></th> -->
														<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Symbol Set' ); ?></th>								
														<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Symbol Pieces' );?></th>
														<th width="38%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Symbol Pieces To Collect' ); ?></th>																							
													</tr>
												</thead>
												<tbody>	
													<?php if(!empty($this->symbolSelected)) { ?> 
													<?php 
													$k = 0;
													for ($i=0, $n=count( $this->symbolSelected ); $i < $n; $i++){
														$row =& $this->symbolSelected[$i];
													?>
													<tr>
														<!--<td> <span style="text-decoration:underline;cursor:pointer" id="idSymbolName" onclick="openModalSymbol();"><?php echo $row->symbol_name ?></span></td> -->
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><span id="idSymbolSet">
															<img
															src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
															style="width:100px;cursor:pointer;" onclick="openModalSymbol();"/>
														</span>
														</td>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><span id="idSymbolPieces"><?php echo $row->pieces; ?></span></td>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
															<input type="Hidden" name="cols" id="cols" value="<?php echo $row->cols; ?>">
															<input type="Hidden" name="rows" id="rows" value="<?php echo $row->rows; ?>">
															<span id="idSymbolPiecesToCollect">
															<table border="1px">
																<?php															
																$segment_width = 80/$row->cols; //Determine the width of the individual segments
																$segment_height = 80/$row->rows; //Determine the height of the individual segments
					 										    $show = '';
																for( $rownya = 0; $rownya < $row->rows; $rownya++){
																	$show .= '<tr>';
																	for( $colnya = 0; $colnya < $row->cols; $colnya++){
																			
																		$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
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
															</span>
															<input type="hidden" name="idSymbolId" id="idSymbolId" value="<?php echo $row->symbol_id; ?>" >
														</td>														
													</tr>
													<?php
														$k++;
													
													} 
													?>								
													<?php } else { ?>
													<tr>
														<!-- <td><span style="text-decoration:underline;cursor:pointer" id="idSymbolName" onclick="openModalSymbol();">New</span></td> -->
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><span id="idSymbolSet" style="text-decoration:underline;cursor:pointer" onclick="openModalSymbol()">New</span></td>
														<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><span id="idSymbolPieces">&nbsp;</span></td>
														<td style="border-width: 1px; border-style: border-color: #000 #000 #000 #000;"><span id="idSymbolPiecesToCollect">&nbsp;</span>
														<input type="hidden" name="idSymbolId" id="idSymbolId" value="" >
														</td>
													</tr>
													<?php } ?>											

												</tbody>																									
											</table>										
										</td>										
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>					
			</div>
		</td>
	</tr>		
	<tr>
		<td colspan="3"><hr/></td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
				<thead>
                <tr><td>                                    
                                                                             
                                    </td>
<td colspan="5" style="text-align:right;">                                   <?php echo $this->pagination->getLimitBox(); ?>

                                    </td>                                   
    </tr>
					<tr>
						<th width="20" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>													
						<!-- <th width="20%"><?php echo JText::_( 'Prize Name' ); ?></th> -->
						<th width="15%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Prize' ); ?></th>
						<th width="10%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Prize Value' ); ?></th>						
						<!-- <th><?php echo JText::_( 'Symbol Name' ); ?></th> -->
						<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;" ><?php echo JText::_( 'Symbol Set' ); ?></th>																													
						<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Symbol Pieces' ); ?></th>
						<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Symbol Pieces To Collect' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						$k = 0;
						for ($i=0, $n=count( $this->symbolPrizes ); $i < $n; $i++){
							$row =& $this->symbolPrizes[$i];									
						?>													
						<tr class="row<?echo $k ?>">
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<input type="checkbox" id="cb<?php echo $i; ?>" name="cidd[]" value="<?php echo JText::_( $row->presentation_id); ?>" 
								onclick="Joomla.isChecked(this.checked);"  /> 
							</td>
							<!-- <?php echo JText::_( $row->prize_name ); ?></td> -->
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<input type="Hidden" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo JText::_( $row->presentation_id); ?>"/>
								<img
									src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>"
									style="width:150px;" /></td>
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( '$'.$row->prize_value ); ?></td>
							<!-- <td><?php echo JText::_( $row->symbol_name ); ?></td> -->
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<img
									src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
									style="width:150px;" /></td>
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( $row->pieces ); ?> </td>
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<?php if($row->cols > 0 && $row->rows > 0){ ?>
								<table border="1px">
								<?php															
								$segment_width = 150/$row->cols; //Determine the width of the individual segments
								$segment_height = 150/$row->rows; //Determine the height of the individual segments
								$show = '';
								for( $rownya = 0; $rownya < $row->rows; $rownya++){
									$show .= '<tr>';
									for( $colnya = 0; $colnya < $row->cols; $colnya++){
											
										$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
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
								<?php } ?>
							</td>							
						</tr>
						<?php
						$k = 1 - $k;
							}													
					?>
                                                                             <tr><td>                                    
                                    </td>
<td colspan="5" style="text-align:right;">                                   <div class="pagination">
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
<input type="hidden" name="boxchecked" value="0" />
</form>
<div id="prizeModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Prize');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th><u><?php echo JText::_('Prize Name')?></u></th>
					<th><u><?php echo JText::_('Prize Value')?></u></th>
					<th><u><?php echo JText::_('Prize Image')?></u></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->prizes as $prize){ ?>
				<tr>
					<td>
						<input type="radio" name="radio_prize" class="radioPrizeClass" 
						value="<?php echo $i; ?>" onclick="onClosePrizeModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($prize->id); ?>">
					</td>
					<td>
						<?php echo JText::_( $prize->prize_name ); ?>
						<input type="Hidden" name="prizeImage" value="<?php echo $prize->prize_image; ?>" />
					</td>
					<td>
						<?php echo (empty($prize->prize_value) ? '' : JText::_('$'.$prize->prize_value )); ?>						
					</td>
					<td>					
						<img
							src="./components/com_awardpackage/asset/prize/<?php echo $prize->prize_image; ?>"
							style="width:100px;"/>
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
<div id="symbolModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Symbol');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped" id="symbolTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th><u><?php echo JText::_('Symbol Name')?></u></th>
					<th><u><?php echo JText::_('Symbol Set')?></u></th>
					<th><u><?php echo JText::_('Symbol Pieces')?></u></th>
					<th><u><?php echo JText::_('Symbol Pieces To Collect')?></u></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$k = 0;
					for ($i=0, $n=count( $this->symbols ); $i < $n; $i++){
						$row =& $this->symbols[$i];									
				?>
					<tr>
						<td>
						<input type="radio" name="radio_symbol" class="radioSymbolClass" 
							value="<?php echo $k; ?>" onclick="onCloseSymbolModalWindow(this);"/>
							<input type="hidden" value="<?php echo JText::_($row->symbol_id); ?>">
						</td>
						<td>
							<?php echo JText::_( $row->symbol_name ); ?>
						</td>
						<td>
							<img
								src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
								style="width:100px;"/>
							<input type="hidden" value="<?php echo $row->symbol_image; ?>">
						</td>
						<td>
							<?php echo JText::_( $row->pieces ); ?>
						</td>
						<td>
							<input type="hidden" name="cols" value="<?php echo $row->cols; ?>">
							<input type="hidden" name="rows" value="<?php echo $row->rows; ?>">
							<table border="1px">
								<?php															
								$segment_width = 80/$row->cols; //Determine the width of the individual segments
								$segment_height = 80/$row->rows; //Determine the height of the individual segments
								$show = '';
								for( $rownya = 0; $rownya < $row->rows; $rownya++){
									$show .= '<tr>';
									for( $colnya = 0; $colnya < $row->cols; $colnya++){
											
										$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
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
						</td>
					</tr>
				<?php
					$k++; 
					}
				?>
			</tbody>
		</table>
	</div>	
</div>
