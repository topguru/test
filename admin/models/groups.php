<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class AwardpackageModelGroups extends JModelLegacy {

    function __construct() {
        parent::__construct();
    }

    public function setGroups($package_id = '', $groupName = '', $groupId = '') {
        $ret = NULL;
        if (!empty($package_id) AND empty($groupId)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $columns = array('name', 'package_id');
            $values = array($db->quote($groupName), $db->quote($package_id));
            $query
                    ->insert($db->quoteName('#__ap_groups'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            $ret = $db->insertid();
        }
        return $ret;
    }

    public function getGroups($package_id = '', $groupId = '',$state='') {
        $query = $this->_db->getQuery(true);
        $sql = "SELECT * FROM #__ap_groups where package_id = '" . $package_id . "' AND id='".$groupId."' ";
        if(!empty($state)){
            $sql = "SELECT * FROM #__ap_groups where package_id = '" . $package_id . "' AND id='".$groupId."' "
                    . "AND id NOT IN(SELECT usergroup FROM #__usergroup_presentation WHERE usergroup='".$groupId."')";
        }
        $query = $sql;
        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        return $rs;
    }
    
    public function groupExist($groupId = '') {
        
    }

}
