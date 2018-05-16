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
class Tableshoppingcreditpackagelist extends JTable
{
	var $shopping_credit_package_list_id		= null;
	var $created                    	= null;
	var $modified                    	= null;

	function __construct(& $db) {
		parent::__construct('#__shopping_credit_package_list', 'shopping_id', $db);
	}
        
        function publish($id){
            $db     = JFactory::getDbo();
            $query  = "UPDATE #__shopping_credit_package_list ".
                      "SET ".$db->quoteName('published')."='1'".
                      "WHERE ".$db->quoteName('shopping_id')."='".$id."'";
            $db->setQuery($query);
            if($db->query()){
                return true;
            }
        }
        
        function unpublish($id){
            $db     = JFactory::getDbo();
            $query  = "UPDATE #__shopping_credit_package_list ".
                      "SET ".$db->quoteName('published')."='0'".
                      "WHERE ".$db->quoteName('shopping_id')."='".$id."'";
            $db->setQuery($query);
            if($db->query()){
                return true;
            }
        }
}
