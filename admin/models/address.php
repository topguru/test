<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelform');

class AwardpackageModelAddress extends JModelForm {
	/*
		sub package awardpackage
	*/
	public function getForm($data = array(), $loadData = true){
	
        // Get the form.
        $form = $this->loadForm('com_awardpackage.profile', 'profile', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
	}
	
    function getItem() {
        //request table 
        $row =& $this->getTable('Address');
        
        //request id 
        $id = JRequest::getInt('id');
        
        //load row 
        $row->load(array("id"=>$id));
        
        return $row;
    }

    protected function loadFormData() {
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
	
    function addItem($data = array()) {
        //load row 
        $row = $this->getTable('Address');
        
        //bind data
        if (!$row->bind($data)) {
            return JError::raiseWarning(500, $row->getError());
			return false;
        } else {
            if (!$row->store()) {
                return JError::raiseError(500, $row->getError());
            } else {
                return true;
            }
        }
        return true;
    }
	
	function info($id){
        //request table 
        $row =& $this->getTable('Address');
        
        //load row 
        $row->load(array("id"=>$id));
        
        return $row;
	}
	
}
