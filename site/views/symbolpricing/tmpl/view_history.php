<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted Access');
?>
<h1>Order history</h1>
<table width="100%" cellpadding="0" cellspacing="0" class="adminlist" style="border: 1px solid #ccc;">
    <thead>
        <?php
        $orders = $this->model->getOrder();
        $temp = '';
        $test = '';
        $img = '';
        foreach ($orders as $order) {
            if ($temp != $order->order_number_id) {

                echo'<tr>
                    <td>';
                echo 'Order Placed :' . $order->order_date;
                echo '<br/>';
                echo 'Order Number :' . $order->order_number_id;
                echo '<br/>';
                echo 'Order total :$' . $order->order_total;
                echo'   </td>
                    <td>';
                $order_details = $this->model->getOrderDetails($order->order_number_id);
                foreach ($order_details as $detail_order) {
                    $pieces = $this->model->getPieces($detail_order->symbol_pieces);
                    echo '<img src="./administrator/components/com_awardpackage/asset/symbol/pieces/' . $pieces->symbol_pieces_image . '" width="80">';
                }
                 echo '</td>';
                if ($order->status) {
                   echo'
                    <td><a href="' . JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=history_detail&order_number_id=' . $order->order_number_id) . '">View Details</a>
                    ';
                }else{
                    echo'<td>Pending order</td>';
                }
                echo '</tr>';
            }

            $temp = $order->order_number_id;
        }
        ?>
        <tr>
            <td colspan="10">
                <i>You need to complate payment order if order staus is <b>pending order</b></i>
            </td>
        </tr>
    </thead>
</table>