<?php
    JToolbarHelper::addNew('packageuser.nonapuser','New');
    JToolbarHelper::custom('packageuser.nonapsynmessage',"copy","copy",'Sync Message');
    JToolbarHelper::deleteList('Are you sure ?','packageuser.nonapuserdelete','Delete');
	//JToolbarHelper::cancel('packageuser.nonapuserclose','Delete');
	JToolbarHelper::custom('packageuser.save',"copy","copy",'Save');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
    function goMessage(i){
        var firstname2="";
        var lastname2="";
        var email2="";
        var package_id = document.getElementById('package_id').value;
        var category_id = document.getElementById('category_id').value;
        var aw_user = document.getElementById('aw_user'+i).value;
        var act = document.getElementById('act').value;
        if(i>0){
            for(j=0;j<=i;j++){
                var firstname=document.getElementById('firstname'+j).value;
                var lastname=document.getElementById('lastname'+j).value;
                var email=document.getElementById('email'+j).value;
                firstname2=firstname2+'&firstname[]='+firstname;
                lastname2=lastname2+'&lastname[]='+lastname;
                email2=email2+'&email[]='+email;
            }
        }else{
                var firstname=document.getElementById('firstname'+i).value;
                var lastname=document.getElementById('lastname'+i).value;
                var email=document.getElementById('email'+i).value;
                firstname2=firstname2+'&firstname[]='+firstname;
                lastname2=lastname2+'&lastname[]='+lastname;
                email2=email2+'&email[]='+email;
        }
        location.href='index.php?option=com_awardpackage&view=packageuser&layout=new_message_non_award&package_id='+package_id+'&category_id='+category_id+'&user_id='+aw_user+firstname2+lastname2+email2+'&act=' + act;
    }
</script>
<?php

    $firstname = JRequest::getVar('firstname');
    $lastname  = JRequest::getVar('lastname');
    $email     = JRequest::getVar('email');
    if ($firstname!=""){
    	
    }
  
?>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=packageuser&layout=email_sent');?>" name="adminForm"  id="adminForm" class="form-validate">
  <input type="hidden" name="act" id="act" value="<?php echo $this->act; ?>">
  <div style="float: right;">
    <input type="submit" value="<?php echo JText::_('Send');?>">
  </div>
  <br />
  <br />
  <br />
  <table border='0' class="adminlist" cellpadding="1" cellspacing="1" width="75%" class="table table-striped">
    <thead>
      <tr class="table table-striped">
        <th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
        <th><?php echo JText::_('First Name');?></th>
        <th><?php echo JText::_('Last Name');?></th>
        <th><?php echo JText::_('Email');?></th>
        <th><?php echo JText::_('Message');?></th>
        <th><?php echo JText::_('Created');?></th>
        <th><?php echo JText::_('Modified');?></th>
      </tr>
    </thead>
    <tbody>
      <?php
                $i=0;
                
                if($this->ug->getNonapUserPackage($this->package_id,$this->category_id)>0):
                	
                    foreach($this->ug->getNonapUserPackage(JRequest::getVar('package_id'),$this->category_id) as $nonAwUser):
                            if($nonAwUser->user_id==0){
                                $totAp = count($this->ug->getNonapUserPackage($this->package_id,JRequest::getVar('category_id')));
                               
								if($nonAwUser->status==0){ // if message not have sending 
									
            ?>
                                  <tr class="row<?php echo $i % 2;?>">
                                    <td width="1" align="center"><?php
                                                echo JHtml::_('grid.id', $i, $nonAwUser->id);
                                                                echo'<input type="hidden" name="awuser[]" value="'.$nonAwUser->id.'">';
                                            ?>
                                    </td>
                                    <td align="center"><input type="text" name="firstname[]" id="firstname<?php echo $i;?>" value="<?php echo $nonAwUser->firstname;?>"></td>
                                    <td align="center"><input type="text" name="lastname[]" id="lastname<?php echo $i;?>" value="<?php echo $nonAwUser->lastname;?>"></td>
                                    <td align="center"><input type="text" name="email[]" class="validate-email inputbox required" id="email<?php echo $i;?>" value="<?php echo $nonAwUser->email;?>"></td>
                                    <td align="center">
									<?php
                                         if($nonAwUser->message==""){
                                         	$label = 'New';
                                         }else{
                                         	$label = 'Edit';
                                         }
                                      ?>
                                    <a href="#" onclick="goMessage(<?php echo $i;?>);"> <?php echo $label;?> </a> </td>
                                    <td align="center"><?php echo $nonAwUser->created_date;?>
                                      <input type="hidden" name="aw_user" id="aw_user<?php echo $i;?>" value="<?php echo $nonAwUser->id;?>">
                                    </td>
                                    <td align="center"><?php echo $nonAwUser->modified_date;?></td>
                                  </tr>
      <?php
                            $i++;
                            }
						}//end if not sending
                    endforeach;
                endif;
           ?>
    </tbody>
  </table>
  <input type="hidden" name="package_id" value="<?php echo $this->package_id;?>" id="package_id">
  <input type="hidden" name="category_id" value="<?php echo $this->category_id;?>" id="category_id">
  <input type="hidden" name="boxchecked" value="1" />
  <input type="hidden" name="task" value="1" />
</form>
