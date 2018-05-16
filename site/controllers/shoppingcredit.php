<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class AwardpackageControllerShoppingcredit extends AwardpackageController {
    public function save_claim(){
        $model = $this->getModel('shoppingcredit');
        $record_id = JRequest::getVar('record_id');
        $claimed = JRequest::getVar('claimed');
        
        echo'<script type="text/javascript">';
        echo'
                function close(){
                    window.parent.location.reload();
                    window.parent.SqueezeBox.close();
                }
            ';
        echo'</script>';
        if($model->save_claim($record_id,$claimed)){
            echo'<body onload="close();"></body>';
        }
    }
}
?>
