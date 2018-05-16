<div>
	<div id="piecesModalWindow<?php echo $j; ?>" class="modal hide fade" style="height:530px; width:800px;padding:10px; ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><?php echo JText::_('Symbol Queue');?></h3>
			</div>
			<div style="overflow:scroll; height:480px; width:100%;">
				<table class="table table-striped table-hover table-bordered" id="symbolQueue"
								style="border: 1px solid #ccc;">
						<tr style="background-color:#CCCCCC">								
							<th colspan="2"><u><?php echo JText::_('Symbol Queue')?></u></th>
						</tr>
						<?php
							  $i = 0; 
							  $pieces = $this->model->getAllSymbolPiecesCollected($prize->prize_id,$selectedPresentation->presentations);
							  foreach ($pieces as $piece) {
							  $i++;				
						?>
						<tr>
							<td width="20"><?php echo $i; ?>.</td>	
							<td>
								<img
										src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $piece->symbol_pieces_image; ?>"
										style="width:100px;" />
							</td>
						</tr>
						<?php }?>
				</table>
			</div>	
		</div>

<div id="prizeModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Fund Prize plan');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th width="30%"><?php echo JText::_( 'Title' ); ?></th>								
								<th width="28%" class="hidden-phone"><?php echo JText::_( 'Value From' ); ?></th>
								<th><?php echo JText::_( 'Value To' ); ?></th>	
				</tr>
				<?php
				$i = 0; 
				foreach ($this->fundprize as $fundprize){ 
				?>
				<tr>
					<td>
						<input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $i; ?>" onclick="onClosePrizeModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($fundprize->id); ?>">
					</td>
					<td>
						<?php echo JText::_( $fundprize->name ); ?>
						<input type="hidden" name="prizeName" value="<?php echo $fundprize->name; ?>" />
					</td>
					<td>
						<?php echo (empty($fundprize->value_from) ? '' : JText::_('$'.$fundprize->value_from )); ?>						
					</td>
					<td>					
						<?php echo (empty($fundprize->value_to) ? '' : JText::_('$'.$fundprize->value_to )); ?>						
					</td>
				</tr>		
				<?php
				$i++;
				} 
				?>
		</table>
	</div>		
</div>

<div id="symbolModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Award Fund plan');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th width="30%"><?php echo JText::_( 'Title' ); ?></th>								
								<th width="28%" class="hidden-phone"><?php echo JText::_( 'Award fund plan' ); ?></th>
								<th><?php echo JText::_( 'Amount' ); ?></th>	
				</tr>
				<?php
				$i = 0; 
				foreach ($this->awardfund as $awardfund){ 
				?>
				<tr>
					<td>
						<input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $i; ?>"  onclick="onCloseSymbolModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($awardfund->id); ?>">
					</td>
					<td>
						<?php echo JText::_( $awardfund->name ); ?>
						<input type="hidden" name="fundName" value="<?php echo $awardfund->name; ?>" />
					</td>
					<td>
						<?php echo (empty($awardfund->rate) ? '' : JText::_($awardfund->rate.' %' )); ?>						
					</td>
					<td>					
						<?php echo (empty($awardfund->amount) ? '' : JText::_('$'.$awardfund->amount )); ?>						
					</td>
				</tr>		
				<?php
				$i++;
				} 
				?>
		</table>
	</div>		
</div>

<div id="SymbolFilledModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Symbol Queues Group');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th width="30%"><?php echo JText::_( 'Name' ); ?></th>	
                    <th ><?php echo JText::_( 'Amount' ); ?></th>								
								<th><?php echo JText::_( 'Status' ); ?></th>	
								<th><?php echo JText::_( 'Created' ); ?></th>	
                                
				</tr>
				<?php
				$i = 0; 
				foreach ($this->symbolqueuegroup as $symbolfilled){ 
			?>
				<tr>
					<td>
						<input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $i; ?>" onclick="onCloseSymbolFilledModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($symbolfilled->id); ?>">
					</td>
                    <td>
						<?php echo JText::_( $symbolfilled->name ); ?>
					</td>
					<td>
						<?php echo JText::_( $symbolfilled->amount ); ?>
						<input type="hidden" name="receiverName" value="<?php echo $symbolfilled->piece; ?>" />
					</td>
					
					<td>					
						<?php echo (empty($symbolfilled->status) ? '' : JText::_($symbolfilled->published )); ?>						
					</td>
                    <td>					
						<?php echo (empty($symbolfilled->date_created) ? '' : JText::_($symbolfilled->date_created )); ?>						
					</td>
				</tr>		
				<?php
				$i++;
				} 
				?>
		</table>
	</div>		
</div>

