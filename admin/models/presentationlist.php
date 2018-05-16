<?php
//restricted
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class AwardPackageModelPresentationList extends JModelList {

    var $_data;
    var $_jml;
    var $_detail;
	
	protected function getListQuery(){
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select("a.presentation_id,a.presentation_create,a.presentation_modify,a.presentation_publish,a.package_id");
		$query->from("#__symbol_presentation AS a");
		return $query;
	}
    
	public function getPrize($presentation_id){
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_prize");
		$query->where("presentation_id='".$presentation_id."'");
		$db->setQuery($query);
		$rows		= $db->loadObjectList();
		return $rows;
	}
	
	function getDataDetail($gcid){
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_prize AS a");
		$query->innerJoin("#__symbol_prize AS b ON a.id=b.id");
		$query->innerJoin("#__symbol_symbol AS c ON a.symbol_id=c.symbol_id");
		$query->where("a.symbol_prize_id = '" . $gcid . "'");
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
 	public function getPresentationDetails($presentation_id, $package_id) {
    	$query = 
    		"
    			SELECT 
				pssp.`presentation_id`, psp.`id`, psp.`prize_name`, psp.`prize_value`, psp.`prize_image`,
				pss.`symbol_id`, pss.`symbol_image`, pss.`symbol_name`,
				pss.`cols`, pss.`rows`, pss.`pieces`
				FROM #__symbol_symbol_prize pssp
				LEFT JOIN #__symbol_presentation sp ON sp.`presentation_id` = pssp.`presentation_id` AND sp.`package_id` = '" .$package_id. "'
				LEFT JOIN #__symbol_prize  psp ON psp.`id` = pssp.`symbol_prize_id` AND psp.`package_id` = '" .$package_id. "'
				LEFT JOIN #__symbol_symbol pss ON pss.`symbol_id` = pssp.`symbol_id` AND pss.`package_id` = '" .$package_id. "'
				WHERE pssp.`presentation_id` = '".$presentation_id."'
    		";
    	$this->_db->setQuery($query);
    	$rows = $this->_db->loadObjectList();
        return $rows;
    }
    
   function Create() {
        $package_id = JRequest::getVar('package_id');
        $row = & $this->getTable('Symbolpresentation');
        $data = array('presentation_create' => date('Y-m-d h:i:s'), 'presentation_modify' => date('Y-m-d h:i:s'), 'presentation_publish' => 0, 'package_id' => $package_id);

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

        $id = $this->_db->insertid();

        return $id;
    }

    function delete($ids) {
        $row = & $this->getTable('Symbolpresentation');       
	    $db = $this->getDbo();
		if (count($ids)) {
            foreach ($ids as $id) {
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

    function publish($publish, $id) {
        $row = & $this->getTable('Symbolpresentation');
		$data['presentation_id']=$id;
		if ($publish) {
			$data['presentation_publish']=1;
		}else{
			$data['presentation_publish']=0;
		}
		if(!$row->bind($data)){
			$this->setError($row->getErrorMsg());
			return false;
		}
		return true;
    }

    public function getSymbolPrize($presentation_id) {
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__symbol_symbol_prize a INNER JOIN #__symbol_presentation b ON b.presentation_id=a.presentation_id 
                   INNER JOIN #__symbol_symbol c ON c.symbol_id=a.symbol_id WHERE a.presentation_id='".$presentation_id."' ORDER BY RAND()";
        $db->setQuery($query);
        $row    = $db->loadObjectList();
        return $row;
    }
    
    public function saveSymbolHelper($data,$package_id){
        $db     = JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
        $user   = JFactory::getUser();
        $Date   = JFactory::getDate();
        $now    = $Date->toFormat();
		$query->insert("#__ap_award_symbol_progress");
		$query->set("symbol_id='".$data['symbol_id']."'");
		$query->set("added_by='".$user->id."'");
		$query->set("created_date='".$now."'");
		$query->set("is_publish='0'");
		$query->set("package_id='".$package_id."'");
		$query->set("symbol_presentation='".$data['presentation_id']."'");        
        $db->setQuery($query);
        if($db->query()){
            return TRUE;
        }
    }
    
    public function deleteSymbolHelper($presentation_id){
        $db     = 	JFactory::getDbo();
        $query	=	$db->getQuery(TRUE);
		$query->delete("#__ap_award_symbol_progress");
		$query->where("symbol_presentation='".$presentation_id."'");
        $db->setQuery($query);
        if($db->query()){
            return TRUE;
        }
    }
    
    public function deletesymbolQueue($presentation_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__symbol_queue_detail WHERE presentation_id='".$presentation_id."'";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
		foreach($rows as $row){
			$q      = "DELETE FROM #__symbol_queue WHERE queue_id='".$row->queue_id."'";
			$db->setQuery($q);
			if($db->query()){
				$q_2 = "DELETE FROM #__symbol_queue_detail WHERE presentation_id='".$presentation_id."'";
				$db->setQuery($q_2);
				$db->query();
			}
		}
    }
	public function saveExtract($process_id,$prize_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_prize_extracted");
		$query->set("prize_id='".$prize_id."'");
		$query->set("process_id='".$process_id."'");		
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function check_prize($presentation_id) {
		$db		= &JFactory::getDbo();
		$query = '
			SELECT a.*,b.*,c.id AS prize_id, a.id AS process_symbol
			FROM
			#__ap_symbol_process AS a
			INNER JOIN #__symbol_symbol_prize AS b ON a.presentation_id=b.presentation_id
			INNER JOIN #__symbol_prize AS c ON c.`id`=b.`id`
			WHERE
			1=1
			AND b.presentation_id= \''.$presentation_id.'\'
			AND c.unlocked_status=\'0\'
		';
		$db->setQuery($query);
		echo $query;
		$db->query();
		$rows	= $db->loadObjectList();
		return $rows;
	}
	public function getExtract_2($process_id, $prize_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_prize_extracted");
		$query->where("prize_id='".$prize_id."' and process_id='".$process_id."'");
		$db->setQuery($query);
		echo $query;
		$db->query();
		return $db->loadObjectList();
	}
public function delete_extract_detail($extract_id) {
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_extract");
		$query->where("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function deleteExtract($id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_prize_extracted");
		$query->where("id='".$id."' ");
		$db->setQuery($query);
		$db->query();		 
	}
	
	public function activeStatusAll($symbol_id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='0'");
		$query->where("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function getPiecesAll($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$query->order("a.symbol_pieces_id");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	
	public function save_extract_detail($extract_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_extract");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function updateStatus($pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='1'");
		$query->where("symbol_pieces_id='".$pieces_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
    public function get_extracted_pieces($presentation_id) {
		$query = '
			SELECT d.`symbol_pieces_id`, d.`symbol_id`, d.`symbol_pieces_image`, d.`is_lock` FROM 
			#__ap_symbol_process_prize_extracted a 
			INNER JOIN #__ap_symbol_process_extract b ON b.`extract_id` = a.`id` 
			INNER JOIN #__symbol_symbol_prize c ON c.`id` = a.`prize_id` AND c.`presentation_id` = \''.$presentation_id.'\'
			INNER JOIN #__symbol_symbol_pieces d ON d.`symbol_pieces_id` = b.`pieces_id`
		 ';		
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return $data;
	}
	
	public function get_clone_prize($prize_id,$process_id,$symbol_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT *
			FROM #__ap_symbol_process_process_clone AS a
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}

	public function delete_clone_pieces($symbol_id, $clone_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			DELETE FROM #__symbol_symbol_pieces WHERE symbol_pieces_id NOT IN 
			(
				SELECT pieces_id FROM #__ap_symbol_process_clone WHERE clone_id = '".$clone_id."'
			) AND symbol_id = '".$symbol_id."' AND is_lock = 0
		";	
		$db->setQuery($query);
		$db->query();		 
	}
	
	public function delete_clone_detail($clone_id){
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_clone
			WHERE clone_id = '".$clone_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	
	public function delete_clone_prize($prize_id,$process_id,$symbol_id) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_process_clone
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	
	public function save_clone_prize($prize_id,$process_id,$symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_process_clone");
		$query->set("process_id='".$process_id."'");
		$query->set("prize_id='".$prize_id."'");
		$query->set("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();		
	}
	
	public function getPieces($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	
	public function save_clone_pieces($symbol_id,$symbol_pieces_image){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__symbol_symbol_pieces");
		$query->set("symbol_id='".$symbol_id."'");
		$query->set("symbol_pieces_image='".$symbol_pieces_image."'");
		$db->setQuery($query);
		$db->query();		
	}
	
	public function save_clone_detail($clone_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_clone");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("clone_id='".$clone_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function saveUpdateExtractData($extra_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("extra_from = '".$extra_from."'");		
		$query->where("id = '".$id."'");		
		$db->setQuery($query);
		return $db->query();
	}
}