<?php 
//redirect 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TablePayment extends JTable{
	var $id				= null;
	var $option			= null;
	var $date_created	= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_payment_options', 'id', $db);
	}
}
?>