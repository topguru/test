<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableArchive extends JTable {

    var $awardpackage = null;
    var $date_created = null;
    var $date_archived = null;
    var $number_of_user = null;
    var $number_of_prize = null;
    var $package_id = null;

    function __construct(& $db) {
        parent::__construct('#__ap_award_archive', 'id', $db);
    }

}

?>
