<?php

/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class AwardpackageViewBpresentationlist extends JViewLegacy {

    function display($tpl = null) {
        $toolbar = JToolbar::getInstance('toolbar');
        CommunitySurveysHelper::initiate();
        JToolBarHelper::title(JText::_('Presentation Directory'), 'logo.png');
        $model = & JModelLegacy::getInstance('apresentationlist', 'AwardpackageModel');
        switch ($this->action) {
            case 'initiate' :
                $presentations = $model->getPresentationWithStatusActive(JRequest::getVar('package_id'), '1');
                if (empty($presentations)) {
                    $presentations = $model->getPresentationWithStatusActive(JRequest::getVar('package_id'), '0');
                }
                //$usergroups = $model->getAllUserGroups(JRequest::getVar('package_id'), '0');
                $usergroups = $model->getAllUserGroups(JRequest::getVar('package_id'), JRequest::getVar('idUserGroupsId'));
                //$presentationUserGroups = $model->getPresentationUserGroups(JRequest::getVar('package_id'));
                $presentationUserGroups = $model->getDetailPresentation(JRequest::getVar('package_id'), null, '1');
                if (empty($presentationUserGroups)) {
                    $presentationUserGroups = $model->getDetailPresentation(JRequest::getVar('package_id'), null, '0');
                }
                $process_symbol_id = JRequest::getVar('processSymbolId');

                $detailPresentations = $model->getDetailPresentation_2(JRequest::getVar('package_id'), null, null);
                //$process_symbol_id = JRequest::getVar('process_symbol_id');
                //$processPresentation = JRequest::getVar('processPresentation');
                $pids = JRequest::getVar('pids');
                if (!empty($detailPresentations) && count($detailPresentations) > 0) {
                    $detailPresentation = $detailPresentations[0];
                    if (((int) $detailPresentation->presentations) > 0) {
                        $this->assignRef('detailPresentation', $detailPresentation);
                    } else {
                        $param = null;
                        $this->assignRef('detailPresentation', $param);
                    }
                } else {
                    $param = null;
                    $this->assignRef('detailPresentation', $param);
                }

                $processPresentation = JRequest::getVar('processPresentation');

                $procs = $model->getProcessPresentation($processPresentation);
                $jml_pres = "";
                if (!empty($procs)) {
                    $proc = $procs[0];
                    $pres = $proc->selected_presentation;
                    $jml_pres = empty($proc->selected_presentation) ? '0' : count(explode(',', $pres));
                }

                $userGroupsPresentation = $model->getUserGroupPresentations(JRequest::getVar('package_id'));
                $this->assignRef('userGroupsPresentation', $userGroupsPresentation);

                $this->assignRef('processPresentation', $processPresentation);
                $this->assignRef('jml_pres', $jml_pres);
                $this->assignRef('presentations', $presentations);
                $this->assignRef('usergroups', $usergroups);
                $this->assignRef('presentationUserGroups', $presentationUserGroups);
                $this->assignRef('selectedGroups', $selectedGroups);
                $this->assignRef('process_symbol_id', $process_symbol_id);
                $this->assignRef('pids', $pids);
                //JToolBarHelper::deleteList('are you sure ?', 'apresentationlist.deleteSelectedPresentation', 'Delete');
                JToolBarHelper::divider();
                //JToolbarHelper::cancel('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));
                $toolbar->appendButton('Link', 'cancel', 'Close', JRoute::_('index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id')));
                break;
            case 'fund_prize_history':
                $package_id = JRequest::getVar('package_id');
                $result = $model->getFundPrizeHistory($package_id);
                $this->rows = $result['data'];
                $this->pagination = $result['pagination'];
                $this->lists = $result['lists'];
                JToolBarHelper::title(JText::_('Fund Prize History'), 'logo.png');
                JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
                break;
        }
        $this->model = $model;
        parent::display($tpl);
    }
}
