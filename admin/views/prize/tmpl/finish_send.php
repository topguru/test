<?php 
defined('_JEXEC') or die('Restricted Access');
$winner_id = JRequest::getVar('winner_id');
if($this->prize_model->update_claim($winner_id)){
?>
<h1>Prize success to sent</h1>
send prize is success
<?php
}
?>
