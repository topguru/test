<?php

// No direct access
defined('_JEXEC') or die;

class AwardpackageTableUserGroupEmail extends JTable {

    public function __construct(& $db) {
        parent::__construct('#__ap_usergroup_email', 'id', $db);
    }

}
