<?php
/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * refundpackagelist Table class
 */
class TableRefundpackage extends JTable
{
	var $refund_package_id		= null;
	var $refund_package_list_id     = null;

	function __construct(& $db) {
		parent::__construct('#__refund_package', 'refund_package_id', $db);
	}
       
}
