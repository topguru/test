<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class TTools {
	
	/**
	 * This function will redirect the current page to the joomla login page
	 * @param URL $returnurl, after login redirect to this url
	 */
	static function redirectToLogin($returnurl="") {
		$app = JFactory::getApplication();
		$returnurl = base64_encode(TRoute::_($returnurl,false));
		if(version_compare(JVERSION,'1.6.0','>=')){
			//joomla 1.6 format
			$app->redirect( "index.php?option=com_users&view=login&return=$returnurl","");
		} else {
			//joomla 1.5 format
			$app->redirect( "index.php?option=com_user&view=login&return=$returnurl","");
		}
	}
}