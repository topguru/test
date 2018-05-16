<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once('phpcompat.php');
require_once('route.php');
require_once('pagination.php');
require_once('tools.php');

// Load Paidsystem
if ( file_exists( JPATH_ROOT. "/components/com_paidsystem/api.paidsystem.php")) 
{
	require_once(JPATH_ROOT . "/components/com_paidsystem/api.paidsystem.php");
}

//special override for images
$app = JFactory::getApplication();
$templateDir = JPATH_ROOT . '/templates/' . $app->getTemplate();
if (is_file($templateDir.'/html/com_adsmanager/images/nopic.gif')) {
	define('ADSMANAGER_NOPIC_IMG',JURI::root() . 'templates/' . $app->getTemplate().'/html/com_adsmanager/images/nopic.gif');
} else {
	define('ADSMANAGER_NOPIC_IMG',JURI::root() . 'components/com_adsmanager/images/nopic.gif');
}

// Special config
$db = JFactory::getDBO();
$db->setQuery("SELECT * FROM #__adsmanager_config");
$config = $db->loadObject();
if (isset($config->special))
	define('ADSMANAGER_SPECIAL',$config->special);
else
	define('ADSMANAGER_SPECIAL','');

//Community Builder settings
if (file_exists(JPATH_ROOT.'/components/com_comprofiler/')) {
	define('COMMUNITY_BUILDER',1);
	if (($config->comprofiler == 2)&&(file_exists(JPATH_ROOT.'/components/com_comprofiler/plugin/user/plug_adsmanager-tab'))) {
		define('COMMUNITY_BUILDER_ADSTAB',1);
	} else {
		define('COMMUNITY_BUILDER_ADSTAB',0);
	}
} else {
	define('COMMUNITY_BUILDER',0);
	define('COMMUNITY_BUILDER_ADSTAB',0);
}

//Jquery non conflict mode
$document = JFactory::getDocument();

if ($config->jquery) {
	$document->addScript(JURI::root().'components/com_adsmanager/js/jquery-1.6.2.min.js');
}
$document->addScript(JURI::root().'components/com_adsmanager/js/noconflict.js');
if ($config->jqueryui) {
	$document->addScript(JURI::root().'components/com_adsmanager/js/jquery-ui-1.8.16.custom.min.js');
	$document->addStyleSheet(JURI::root().'components/com_adsmanager/css/ui-lightness/jquery-ui-1.8.16.custom.css');
}