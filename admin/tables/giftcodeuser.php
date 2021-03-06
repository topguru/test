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

class TableGiftcodeuser extends JTable
{
	var $id = null;
	var $email = null;
	var $name = null;

	function __construct(& $db) {
		parent::__construct('#__giftcode_user', 'id', $db);
	}
}
?>