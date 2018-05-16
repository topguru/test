<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');

$app = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$headerstuff = $document->getHeadData();
$jpath = JURI::root();
$document->addStyleSheet($jpath . 'templates/beez3/css/personal.css', 'text/css');
$customstyle = "#main-container .panel h3.pane-toggler a {
    padding-bottom: 5px;
    padding-top: 6px;
}";
$document->addStyleDeclaration($customstyle);
?>

<?php
$id = JRequest::getVar('package_id');
$urls ="index.php?option=com_awardpackage&view=pres_user_group&package_id=".$id;
$app->redirect($urls);
echo $this->loadTemplate("left");
echo $this->loadTemplate("right");
echo $this->loadTemplate("javascript");
?>