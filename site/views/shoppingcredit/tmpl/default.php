<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted Access');
$mainmodel = & JModel::getInstance('award', 'AwardpackageModel');
$this->symbolmodel = & JModel::getInstance('symbolpricing', 'AwardpackageModel');
$userInfo = $mainmodel->getInfo();
$user = JFactory::getUser();
$date = JFactory::getDate();
foreach ($this->items as $data) {
    if (date('Y-m-d') <= $data->expire_date) {
        if (!$this->model->checkRecord($data->distribution_list_id)) {
            if ($this->total_depo > $data->shopping_credit_amount) {
                $data_transaction = new JObject();
                if ($this->model->updateStatus($data->distribution_list_id)) {
                    $data_transaction->package_id = $userInfo->package_id;
                    $data_transaction->user_id = $user->id;
                    $data_transaction->payment_gateway = 'Automatic payment';
                    $data_transaction->dated = $date->toFormat();
                    $data_transaction->transaction = 'Transaction shopping credit';
                    $data_transaction->debit = $data->shopping_credit_amount;
                    $data_transaction->status = 'Complate';

                    //save transaction
                    if ($this->symbolmodel->saveOrderDonation($data_transaction)) {
                        //save record 
                        $data_order = new JObject();
                        $data_order->shopping_credit_package_list_id = $data->shopping_credit_id;
                        $data_order->date_recived = date('Y-m-d');
                        $data_order->description = 'You must unlock and claim your shopping credits before ';
                        $data_order->amount = $data->shopping_credit_amount;
                        $data_order->unlocked = $data->shopping_credit_amount;
                        $data_order->user_id = $data->user_id;
                        $data_order->distribution_id = $data->distribution_list_id;
                        if (date('Y-m-d') <= $data->ready_for_use) {
                            $this->model->saveOrder($data_order);
                        }
                    }
                }
            }
        }
    }
}
$items = $this->model->getShoppingCredit($this->userInfo->package_id);
?>
<h1>Shopping Credit Distribution List</h1>
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=view_order'); ?>" method="post" name="adminForm" id="adminForm">
    <table width="100%" cellpadding="0" cellspacing="0" class="adminlist">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo JText::_('Date Received'); ?></th>
                <th><?php echo JText::_('Expire date'); ?></th>
                <th><?php echo JText::_('Amount'); ?></th>
                <th><?php echo JText::_('Action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($items as $i => $item) {
                ?>
                <tr class="row<?php echo $i; ?>">
                    <td align="center"><?php echo $j; ?></td>
                    <td align="center"><?php echo $item->distribute_date; ?></td>
                    <td align="center"><?php echo $item->expire_date; ?></td>
                    <td align="right">$ <?php echo number_format($item->shopping_credit_amount); ?></td>
                    <td align="center">
                        <?PHP if(date('Y-m-d')>$item->expire_date){
                            echo 'Expired';
                        }else{
                        ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing'); ?>">Donate</a>
                        <?PHP 
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>

        <tr>
            <td colspan="20">
                <i>You need Deposit to get shopping credits</i>
            </td>
        </tr>

    </table>
</form>
