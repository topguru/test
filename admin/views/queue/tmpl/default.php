<?php 
defined('_JEXEC') or die('Restricted access'); 
?>
<style>
th { text-align: center; font-weight: bold; }
td { text-align: center; }
</style>
<table class="table table-striped">
    <thead>
        <tr style="text-align:center; background-color:#CCCCCC">
            <th width="17%"><?php echo JText::_('COM_GIFT_CODE_CREATED');?></th>
            <th width="17%"><?php echo JText::_('COM_GIFT_CODE_USER');?></th>
            <th width="17%"><?php echo JText::_('COM_GIFT_CODE_NO_OF_GIFT_CODES');?></th>
            <th width="32%"><?php echo JText::_('COM_GIFT_CODE_NO_OF_GIFT_CODES_REDEEMED');?></th>
            <th width="17%"><?php echo JText::_('COM_GIFT_CODE_QUEEN');?></th>            
        </tr>       
    </thead>
    <tbody>
    <?php 
    $giftcode_collection = $this->giftcode_collection;
    foreach ($giftcode_collection as $gc_collection) {
      if($gc_collection->package_id == JRequest::getVar('package_id')):
    ?>
        <tr>
            <td><?php echo $gc_collection->created_date_time; ?></td>
            <td><?php echo $this->model->get_name($gc_collection->user_id) != "" ? $this->model->get_name($gc_collection->user_id) : "-"; ?></td>
            <td><?php echo $gc_collection->total_giftcodes; ?></td>
            <td>-</td>
            <td><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=queue&layout=profile&gcid=".$gc_collection->id."&package_id=".JRequest::getVar('package_id')) ;?>"><?php echo JText::_('COM_GIFT_CODE_VIEW');?></a></td>            
        </tr>
    <?php
    endif;
    }
    ?>
    </tbody>
</table>
