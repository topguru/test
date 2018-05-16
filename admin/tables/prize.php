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

class TablePrize extends JTable
{
	var $id 				= null;
	var $date_created	 	= null;
	var $prize_name 		= null;
	var $prize_value 		= null;
	var $prize_image		= null;
	var $created_by			= null;
	var $desc				= null;
	var $status				= null;

	function __construct(& $db) {
		parent::__construct('#__symbol_prize', 'id', $db);
	}
}
?>