<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardPackageControllerPresentation extends AwardPackageController {

    function display() {
        parent::display();
    }

    function add() {
        if (JRequest::getVar('gcid') == '') {
            JRequest::setVar('presentation_id', JRequest::getVar('presentation_id'));
            JRequest::setVar('gcid', JRequest::getVar('presentation_id'));
        } else {
            JRequest::setVar('presentation_id', JRequest::getVar('gcid'));
            JRequest::setVar('gcid', JRequest::getVar('presentation_id'));
        }

        JRequest::setVar('package_id', JRequest::getVar('package_id'));

        JRequest::setVar('view', 'Presentation');
        JRequest::setVar('layout', 'create');
        parent::display();
    }

    function edit() {
        if (JRequest::getVar('gcid') == '') {
            JRequest::setVar('presentation_id', JRequest::getVar('presentation_id'));
            JRequest::setVar('gcid', JRequest::getVar('presentation_id'));
        } else {
            JRequest::setVar('presentation_id', JRequest::getVar('gcid'));
            JRequest::setVar('gcid', JRequest::getVar('presentation_id'));
        }
        JRequest::setVar('view', 'presentation');
        JRequest::setVar('layout', 'create');
        JRequest::setVar('package_id', JRequest::getVar('package_id'));
        parent::display();
    }


    function preview() {
        JRequest::setVar('layout', 'create');
        parent::display();
    }

    function viewgiftcode() {
        JRequest::setVar('view', 'viewgiftcode');
        JRequest::setVar('layout', 'default');
        parent::display();
    }

    function remove() {
        $ids = JRequest::getVar('cid');
        $model = & JModelLegacy::getInstance('Presentation', 'AwardPackageModel');
        $bool = $model->delete($ids);
        if ($bool == true)
            $msg = 'Presentation Deleted';
        else
            $msg = 'Presentation Delete false';
        $this->setRedirect('index.php?option=com_awardpackage&view=presentation&gcid=' . JRequest::getVar('presentation_id') . '&package_id=' . JRequest::getVar('package_id'), $msg);
    }

    function save() {
    	if (JRequest::getVar('pr_id') == '') {
            exit();
            $this->do_save(false);
            $msg = 'Presentation Created';
        } else {

            $id = $this->do_save(true);
            $this->do_queue($id);
            $msg = 'Presentation Edited';
        }
        $this->setRedirect('index.php?option=com_awardpackage&view=presentation&gcid=' . JRequest::getVar('pr_id') . '&package_id=' . JRequest::getVar('package_id'), $msg);
    }

    function apply() {
        if (JRequest::getVar('id') == '') {
            $this->do_queue($prs_id);
            $msg = 'Presentation Created';
        } else {
            $ngewa = $this->do_save(true);
            $this->do_queue($ngewa);
            $msg = 'Presentation Saved';
        }

        $this->setRedirect('index.php?option=com_awardpackage&controller=presentation&task=edit&cid[0]=' . $ngewa . '&presentation_id=' . JRequest::getVar('pr_id') . '&package_id=' . JRequest::getVar('package_id'), $msg);
    }

    function do_save($edit) {

        $data = array(
            'symbol_prize_id' => JRequest::getVar('symbol_prize_id'),
            'id' => JRequest::getVar('id'),
            'symbol_id' => JRequest::getVar('symbol_id'),
            'presentation_id' => JRequest::getVar('pr_id')
        );

        $model = & JModelLegacy::getInstance('Presentation', 'AwardPackageModel');

        $ngewi = $model->saveData($data);

        return $ngewi;
    }

    function do_queue($dt) {

        $data = array(
            'symbol_id' => JRequest::getVar('symbol_id'),
            'symbol_prize_id' => $dt
        );

        $model = & JModelLegacy::getInstance('Shuffle', 'ShuffleModel');

        $q = $model->queue($data);

        return $q;
    }

    public function cancel() {

        $link = 'index.php?option=com_awardpackage&view=presentation&gcid=' . JRequest::getVar('pr_id') . '&package_id=' . JRequest::getVar('package_id');

        $this->setRedirect($link);
    }

    public function persentationclose() {

        $link = 'index.php?option=com_awardpackage&view=presentationList&package_id=' . JRequest::getVar('package_id');

        $this->setRedirect($link);
    }

}

?>