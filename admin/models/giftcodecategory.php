<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardpackageModelGiftcodeCategory extends JModelLegacy {

    var $_data;

    function __construct($config = array()) {
        parent::__construct($config);
        $this->_db = JFactory::getDBO();
    }

    function getData() {
        if (empty($this->_data)) {
            $this->_data = $this->_getList("SELECT * FROM #__giftcode_category ORDER BY id");
        }
        return $this->_data;
    }

    function getDataDetail($gcid) {
        if (empty($this->_data)) {
            $this->_data = $this->_getList("SELECT * FROM #__giftcode_category  WHERE id = '" . $gcid . "'");
        }
        return $this->_data;
    }

    function published($id) {
        //$db =& JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__giftcode_category'));
        $query->set("published='1'");
        $query->where("id='$id'");
        //$query = "UPDATE #__giftcode_category SET published='1' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        }
    }

    function edit($id) {
        //$db =& JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__giftcode_category'));
        $query->set("locked='0'");
        $query->where("id='" . $id . "'");
        //$query = "UPDATE #__giftcode_category SET locked='0' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        }
    }

    function save($id, $name, $color_code) {
        //$db =& JFactory::getDBO();
        $q_check = $this->_db->getQuery(TRUE);
        $q_check->select('*');
        $q_check->from($this->_db->QuoteName('#__giftcode_category'));
        $q_check->where("id='$id'");
        $q_check->where("locked='0'");
        //$q_check = "SELECT * FROM #__giftcode_category WHERE id='$id' AND locked='0'";
        $this->_db->setQuery($q_check);
        $this->_db->query();
        $numRows = $this->_db->getNumRows();
        if ($numRows > 0):
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("locked='1'");
            $query->where("id='$id'");
            //$query = "UPDATE #__giftcode_category SET locked='1' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("name='$name'");
            $query->where("id='$id'");
            //$query = "UPDATE #__giftcode_category SET name='".$name."' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("color_code='" . $color_code . "'");
            $query->where("id='$id'");
            //$query = "UPDATE #__giftcode_category SET color_code='".$color_code."' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
        endif;
    }

    function unpublished($id) {
        //$db = & JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->QuoteName('#__giftcode_category'));
        $query->set("published='0'");
        $query->where("id='$id'");
        //$query = "UPDATE #__giftcode_category SET published='0' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        }
    }

    function getlast() {
        $id = $this->_db->insertid();
        return $id;
    }

}