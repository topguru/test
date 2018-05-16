<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class AwardpackageControllerPrizewon extends AwardpackageController{
	
	public function save_claim(){
		$model = & JModelLegacy::getInstance('prizewon', 'AwardpackageModel');
		$mainmodel = & JModelLegacy::getInstance('award', 'AwardpackageModel');
		$user_info = $mainmodel->getInfo();
		$winner_id = JRequest::getVar('winner_id');
        echo'<script type="text/javascript">';
        echo'
                function close(){
                    window.parent.location.reload();
                    window.parent.SqueezeBox.close();
                }
            ';
        echo'</script>';
		if($model->save_claim($winner_id,$user_info)){
			 echo'<body onload="close();"></body>';
		}
	}
} 
?>