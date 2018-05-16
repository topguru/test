<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_collection_setting extends JTable
{
	var $id 		= null;
	var $code_length	 		= null;
	var $allow_to_repeat 		= null;
	var $number_compostion 		= null;
	var $aphabet_composition 	= null;
	var $min_number_of_code		= null;
	var $max_number_of_code 	= null;
	var $comment					= null;	

	function __construct(& $db) {
		parent::__construct('#__giftcode_collection_setting', 'id', $db);
	}
}
?>