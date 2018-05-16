<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerGiftcodeCategory extends AwardPackageController
{
	function display() {
		parent::display();
	}
		
	function cancel() {
		JRequest::setVar('view', 'giftcodecategory');
		parent::display();
	}
	
	function edit() {
		
        $cid = JRequest::getVar('cid');
        
        foreach ($cid as $id) {
    	    
	    $model =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
            
	    $update = $model->edit($id);
        
	}
        
	$msg = "Category unlocked";
        
	$this->setRedirect('index.php?option=com_awardpackage&view=giftcodecategory&package_id='.JRequest::getVar('package_id').'&locked=false', $msg);			   
	}
    
	function save() {
		
		$i=0;
		
		$cid = JRequest::getVar('cid');
	
		$name = JRequest::getVar("name");
		
		$color_code = JRequest::getVar("color");
			
		foreach ($cid as $id) {
			
			$model =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
			
			$update = $model->save($id, $name[$i], $color_code[$i]);
			
			$i++;
			
		}
		
		$msg = "Category has been saved and unlocked";
		$this->setRedirect('index.php?option=com_awardpackage&view=giftcodecategory&package_id='.JRequest::getVar('package_id'), $msg);			   
	}    
    
    function publish() {
        $cid = JRequest::getVar("cid");
        switch($cid) {
            case 0 :
                $msg = "No Category Published";
                break;
            default :
                $msg = count($cid)." Categories Published";
                break;            
        }
        
        foreach ($cid as $id) {
    		$model =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
            $update = $model->published($id);
        }
        
        $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecategory&package_id='.JRequest::getVar('package_id'), $msg);		
    }

    function unpublish() {
        $cid = JRequest::getVar("cid");
        switch($cid) {
            case 0 :
                $msg = "No Category Unpublished";
                break;
            default :
                $msg = count($cid)." Categories Unpublished";
                break;            
        }
        
        foreach ($cid as $id) {
    		$model =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
            $update = $model->unpublished($id);
        }
        
        $this->setRedirect('index.php?option=com_awardpackage&view=giftcodecategory&package_id='.JRequest::getVar('package_id'), $msg);		
    }						
                        	        
	function do_save($edit){		
		$id = JRequest::getVar('id');
		$published = JRequest::getVar('published');
 			
		$data = array(
			'id' => $id,
            'published' => $published
		);
		
		$model =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
		$store = $model->save($data, $edit);
		
		return $store;
	}	
}

?>