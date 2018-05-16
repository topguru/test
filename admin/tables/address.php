<?php 
//redirect 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableAddress extends JTable{
	var $ap_account_id	= null;
	var $id				= null;
	var $firstname		= null;
	var $lastname		= null;
	var $birtday		= null;
	var $gender			= null;
	var $street			= null;
	var $city			= null;
	var $state			= null;
	var $post_code		= null;
	var $country		= null;
	var $phone			= null;
	var $paypal_account	= null;
	var $package_id		= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_useraccounts', 'ap_account_id', $db);
	}
}
?>