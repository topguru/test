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
class TableShoppingrecipientgroup extends JTable
{
	var $criteria_id		= null;
        var $package_id                 = null;
        var $population                 = null;
        var $firstname                  = null;
        var $lastname                   = null;
        var $email                      = null;
        var $from_age                   = null;
        var $to_age                     = null;
        var $gender                     = null;
        var $street                     = null;
        var $city                       = null;
        var $state                      = null;
        var $post_code                  = null;
        var $country                    = null;
        var $field                      = null;
        var $package_list_id            = null;
	
	function __construct(& $db) {
		parent::__construct('#__shopping_usergroup', 'criteria_id', $db);
	}
        
}
