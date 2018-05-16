<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
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
<form id="adminForm" name="adminForm" action=<?php echo JRoute::_("index.php?option=com_awardpackage&view=ugiftcode");?> method="post">
<input type="hidden" id="task" name="task" value="ugiftcode.getHistoryGiftcode" />

<div id="cj-wrapper">	
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<div class="container-fluid no-space-left no-space-right surveys-wrapper">
					<div class="row-fluid">			
						<div class="span12">
							<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Giftcode'); ?>
								</h2>	
                                <nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage");?>">Home</a></li>
                              <li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getHistoryGiftcode");?>">Giftcode History</a></li>	
                              </ul>
                              </nav>	
							</div>
						</div>		
						<div class="span12">
							<table class="table table-hover table-striped">
						<table class="table table-hover table-striped table-bordered">
						<thead>
                                                     <tr><td colspan="5">                                    
                                   
                                    </td>
<td><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
							<tr>
								<th style="text-align:center;"><?php echo JText::_('Date');?></th>
								<th style="text-align:center;"><?php echo JText::_('Giftcode');?></th>
								<th style="text-align:center;"><?php echo JText::_('Total symbol pieces');?></th>
								<th style="text-align:center;"><?php echo JText::_('Priced symbol pieces accepted');?></th>	
								<th style="text-align:center;"><?php echo JText::_('Free symbol pieces accepted');?></th>	
								<th style="text-align:center;"><?php echo JText::_('Symbol pieces discarded');?></th>																	
															
							</tr>
						</thead>
						<tbody>
							<?php //var_dump($this->giftcodes);
							foreach($this->giftcodes as $giftcode)
							{
								$i++;
								?>
<tr>
												<td style="padding-top:14px;height:30px;text-align:center;background-color:<?php echo $giftcode->colour_code ?>;" valign="center">
												<font color="white" ><b><?php echo $giftcode->date_time; ?></b></font>
												
									</td>
									<td ><?php echo $giftcode->category_name; ?></td>
									<td style="text-align:center;"><?php echo $giftcode->total; ?></td>
                                    <td style="text-align:center;">	<?php echo $giftcode->jml; ?></td>
									<td style="text-align:center;">	<?php 
									echo ( $giftcode->jml == 0  ? 0 : $giftcode->total - $giftcode->jml); ?></td>
									<td style="text-align:center;"><?php echo $giftcode->total - $giftcode->jml; ?></td>

								</tr>
								<?php
							}
							?>
                              <tr><td colspan="6" style="text-align:right;">                                    
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