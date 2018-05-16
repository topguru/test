<div id="AwardFundPlanModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Award Fund Plan List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	

			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th style="text-align:center;"><?php echo JText::_( 'Title' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Award Funds Rate (%)' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Award funds Amount' ); ?></th>	                                
								<th style="text-align:center;"><?php echo JText::_( 'Award funds spent' ); ?></th>	
								<th style="text-align:center;"><?php echo JText::_( 'Award funds remain' ); ?></th>	
                                
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->awardfundplan as $awardfundplan){ 
				?>
				<tr>
					<td>
						<input type="radio" name="radio_fund" class="radioFundClass" 
						value="<?php echo $i; ?>" onclick="onCloseAwardFundPlanModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($awardfundplan->id); ?>">
					</td>
					<td>
						<?php echo JText::_( $awardfundplan->name ); ?>
						<input type="hidden" name="AwardFundName" value="<?php echo $awardfundplan->name; ?>" />
					</td>
					<td style="text-align:center;">
						<?php echo (empty($awardfundplan->rate) ? '' : $awardfundplan->rate ); ?>						
					</td>
					<td style="text-align:center;">					
						<?php echo (empty($awardfundplan->amount) ? '' : $awardfundplan->amount ); ?>						
					</td>
                    <td style="text-align:center;">					
						<?php echo (empty($awardfundplan->spent) ? '' :$awardfundplan->spent ); ?>						
					</td>
                     <td style="text-align:center;">					
						<?php echo (empty($awardfundplan->remain) ? '' : $awardfundplan->remain ); ?>						
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

<div id="SymbolQueueGroupModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Symbol Queue Group List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	

			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th style="text-align:center;"><?php echo JText::_( 'Title' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Created' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Symbol queue list' ); ?></th>	
                                
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->symbolgroup as $symbolgroup){ 
				?>
				<tr>
					<td style="text-align:center;">
						<input type="radio" name="radio_fund" class="radioFundClass" 
						value="<?php echo $i; ?>" onclick="onCloseSymbolQueueGroupModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($symbolgroup->id); ?>">
					</td>
					<td style="text-align:center;">
						<?php echo JText::_( $symbolgroup->name ); ?>
						<input type="hidden" name="prizeName" value="<?php echo $symbolgroup->name; ?>" />
					</td>
					<td style="text-align:center;">
						<?php echo (empty($symbolgroup->date_created) ? '' : JText::_($symbolgroup->date_created )); ?>						
					</td>
					<td style="text-align:center;">	
                   <?php  
    if (!empty($symbolgroup->id)){
	$jumlah = $this->model->getSymbolCount($symbolgroup->id);
	}
	echo (!empty($jumlah) ? $jumlah : 0 );				
						 //echo (empty($symbolgroup->amount) ? '' : JText::_($symbolgroup->amount )); ?>						
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

<div id="SymbolQueueModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Symbol Queue List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	

			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
								<th style="text-align:center;"><?php echo JText::_( 'Pieces' ); ?></th>	
								<th style="text-align:center;"><?php echo JText::_( 'Shuffle' ); ?></th>	
								<th style="text-align:center;"><?php echo JText::_( 'Created' ); ?></th>
                                
                                
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->symbolqueue as $symbolqueue){ 
				?>
				<tr>
					<td style="text-align:center;">
						<input type="radio" name="radio_fund" class="radioFundClass" 
						value="<?php echo $i; ?>" onclick="onCloseSymbolQueueModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($symbolqueue->queue_id); ?>">
					</td>
					
					
					<td style="text-align:center;">					
						<?php echo (empty($symbolqueue->piece) ? '' : JText::_($symbolqueue->piece )); ?>						
					</td>
                    <td style="text-align:center;">					
						<?php echo (empty($symbolqueue->shufle) ? '' : JText::_($symbolqueue->shufle )); ?>						
					</td>
                    <td style="text-align:center;">
						<?php echo (empty($symbolqueue->date_created) ? '' : JText::_($symbolqueue->date_created )); ?>						
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

<div id="PresentationModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Process Presentation List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	

			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th style="text-align:center;"><?php echo JText::_( 'Title' ); ?></th>								
								<th style="text-align:center;"><?php echo JText::_( 'Prize' ); ?></th>	
								<th style="text-align:center;"><?php echo JText::_( 'Symbol queue group' ); ?></th>	
                                <th style="text-align:center;"><?php echo JText::_( 'Prize Value' ); ?></th>	                                
								<th style="text-align:center;"><?php echo JText::_( 'Created' ); ?></th>
                                

				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0; 
				foreach ($this->proses as $proses){ 
				?>
				<tr>
					<td style="text-align:center;">
						<input type="radio" name="radio_fund" class="radioFundClass" 
						value="<?php echo $i; ?>" onclick="onClosePresentationModalWindow(this);"/>
						<input type="hidden" value="<?php echo JText::_($proses->id); ?>">
					</td>
					<td style="text-align:center;">
						<?php echo JText::_( $proses->name ); ?>
						<input type="hidden" name="prizeName" value="<?php echo $proses->name; ?>" />
					</td>					
					<td style="text-align:center;">					
						<?php 
						$this->PrizeSelected = $this->model->getProcessPresentationDetailPrize($proses->id,$this->package_id);
 echo (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0 ? count($this->PrizeSelected)  : 'New' ); 
						//echo (empty($proses->prize_name) ? '' : JText::_($proses->prize_name )); ?>						
					</td>
                    <td style="text-align:center;">					
						<?php echo (empty($proses->symbol_queue) ? '' : JText::_($proses->symbol_queue )); ?>						
					</td>
                     
                    <td style="text-align:center;">					
						<?php echo (empty($proses->prize_value) ? '' : JText::_($proses->prize_value )); ?>						
					</td>
                    <td style="text-align:center;">
						<?php echo (empty($proses->date_created) ? '' : JText::_($proses->date_created )); ?>						
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