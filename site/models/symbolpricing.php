<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class AwardpackageModelSymbolPricing extends JModelList {

    public function getDataPricing() {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = "SELECT * FROM `#__symbol_queue` a INNER JOIN `#__symbol_queue_detail` b ON a.queue_id=b.queue_id INNER JOIN `#__symbol_pricing` c ON c.presentation_id=b.presentation_id INNER JOIN #__users d ON d.id=a.user_id LEFT JOIN #__symbol_presentation e ON b.presentation_id=e.presentation_id WHERE a.user_id='$user->id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getPricingDetails($symbol_pricing_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_pricing_details a WHERE a.symbol_pricing_id='$symbol_pricing_id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getPieces($pieces_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_symbol_pieces WHERE symbol_pieces_id='$pieces_id'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }
	
	public function getBreakdownDetails($details_id, $pieces_id) {
		$db	   =&JFactory::getDbo();
        $query = $db->getQuery(TRUE);
        $query->select('*');
        $query->from($db->QuoteName('#__symbol_pricing_breakdown'));
        $query->where("detailsid='$details_id'");
        $query->where("symbol_pieces_id='$pieces_id'");
        $query->where("status='1'");
        //$db = JFactory::getDbo();
        //$query = "SELECT * FROM #__symbol_pricing_breakdown WHERE detailsid='$details_id' AND symbol_pieces_id='$pieces_id' AND status='1'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }
	
    public function symbolGroupModule($symbol_pricing_id, $email) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_group_module WHERE symbol_pricing_id='" . $symbol_pricing_id . "' AND email='$email'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getPricingBreakdown($breakdown_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM `#__symbol_pricing_breakdown` a INNER JOIN `#__symbol_pricing_details` b ON a.detailsid=b.details_id";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }

    public function chekOrderNumber() {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_order ORDER BY order_number_id DESC";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }

    public function CheckOrderInfo($breakdown_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__symbol_pricing_breakdown a 
                   INNER JOIN #__symbol_pricing_details b ON a.detailsid=b.details_id 
                   INNER JOIN #__symbol_prize c ON c.id=b.prize_id 
                   INNER JOIN #__symbol_symbol_pieces d ON d.symbol_pieces_id=a.symbol_pieces_id
                   WHERE a.breakdownid='$breakdown_id'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }

    public function saveOrder($data) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = "INSERT INTO #__symbol_order (order_number_id,order_date,prize_id,symbol_pieces,price,order_total,status,user_id) 
                   VALUES ('" . $data['order_number'] . "','" . $data['order_date'] . "','" . $data['prize_id'] . "','" . $data['symbol_pieces'] . "','" . $data['price'] . "','" . $data['order_total'] . "','0','" . $user->id . "')";
        $db->setQuery($query);
        if ($db->query()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getOrder() {
        $db    = JFactory::getDbo();
        $user  = JFactory::getUser();
        $query = "SELECT * FROM #__symbol_order WHERE user_id='".$user->id."' ORDER BY order_date DESC";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        return $rows;
    }
    
    public function getOrderDetails($order_number_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__symbol_order WHERE order_number_id='".$order_number_id."'";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        return $rows;
    }
    
    public function getPrizeInfo($prize_id){
         $db     = JFactory::getDbo();
         $query  = "SELECT * FROM #__symbol_prize WHERE id='$prize_id'";
         $db->setQuery($query);
         $rows   = $db->loadObject();
         return $rows;
    }
    
    public function checkOrder($symbol_pieces){
        $user    = &JFactory::getUser();
        $db      = JFactory::getDbo();
        $query   = "SELECT * FROM #__symbol_order WHERE user_id='".$user->id."' AND symbol_pieces='".$symbol_pieces."'";
        $db->setQuery($query);
        $rows    = $db->loadObjectList();
        return $rows;
    }
    
    public function saveOrderDonation($data){
        $db     = JFactory::getDbo();
        $query= $db->insertObject('#__ap_donation_transactions', $data);
        if($query){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function updateOrderStatus($order_number){
        $db     = JFactory::getDbo();
        $query  = "UPDATE #__symbol_order SET status='1' WHERE order_number_id='$order_number'";
        $db->setQuery($query);
        if($db->query()){
            return TRUE;
        }else{
            return FALSE;
        }
    }
	
	public function getWion(){
		$user 		= &JFactory::getUser();
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select('*');
		$query->from($db->QuoteName('#__ap_winners_actual').' AS a');
		$query->innerJoin($db->QuoteName('#__symbol_prize').' AS b ON a.prize_id=b.id');
		$query->where("a.user_id='".$user->id."'");
		$db->setQuery($query);
		$rows		= $db->loadObjectList();
		return $rows;
	}
	function getSymbol(){
  		$db = &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from($db->QuoteName('#__symbol_prize').' AS a');
		$query->innerJoin('#__symbol_symbol AS b ON a.id=b.symbol_id');
		$query->innerJoin('#__symbol_symbol_pieces AS c ON c.symbol_id=b.symbol_id');
		$query->where("a.id=c.symbol_id");
		//$query->where("a.id='".$user->id."'");
		//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
  	}
}

?>