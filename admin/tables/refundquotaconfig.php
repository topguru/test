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
class TableRefundquotaconfig extends JTable
{
	var $refund_quota_id		= null;
	var $refund_quota     = null;
        var $start_date     = null;
        var $end_date     = null;
        var $refund_claim_date     = null;
        var $refund_package_id     = null;
        var $rcc_price_value_above     = null;
        var $rcc_dollar_amount     = null;
        var $rcc_percentage_amount     = null;

	function __construct(& $db) {
		parent::__construct('#__refund_quota', 'refund_quota_id', $db);
	}
       
}
