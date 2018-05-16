<?php	
	
    $firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
    
    $lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
    
    $emails      	= JRequest::getVar('email',array(0), 'post', 'array');
		
    $varFirstname	="";
		
    $varLastname	="";
		
    $varEmail	        ="";
		
    $i          	= 0;
	//echo "add   ".$_SERVER['REMOTE_ADDR']."</br>";
    foreach($firstnames as $firstname){
			
		if($firstname!=""){
				
			$varFirstname.="&firstname[]=".$firstname;
				
			$varLastname.="&lastname[]=".$lastnames[$i];
				
			$varEmail.="&email[]=".$emails[$i];
			
		}
			
		$i++;
    }
    $mailer =& JFactory::getMailer();
    
    $config =& JFactory::getConfig();
    
    $sender = array(
                    
                $config->get( 'config.mailfrom' ),
                
                $config->get( 'config.fromname' )
    );
 
    $mailer->setSender($sender);
    
    $emails     = JRequest::getVar( 'email', array(0), 'post', 'array' );
    
    $cids       = JRequest::getVar( 'awuser','array(0)','post','array');
    
    $i          = 0;
    
    $ip         = $_SERVER['REMOTE_ADDR'];
   
    foreach($emails as $email){
        
        $mailer->addRecipient($email);
        
        $msg_config = $this->ug->getMessageNonAwUser($cids[$i]);
        
        $app = JFactory::getApplication();
        
        if($this->ug->CheckUser($email)>0){
        	
            
            JError::raiseWarning( 100, 'Error : '.$email.' allready registered ' );
            
            $app->redirect('index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.JRequest::getVar('package_id').'&category_id=1'.$varFirstname.$varLastname.$varEmail.'&act='.JRequest::getVar('act'));
        
        }else{
            if($email==""){
              
                JError::raiseWarning( 100, 'Error : Email not be empty');
                
               $app->redirect('index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.JRequest::getVar('package_id').'&category_id=1'.$varFirstname.$varLastname.$varEmail.'&act='.JRequest::getVar('act'));
            
            }
            
            if($email!=""){
            
                $mailer->addRecipient($email);
                
                $mailer->setSubject($msg_config->subject);
                
                $mailer->isHTML(true);
                
                $mailer->Encoding = 'base64';
                
                $message_body='<b>Message :</b><br/>';
                
                $message_body.=$msg_config->message.'<br/>';

                $message_body.= 'Click link below to continue registering,  if you can not click copy and paste in your browser<br/>';
                        
                $message_body.= '<a href="'.JURI::root().'index.php?option=com_users&view=registration" target="_blank">'.JURI::root().'index.php?option=com_users&view=registration</a>';
                
                $mailer->setBody($message_body);
                
                $send =& $mailer->Send();
                
                 if($ip!='127.0.0.1'){
                                
                    if ( $send !== true ) {
                                    
                            echo 'Error sending email: '.$send->get('message');
                                
                    }  
                }else{
                    echo $email.'<br/>';
                    echo $message_body.'<br/>';
                }
				$data = array();
				$data['firstname']=$firstnames[$i];
				$data['lastname']=$lastnames[$i];
				$data['subject']=$msg_config->subject;
				$data['email']=$email;
				$data['message']=$msg_config->message;
				//$update = $this->ug->updateNonAwUser($cids[$i],$data);
            }
        }
        
        $i++;
    }
    if($send){
         //echo '<h2>'.count($emails).' Mail sent</h2>';
        JError::raiseWarning( 100, count($email).' Mail Sent ' );
            
            $app->redirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.$this->package_id);
     
    }
?>