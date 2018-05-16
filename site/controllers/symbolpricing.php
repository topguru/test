<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class AwardpackageControllerSymbolPricing extends AwardpackageController {

    public function confirm() {
        $model = & JModelLegacy::getInstance('symbolpricing', 'AwardpackageModel');
        $prize_id = JRequest::getVar('prize_id');
        $symbol_pieces = JRequest::getVar('symbol_pieces');
        $price = JRequest::getVar('price');
        $data['order_total'] = JRequest::getVar('total');
        $data['order_number'] = JRequest::getVar('order_number');
        $data['order_date'] = JRequest::getVar('order_date');
        $config = & JFactory::getConfig();
        $ip = $_SERVER['REMOTE_ADDR'];
        $mailer = & JFactory::getMailer();
        $sender = array(
            $config->getValue('config.mailfrom'),
            $config->getValue('config.fromname')
        );
        $mailer->setSender($sender);
        $user = JFactory::getUser();
        $mailer->addRecipient($user->email);
        $cids = JRequest::getVar('cid');
        $order_date = JRequest::getVar('order_date');
        $total = JRequest::getVar('total');
        $order_number = JRequest::getVar('order_number');
        $url = JFactory::getURI();
        $msg = '<table width="100%">';
        $msg.='<tr>
                    <td colspan="10">Order placed:' . $order_date . '
                        <br/>
                        Order Number :' . $order_number . '
                    </td>
               </tr>
               <tr>
                    <td align="center">Prize</td>
                    <td align="center">Value</td>
                    <td align="center">Symbol Pieces</td>
                    <td align="center">Price</td>
               </tr>';
        foreach ($cids as $cid) {
            $order_info = $model->CheckOrderInfo($cid);
            $msg.='<tr>
                        <td align="center">' . $order_info->prize_name . '</td>
                        <td align="center">$' . $order_info->prize_value . '</td>
                        <td align="center"><img src="' . $url->base() . '/administrator/components/com_awardpackage/asset/symbol/pieces/' . $order_info->symbol_pieces_image . '"></td>
                        <td align="center">$' . $order_info->virtual_price_breakdown . '</td>
                   </tr>
                   <tr>
                        <td colspan="10" align="center">&nbsp;</td>
                   </tr>
                   
                    ';
        }
        $msg.='<tr>
                        <td colspan="10" align="right"><strong>Order Total = $ ' . $total . '</strong></td>
                   </tr>';
        $msg.='</table>';
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($msg);


        if ($ip != '127.0.0.1') {
            $send = & $mailer->Send();
            if ($send !== true) {

                echo 'Error sending email: ' . $send->message;
            } else {

                echo 'Mail sent';
            }
        }
        $i = 0;
        $total =0;
        foreach ($prize_id as $prize) {
            $data['prize_id'] = $prize;
            $data['symbol_pieces'] = $symbol_pieces[$i];
            $data['price'] = $price[$i];
            $save = $model->saveOrder($data);
            $total = $price[$i]+$total;
            $i++;
        }
        if ($save) {
            JRequest::setVar('view', 'symbolpricing');
            JRequest::setVar('layout', 'complate');
            $settingmodel = & JModelLegacy::getInstance('settings', 'AwardpackageModel');
            if ($settingmodel->invar('test_mode', 0) == 1) {
                $destination = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                $business = $settingmodel->invar('sandbox', 'seller_1315922650_biz@gmail.com');
                $test = 1;
            } else {
                $destination = 'https://www.paypal.com/cgi-bin/webscr';
                $business = $settingmodel->invar('business', '');
                $test = 0;
            }
            $user = JFactory::getUser();
            $currency_code = $settingmodel->invar('currency_code', '');
            $notify_url = JURI::base() . 'index.php?option=com_awardpackage&view=symbolpricing';
            $return_url = JURI::base() . 'index.php?option=com_awardpackage&view=symbolpricing&layout=thankyou&donation='.$order_number;
            JRequest::setVar('destination',$destination);
            JRequest::setVar('bussiness',$business);
            JRequest::setVar('currency_code',$currency_code);
            JRequest::setVar('custom',$user->id);
            JRequest::setVar('ordernumber',$order_number);
            JRequest::setVar('notify_url',$notify_url);
            JRequest::setVar('return_url',$return_url);
            JRequest::setVar('amount',$total);
            parent::display($cachable);
            //$link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=thankyou';
        } else {
            $link = 'index.php?option=com_awardpackage&view=symbolpricing';
            $this->setRedirect($link);
        }
    }

}

?>