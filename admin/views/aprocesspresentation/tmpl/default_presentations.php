					<div style="height:auto;padding:0 0 10px;">
                    <nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Selected presentation</a></li>
                              <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundPrizePlan&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund prize plan - selected prizes</a></li>
	                          <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundReceiverList&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund receiver list queue</a></li>    	
                              </ul>
                              </nav>	
                              					
					<table width="100%">                    
                    <tr>
                   	<td valign="top">
						<table class="table table-striped table-hover table-bordered">  
                                <thead>                              	
                                <tr>
		                                <th colspan="2"><span>Create Process Presentation</span>
        		                        <span style="float:right;padding:0 0 0 10px;">
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewProcessTitle();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Add');?></button>
                        		        </span>
                                        </th>
                                	</tr>                           	
                                    <tr style="height:55px;">
		                                <th style="text-align:center;">Title
        		                        </th>
                		                <th style="text-align:center;">Start fund prize <br/>when award funds plan above
                        		        </th>
                                	</tr></thead>
											<?php 
												foreach ($this->presentations as $presentation){
													$title = $presentation->name;
													$fund_amount = $presentation->fund_amount;
													}
												$idProses = (!empty(JRequest::getVar('idProcess')) ? JRequest::getVar('idProcess') : $title );
												$idProsesValue = (!empty(JRequest::getVar('idProcessValue')) ? JRequest::getVar('idProcessValue') : $fund_amount );
												
											?>                                    
                                      <tbody><tr>
	                               <td class="hidden-phone">
                                   <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.create_update&package_id='.JRequest::getVar('package_id').'&process_id='.$presentation->id)?>">  <?php echo (!empty($idProses) ? $idProses : 'Create' );?></a>
                                   </td>
                		           <td>
								   <?php echo (!empty($idProsesValue ) ? $idProsesValue  : 0 );?>
                      <input type="hidden" name="idProcess" id="idProcess" value="<?php echo (!empty($idProses) ? $idProses : 0 );?>">
                      <input type="hidden" name="idProcessValue" id="idProcessValue" value="<?php echo (!empty($idProsesValue) ? $idProsesValue : 0 );?>">
                        		        </td>
                                	</tr>  
                                    </tbody>
                                </table>
					</td>

                   <td valign="top">
						<table class="table table-striped table-hover table-bordered">  
                                <thead>
                                <tr>
		                                <th colspan="2"><span>Add presentation/prize from presentation list</span>
        		                        <span style="float:right;padding:0 0 0 10px;">
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewProcess_1();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Add');?></button>
                        		        </span>
                                        </th>
                                	</tr>                           	
                                    <tr style="height:55px;">
		                                <th style="text-align:center;">Presentation
        		                        </th>
                		                <th style="text-align:center;">Prizes
                        		        </th>
                                	</tr>
                                    </thead>
                                    <tbody>
                                     <tr>
                                   <?php $prizeCount = (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0  ? count($this->PrizeSelected)  : 0 ); ?>
		                                <td> <span style="text-decoration:underline;cursor:pointer" id="idPresentation" onclick="openModalPresentation(<?php echo $prizeCount;?>);"><?php echo (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0  ? count($this->PrizeSelected)  : 0 ); ?></span></td>                                        
                                       
                		                <td><span id="idPrizePres">
<?php echo (!empty($this->PrizeSelected) && count($this->PrizeSelected) > 0  ? count($this->PrizeSelected)  : 0 ); ?>                                        </span>
                                          
                               <input type="hidden" name="idPresID" id="idPresID" value="<?php echo $presentation->selected_presentation;?>">

                        		        </td>
                                	</tr>
                                    </tbody>
                                </table>
					</td>

                    <td valign="top">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	
                                <thead>
                                <tr>
		                                <th colspan="2"><span>Add funds prize plan</span>
        		                        <span style="float:right;padding:0 0 0 10px">
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewProcess_2();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Add');?></button>
                        		        </span>
                                        </th>
                                	</tr>                           	
                                    <tr style="height:55px;">
		                                <th style="text-align:center;">Fund prize plan
        		                        </th>
                		                <th style="text-align:center;">Funding value
                        		        </th>
                                	</tr>
                                    </thead>
                                    <tbody>
                                    <tr>
		                                <td><span style="text-decoration:underline;cursor:pointer" id="idPrizeName" onclick="openModalPrize();"><?php echo (!empty($presentation->fund_prize_plan) ? $presentation->fund_receiver_name : 0 );?></span>
        		                        </td>
                		                <td><span id="idPrizeValuefrom"><?php 
										echo $presentation->funding_value_from;?></span> To
                                        <span id="idPrizeValueto">
										<?php echo $presentation->funding_value_to ;?></span>
                                        <input type="hidden" name="idFundPrizePlan" id="idFundPrizePlan" value="">

                        		        </td>
                                	</tr>
                                    </tbody>
                                </table>
					</td>

                    <td valign="top">
								<table class="table table-striped table-hover table-bordered" width="100%">                                	
                                <thead>
                                <tr>
		                                <th colspan="2"><span>Select at least one prize to add</span>
        		                        <span style="float:right;padding:0 0 0 10px">
											<button type="button" class="btn btn-primary btn-invite-reg-groups"
												onclick="onAddNewProcess_4();" id="addNewProcessBtn"><i></i> <?php echo JText::_('Add');?></button>
                        		        </span>
                                        </th>
                                	</tr>                           	
                                    <tr style="height:55px;">
		                                <th style="text-align:center;">Fund receiver plan
        		                        </th>
                		                <th style="text-align:center;">Limited receiver
                        		        </th>
                                	</tr>
                                    </thead>
                                    <tbody>
                                    <tr>
		                                <td><span style="text-decoration:underline;cursor:pointer" id="idReceiverName" onclick="openModalReceiver();"><?php echo (!empty($presentation->fund_receiver) ? $presentation->title : 0 );?></span>
        		                        </td>
                		                <td><span id="idReceiverLimit"><?php echo (!empty($presentation->limit_receiver) ? $presentation->limit_receiver : 0 );?></span> 
                                        <input type="hidden" name="idReceiverID" id="idReceiverID" value="">
                                        
                        		        </td>
                                	</tr>
                                    </tbody>
                                </table>
					</td>

                   
                    </tr>
                    </table>
																
					</div>
		
			
		
