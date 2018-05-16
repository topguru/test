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
class TableRefundpackageslist extends JTable
{
	var $refund_package_list_id		= null;
	var $created                    	= null;
	var $modified                    	= null;

	function __construct(& $db) {
		parent::__construct('#__refund_package_list', 'refund_package_list_id', $db);
	}
        
        function publish($id){
            $db     = JFactory::getDbo();
            $query  = "UPDATE #__refund_package_list ".
                      "SET ".$db->quoteName('state')."='1'".
                      "WHERE ".$db->quoteName('refund_package_list_id')."='".$id."'";
            $db->setQuery($query);
            if($db->query()){
                return true;
            }
        }
        
        function unpublish($id){
            $db     = JFactory::getDbo();
            $query  = "UPDATE #__refund_package_list ".
                      "SET ".$db->quoteName('state')."='0'".
                      "WHERE ".$db->quoteName('refund_package_list_id')."='".$id."'";
            $db->setQuery($query);
            if($db->query()){
                return true;
            }
        }
}
