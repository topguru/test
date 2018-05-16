<?php

/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/**
 * Refundpackageslist list controller class.
 */
class AwardPackageControllerSymbolPricing extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function &getModel($name = 'refundpackage', $prefix = 'RefundModel') {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function close() {
        $link = 'index.php?option=com_awardpackage&view=presentationlist&package_id=' . JRequest::getVar('package_id');
        $this->setRedirect($link);
    }

    public function add() {
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        if ($model->saveSymbolPricing()) {
            $msg = 'Pricing added';
        } else {
            $msg = 'Data locked';
        }
        $this->setRedirect($link, $msg);
    }

    public function publish() {
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $cids = JRequest::getVar('cid');
        $task = JRequest::getVar('task');
        foreach ($cids as $cid) {
            if ($task == 'publish') {
                if ($model->pulbishSymbolPricing($cid)) {
                    $msg = 'Pricing published';
                }
            } else {
                if ($model->unpulbishSymbolPricing($cid)) {
                    $msg = 'Pricing unpublished';
                }
            }
        }
        $this->setRedirect($link, $msg);
    }

    public function unpulbish() {
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $cids = JRequest::getVar('cid');
        foreach ($cids as $cid) {
            if ($model->unpulbishSymbolPricing($cid)) {
                $msg = 'Pricing unpublished';
            }
        }
        $this->setRedirect($link, $msg);
    }

    public function delete() {
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $cids = JRequest::getVar('cid');
        foreach ($cids as $cid) {
            if ($model->deleteSymbolPricing($cid)) {
                $msg = 'Pricing Deleted';
            }
        }
        $this->setRedirect($link, $msg);
    }

    public function insert_price() {
        $prize_id = JRequest::getVar('prize_id');
        $symbol_id = JRequest::getVar('symbol_id');
        $pricing_id = JRequest::getVar('pricing_id');
        $price_from = JRequest::getVar('price_from');
        $price_to = JRequest::getVar('price_to');
        $prize_value = JRequest::getVar('prize_value');
        $price_from_form = JRequest::getVar('price_from_form');
        $price_to_form = JRequest::getVar('price_to_form');
        $cids = JRequest::getVar('cid');
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        if ($price_from_form > $price_to_form) {
            $msg = 'error price range';
        } else {
            if ($cids) {
                foreach ($cids as $details_id) {
                    //echo $details_id;
                    $save = $model->addPricingDetail($price_from_form, $price_to_form, $pricing_id, $prize_id, $symbol_id, $price_from, $price_to, $prize_value, $details_id);
                }
            } else {
                $details_id = JRequest::getVar('details_id');
                if ($details_id) {
                    $save = $model->addPricingDetail($price_from_form, $price_to_form, $pricing_id, $prize_id, $symbol_id, $price_from, $price_to, $prize_value, $details_id);
                } else {
                    $details_id = 0;
                    $save = $model->addPricingDetail($price_from_form, $price_to_form, $pricing_id, $prize_id, $symbol_id, $price_from, $price_to, $prize_value, $details_id);
                }
            }
        }

        $link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id');
        $this->setRedirect($link);
    }

    public function insert_price_breakdown() {
        $price_from = JRequest::getVar('price_from');
        $price_to = JRequest::getVar('price_to');
        $price_from_form = JRequest::getVar('price_from_form');
        $price_to_form = JRequest::getVar('price_to_form');
        $details_id = JRequest::getVar('details_id');
        $pieces_id = JRequest::getVar('pieces_id');
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $cids = JRequest::getVar('cid');
        if ($price_from_form > $price_to_form) {
            $msg = 'error price range';
        } else {
            if ($cids) {
                foreach ($cids as $cid) {
                    $model->addPricingBreakDown($details_id, $price_from_form, $price_to_form, $price_from, $price_to, $pieces_id, $cid);
                }
            } else {
                $cid = 0;
                $model->addPricingBreakDown($details_id, $price_from_form, $price_to_form, $price_from, $price_to, $pieces_id, $cid);
            }
        }

        $link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=pricingbreakdown&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id') . '&details_id=' . JRequest::getVar('details_id');
        $this->setRedirect($link, $msg);
    }

    public function edit() {
        $cids = JRequest::getVar('cid');
        foreach ($cids as $cid) {

            $link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id') . '&details_id=' . $cid;
        }
        $this->setRedirect($link);
    }

    public function detailclose() {
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $this->setRedirect($link);
    }

    public function detailsave() {
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $this->setRedirect($link);
    }

    public function save() {
        $model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        if ($model->createSymbolPricing()) {
            $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
            $msg = 'Data has been saved';
        } else {
            $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        }
        $this->setRedirect($link, $msg);
    }

    public function breakdownclose() {
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id');
        $this->setRedirect($link);
    }

    public function breakdownsave() {
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id');
        $msg = 'Data has been saved';
        $this->setRedirect($link, $msg);
    }

}