<?php
JToolBarHelper::custom( 'packageuser.back_to_message', 'save', 'save', 'Back', false, false );

$mailer =& JFactory::getMailer();


$firstnames  = JRequest::getVar('firstname');

$lastnames   = JRequest::getVar('lastname');

$emails      = JRequest::getVar('email');

$awUser = JRequest::getVar('awuser');

foreach ($awUser as $user){
	$users = $this->itemmodel->getMessageNonAwUser($user);
	$sender = array(
		    'system@award.com',
		    'System Admin'		    
		    );
            
			$emails      = JRequest::getVar('email');
		    $mailer->setSender($sender);

		    $mailer->addRecipient($emails);

		    $mailer->setSubject($users->subject);

		    $mailer->setBody($users->message);

		    $send =& $mailer->Send();
			//var_dump($mailer);

		    if ( $send !== true ) {
		    	echo 'Error sending email:'.$send->get('message');
		    } else {
		    	echo 'Mail sent';
		    }
}
$i = 0;

?>
<h3><?php echo count($awUser); ?> Mail Sent</h3>
<form action="#" method="post" name="adminForm" id="adminForm"><input
	type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="category_id"
	value="<?php echo JRequest::getVar('category_id');?>"> <input
	type="hidden" name="act" value="<?php echo JRequest::getVar('act'); ?>">
<input type="hidden" name="user_id"
	value="<?php echo JRequest::getVar('user_id');?>"> <?php
	foreach($this->firstnames as $firstname):
	if($firstname!=""):
	?> <input type="hidden" name="firstname[]"
	value="<?php echo $firstnames[$i];?>"> <input type="hidden"
	name="lastname[]" value="<?php echo $lastnames[$i];?>"> <input
	type="hidden" name="email[]" value="<?php echo $emails[$i];?>"> <input
	type="hidden" name="id_user"
	value="<?php echo JRequest::getVar('user_id');?>"> <?php
	$i++;
	endif;
	endforeach;
	?> <input type="hidden" name="task" value="" /></form>
