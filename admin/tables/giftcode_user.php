<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_user extends JTable
{
	var $id = null;
	var $name	 		= null;
	var $email 		= null;

	function __construct(&$db) {
		parent::__construct('#__gifcode_user', 'id', $db);
	}
}
?>