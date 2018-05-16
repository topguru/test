<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">
function onSelectCategory(category,categoryid){
	jQuery('#category').val(category);
	jQuery('#categoryid').val(categoryid);
	jQuery('#task').val('ugiftcode.onSelectCategory');
	jQuery('form#adminForm').submit();	
}

function myFunction() {
    var x;
    if (confirm("Unlock this Giftcode!") == true) {
        jQuery('#task').val('ugiftcode.doSave');
		jQuery('form#adminForm').submit();
    } 
}

function open_modal_window(e, index, id){		
	var row = jQuery(e).parent().parent().parent().parent().parent().parent().parent().parent().parent().index();
	jQuery('#rowQuestionText').val(row);
	jQuery('#giftcode').val(index);
	jQuery('#giftcodeId').val(id);	
	jQuery('#loadSymbol').modal('show');	
}

function close_modal_window(){	
	jQuery('#category').val(category);
	jQuery('#task').val('ugiftcode.update_symbol');
	jQuery('form#adminForm').submit();	
}

function discard_symbol(){	
	jQuery('#loadSymbol').modal('toggle');		
}

function openModalSymbol(){
	jQuery('#presentationModalWindow').modal('show');
}

function onCloseSymbolFilledModalWindow(e){			
		jQuery('#presentationModalWindow').modal('toggle');				    			    
}
</script>
<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=ugiftcode')?>" method="post">
<input type="hidden" name="category" id="category" value="">
<input type="hidden" name="categoryid" id="categoryid" value="">
<input type="hidden" name="task" id="task" value="">
<div id="cj-wrapper">	
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<div class="container-fluid no-space-left no-space-right surveys-wrapper">
					<div class="row-fluid">			
 <?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>								<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Giftcode'); ?>
								</h2>	
                                <nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getGiftcode");?>">Home</a></li>
                              <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getHistoryGiftcode");?>">Giftcode History</a></li>	
                              </ul>
                              </nav>	
							</div>
						
							<table class="table table-hover table-striped">
								<tr>
									<?php 
									foreach ($this->categories as $row){ ?>
									<td class="hidden-phone">
										<div onclick="onSelectCategory('<?php echo $row->setting_id; ?>','<?php echo $row->category_id; ?>')" style="cursor:pointer">							
										<table>
											<tr>
												<td style="padding-top:14px;width:40px;height:
														30px;text-align:center;background-color:<?php echo $row->colour_code;?>" valign="center">
												<font color="white" size="5"><b><?php echo $row->category_id; ?></b></font>
												</td>
											</tr>
										</table>
										</div>
									</td>
									<?php } ?>
								</tr>
							</table>	
                            <ul style="padding:10px;">
            			<?php
            				foreach ($this->collects as $i => $item) {
							//foreach ($giftcode as $row)
							
						   $total_giftcode = count($this->usercollection);
						 //  $total_user_giftcode = count($this->collect_user);
						   if ($tmp++ < $total_giftcode ){
                		?>
                            	<li style="padding:5px;float:right; list-style-type:none;"> 
                                    <?php echo '<button type="button" class="btn btn-default" style="width:70px; " onclick="open_modal_window(this, '.$item->giftcode_category_id.', '.$item->id.' ); return false;">';
                                     echo $item->giftcode; ?>
                                    </button>

                                    </li>
                           <?php } else { ?>
                           <li style="padding:5px;float:right; list-style-type:none;"> 
                                <button type="button" class="btn btn-default" style="width:70px; "
												id="addNewPrizeBtn" disabled="disabled"><i></i> <?php echo $item->giftcode;?></button>
                                                
                                	</li>
                                    
                                    <?php } } ?>
                          
	      				</ul>			
						</div>			
					</div>
				</div>
			</td>
		</tr>
	</table>	
    
    	
</div>

 <?php 

 echo '<div id="loadSymbol" class="modal hide fade" style="height:500px; width:500px;padding:10px;">';?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3><?php echo JText::_('Symbol List');?></h3>
</div>

<ul style="padding:10px;">
<?php 
$i=0;
foreach ($this->usersymbol as $rows) {
$total_symbol = $this->tot_symbol;
if ($tts++ < $total_symbol) {
$i = $i+1;
echo '<li style="padding:8px;float:left; list-style-type:none;border:1px solid #ccc;text-align:center;"> ';
$filename = $rows->symbol_pieces_image;
$file = '/administrator/components/com_awardpackage/asset/symbol/pieces/'.$filename; ?>
<img src="./administrator/components/com_awardpackage/asset/symbol/pieces/<?php echo $rows->symbol_pieces_image; ?>" width="80"><br/>
<?php
echo '<input type="hidden" value="'.$rows->symbol_pieces_id.'" name="setting_id[]">';		 
echo JHtml::_('grid.id', $i, $rows->symbol_pieces_id); 
echo '<br/>';
echo '</li>';
	}
}
?>
</ul>

<div style="display:inline-table;padding:10px;margin:100px;">
<input type="hidden" name="giftcode" id="giftcode" value="<?php echo $item->giftcode_category_id;?>">
<input type="hidden" name="giftcodeId" id="giftcodeId" value="<?php echo $item->id;?>">
<button type="button" class="btn" onclick="openModalSymbol();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Check Prize');?></button>
<button type="button" class="btn" onclick="discard_symbol();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Discard');?></button>

<input type="submit" class="btn btn-default" style="width:70px;" id="button_check" name="button_check" value="Accept" onclick="close_modal_window();" />
</div>
													
</div>
</form>
<div id="presentationModalWindow" class="modal hide fade" style="height:500px; width:600px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Presentation List');?></h3>
	</div>
	<div style="overflow:scroll; height:450px; width:100%;">
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">#</th>
					<th ><?php echo JText::_( 'Presentation' ); ?></th>			
                    					<th ><?php echo JText::_( 'Prize Name' ); ?></th>													
								<th class="hidden-phone"><?php echo JText::_( 'Prize Image' ); ?></th>
								<th><?php echo JText::_( 'Prize Value' ); ?></th>	
                                <th><?php echo JText::_( 'Symbol Set' ); ?></th>								
								<th class="hidden-phone"><?php echo JText::_( 'Symbol Piece' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->symbolPrizes as $symbolPrizes){ 
				?>
				<tr>
					<td>
						<?php echo $i+1; ?>
					</td>
					<td style="text-align:center;">
						<?php echo JText::_( $symbolPrizes->presentation_id ); ?>
						<input type="hidden" name="presentationName" value="<?php echo $symbolPrizes->presentation_id; ?>" />
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
				</tr>		
				<?php
				$i++;
				} 
				?>
			</tbody>
		</table>
	</div>		
</div>