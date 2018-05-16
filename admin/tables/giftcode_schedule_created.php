<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_schedule_created extends JTable
{
  var $id                 = null;
  var $created_date 		  = null;
  var $color_id           = null;
    
	function __construct(& $db) {
		parent::__construct('#__giftcode_schedule_created', 'id', $db);
	}
}
?>