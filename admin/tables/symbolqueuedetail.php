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

class TableSymbolqueuedetail extends JTable
{
	var $queuedetail_id 	= null;
	var $queue_id			= null;
	var $symbol_pieces_id	= null;
	var $status				= null;
	var $symbol_prize_id	= null;
	

	function __construct(& $db) {
		parent::__construct('#__symbol_queue_detail', 'queuedetail_id', $db);
	}
}
?>