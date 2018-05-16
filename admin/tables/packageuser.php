<?php 
//restricted
defined('_JEXEC') or die('Restricted access');

class TablePackageUser extends JTable{
	var $id 				= null;
	var $package_id 		= null;
	var $category_id 		= null;
	var $population			= null;
	var $firstname			= null;
	var $lastname			= null;
	var $email				= null;
	var $from_age			= null;
	var $gender				= null;
	var $city				= null;
	var $state				= null;
	var $post_code			= null;
	var $country			= null;
	var $field				= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_user_packages','id', $db);
	}
}
?>