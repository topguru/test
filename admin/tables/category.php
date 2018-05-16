<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableCategory extends JTable
{
	var $id                    = null;
	var $name                  = null;
	var $image                 = null;
	var $description           = null;
	var $published             = null;
	var $symbol_pieces_award   = null;
	var $created_date_time     = null;

	function __construct(& $db) {
		parent::__construct('#__giftcode_category', 'id', $db);
	}
}
?>