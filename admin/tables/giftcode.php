<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

 defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableGiftcode extends JTable
{
	var $gift_code_id 		= null;
	var $code	 			= null;
	var $date_created 		= null;
	var $publish_setting_id = null;
	var $code_setting_id	= null;
    var $active             = null;
    var $time_last_fired    = '0000-00-00 00:00:00';
    var $sch_id             = null;

	function __construct(& $db) {
		parent::__construct('#__gc_gift_code', 'gift_code_id', $db);
	}
}
?>