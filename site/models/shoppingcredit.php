<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class AwardpackageModelShoppingcredit extends JModelList {

    public function getShoppingCredit($package_id) {
        $db = JFactory::getDbo();
        $user = &JFactory::getUser();
        $user_id = $user->id;
        $query = "SELECT * FROM #__shopping_credit_distribution_list a INNER JOIN #__shopping_credit_package b 
                   ON a.shopping_credit_id=b.shopping_credit_id INNER JOIN #__shopping_credit_package_list c ON c.shopping_id=b.shopping_package_list_id 
                   WHERE a.user_id='".$user_id."' AND a.status='0' AND c.published='1'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getDonationTotal($user_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_donation_transactions WHERE user_id='$user_id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = 0;
        foreach ($rows as $row) {
            $total = $row->credit + $total;
        }
        return $total;
    }

    public function getTransactionTotal($user_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_donation_transactions WHERE user_id='$user_id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = 0;
        foreach ($rows as $row) {
            $total = $row->debit + $total;
        }
        return $total;
    }

    public function updateStatus($distribution_id) {
        $db = JFactory::getDbo();
        $query = "UPDATE #__shopping_credit_distribution_list SET status='1' WHERE distribution_list_id='$distribution_id'";
        $db->setQuery($query);
        if ($db->query()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function saveOrder($data) {
        $db = JFactory::getDbo();
        $query = $db->insertObject('#__shopping_record', $data);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        // Filter the items over the search string if set.
        if (JRequest::getVar('filter_search')) {
            $token = $db->Quote('%' . $db->escape(JRequest::getVar('filter_search')) . '%');
            $searches = array();
            $searches[] = 'a.amount LIKE ' . $token;
            $searches[] = 'a.date_recived LIKE ' . $token;
            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        
        $shopping_id = JRequest::getVar('shopping_id');
        $package_id = JRequest::getVar('package_id');
        $query->from($db->QuoteName('#__shopping_record') . 'AS a');
        $query->innerJoin('#__shopping_credit_package b ON a.shopping_credit_package_list_id = b.shopping_credit_id');
        $query->innerJoin('#__shopping_credit_package_list c ON c.shopping_id=b.shopping_package_list_id');
        //$query->innerJoin('#__shopping_credit_config d ON d.shopping_credit_id=a.shopping_credit_package_list_id');
        $query->where("a.user_id='$user->id'");
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }
    
    public function save_claim($record_id, $claimed) {
        $db = JFactory::getDbo();
        $date = date('Y-m-d');
        $check_record = "SELECT * FROM #__shopping_record a INNER JOIN #__shopping_credit_distribution_list b ON a.shopping_credit_package_list_id = b.shopping_credit_id WHERE a.shopping_record_id='$record_id'";
        $db->setQuery($check_record);
        $row_record = $db->loadObject();
        if ($date <= $row_record->ready_for_use) {
            $query = "UPDATE #__shopping_record SET unlocked_date='$date',unlocked_status='1', claimed_status='$claimed' WHERE shopping_record_id='$record_id'";
            $db->setQuery($query);
            if ($db->query()) {
                if ($claimed == '1') {
                    $check = "SELECT * FROM #__shopping_claim WHERE record_id='$record_id'";
                    $db->setQuery($check);
                    $row = $db->loadObject();
                    if (!$row) {
                        $insert = "INSERT INTO #__shopping_claim (date_claimed,amount,record_id,description) VALUES ('$date','$row_record->amount','$record_id','Shopping credit unlocked and ready for use')";
                        $db->setQuery($insert);
                        if ($db->query()) {
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    }
                }
                return TRUE;
            }
        }else{
            echo'Shopping credit can not to unlocked or claimed';
        }
    }
    
    public function getSpenShoppingCredit(){
        $db     = JFactory::getDbo();
        $user   = JFactory::getUser();
        $query  = "SELECT * FROM #__shopping_claim a INNER JOIN #__shopping_record b ON a.record_id=b.shopping_record_id
                   INNER JOIN #__shopping_credit_package c ON b.shopping_credit_package_list_id=c.shopping_credit_id
                   INNER JOIN #__shopping_credit_package_list e ON e.shopping_id=c.shopping_package_list_id 
                   WHERE b.claimed_status='1' AND b.user_id='$user->id'";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        return $rows;
    }
    
    public function checkRecord($distribution_list_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__shopping_record WHERE distribution_id='$distribution_list_id'";
        $db->setQuery($query);
        $row    = $db->loadObject();
        return $row;
    }
	
    public function getDistributionListData($shopping_credit_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__shopping_credit_distribution_list WHERE shopping_credit_id='$shopping_credit_id'";
        $db->setQuery($query);
        $row    = $db->loadObject();
        return $row;
    }
}

?>