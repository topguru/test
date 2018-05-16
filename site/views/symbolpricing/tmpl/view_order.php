<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted Access');
$cids = JRequest::getVar('cid');
$date = JFactory::getDate();
$order_number = $this->model->chekOrderNumber();
if ($cids) {
    ?>
    <h1>Order details </h1>
   
    <form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=view_order&controller=symbolpricing&task=confirm'); ?>" method="post" name="adminForm" id="adminForm">
        <table width="100%" cellpadding="0" cellspacing="0" class="adminlist" style="border: 1px solid #ccc;">
            <thead>
                <tr>
                    <td colspan="10">
                        Order placed : <?php echo $date->toFormat(); ?>
                        <br/>
                        Order Number : <?php echo $order_number->order_number_id + 1; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="10">&nbsp;</td>
                </tr>
                <tr>
                    <th>Prize</th>
                    <th>Value</th>
                    <th>Symbol Pieces</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cids as $cid) {
					
                    $order_info = $this->model->CheckOrderInfo($cid);
                    ?>
                <input type="hidden" name="cid[]" value="<?php echo $cid;?>">
                <input type="hidden" name="prize_id[]" value="<?php echo $order_info->prize_id; ?>">
                <input type="hidden" name="symbol_pieces[]" value="<?php echo $order_info->symbol_pieces_id; ?>">
                <input type="hidden" name="price[]" value="<?php echo $order_info->virtual_price_breakdown; ?>">
                <tr class="row<?php echo $cid % 2; ?>">
                    <td align="center"> <?php echo $order_info->prize_name; ?></td>
                    <td align="right"><?php echo '$' . $order_info->prize_value; ?></td>
                    <td align="center"><img src="./administrator/components/com_awardpackage/asset/symbol/pieces/<?php echo $order_info->symbol_pieces_image; ?>" width="80"></td>
                    <td align="right"><?php echo '$' . $order_info->virtual_price_breakdown; ?></td>
                </tr>
                <?php
                $total = $order_info->virtual_price_breakdown + $total;
            }
            ?>
            </tbody>
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <input type="hidden" name="order_number" value="<?php echo $order_number->order_number_id+1;?>">
            <input type="hidden" name="order_date" value="<?php echo date('Y-m-d'); ?>">
            <tr>
                <td colspan="10" align="right"><strong>Order Total = <?php echo '$' . $total; ?></strong></td>
            </tr>
            
                <tr>
                    <td colspan="10" align="right" >
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing');?>" style="border:1px solid #ccc; padding: 5px; font-weight: bold; text-decoration: none;">BACK</a>
                        <button class="button">Confirm Order</button>
                    </td>
                </tr>
          
        </table>
    </form>
    <?php
}else{
    echo 'No item';
}
?>