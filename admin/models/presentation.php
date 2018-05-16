<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardPackageModelPresentation extends JModelLegacy {

    var $_data;
    var $_jml;
    var $_detail;
    
    function __construct($config = array()) {
        parent::__construct($config);
        $this->_db = &JFactory::getDBO();
    }
    function _getDataQuery() {
       
        $query = 'SELECT #__symbol_symbol_prize.*,#__symbol_prize.prize_name,#__symbol_prize.prize_image,#__symbol_symbol.symbol_name,#__symbol_symbol.symbol_image,#__symbol_symbol.rows,#__symbol_symbol.cols FROM (#__symbol_symbol_prize INNER JOIN #__symbol_prize USING (id)) INNER JOIN #__symbol_symbol USING (symbol_id) 
		WHERE #__symbol_symbol_prize.presentation_id=' . JRequest::getVar("pr_id") . '
		ORDER BY symbol_prize_id DESC';
        return $query;
    }

    function getData() {

        if (empty($this->_data)) {

            $query = $this->_getDataQuery();

            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }

    function getDataDetail($gcid) {
        if (empty($this->_data)) {
            $query = $this->_getDataQuery();
            $this->_data = $this->_getList("SELECT #__symbol_symbol_prize.*,#__symbol_prize.prize_name,#__symbol_prize.prize_image,#__symbol_symbol.symbol_name,#__symbol_symbol.symbol_image,#__symbol_symbol.rows,#__symbol_symbol.cols FROM (#__symbol_symbol_prize INNER JOIN #__symbol_prize USING (id)) INNER JOIN #__symbol_symbol USING (symbol_id) WHERE #__symbol_symbol_prize.symbol_prize_id = '" . $gcid . "'");
        }       
        return $this->_data;
    }

    function getSymbolSymbolPrize($presentation_id) {
        
        $query = $this->_db->getQuery(true);
        
        $query->select ('*');
        
        $query->from($this->_db->QuoteName('#__symbol_symbol_prize'));
        
        $query->where($this->_db->QuoteName('presentation_id')."='".$presentation_id."'");

        $this->_db->setQuery($query);

        $rows = $this->_db->loadObjectList();

        $totalFunding = 0;

        $totalPrizeFunding = 0;

        foreach ($rows as $row) {
            
            $Query = $this->_db->getQuery(true);
            
            $Query->select('*');
            
            $Query->from('#__funding_presentations');
            
            $Query->where("prize_id='".$row->id."'");
            
            $this->_db->setQuery($Query);

            $dt = $this->_db->loadObjectList();

            foreach ($dt as $r) {

                $totalPrizeFunding = $totalPrizeFunding + $r->value;

                $totalFunding = $totalFunding + $r->funding;

                $data['session_funding_id'] = $r->prize_funding_session_id;
            }
        }

        $data['totalFunding'] = $totalFunding;

        $data['totalPrizeFunding'] = $totalPrizeFunding;

        return $data;
    }

    function saveData($data) {
        $row = & $this->getTable('Symbolprize');

        if (!$row->bind($data)) {

            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the hello record is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Store the web link table to the database
        if (!$row->store()) {
            $this->setError($row->getErrorMsg());
            return false;
        }
        if ($data['symbol_prize_id'] == '') {
            $id = $this->_db->insertid();
        } else {
            $id = $data['symbol_prize_id'];
        }
        return $id;
    }

    function delete($ids) {
        $row = & $this->getTable('Symbolprize');
        if (count($ids)) {
            foreach ($ids as $id) {
                $query = $this->_db->getQuery(true);
                $query->delete();
                $query->from($this->_db->QuoteName('#__symbol_queue_detail'));
                $query->where($this->_db->QuoteName('symbol_prize_id')."='".$id."'");
                $this->_db->setQuery($query);
                $this->_db->query();
                if (!$row->delete($id)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }

    function deleteData($id) {
        $row = & $this->getTable('Symbolprize');
        $row->delete($id);
        $query = "delete from #__symbol_queue_detail where symbol_pieces_id in (select symbol_pieces_id from #__symbol_symbol_pieces a left join #__symbol_symbol_prize b on a.symbol_id=b.symbol_id where b.id=" . $id . ")";
        $this->setQuery($query);
    }

    function checkSymbolPricing($presentation_id) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing'));
        $query->where($this->_db->QuoteName('presentation_id')."='".$presentation_id."'");
        //$query = "SELECT * FROM #__symbol_pricing WHERE " . $db->QuoteName('presentation_id') . "='" . $presentation_id . "'";        
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function symbolDetails($symbol_pricing) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_details'));
        $query->where($this->_db->QuoteName('symbol_pricing_id')."='".$symbol_pricing."'");
        //$db = JFactory::getDbo();
        //$query = "SELECT * FROM #__symbol_pricing_details WHERE symbol_pricing_id='" . $symbol_pricing . "'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function saveSymbolPricing() {
        //$db = JFactory::getDbo();
        $presentation_id = JRequest::getVar('presentation_id');
        $is_all_user = JRequest::getVar('all_users');
        if ($is_all_user == "") {
            $query = $this->_db->getQuery(TRUE);
            $query->insert($this->_db->QuoteName('#__symbol_pricing'));
            $query->set("is_all_user='".$is_all_user."'");
            $query->set("presentation_id='".$presentation_id."'");
            $query->set("is_publish='0'");
            //$query = "INSERT INTO #__symbol_pricing (is_all_user,presentation_id,is_publish) VALUES ('" . $is_all_user . "','" . $presentation_id . "','0')";
            $this->_db->setQuery($query);
            if ($this->_db->query()) {
                $query = $this->_db->getQuery(TRUE);
                $query->delete();
                $query->from($this->_db->QuoteName('#__symbol_pricing'));
                $query->where($this->_db->QuoteName('presentation_id')."='".$presentation_id."'");
                $query->where($this->_db->QuoteName('is_all_user')."='1'");
                //$query = "DELETE FROM #__symbol_pricing WHERE presentation_id='" . $presentation_id . "' AND is_all_user='1'";
                $this->_db->setQuery($query);
                $this->_db->query();
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function pulbishSymbolPricing($cid) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__symbol_pricing'));
        $query->set("is_publish='1'");
        $query->where($this->_db->QuoteName('symbol_pricing_id')."='".$cid."'");
        //$query = "UPDATE #__symbol_pricing SET is_publish='1' WHERE symbol_pricing_id='$cid'";
        $this->_db->setQuery($query);
        return $this->_db->query();
    }

    public function unpulbishSymbolPricing($cid) {
        $db = JFactory::getDbo();
        $query = $this->_db->getQuery(true);
        $query->update($this->_db->QuoteName('#__symbol_pricing'));
        $query->set($this->_db->QuoteName('is_publish')."='0'");
        $query->where($this->_db->QuoteName('symbol_pricing_id')."='".$cid."'");
        //$query = "UPDATE #__symbol_pricing SET is_publish='0' WHERE symbol_pricing_id='$cid'";
        $this->_db->setQuery($query);
        return $this->_db->query();
    }

    public function deleteSymbolPricing($cid) {
        $query = $this->_db->getQuery(true);
        $query->delete();
        $query->from($this->_db->QuoteName('#__symbol_pricing'));
        $query->where($this->_db->QuoteName('symbol_pricing_id')."='".$cid."'");
        $this->_db->setQuery($query);
        return $this->_db->query();
    }

    public function getPricingDetails($presentation_id) {
        $query = $this->_db->getQuery(true);
        //$query->select('*');
        //$query->from($this->_db->QuoteName('#__symbol_symbol_prize').' AS a');
        //$query->innerJoin($this->_db->QuoteName('#__symbol_prize').' AS b ON a.id=b.id');
        //$query->leftJoint($this->_db->QouteName('#__symbol_symbol').' AS c ON a.symbol_id=c.symbol_id');
        //$query->where("a.presentation_id='$presentation_id'");
        $query = "SELECT * FROM #__symbol_symbol_prize a INNER JOIN #__symbol_prize b ON a.id=b.id LEFT JOIN #__symbol_symbol c ON a.symbol_id=c.symbol_id WHERE a.presentation_id='$presentation_id'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }
  
    public function PricingDetails($pricing_id, $prize_id, $symbol_id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_details'));
        $query->where($this->_db->QuoteName('symbol_pricing_id')."='$pricing_id'");
        $query->where($this->_db->QuoteName('prize_id')."='".$prize_id."'");
        $query->where($this->_db->QuoteName('symbol_id')."='".$symbol_id."'");
        //$query = "SELECT * FROM #__symbol_pricing_details WHERE symbol_pricing_id='$pricing_id' AND prize_id='$prize_id' AND symbol_id='$symbol_id'";        
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObject();        
        if (count($rows) < 1) {
            $q = $this->_db->getQuery(true);
            $q->insert($this->_db->QuoteName('#__symbol_pricing_details'));
            $q->set("symbol_pricing_id='".$pricing_id."'");
            $q->set("prize_id='".$prize_id."'");
            $q->set("symbol_id='".$symbol_id."'");
            //$q = "INSERT INTO #__symbol_pricing_details (symbol_pricing_id,prize_id,symbol_id) VALUES ('" . $pricing_id . "','" . $prize_id . "','" . $symbol_id . "')";
            $this->_db->setQuery($q);
            $this->_db->query();
            //echo $db->getErrorMsg();
        }
        return $rows;
    }

    public function addPricingDetail($price_from_form, $price_to_form, $pricing_id, $prize_id, $symbol_id, $price_from, $price_to, $prize_value, $details_id) {
        $db = JFactory::getDbo();
        for ($j = $price_from_form; $j < $price_to_form; $j++) {
            $total++;
        }
        $_data = $total / 2;
        $i = 0;
        // = JRequest::getVar('details_id');
        $virtual_price = $_data + $price_from_form;

        if (!$details_id) {
            $details = $this->getPricingDetails(JRequest::getVar('presentation_id'));
            foreach ($details as $price_detail) {
                $pricing_details = $this->PricingDetails($pricing_id, $price_detail->id, $price_detail->symbol_id);
                $query = "UPDATE #__symbol_pricing_details SET price_from='" . $price_from_form . "',price_to='" . $price_to_form . "',virtual_price='" . $virtual_price . "' WHERE details_id='" . $pricing_details->details_id . "'";
                $db->setQuery($query);
                $db->query();
                $detail_pieces = $this->getPieces($price_detail->symbol_id);
                foreach ($detail_pieces as $pieces_detail) {
                    $q_breakdown = "INSERT INTO #__symbol_pricing_breakdown (detailsid,price_from,price_to,virtual_price_breakdown,symbol_pieces_id,status) VALUES ('" . $pricing_details->details_id . "','" . $price_from_form . "','" . $price_to_form . "','" . $virtual_price . "','" . $pieces_detail->symbol_pieces_id . "','0')";
                    $db->setQuery($q_breakdown);
                    $db->query();
                }
            }
        } else {
            $query = "UPDATE #__symbol_pricing_details SET price_from='" . $price_from_form . "',price_to='" . $price_to_form . "',virtual_price='" . $virtual_price . "' WHERE details_id='" . $details_id . "'";
            $db->setQuery($query);
            $db->query();
            $breakdowns = $this->pricingBreakdownDetails($details_id);
            foreach ($breakdowns as $breakdown) {
                $q_update = "UPDATE #__symbol_pricing_breakdown SET price_from='" . $price_from_form . "',price_to='" . $price_to_form . "',virtual_price_breakdown='" . $virtual_price . "' WHERE breakdownid='" . $breakdown->breakdownid . "'";
                $db->setQuery($q_update);
                $db->query();
            }
        }
    }

    public function pricingBreakdownDetails($detailsid) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_breakdown'));
        $query->where($this->_db->QouteName('detailsid')."='".$detailsid."'");
       	//$query = "SELECT * FROM #__symbol_pricing_breakdown WHERE detailsid='" . $detailsid . "'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function addPricingBreakDown($details_id, $price_from_form, $price_to_form, $price_from, $price_to, $pieces_id, $cid) {
        //$db = JFactory::getDbo();
        for ($j = $price_from_form; $j < $price_to_form; $j++) {
            $total++;
        }
        $_data = $total / 2;

        $i = 0;
        $virtual_price = $_data + $price_from_form;
        $prize_value = JRequest::getVar('prize_value');
        $breakdowns = $this->breakdownDetailsId($details_id);
        if (!$cid) {
            foreach ($breakdowns as $breakdown) {
                $_q_update = $this->_db->getQuery(true);
                $_q_update->update($this->_db->QuoteName('#__symbol_pricing_breakdown'));
                $_q_update->set("price_from ='".$price_from_form."'");
                $_q_update->set("price_to='".$price_to_form."'");
                $_q_update->set("virtual_price_breakdown='".$virtual_price."'");
                $_q_update->set("status='1'");
                $_q_update->where("breakdownid='".$breakdown->breakdownid."'");
                //$_q_update = "UPDATE #__symbol_pricing_breakdown SET price_from='" . $price_from_form . "',price_to='" . $price_to_form . "',virtual_price_breakdown='" . $virtual_price . "',status='1' WHERE breakdownid='" . $breakdown->breakdownid . "'";
                $this->_db->setQuery($_q_update);
                $this->_db->query();
            }
            $query = $this->_db->getQuery(true);
            $query->update($this->_db->QuoteName('#__symbol_pricing_details'));
            $query->set("price_from='0'");
            $query->set("price_to='0'");
            $query->set("virtual_price='0'");
            $query->where("details_id='$details_id'");
            //$query = "UPDATE #__symbol_pricing_details SET price_from='0',price_to='0',virtual_price='0' WHERE details_id='$details_id'";
            $this->_db->setQuery($query);
            $this->_db->query();
        } else {
            $_q_update = "UPDATE #__symbol_pricing_breakdown SET price_from='" . $price_from_form . "',price_to='" . $price_to_form . "',virtual_price_breakdown='" . $virtual_price . "',status='1' WHERE breakdownid='" . $cid . "'";
            $this->_db->setQuery($_q_update);
            $this->_db->query();
            $query = "UPDATE #__symbol_pricing_details SET price_from='0',price_to='0',virtual_price='0' WHERE details_id='$details_id'";
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        
    }

    public function breakdownDetailsId($details_id) {
        //$db = JFactory::getDbo();
        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_breakdown'));
        $query->where("detailsid='".$details_id."'");
        //$query = "SELECT * FROM #__symbol_pricing_breakdown WHERE detailsid='" . $details_id . "'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function getPricingDetail($id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_details'));
        $query->where("details_id='$id'");
        //$query = "SELECT * FROM #__symbol_pricing_details WHERE details_id='$id'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObject();
        return $rows;
    }

    public function createSymbolPricing() {
        //$db = JFactory::getDbo();
        $is_all_user = JRequest::getVar('all_users');
        $presentation_id = JRequest::getVar('presentation_id');
        $pricing_id = JRequest::getVar('pricing_id');
        $query = $this->_db->getQuery(true);
        if ($is_all_user != "") {
            $this->deletesymbolpricingPresentation($presentation_id);
            if (!$pricing_id) {
                $query->insert($this->_db->QuoteName('#__symbol_pricing'));
                $query->set("is_all_user='$is_all_user'");
                $query->set("presentation_id='$presentation_id'");
                $query->set("is_publish='1'");
                //$query = "INSERT INTO #__symbol_pricing (is_all_user,presentation_id,is_publish) VALUES ('" . $is_all_user . "','" . $presentation_id . "','1')";
            } else {
                $query->update($this->_db->QuoteName('#__symbol_pricing'));
                $query->set("is_all_user='$is_all_user'");
                $query->where("symbol_pricing_id='".JRequest::getVar('symbol_pricing_id')."'");
                //$query = "UPDATE #__symbol_pricing SET is_all_user='$is_all_user'";
            }
        } else {
            $query->delete();
            $query->from($this->_db->QuoteName('#__symbol_pricing'));
            $query->where("symbol_pricing_id='".JRequest::getVar('symbol_pricing_id')."'");
            //$query = "DELETE FROM #__symbol_pricing WHERE  symbol_pricing_id='" . JRequest::getVar('symbol_pricing_id') . "'";
        }
        $this->_db->setQuery($query);

        if ($this->_db->query()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function CheckPricingBreakdown($details_id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_breakdown'));
        $query->where("detailsid='$details_id'");
        $query->where("status='1'");
        //$query = "SELECT * FROM #__symbol_pricing_breakdown WHERE detailsid='" . $details_id . "' AND status='1'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function getPieces($symbol_id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_symbol_pieces'));
        $query->where($this->_db->QuoteName('symbol_id')."='".$symbol_id."'");
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function getPrizeDetails($prize_id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_prize'));
        $query->where("id='".$prize_id."'");
        //$query = "SELECT * FROM #__symbol_prize WHERE id='$prize_id'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObject();
        return $rows;
    }

    public function getBreakdownDetails($details_id, $pieces_id) {
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_pricing_breakdown'));
        $query->where("detailsid='$details_id'");
        $query->where("symbol_pieces_id='$pieces_id'");
        $query->where("status='1'");
        //$db = JFactory::getDbo();
        //$query = "SELECT * FROM #__symbol_pricing_breakdown WHERE detailsid='$details_id' AND symbol_pieces_id='$pieces_id' AND status='1'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObject();
        return $rows;
    }

    public function getPricingGroup($symbol_pricing_id) {
        //$db = JFactory::getDbo();
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__symbol_group_module'));
        $query->where("symbol_pricing_id='".$symbol_pricing_id."'");
        //$query = "SELECT * FROM #__symbol_group_module WHERE symbol_pricing_id='" . $symbol_pricing_id . "'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    public function deletesymbolpricingPresentation($presentation_id) {
        //$this->_db = JFactory::getDbo();
        $query=$this->_db->getQuery(TRUE);
        $query->delete();
        $query->from($this->_db->QuoteName('#__symbol_pricing'));
        $query->where("presentation_id='$presentation_id'");
        $query->where("is_all_user='0'");
        //$query = "DELETE FROM #__symbol_pricing WHERE presentation_id='$presentation_id' AND is_all_user='0'";
        $this->_db->setQuery($query);
        $this->_db->query();
    }
    
    public function get_funding($presentation_id){
        //$db     = JFactory::getDbo();
        $query=$this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__funding'));
        $query->where("presentation_id='$presentation_id'");
        //$query  = "SELECT * FROM #__funding WHERE presentation_id='$presentation_id'";
        $this->_db->setQuery($query);
        $rows   = $this->_db->loadObjectList();
        return $rows;
    }
	
	public function getWinner($presentation_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winners'));
		$query->where("presentation_id='".$presentation_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
}