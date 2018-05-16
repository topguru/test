<?php
defined('_JEXEC') or die('Restricted access');
ini_set('memory_limit','256M');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardPackageControllerGiftcodecode extends AwardpackageController
{
    
  function display() {
  	//tambah
    $this->save();
    parent::display();
   
  }
  
  function add() {
    $categoryModel =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
    $categoryData = $categoryModel->getData();	   
    $app = JFactory::getApplication();
    $color = JRequest::getVar("color");  
    $link = 'index.php?option=com_awardpackage&view=giftcodecode&layout=create&color='.$color.'&package_id='.JRequest::getVar('package_id');
    $app->redirect($link, $color_name, $msgType='message');	   
  }
  
  function edit() {
    
    JRequest::setVar('view','giftcodecode');
    
    JRequest::setVar('layout', 'create');
    
    parent::display();
  }
  
  function remove() { 	 
    $code_collection_id = JRequest::getVar('cid');
    $check_all = JRequest::getVar('checkall-toggle');
    $model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
    $color = JRequest::getVar('color');
    
    //Remove multiple giftcode
    for ($i = 0; $i <= sizeof($code_collection_id); $i++) {
      $delete = $model->remove($code_collection_id[$i]);            
    }
  
    //Deciding notification
    if ($check_all == "on") {
      $msg = sizeof($code_collection_id) > 1 ? (($i-1)/7)." giftcodes deleted" : "One giftcode deleted";                                                            
    } else {
      $msg = sizeof($code_collection_id) > 1 ? ($i-1)." giftcodes deleted" : "One giftcode deleted";                                                                        
    }
    $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&color='.$color.'&package_id='.JRequest::getVar('package_id'), $msg);		
  }
  
  function save(){  
    $color = JRequest::getVar('color'); 	 
	if(JRequest::getVar('collection_setting_id')==''){
      $this->do_save(false);
      $msg = 'Gift Code Created';
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&color='.$color.'&package_id='.JRequest::getVar('package_id'), $msg);		
    } else {
      $this->do_save(true);
      $msg = 'Gift Code Edited';
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&color='.$color.'&package_id='.JRequest::getVar('package_id'), $msg);		
    }	
  }
  
  function do_save($edit){ 
    $collection_setting_id = JRequest::getVar('collection_setting_id');        
    $max = JRequest::getVar('max_number_of_code');
    $giftcodes = $this->create_giftcode_collection($max);   
    $model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
    
    $giftcode_collection = array(
      'id' => $collection_setting_id,
      'code_length' => 0,
      'allow_to_repeat' => 0,
      'number_compostion' => null,
      'aphabet_composition' => null,
      'min_number_of_code' => 0,
      'max_number_of_code' => $max,
      'comment' => '',
      'color' => JRequest::getVar('color')            
    );
    
    $store = $model->save($giftcodes, $giftcode_collection, $edit);
    
    return $store;
  }
  
  
  function create_giftcode_collection($max_number) {
    $from_char = rand(1, 20); //the selected number is the order of the character to start from md5 which is 32 characters long. min 1, max 20    
    $code_length = rand(4, 7); //pull out x number of characters starting from the number selected by $from_char. min 4, max 7 characters long.     
    $code_number = $max_number; // number of gift codes to create. allow user input 
    $giftcode_collection = Array();
    for ($i = 0; $i < $max_number; $i++)
    { 
      $res = substr(md5(uniqid(rand(), true)), $from_char, $code_length);
      $giftcode_collection[]= $res;
    };
    return $giftcode_collection;
  }
  
  function publish() {
    $cid = JRequest::getVar("cid");
    $color_id = JRequest::getVar("color") == "" ? "1" : JRequest::getVar("color");
    $i = 0;            
    foreach ($cid as $id) {
      $model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
      $find_unrenewed_giftcode = $model->find_unrenewed_giftcode_by_id($id);
      if (count($find_unrenewed_giftcode) == 1) {
        $update = $model->published($id);        
        $update_giftcode = $model->published_giftcode($id);                
        $i++;
      }
    }
    
    $publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
    
    $msg = "$publish_counter Giftcode(s) Published ";                  
    
    $this->setRedirect("index.php?option=com_awardpackage&view=giftcodecode&color=$color_id&package_id=".JRequest::getVar('package_id'), $msg);
  }
  
  function unpublish() {
    $cid = JRequest::getVar("cid");
    $color_id = JRequest::getVar("color") == "" ? "1" : JRequest::getVar("color");    
    $i = 0;                
    foreach ($cid as $id) {
      $model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
      $update = $model->unpublished($id);
      $update_giftcode = $model->unpublished_giftcode($id);      
    }
    $unpublish_counter = JRequest::getVar("checkall-toggle") == "on" ? (count($cid)/7) : count($cid);    
    $msg = "$unpublish_counter Giftcode(s) Unpublished";
    $this->setRedirect("index.php?option=com_awardpackage&view=giftcodecode&color=$color_id&package_id=".JRequest::getVar('package_id'), $msg);
  }						
  
  function cancel() {
    $color_id = JRequest::getVar("color");        
    $this->setRedirect("index.php?option=com_awardpackage&view=giftcodecode&color=".$color_id."&package_id=".JRequest::getVar('package_id'), $msg);      
  }

  function save_renew() {
  	$color_id = JRequest::getVar("color");    
    $cid = JRequest::getVar("cid");    
    if (count($cid) == 0) {
    	$msg = "You don't choose any days!";
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&layout=renew_schedule&color='.$color_id.'&package_id='.JRequest::getVar('package_id'), $msg);                
    } else {
     $giftcode_model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');      
      $save = $giftcode_model->save_renew($cid, $color_id);      
      $msg = "Renew schedule created!";
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&color='.$color_id.'&package_id='.JRequest::getVar('package_id'), $msg);                      
    }
  }

  function update_renew() {
    
    $color_id = JRequest::getVar("color");    
    
    $cid = JRequest::getVar("cid");    
    
    if (count($cid) == 0) {
    
      $msg = "You don't choose any days!";
    
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&layout=edit_renew_schedule&color='.$color_id.'&package_id='.JRequest::getVar('package_id'), $msg);                
    
    } else {
    
      $giftcode_model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');      
    
      $save = $giftcode_model->update_renew($cid, $color_id);      
    
      $msg = "Renew schedule updated!";
    
      $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecode&color='.$color_id.'&package_id='.JRequest::getVar('package_id'), $msg);                      
    
    }
  }
  
  public function copy(){
    
    $model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');  
    
    $collection_id = JRequest::getVar('cid');
    
    $edit = false;
    
    foreach($collection_id as $collection){
      
        $getCollection = $model->getGiftCodeCollection($collection);
        
        $max  = $getCollection->total_giftcodes;
        
        $giftcodes = $this->create_giftcode_collection($max);
        
        $giftcode_collection = array(
            'id' => $collection_setting_id,
            'code_length' => 0,
            'allow_to_repeat' => 0,
            'number_compostion' => null,
            'aphabet_composition' => null,
            'min_number_of_code' => 0,
            'max_number_of_code' => $max,
            'comment' => '',
            'color' => JRequest::getVar('color')            
        );
    
        
        $model->save($giftcodes,$giftcode_collection,false);
          
        $msg  = "Gift Code cloning";
            
       // $link ="index.php?option=com_awardpackage&view=giftcodecode&color=".JRequest::getVar('color')."&package_id=".JRequest::getVar('package_id');
  
    }
    $this->setRedirect("index.php?option=com_awardpackage&view=giftcodecode&color=".JRequest::getVar('color')."&package_id=".JRequest::getVar('package_id'), $msg);                      
    
    //$this->setRedirect($link,$msg);
  }
}