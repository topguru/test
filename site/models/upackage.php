<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
class AwardpackageUsersModelUpackage extends JModelLegacy {
	
	function __construct() {
		parent::__construct ();
	}
	
	function allPackages(){
		$query = "select package_id, package_name from #__ap_awardpackages";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function updatePackageForUser($package_id){
		if(isset($_SESSION['login__'])){
			$login__ = $_SESSION['login__'];
			$userId = $login__->ap_account_id;
			$db 	= JFactory::getDbo();
			$query = "update #__ap_useraccounts set package_id = '".$package_id."' where ap_account_id = '".$userId."' ";
			$db->setQuery($query);
			if ($db->query()) {
				return true;
			}					
		}
		return false;
	}
}