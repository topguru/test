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

class TableAwardsymbol extends JTable
{
	var $symbol_id 		= null;
	var $date_created	= null;
	var $symbol_name 	= null;
	var $symbol_image 	= null;
	var $pieces			= null;
	var $rows			= null;
	var $cols			= null;

	function __construct(& $db) {
		parent::__construct('#__symbol_symbol', 'symbol_id', $db);
	}
}
?>