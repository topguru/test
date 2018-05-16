Sending Emails In Progress
<?php
$app = JFactory::getApplication();
$link = 'index.php?option=com_awardpackage&view=package_users&layout=email_sent_select_recipient';
$app->redirect($link, $msg, $msgType='message');
?>