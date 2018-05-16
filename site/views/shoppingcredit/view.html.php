<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AwardPackageViewShoppingcredit extends JView {

    public function display($tpl = null) {
        $this->mainmodel = & JModel::getInstance('award', 'AwardpackageModel');
        $this->userInfo = $this->mainmodel->getInfo();
        $user = JFactory::getUser();
        $this->model = $this->getModel('shoppingcredit');
        $this->items = $this->model->getShoppingCredit($this->userInfo->package_id);
        $deposit_total = $this->model->getDonationTotal($user->id);
        $transaction_total = $this->model->getTransactionTotal($user->id);
        $items = $this->get('Items');
        foreach ($items as $data) {
            $this->row = $this->model->getDistributionListData($data->shopping_credit_package_list_id);
        }
        $this->total_depo = $deposit_total - $transaction_total;
        $this->data = $this->get('Items');
        $pagination = $this->get('Pagination');
        $this->pagination = $pagination;
        $document = & JFactory::getDocument();
        $document->addStyleSheet('administrator/templates/system/css/system.css');
        $document->addStyleSheet('./media/system/css/modal.css');
        $document->addCustomTag(
                '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
                '<!--[if IE 7]>' . "\n" .
                '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<!--[if gte IE 8]>' . "\n\n" .
                '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );
        parent::display($tpl);
    }

}

?>