<?php

// No direct access
defined('_JEXEC') or die;

class AwardPackageTableUserGroupName extends JTable {

    public function __construct(& $db) {
        parent::__construct('#__ap_usergroup_name', 'id', $db);
    }

}
