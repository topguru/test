<?php

defined('_JEXEC') or die;

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

jimport('joomla.application.component.controller');
require_once( JPATH_COMPONENT.DS.'controller.php');

$controller = JRequest::getWord('controller');

if($controller){
	$controller_path = strtolower($controller);
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller_path.'.php';
	require_once $path;
	$classname    = 'AwardpackageController'.ucfirst($controller);
	$controller   = new $classname( );
}else{
	$controller = JControllerLegacy::getInstance('Awardpackage');
}
JLoader::register('UserGroup', dirname(__FILE__) . DS . 'framework' . DS . 'usergroup.php');
JLoader::register('CountryHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'country.php');
JLoader::register('CommunitySurveysHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'communitysurveys.php');
JLoader::register('ViewdbHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'viewdbhlpr.php');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>