<div id="receiverModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Fund Receiver');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th width="30%"><?php echo JText::_( 'Title' ); ?></th>								
								<th width="28%" class="hidden-phone"><?php echo JText::_( 'Limit Prize Receiver' ); ?></th>
								<th><?php echo JText::_( 'Status' ); ?></th>	
				</tr>
				<?php
				$i = 0; 
				foreach ($this->receiver as $receiver){ 
			?>
				<tr>
					<td>
				<input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $i; ?>" onclick="onCloseReceiverModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($receiver->criteria_id); ?>">
					</td>
					<td>
						<?php echo JText::_( $receiver->title ); ?>
						<input type="hidden" name="receiverName" value="<?php echo $receiver->title; ?>" />
					</td>
					<td>
						<?php echo (empty($receiver->filter) ? '' : JText::_($receiver->filter )); ?>						
					</td>
					<td>					
						<?php echo (empty($receiver->status) ? '' : JText::_($receiver->status )); ?>						
					</td>
				</tr>		
				<?php
				$i++;
				} 
				?>
		</table>
	</div>		
</div>

<div id="presentationModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Presentation List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th ><?php echo JText::_( 'Presentation' ); ?></th>			
                    					<th ><?php echo JText::_( 'Prize Name' ); ?></th>													
								<th class="hidden-phone"><?php echo JText::_( 'Prize Image' ); ?></th>
								<th><?php echo JText::_( 'Prize Value' ); ?></th>	
                                <th><?php echo JText::_( 'Symbol Set' ); ?></th>								
								<th class="hidden-phone"><?php echo JText::_( 'Symbol Piece' ); ?></th>
				</tr>
				<?php
				$i = 0; 
				foreach ($this->symbolPrizes as $symbolPrizes){ 
				$i++;
				?>
				<tr>
					<td>
                    <?php $prizeCount = (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0  ? count($this->PrizeSelected)  : 0 ); ?>
                    <input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $symbolPrizes->presentation_id; ?>" onclick="onClosePresentationModalWindow(this,<?php echo $prizeCount;?>);"/>
   
						<input type="hidden" value="<?php echo $symbolPrizes->presentation_id; ?>">
					</td>
					<td>
						<?php //echo JText::_( $symbolPrizes->presentation_id ); ?>
						<?php 
							/* Code added by Sushil on 01-12-2015 */
							echo $i; 
						?>
						<input type="hidden" name="presentationName" value="<?php echo $symbolPrizes->presentation_id; ?>" />
					</td>
                    <td>					
						<?php echo (empty($symbolPrizes->prize_name) ? '' : JText::_($symbolPrizes->prize_name )); ?>						
					</td>
					<td><img
				src="./components/com_awardpackage/asset/prize/<?php echo $symbolPrizes->prize_image; ?>"
				style="width: 100px;" />
					</td>
					<td>					
						<?php echo (empty($symbolPrizes->prize_value) ? '' : JText::_($symbolPrizes->prize_value )); ?>						
					</td>
                    <td><img
				src="./components/com_awardpackage/asset/symbol/<?php echo $symbolPrizes->symbol_image; ?>"
				style="width: 100px;" />
					</td>
                    <td>					
						<?php echo (empty($symbolPrizes->pieces) ? '' : JText::_($symbolPrizes->pieces )); ?>						
					</td>
				</tr>		
				<?php
				} 
				?>
		</table>
	</div>		
</div>

<div id="SymbolQueueModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Presentation List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<table class="table table-striped table-hover table-bordered" id="prizeTable"
			style="border: 1px solid #ccc;">
				<tr style="background-color:#CCCCCC">
					<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th ><?php echo JText::_( 'Presentation' ); ?></th>			
                    					<th ><?php echo JText::_( 'Prize Name' ); ?></th>													
								<th class="hidden-phone"><?php echo JText::_( 'Prize Image' ); ?></th>
								<th><?php echo JText::_( 'Prize Value' ); ?></th>	
                                <th><?php echo JText::_( 'Symbol Set' ); ?></th>								
								<th class="hidden-phone"><?php echo JText::_( 'Symbol Piece' ); ?></th>
				</tr>
				<?php
				$i = 0; 
				foreach ($this->symbolPrizes as $symbolPrizes){ ?>
				<tr>
					<td>
                    <input type="radio" name="radio_filled" class="radioFilledClass" 
-						value="<?php echo $i; ?>" onclick="onCloseSymbolQueueModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($symbolPrizes->id); ?>">
					</td>
					<td>
						<?php echo JText::_( $symbolPrizes->presentation_id ); ?>
						<input type="hidden" name="presentationName" value="<?php echo $symbolPrizes->presentation_id; ?>" />
					</td>
                    <td>					
						<?php echo (empty($symbolPrizes->prize_name) ? '' : JText::_($symbolPrizes->prize_name )); ?>						
					</td>
					<td><img
				src="./components/com_awardpackage/asset/prize/<?php echo $symbolPrizes->prize_image; ?>"
				style="width: 100px;" />
					</td>
					<td>					
						<?php echo (empty($symbolPrizes->prize_value) ? '' : JText::_($symbolPrizes->prize_value )); ?>						
					</td>
                    <td><img
				src="./components/com_awardpackage/asset/symbol/<?php echo $symbolPrizes->symbol_image; ?>"
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
		</table>
	</div>		
</div>

</div>