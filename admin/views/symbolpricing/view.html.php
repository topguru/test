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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class AwardPackageViewSymbolPricing extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {

        $this->model = & JModelLegacy::getInstance('presentation', 'AwardPackageModel');
        $this->model_prize = & JModelLegacy::getInstance('prize', 'AwardPackageModel');
        $this->data = $this->model->checkSymbolPricing(JRequest::getVar('presentation_id'));
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        $layout = JRequest::getVar('layout');
        JToolBarHelper::title(JText::_('Presentation ' . JRequest::getVar('presentation_id')));
        // If not checked out, can save the item.
        if ($layout == "") {
            JToolBarHelper::addNew('symbolpricing.add', 'Add');
            JToolBarHelper::deleteList('Are you sure?', 'symbolpricing.delete', 'Delete');
            JToolBarHelper::save('symbolpricing.save', 'Save');
            JToolBarHelper::cancel('symbolpricing.close', 'JTOOLBAR_CLOSE');
        } else if($layout=='pricingdetails'){
            JToolBarHelper::cancel('detailclose','JTOOLBAR_CLOSE');
            JToolBarHelper::editList('edit','Edit');
            JToolBarHelper::save('detailsave','Save & Close');
        }else if($layout=='pricingbreakdown'){
            JToolBarHelper::cancel('breakdownclose','JTOOLBAR_CLOSE');
            JToolBarHelper::save('breakdownsave','Save & Close');
        }
    }

}
