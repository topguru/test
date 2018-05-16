<?php 
//restricted
defined('_JEXEC') or die('Restricted access');

class TableNonApUser extends JTable{
	var $id 				= null;
	var $firstname			= null;
	var $lastname			= null;
	var $email				= null;
	var $subject			= null;
	var $message			= null;
	var $created_date		= null;
	var $modified_date		= null;
	var $package_id			= null;
	var $category_id		= null;
	var $status				= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_non_user_packages','id', $db);
	}
}
?>