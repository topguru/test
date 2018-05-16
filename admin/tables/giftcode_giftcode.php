<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_giftcode extends JTable
{
	var $id     = null;    
	var $giftcode_category_id     = null;
    var $giftcode_setting_id     = null;
    var $giftcode = null;
    var $created_date_time = null;
    var $published = null;
    var $giftcode_queue_id = null;
    var $redeemed = null;
    var $giftcode_collection_id = null;

	function __construct(& $db) {
		parent::__construct('#__giftcode_giftcode', 'id', $db);
	}
}
?>