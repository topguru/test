<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TablePaypal extends JTable{
	
	var $id					= null;
	var $paypal_account		= null;
	var $sandbox_account	= null;
	var $return_url			= null;
	var $notify_url			= null;
	var $is_test 			= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_paypal', 'id', $db);
	}
}
?>