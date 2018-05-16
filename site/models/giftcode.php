<?php 
//redirect
defined('_JEXEC') or die('Restricted access');

//import file
jimport('joomla.application.component.modellist');

class AwardPackageModelGiftcode extends JModelList{
	/*
		class giftcode
	*/
    function __construct($config = array()) {
		$this->color 	= JRequest::getVar('color');
        parent::__construct($config);
	}
	
	protected function getListQuery(){
		$db			= 	&JFactory::getDbo();
		//get user
		$user 		=	&JFactory::getUser();
		
		//check user
		$model 		=	&JModelLegacy::getInstance('address','AwardPackageModel');
		$userinfo	= 	$model->info($user->id);
		
		if($this->color){
			$color	= 	$this->color;
		}else{
			$row	= 	$this->getGiftCodePackage($userinfo->package_id);
			$color	= 	$row->id;
		}
		
		$query		= 	$db->getQuery(TRUE);
		$query->select('*');
		$query->from("#__giftcode_giftcode AS a");
		$query->where("a.giftcode_category_id='".$color."'");
		$query->where("a.published='1'");
		//echo $query;
		return $query;
	}
	
	function getGiftCodePackage($package_id){
		//get db
		$db			= &JFactory::getDBO();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_category");
		$query->where("published='1'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObject();		
	}
	
	function check_user($email){
		//get db
		$db			= &JFactory::getDBO();
		
		//query
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_user");
		$query->where("email='".$email."'");
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function create_user($user){
		//get row
		$row =& $this->getTable('Giftcodeuser');
		
		//bind
		if (!$row->bind($user)) {  
		  $this->setError($this->_db->getErrorMsg());
		  return false;
		}
    	//check
		if (!$row->check()) {
		  $this->setError($this->_db->getErrorMsg());
		  return false;
		}
    	//store
		if (!$row->store()) {
		  $this->setError( $row->getErrorMsg() );
		  return false;
		}
	}
	
	function getGiftcodeCollection() {
		$db		= &JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_collection");
		$query->where("user_id IS NULL");
		
		//set query
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function updateGiftcodeCollection($user_id, $giftcodes_id){
		$db 		=	& JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->update("#__giftcode_collection");
		$query->set("user_id='".$user_id."'");
		$query->where("id='".$giftcodes_id."'");
		$db->setQuery($query);
		
		return $db->query();
	}
	
	function getGiftcodeCategory() {
		$db 		=	& JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_category");
		$query->where("published='1'");
		$query->order("category_id ASC ");
		
		//set query
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getGiftcodes($gcid, $offset = 0){
		$db 		=	& JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		
		if ($offset > 1) {
			$offset = ($offset * 250) - 249;        
		}
		
		$query->select("*");
		$query->from("#__giftcode_giftcode");
		$query->where("giftcode_category_id='".$gcid."'");
		$query->where("published='1'");
		$db->setQuery($query,$offset,250);
		
		return $db->loadObjectList();
	}
	
	function getAllGiftcodes ($gcid) {
		$db 		=	& JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_giftcode");
		$query->where("giftcode_category_id='".$gcid."'");
		$db->setQuery($query);
		
		return $db->loadObjecList();
	}
	
	function check_giftcode_owner($user_id){
		$db 		=	& JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_collection");
		$query->where("user_id='".$user_id."'");
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	function verify_giftcode_owner(){
		$db 		=	& JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_collection");
		$query->where("user_id IS NULL");
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	public function getGiftCodePackageAll($package_id){
		$db		= &JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_category");
		$query->where("published='1'");
		$query->order("category_id ASC");
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getGiftCodeCategoryId($cat_id){
		$db			= 	&JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__giftcode_category");
		$query->where("id='".$cat_id."'");
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	public function getSymbol($pieces,$package_id){
		$db			= 	&JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol");
		$query->where("pieces='".$pieces."'");
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$row		= 	$db->loadObject();
		
		return $row;		
	}
	
  	public function getList(){
		$db			= 	&JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$UserInfo   = &JFactory::getUser();
		
		$query->select("*");
		$query->from("#__gc_recieve_user AS a");
		$query->innerJoin("#__giftcode_giftcode AS b ON a.category_id=b.giftcode_category_id");
		$query->leftJoin("#__giftcode_category AS c ON c.id=b.giftcode_category_id");
		$query->where("a.user_id='".$UserInfo->id."'");
		$query->where("c.published='1'");
		//echo $query;
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function UpdateUserRecive($gcid,$id){
		$db     = JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		
		$query->update("#__gc_recieve_user");
		$query->set("gcid='".$gcid."'");
		$query->set("status='1'");
		$query->where("id='".$id."'");
      
      	$db->setQuery($query);
      	if($db->query()){
        	return true;
      	}else{
			return false;
		}
	}
	public function checkRecieve(){
		$db 	    	= &JFactory::getDBO();
		$UserInfo    	= &JFactory::getUser();
		$query			= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__gc_recieve_user");
		//$query->where("user_id='".$UserInfo->id."'");
		$query->where("gcid='0'");
		$query->where("status='0'");
		//$query->where("category_id='".$color."'");
		//echo $query;
		$query->order("gcid DESC");
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function CheckGcid($gcid){
		$db 	    	= &JFactory::getDBO();
		$query->select("*");
		$query->from("#__gc_recieve_user");
		$query->where("gcid='$gcid'");
		
      	$db->setQuery($query);
      	if($db->query()){
          if($db->getNumRows()>0){
            return true;
          }else{
            return false;
          }
      	}else{
			return false;
      	}
	}
		
 	function checkSchedule(){
  		$db = &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		//check user 
		$user = &JFactory::getUser();
		
		$query->from($db->QuoteName('#__ap_useraccounts').' AS a');
		$query->innerJoin('#__ap_award_schedule AS b ON a.package_id=b.package_id');
		$query->innerJoin('#__ap_categories AS c ON c.package_id=b.package_id');
		$query->where("c.category_id=b.category_id");
		$query->where("a.id='".$user->id."'");
		//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
  	}
  
  	function checkGiftcode(){
  		$db = &JFactory::getDbo();
		$date = date('Y-m-d');
		$user = &JFactory::getUser();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__gc_recieve_user AS a');
		$query->where("a.user_id='".$user->id."'");
		$query->where("a.giftcode_type='0'");
		$db->setQuery($query);
		$rows   = $db->loadObjectList();
		$return = FALSE;
		foreach($rows as $row){
			if(substr($row->date_time,0,10)==$date){
				$return=TRUE;
			}
		}
		return $return;
  	}
  
  	function getGiftCodeSend($category_id){
  		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__giftcode_giftcode AS a');
		$query->where("a.giftcode_category_id='".$category_id."'");
		$query->where("a.published='1'");
		$db->setQuery($query);
		$rows = $db->loadObject();
		return $rows;
  	}
  
 	 function saveGiftCode($category_id){
  		$db		= &JFactory::getDbo();
		$date	= &JFactory::getDate();
		$user	= &JFactory::getUser();
		$query	= $db->getQuery(TRUE);
		$query->insert($db->QuoteName('#__gc_recieve_user'));
		$query->set("category_id='".$category_id."'");
		$query->set("user_id='".$user->id."'");
		$query->set("gcid='0'");
		$query->set("date_time='".$date->toFormat()."'");
		$query->set("status='0'");
		$query->set("giftcode_type='0'");
		$db->setQuery($query);
		$db->query();
  	}
	
}