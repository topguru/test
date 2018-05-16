<?php 
//redirect 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableProcessSymbol extends JTable{
	var $id						= null;
	var $prize_value_from		= null;
	var $prize_value_to			= null;
	var $extra_from				= null;
	var $extra_to				= null;
	var $clone_from				= null;
	var $clone_to				= null;
	var $shuffle_from			= null;
	var $shuffle_to				= null;
	var $presentation_id		= null;
	
	function __construct(& $db) {
		parent::__construct('#__ap_symbol_process', 'id', $db);
	}
}
?>