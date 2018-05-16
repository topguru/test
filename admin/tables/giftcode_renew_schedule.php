<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode_renew_schedule extends JTable
{
  var $id                 = null;
  var $giftcode_color_id  = null;
  var $created 		        = null;
  var $modified           = null;
  var $sunday             = null;
  var $monday             = null;
  var $tuesday            = null;
  var $wednesday          = null;
  var $thursday           = null;
  var $friday             = null;
  var $saturday           = null;
    
	function __construct(& $db) {
		parent::__construct('#__giftcode_renew_schedule', 'id', $db);
	}
}
?>