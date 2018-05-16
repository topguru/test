<?php 
//redirect 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableCurrency extends JTable{
	var $id			= null;
	var $code		= null;
	var $currency	= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_currencies', 'id', $db);
	}
}
?>