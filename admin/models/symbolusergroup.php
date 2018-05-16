<?php

/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Refund model.
 */
class AwardPackageModelSymbolusergroup extends JModelAdmin {

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_AWARDPACKAGE';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
	public function __construct($config = array()) {
        parent::__construct($config);
        $this->symbol_pricing_id = JRequest::getVar('symbol_pricing_id');
        $this->package_id = JRequest::getVar('package_id');
		$this->_db = JFactory::getDbo();
    }
	
    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_awardpackage.refundpackagelist', 'refundreciepentgroup', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        $id = JRequest::getVar('id');
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_pricing_usergroup WHERE " . $db->quoteName('criteria_id') . "='" . $id . "'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        if ($rows) {
            foreach ($rows as $k => $row) {
                $data[$k] = $row;
            }
        }
        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since	1.6
     */
    protected function prepareTable(&$table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__refund_package');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function getDataField($field, $symbol_pricing_id) {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__symbol_pricing_usergroup WHERE " . $db->quoteName('field') . "='" . $field . "' AND " .
                $db->quoteName('symbol_pricing_id') . "='" . $symbol_pricing_id . "' ORDER BY criteria_id DESC";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function save($data) {
        $db = &JFactory::getDBO();
        if (!$data['creteria_id']) {
            $query = "INSERT INTO #__symbol_pricing_usergroup (" . $db->QuoteName('package_id') . "," .
                    $db->QuoteName('population') . "," . $db->QuoteName('firstname') . "," . $db->QuoteName('lastname') . "," . $db->QuoteName('field') . "," .
                    $db->QuoteName('email') . "," .
                    $db->QuoteName('from_age') . "," .
                    $db->QuoteName('to_age') . "," .
                    $db->QuoteName('gender') . "," .
                    $db->QuoteName('street') . "," .
                    $db->QuoteName('city') . "," .
                    $db->QuoteName('state') . "," .
                    $db->QuoteName('post_code') . "," .
                    $db->QuoteName('symbol_pricing_id') . "," .
                    $db->QuoteName('country') .
                    ") VALUES ('" .
                    $data['package_id'] . "','" . $data['population'] . "','" . $data['firstname'] . "','" .
                    $data['lastname'] . "','" . $data['field'] . "','" . $data['email'] . "','" . $data['from_age'] . "','" . $data['to_age'] .
                    "','" . $data['gender'] . "','" . $data['street'] . "','" . $data['city'] . "','" . $data['state'] . "','" . $data['post_code'] . "','" . $data['symbol_pricing_id'] . "','" . $data['country'] . "')";
        } else {
            $query = "UPDATE #__symbol_pricing_usergroup SET" .
                    $db->QuoteName('package_id') . "='" . $data['package_id'] . "'," .
                    $db->QuoteName('population') . "='" . $data['population'] . "'," .
                    $db->QuoteName('firstname') . "='" . $data['firstname'] . "'," .
                    $db->QuoteName('lastname') . "='" . $data['lastname'] . "'," .
                    $db->quoteName('email') . "='" . $data['email'] . "'," .
                    $db->quoteName('from_age') . "='" . $data['from_age'] . "'," .
                    $db->QuoteName('to_age') . "='" . $data['to_age'] . "'," .
                    $db->QuoteName('gender') . "='" . $data['gender'] . "'," .
                    $db->QuoteName('street') . "='" . $data['street'] . "'," .
                    $db->QuoteName('city') . "='" . $data['city'] . "'," .
                    $db->QuoteName('state') . "='" . $data['state'] . "'," .
                    $db->QuoteName('post_code') . "='" . $data['post_code'] . "'," .
                    $db->QuoteName('symbol_pricing_id') . "='" . $data['symbol_pricing_id'] . "'," .
                    $db->quoteName('country') . "='" . $data['country'] . "' WHERE " .
                    $db->quoteName('criteria_id') . "='" . $data['creteria_id'] . "'";
        }
        $db->setQuery($query);

        if ($db->query()) {

            return true;
        } else {

            echo $db->getErrorMsg();
            return false;
        }
    }

    public function CheckUserGroupName($first_name, $last_name, $symbol_pricing_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_pricing_usergroup WHERE symbol_pricing_id='$symbol_pricing_id' AND " . $db->quoteName('firstname') . "='" . $first_name . "' AND " . $db->quoteName('lastname') . "='" . $last_name . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function delete($id) {
        $row = $this->getTable();
        if (!$row->delete($id)) {
            return false;
        } else {
            return true;
        }
    }

    public function search_result() {
       	//delete first 
		$this->deleteSymbolUserGroup();
		
		//show 
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_pricing_usergroup'));
		$query->where("package_id='".$this->package_id."'");
		$query->where("symbol_pricing_id='".$this->symbol_pricing_id."'");
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		 if (count($rs) > 0) {
            foreach ($rs as $k => $v) {
                $field = $v->field;
                $num = ceil(($v->population / 100) * $this->get_total($this->check_field($v->field, $v)));
                $usr[] = $this->get_result($this->check_field($v->field, $v, $num));
                $i++;
            }
            $new_array = array();
            if ($i > 0) {
                foreach ($usr as $array) {
                    if ($array) {
                        foreach ($array as $k => $v) {
                            if (!array_key_exists($k, $new_array)) {
                                $new_array[$k] = $v;
                                $j++;
                            }
                        }
                    }
                }
            }
            foreach ($new_array as $k => $v) {
                $this->update_refund_user(JRequest::getVar('symbol_pricing_id'), $k);
            }
			$_query = $this->_db->getQuery(TRUE);
			$_query->select('*');
			$_query->from($this->_db->QuoteName('#__symbol_group_module'));
			$_query->where("symbol_pricing_id='".$this->symbol_pricing_id."'");
			$this->_db->setQuery($_query);
			$rows = $this->_db->loadObjectList();
		}
        return $rows;
    }
	public function deleteSymbolUserGroup(){
		$query = $this->_db->getQuery(TRUE);
		$query->delete();
		$query->from($this->_db->QuoteName('#__symbol_group_module'));
		$query->where("symbol_pricing_id='".$this->symbol_pricing_id."'");
		$this->_db->setQuery($query);
		$this->_db->query();
	}
    public function update_refund_user($symbol_pricing_id, $id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_useraccounts a INNER JOIN #__users b ON a.id=b.id WHERE " . $db->QuoteName('a.id') . "='" . $id . "'";
        $db->setQuery($query);
        $row = $db->loadObject();
        $q_check = "SELECT * FROM #__symbol_group_module WHERE email='" . $row->email . "'";
        $db->setQuery($q_check);
        $rows = $db->loadObjectList();
        if (count($rows) < 1) {
            $date1 = explode("-", $row->birtday);
            $start = $date[0];
            $now = date('Y');
            $age = $now - $start;
            $q = "INSERT INTO #__symbol_group_module (symbol_pricing_id,first_name,last_name,age,gender,email,state_province,country,city,address)
                       VALUES ('$symbol_pricing_id','$row->firstname','$row->lastname','$age','$row->gender','$row->email','$row->state','$row->country','$row->city','')";
            $db->setQuery($q);
            $db->query();
        }
    }

    function get_total($qry) {

        $db = & JFactory::getDBO();

        $query = "SELECT email FROM `#__ap_useraccounts` t, `#__users` u WHERE $qry ";

        $db->setQuery($query);

        $rs = $db->loadObjectList();

        return count($rs);
    }

    function check_field($field, $v, $limit = 0) {
        if ($limit > 0) {
            $rand = "ORDER BY RAND() LIMIT $limit";
        }
        switch ($field) {
            case 'name':
                $qry = "t.id = u.id AND firstname LIKE '%$v->firstname%' AND lastname LIKE '%$v->lastname%'  $rand";
                break;
            case 'email':
                $qry = "t.id = u.id AND email LIKE '%$v->email%'  $rand";
                break;
            case 'age':
                $qry = "t.id = u.id AND YEAR( NOW( ) ) - YEAR( birthday ) BETWEEN $v->from_age AND $v->to_age  $rand";
                break;
            case 'gender':
                $qry = "t.id = u.id AND gender = '$v->gender'  $rand";
                break;
            case 'location':
                $qry = "t.id = u.id AND street LIKE '%$v->street%' OR city LIKE '%$v->city%' OR state LIKE '%$v->state%' OR post_code LIKE '%$v->post_code%' OR country = '$v->country' $rand";
                break;
        }
        //echo $qry.'<br>';
        return $qry;
    }

    function get_result($qry) {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__ap_useraccounts` t, `#__users` u WHERE $qry";
        //echo $query.'<br>';
        $db->setQuery($query);
        $rs = $db->loadObjectList();
        if (count($rs) > 0) {
            foreach ($rs as $k => $v) {
                $user[$v->id] = array(
                    'package_id' => $v->package_id,
                    'id' => $v->id,
                    'username' => $v->username,
                    'firstname' => $v->firstname,
                    'lastname' => $v->lastname,
                    'birthday' => $v->birthday,
                    'email' => $v->email,
                    'gender' => $v->gender,
                    'street' => $v->street,
                    'city' => $v->city,
                    'state' => $v->state,
                    'post_code' => $v->post_code,
                    'country' => $v->country,
                    'phone' => $v->phone,
                );
            }
            return $user;
        }
    }
    
    protected function preprocessForm(JForm $form, $data, $group = 'user') {
        parent::preprocessForm($form, $data, $group);
    }
}