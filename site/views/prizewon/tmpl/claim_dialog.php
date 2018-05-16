<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div style="font-size:13px;">
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=prizewon&task=save_claim&tmpl=component');?>" method="post">
    <button name="save" style="float: right; padding: 5px;">Save & Close</button>
    <div style="clear:both;"></div>
    <h4>
        Would you like to claim your prize now ?
    </h4> 
    <?php $claimed =  JRequest::getVar('claimed');?>
    <input type="radio" name="claimed" value="1" <?php if($claimed){?> checked="checked" <?php }?>><strong>Yes</strong>
    <br/>
    <br/>
    <input type="radio" name="claimed" value="0" <?php if(!$claimed){?> checked="checked" <?php }?>><strong>No</strong>
    <input type="hidden" name="winner_id" value="<?php echo JRequest::getVar('winner_id');?>">
</form>
</div>
