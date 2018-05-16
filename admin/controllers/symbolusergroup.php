<?php

/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/**
 * Refundpackagelist controller class.
 */
class AwardPackageControllerSymbolusergroup extends JControllerForm {

    function __construct() {
        $this->view_list = 'refundpackages';
        parent::__construct();
    }

    public function save_usergroup() {
        $model = $this->getModel('symbolusergroup');
        $data = JRequest::getVar('jform');
        $link = 'index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . $data['symbol_pricing_id'] . '&package_id=' . $data['package_id'] . '&field=' . $data['field'] . '&presentation_id=' . JRequest::getVar('presentation_id');
        if ($data['field'] == 'name') {
            if ($data['firstname'] == "" && $data['lastname'] == "") {
                $msg = 'First name or last name not be empty';
            } else {
                if ($model->save($data)) {
                    $msg = 'Data has been saved';
                }
            }
        } else {
            if ($model->save($data)) {
                $msg = 'Data has been saved';
            }
        }
        $this->setRedirect($link, $msg);
    }

    public function delete() {
        $id = JRequest::getVar('id');
        $model = $this->getModel('symbolusergroup');
        $link = 'index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . JRequest::getVar('symbol_pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=' . JRequest::getVar('field') . '&presentation_id=' . JRequest::getVar('presentation_id');
        if ($model->delete($id)) {
            $msg = 'Data has been deleted';
        }
        $this->setRedirect($link, $msg);
    }

    public function edit() {
        $link = 'index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . JRequest::getVar('symbol_pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=' . JRequest::getVar('field') . '&id=' . JRequest::getVar('id') . '&presentation_id=' . JRequest::getVar('presentation_id');
        $this->setRedirect($link);
    }

    public function close() {
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $this->setRedirect($link);
    }

    public function save() {
        $msg = 'Data has been saved';
        $link = 'index.php?option=com_awardpackage&view=symbolpricing&presentation_id=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id');
        $this->setRedirect($link, $msg);
    }

}