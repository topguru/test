Sending Emails In Progress
<?php
$app = JFactory::getApplication();
$link = 'index.php?option=com_awardpackage&view=package_users&layout=emails_sent_non_award';
$app->redirect($link, $msg, $msgType='message');
?>