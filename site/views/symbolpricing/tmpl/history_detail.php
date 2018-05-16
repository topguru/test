<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted Access');
$dates = $this->model->getOrderDetails(JRequest::getVar('order_number_id'));
foreach ($dates as $date) {
    $data = $date->order_date;
}
?>
<h1>History Details</h1>
<table width="100%" cellpadding="0" cellspacing="0" class="adminlist" style="border: 1px solid #ccc;">
    <thead>
        <tr>
            <td colspan="10">
                Order placed : <?php echo $data; ?>
                <br/>
                Order Number : <?php echo JRequest::getVar('order_number_id'); ?>
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
        <?php
        $total=0;
        foreach ($dates as $item) {
            $pieces = $this->model->getPieces($item->symbol_pieces);
            $prize_info = $this->model->getPrizeInfo($item->prize_id);
            ?>
            <tr>
                <td><?php echo $prize_info->prize_name;?></td>
                <td align="right"><?php echo'$'. $prize_info->prize_value;?></td>
                <td align="center">
                    <?php
                     echo '<img src="./administrator/components/com_awardpackage/asset/symbol/pieces/'.$pieces->symbol_pieces_image.'" width="80">';
                    ?>
                </td>
                <td align="right">
                    <?php echo'$'.$item->price;?>
                </td>
            </tr>
            <?php
            $total=$total+$item->price;
        }
        ?>
            <tr>
                <td colspan="10" align="right"><b>Total : </b><?php echo'$'. $total;?></td>
            </tr>
    </thead>
</table>