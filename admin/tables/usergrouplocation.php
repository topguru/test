<?php

// No direct access
defined('_JEXEC') or die;

class AwardpackageTableUserGroupLocation extends JTable {

    public function __construct(& $db) {
        parent::__construct('#__ap_usergroup_location', 'id', $db);
    }

}
