<?php
 
 defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcodefree extends JTable
{
	var $id 		= null;
	var $name	 			= null;
	var $intervalvalue 		= null;
	var $weekdayvalue = null;
	var $calendarvalue	= '00-00-0000';
    var $freeby =null;
    var $gcfreequantity =0;
    var $sch_id =null;
    var $cat_id =null;

	function __construct(& $db) {
		parent::__construct('#__gc_free', 'id', $db);
	}
}
?>