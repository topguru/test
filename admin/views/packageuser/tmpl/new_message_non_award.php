<?php
	JToolBarHelper::custom( 'packageuser.back_to_message', 'save', 'save', 'Back', false, false );
    JToolbarHelper::save('packageuser.messagesave','Save');    
    JToolbarHelper::cancel('packageuser.messageclose','Close');    
    $firstnames  = JRequest::getVar('firstname');    
    $lastnames   = JRequest::getVar('lastname');    
    $emails      = JRequest::getVar('email');    
    $i           = 0;
    foreach($this->ug->getUserPackages() as $userPackage){
        
        if(JRequest::getVar('user_id')==$userPackage->id){
            
            $msg= $userPackage->message;
            
            $subject = $userPackage->subject;
        }
        
    }
?>
<form action="#" method="post" name="adminForm" id="adminForm">
    <table width="100%">
        <tbody>
            <td width="150"><?php echo JText::_('Message Subject');?></td>
            <td width="10">:</td>
            <td>
                <input type="text" size="100" name="subject" value="<?php echo $subject;?>"/>
                <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
                <input type="hidden" name="category_id" value="<?php echo JRequest::getVar('category_id');?>">
                <input type="hidden" name="user_id" value="<?php echo JRequest::getVar('user_id');?>">
                <input type="hidden" name="act" value="<?php echo JRequest::getVar('act'); ?>">
                <?php
                    foreach($firstnames as $firstname):
                        if($firstname!=""):
                        ?>
                            <input type="text" name="firstname[]" value="<?php echo $firstnames[$i];?>">
                            <input type="text" name="lastname[]" value="<?php echo $lastnames[$i];?>">
                            <input type="text" name="email[]" value="<?php echo $emails[$i];?>">
                            <input type="text" name="id_user" value="<?php echo JRequest::getVar('user_id');?>">
                        <?php
                            $i++;
                        endif;
                    endforeach;
                ?>
            </td>
        </tbody>
    </table>
    
        <br/>
         <?php 
     
echo $this->editor->display("body", $msg, "15", "3", "25", "10", 10, null, null, null, array('mode' => 'extended'));?> 

       
      
        <div>
                <input type="hidden" name="id" value="<?php echo JRequest::getVar( 'id', '','get', 'string', JREQUEST_ALLOWRAW );?>">
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
 </form>

