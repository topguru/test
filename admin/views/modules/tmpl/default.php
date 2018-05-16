<div style="border: 2px solid grey; padding: 2px; width: 200px">
    <b>Award Package : <?php echo $this->package_name; ?></b>
</div>
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Donation</h1>
    <i>( Total donors :  <?php echo count($this->actionModel->getDonors($this->package_id)); ?>)</i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=donationlist&package_id=' . JRequest::getVar('package_id')); ?>"> Donation Category </a></td>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=transaction&package_id=' . JRequest::getVar('package_id')); ?>">Transaction List</a></td>
                </tr>                
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=donorlist&package_id=' . JRequest::getVar('package_id')); ?>">Donor List</a></td>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=donation&package_id=' . JRequest::getVar('package_id') . '&task=settings'); ?>">Donor Settings</a></td>                                    
                </tr>        
            </table>
        </center>                        
    </div>
</div>
<div style="padding: 20px;"></div>
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Giftcode</h1>
    <i>( Total Giftcodes : <?php echo count($this->actionModel->getGiftcodes($this->package_id)); ?>)</i>
    <div style="padding: 10px;">
        <?php
        $color = $this->model->getGiftCode(JRequest::getVar('package_id'));
        ?>
        <center>
            <table>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcode&package_id=' . JRequest::getVar('package_id')); ?>">Giftcode Category</a></td>                                    
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcodecode&layout=create&color=' . $color->id . '&package_id=' . JRequest::getVar('package_id')); ?>">Create Giftcode Collection</a></td>                                    
                </tr>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcodecode&package_id=' . JRequest::getVar('package_id')); ?>">Giftcode Collection List</a></td>                                    
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=queue&package_id=' . JRequest::getVar('package_id')); ?>">Giftcode Queue List</a></td>                                                                                                        
                </tr>                            
            </table>                        
        </center>
    </div>
</div>                
<div style="padding: 20px;"></div>
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Refund & Refund Quota</h1>
    <i>(Total Refund & Refund Quota : )</i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=refundpackageslist&package_id='.JRequest::getVar('package_id'));?>">Refund Package List</a></td>
                </tr>
            </table>
        </center>
    </div>
</div>                                
<div style="padding: 20px;"></div>
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1>Analystics System</h1>
    
    <div style="padding: 10px;">
        <center>
            
        </center>
    </div>
</div>  
<div style="padding: 20px;"></div>
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1>Online Shopping Credit System</h1>
    
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditpackagelist&package_id='.JRequest::getVar('package_id'));?>">Shopping credit package</a></td>
                </tr>
            </table>
        </center>
    </div>
</div>                                
<div style="padding: 20px;"></div>                
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Award</h1>
    <i>( Total Prizes : <?php echo count($this->actionModel->getPrize($this->package_id)); ?>)</i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=presentationList&package_id=' . JRequest::getVar('package_id')); ?>">Presentation List</a></td>                                                                                                        
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardsymbol&package_id=' . JRequest::getVar('package_id')); ?>">Award Symbol List</a></td>                                                                                                        
                </tr>
                <tr>                                    
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&package_id=' . JRequest::getVar('package_id')); ?>">Prize List</a></td>                                                                                                        
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&package_id=' . JRequest::getVar('package_id')); ?>">Symbol Queue List</a></td>                                                                                                                                            
                </tr>
                <tr>
                    <td><a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&package_id=' . JRequest::getVar('package_id')); ?>">User Record List</a></td>                                                                                                        
                    <td>
                        <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardprogress&package_id=' . JRequest::getVar('package_id')); ?>">Symbol Progress</a>
                        </td>                                    
                </tr>
                <tr>
                    <td>
                    	<a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&layout=claim_list&package_id='.JRequest::getVar('package_id'));?>">Prize Claim List</a>
                    </td>                                                                                                        
                </tr>                                
            </table>                        
        </center>                                                
    </div>
</div>          
 
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Polls</h1>
    <!-- (Total polls : <?php echo count($this->actionModel->getPolls($this->package_id)); ?>)</i> --><i>
    (Total polls : </i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a href="index.php?option=com_awardpackage&view=boxen&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent">Poll List</a></td>                                                                                                        
                    <td>&nbsp;&nbsp;<a href="index.php?option=com_awardpackage&view=answers&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent">Answers</a></td>                                                                                                        
                </tr>
                <tr>
                    <td><a href="index.php?option=com_awardpackage&view=comments&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent"><?php echo JText::_('Comments');?></a></td>
                </tr>
            </table>                        
        </center>                                                
    </div>
</div>   
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Ads</h1>
    <!-- <i>(Total Ads :<?php echo count($this->actionModel->getAds($this->package_id)); ?>)</i> -->
    <i>(Total Ads : </i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a href="index.php?option=com_awardpackage&view=adadmin&layout=listcontents&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent">Ads manager</a></td>                                                                                                        
                    <td><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=adadmin&layout=listcategories&package_id='.JRequest::getVar('package_id'));?>" target="_parent"><?php echo JText::_('Ad Categories');?></a></td>                                                                                                        
                </tr>  
                <tr>
                    <td><a href="index.php?option=com_awardpackage&view=adadmin&layout=configuration&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent"><?php echo JText::_('Configurations');?></a></td>                                                                                                        
                    <td>&nbsp;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=adadmin&layout=listfields&package_id='.JRequest::getVar('package_id'));?>" target="_parent"><?php echo JText::_('Fields');?></a></td>                                                                                                        
                </tr>  
            </table>                        
        </center>                                                
    </div>
</div>  
<div style="text-align:center; border: 2px solid gray; margin-top: 20px;">
    <h1 style="margin: 0;padding: 0;">Deposit</h1>
    <?php 
        $depositModel =& JModel::getInstance('deposit','AwardpackageModel');
    ?>
    <i>(Total Deposit :<?php echo count($depositModel->getDeposit());?>)</i>
    <div style="padding: 10px;">
        <center>
            <table>
                <tr>
                    <td><a href="index.php?option=com_awardpackage&view=deposit&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent">View Deposit</a></td>                                                                                                        
                    <td><a href="index.php?option=com_awardpackage&view=deposit&layout=transaction&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent">View Transaction</a></td>                                                                                                        
                </tr>                         
            </table>                        
        </center>                                                
    </div>
</div>        