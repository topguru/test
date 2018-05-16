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

class TableSchedule extends JTable
{
	var $sch_id 		= null;
	var $config_id 		= null;
	//var $scstart_date	 	= '00-00-0000';
	//var $scend_date 		= '00-00-0000';
    var $scstart_date	 	= null;
    var $scend_date 		= null;
    
	function __construct(& $db) {
		parent::__construct('#__gc_schedule', 'sch_id', $db);
	}
}
?>