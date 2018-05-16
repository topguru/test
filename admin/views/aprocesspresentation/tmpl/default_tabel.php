<div style="background-color:#EEF5EB; width:90%;">	
					<table class="table table-striped table-hover table-bordered">
                    <thead>
						<tr>
							<th style="text-align:center;"><?php echo JText::_( 'Title' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Start fund prize when award funds
plan is above' ); ?></th>														
							<th style="text-align:center;"><?php echo JText::_( 'Selected presentations' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Award funds plan' ); ?></th>
							<th style="text-align:center;"><?php echo JText::_( 'Award fund for fund receiver plan' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Symbol queues' ); ?></th>
                            <th style="text-align:center;"><?php echo JText::_( 'Fund prize status' ); ?></th>
                            
                            	</tr>	
                                </thead>
                                <tbody>
					<tr bgcolor="white">
                    <?php foreach ($this->presentations as $presentation){ 	?>
						<td style="text-align:center;"><?php echo $presentation->name; ?></td>
						<td style="text-align:left;"><?php echo $presentation->fund_amount; ?></td>
						<td style="text-align:center;"><?php echo '$'.$this->valuePrizeFrom.' to $'.$this->valuePrizeTo; ?></td>
						<td style="text-align:center;"><?php echo $presentation->limit_receiver.'% of award funds'; ?></td>
						<td style="text-align:center;"><?php echo $presentation->fund_receiver_name; ?></td>
						<td style="text-align:center;"><?php 
						$presentationId = $presentation->id;
						$symbolName = $presentation->symbol_name;
						echo $presentation->symbol_name; ?></td>					
                        <td style="text-align:center;"><?php 
						if ($presentation->published = '1')
						{echo 'Off';}else
						{echo 'On';}
						 ?></td>
                         <?php } ?>
                                </tr>
                                </tbody>
				</table>
			</div>		