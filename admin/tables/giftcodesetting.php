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

class TableGiftcodesetting extends JTable
{
	var $code_setting_id 		= null;
	var $code_length	 		= null;
	var $allow_to_repeat 		= null;
	var $number_compostion 		= null;
	var $aphabet_composition 	= null;
	var $min_number_of_code		= null;
	var $max_number_of_code 	= null;
	var $color					= null;
	var $gift_code				= null;
	

	function __construct(& $db) {
		parent::__construct('#__gc_code_setting', 'code_setting_id', $db);
	}
}
?>