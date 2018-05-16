<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_collection extends JTable
{
	var $id                    = null;
	var $created_date_time     = null;
	var $modified_date_time    = null;	
	var $published    = null;	    

	function __construct(& $db) {
		parent::__construct('#__giftcode_collection', 'id', $db);
	}
}
?>