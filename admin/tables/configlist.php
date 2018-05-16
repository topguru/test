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

class TableConfiglist extends JTable
{
	var $config_id 		= null;
	var $start_date	 	= '00-00-0000';
	var $end_date 		= '00-00-0000';

	function __construct(& $db) {
		parent::__construct('#__gc_configlist', 'config_id', $db);
	}
}
?>