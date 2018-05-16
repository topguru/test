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

class TableFilter extends JTable
{
	var $filterid 	= null;
	var $sch_id 	= null;
	var $name	 	= '';
	var $value 		= '';

	function __construct(& $db) {
		parent::__construct('#__gc_filter', 'filterid', $db);
	}
}
?>