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

class TableSymboluser extends JTable
{
	var $user_id 	= null;
	var $name	 	= null;
	var $email 		= null;
	var $address 	= null;
	var $city		= null;
	var $state 		= null;
	var $country	= null;
	var $phone 		= null;
	var $username 	= null;
	var $ordering 	= null;
	var $passkey 	= null;

	function __construct(& $db) {
		parent::__construct('#__symbol_user', 'user_id', $db);
	}
}
?>