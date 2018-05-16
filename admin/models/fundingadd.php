<?php

/* Funding models version 1.0
 * @package joomla 2.5
 * @author kadeyasa@gmail.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class AwardPackageModelFundingadd extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('revenue_id', 'funding_id', 'revenue_percentage', 'revenue_fromprize', 'revenue_toprize', 'revenue_strategy');
        }
		$this->presentation_id = JRequest::getVar('presentation_id');
		$this->edit_data = JRequest::getVar('editdata');
        parent::__construct($config);
    }

    public function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('revenue_id,funding_id,revenue_percentage,revenue_fromprize,revenue_toprize,revenue_strategy,locked');

        // From the hello table
        $query->from('#__funding_revenue');
        $query->order($this->getState('list.ordering', 'revenue_id') . ' ' . $this->getState('list.direction', 'ASC'));

        return $query;
    }

    public function addRevenue($_funding_id) {

        $db = &JFactory::getDBO();

        $_Query = "INSERT INTO #__funding_revenue (funding_id,revenue_strategy) VALUES ('$_funding_id','1')";

        $db->setQuery($_Query);

        if ($db->query()) {

            return true;
        } else {
            return false;
        }
    }

    public function saveRevenue($data) {
		$db = &JFactory::getDBO();
		$_q_revenue = $db->getQuery(TRUE);
		$_q_revenue->update($db->QuoteName('#__funding_revenue'));
		$_q_revenue->set("revenue_percentage='".$data['revenue_percentage']."'");
		$_q_revenue->set("revenue_fromprize='".$data['revenue_fromprize']."'");
		$_q_revenue->set("revenue_toprize='".$data['revenue_toprize']."'");
		$_q_revenue->set("revenue_strategy='".$data['revenue_strategy']."'");
		$_q_revenue->set("locked='1'");
		$_q_revenue->where("revenue_id='" . $data['revenue_id'] . "'");
		$db->setQuery($_q_revenue);
		if($db->query()): // if revenue updated
          	/* Checking the prize value 
             * and get data 
             */
            if ($data['revenue_strategy'] == '1') {
                $order_by = "ORDER BY b.prize_value DESC ";
            } else {
                $order_by = "ORDER BY b.prize_value ASC ";
            }
            $q_check_prize = "SELECT * FROM #__symbol_symbol_prize AS a INNER JOIN #__symbol_prize AS b ON a.id=b.id WHERE b.status='1' AND b.unlocked_status='0' AND  b.package_id='" . $data['package_id'] . "' AND a.presentation_id='".$this->presentation_id."' $order_by";
			$db->setQuery($q_check_prize);
			//echo $q_check_prize;
			$PrizeDatas = $db->loadObjectList();
			foreach($PrizeDatas as $PrizeData){
			
				 if($PrizeData->prize_value>=$data['revenue_fromprize'] && $PrizeData->prize_value<=$data['revenue_toprize']){//check from value
				
						//check funding 
						$data['prizeValue'] = $PrizeData->prize_value;
		
						$donateData = $this->getDonation($data);
		
						$q_funding = "SELECT * FROM #__funding_presentations a INNER JOIN #__funding b ON a.prize_funding_session_id=b.funding_id WHERE b.package_id='" . JRequest::getVar('package_id') . "'";
		
						$db->setQuery($q_funding);
		
						$db->query();
		
						$rs = $db->loadObjectList();
		
						$total_rs = 0;
		
						foreach ($rs as $r) {
							$total_rs = $total_rs + $r->funding;
						}
		
						$total_donate = $this->getTotalDonate($data['package_id']);//get donation in package
		
						$tot_donation = $total_donate - $total_rs; // check donation with fund
						
						
						if ($tot_donation >= $data['revenue_fromprize']) {
						
		
							$data['donation_id'] = $donateData->transaction_id;
							if($tot_donation>$data['prizeValue']){
								$fundprize = $data['prizeValue'];
							}else{
								$fundprize = $tot_donation;
							}
							$data['FundPrize'] = $fundprize;
		
							$data['prize_id'] = $PrizeData->id;
		
							$data['shortfall'] = $data['prizeValue'] - $data['FundPrize'];
		
							$data['FundedPercentage'] = ($data['FundPrize'] / $data['prizeValue']) * 100;
		
							$this->addFundingPresentation($data);
							
							//save to transaction 
							$q_save = $db->getQuery(TRUE);
							$q_save->insert('#__ap_donation_transactions');
							$q_save->set("package_id='".$data[package_id]."'");
							$q_save->set("payment_gateway='Funding'");
							$date = &JFactory::getDate();
							$q_save->set("dated='".$date->toFormat()."'");
							$q_save->set("transaction='Funding'");
							$q_save->set("debit='".$fundprize."'");
							$q_save->set("status='completed'");
							$db->setQuery($q_save);
							$db->query();
						}
					   
					}
				 }
				 return TRUE;
		endif;//end if revenu updated
    }

    private function unlockPrize($prize_id) {

        $db = &JFactory::getDBO();

        $Query = "UPDATE #__symbol_prize SET status='0' WHERE " . $db->QuoteName('id') . "='" . $prize_id . "'";

        $db->setQuery($Query);

        $db->query();
    }

    private function getDonation($data) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__ap_donation_transactions WHERE package_id='" . $data['package_id'] . "' AND status='completed'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $total = 0;

        foreach ($rows as $row) {
            $transaction_id = $row->transaction_id;
            $total = $row->credit + $total;
        }

        $data_donate = new JObject;
        if ($total >=$data['prizeValue']) {
            $data_donate->credit = $data['prizeValue'];
            $data_donate->transaction_id = $transaction_id;
        }else{
            if($total>=$data['revenue_fromprize']){
                $data_donate->credit = $total;
                $data_donate->transaction_id = $transaction_id;
            }
        }

        return $data_donate;
    }

    public function getTotalDonate($package_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_donation_transactions WHERE package_id='$package_id' AND status='completed' || status='Complate'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = 0;
        foreach ($rows as $row) {
            $total = ($total + $row->credit)-$row->debit;
        }
        return $total;
    }

    private function addFundingPresentation($data) {

        $db = &JFactory::getDBO();

        $date = &JFactory::getDate();

        $now = $date->toFormat();

        $user = &JFactory::getUser();

        $_Query = "INSERT INTO #__funding_presentations (" .
                $db->QuoteName('prize_funding_session_id') . "," .
                $db->QuoteName('prize_id') . "," .
                $db->QuoteName('value') . "," .
                $db->QuoteName('funding') . "," .
                $db->QuoteName('shortfall') . "," .
                $db->QuoteName('pct_funded') . "," .
                $db->QuoteName('status') . "," .
                $db->QuoteName('created') . "," .
                $db->QuoteName('created_by') . "," .
                $db->QuoteName('modified') . "," .
                $db->QuoteName('modified_by') . "," .
                $db->QuoteName('unlocked_date') . "," .
                $db->QuoteName('donation_id') . "," .
                $db->QuoteName('revenue_id') . ") VALUES ('" .
                $data['funding_id'] . "','" . $data['prize_id'] . "','" . $data['prizeValue'] . "','" .
                $data['FundPrize'] . "','" . $data['shortfall'] . "','" . $data['FundedPercentage'] . "','0','" . $now . "','" . $user->id . "','" .
                $now . "','" . $user->id . "','" . $now . "','" . $data['donation_id'] . "','" . $data['revenue_id'] . "')";
        $db->setQuery($_Query);

        if ($db->query()) {
			$this->unlockPrize($data['prize_id']);
        } else {
            echo $db->getErrorMsg();
        }
    }

    public function updateFunding($data) {

        $db = &JFactory::getDBO();

        $_Query = "UPDATE #__funding SET funding_session='" . $data['session'] . "',funding_desc='" . $data['funding_desc'] . "' WHERE funding_id='" . $data['funding_id'] . "'";

        $db->setQuery($_Query);


        if ($db->query()) {

            return true;
        }
    }

    public function DeleteRevenue($cid) {

        $db = &JFactory::getDBO();

        $row = & $this->getTable('Fundingadd');

        if ($row->delete($cid)) {

            $query = "DELETE FROM #__funding_presentations WHERE " . $db->QuoteName('revenue_id') . "='" . $cid . "'";

            $db->setQuery($query);

            $db->query();

            return true;
        } else {
            $this->setError($row->getErrorMsg());

            return false;
        }
    }

    public function UnlockRevenue($cid) {
        $db = &JFactory::getDBO();
		$query=$db->getQuery(TRUE);
		$query->select('*');
		$query->from("#__funding_revenue AS a");
		$query->innerJoin("#__funding AS b ON a.funding_id=b.funding_id");
		$query->innerJoin("#__symbol_symbol_prize  AS c ON b.presentation_id=c.presentation_id");
		$query->innerJoin("#__symbol_prize AS d ON d.id=c.id");
		$query->where("a.revenue_id='".$cid."'");
		$query->where("d.unlocked_status='0'");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$valid=false;
		foreach($rows as $row){
			$query = $db->getQuery(TRUE);
			$query->update('#__symbol_prize');
			$query->set("status='1'");
			$query->where("id='".$row->id."'");
			$db->setQuery($query);
			if($db->query()){
				$valid = true;
			}
		}
		if($valid){
			$query=$db->getQuery(TRUE);
			$query->delete('#__funding_presentations');
			$query->where("revenue_id='".$cid."'");
			$db->setQuery($query);
			if($db->query()){
				$query = "UPDATE #__funding_revenue SET locked='0' WHERE " . $db->QuoteName('revenue_id') . "='" . $cid . "'";
				$db->setQuery($query);
				if ($db->query()) {
					return true;
				} else {
					return false;
				}
			}
		}else{
			return false;
		}
    }
	
}

?>