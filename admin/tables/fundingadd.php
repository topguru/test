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

class TableFundingadd extends JTable
{
	var $revenue_id 		= null;
	var $funding_id		 	= null;
	var $revenue_percentage		= null;
	var $revenue_fromprize 		= null;
	var $revenue_toprize		= null;
	var $revenue_strategy		= null;

	function __construct(& $db) {
		
		parent::__construct('#__funding_revenue', 'revenue_id', $db);
	
	}
}
?>