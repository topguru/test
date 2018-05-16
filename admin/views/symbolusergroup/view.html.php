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
class AwardPackageViewsymbolusergroup extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        //$this->state = $this->get('State');
        //$this->item = $this->get('Item');
        $this->form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        $this->countries = AwardpackagesHelper::Countries_list();
        $this->model = $this->getModel('symbolusergroup');
        $this->search_result = $this->model->search_result();
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {

        $user = JFactory::getUser();

        JToolBarHelper::back();
        
        JToolBarHelper::title('Symbol Pricing Group');

        JToolBarHelper::save('save', 'COM_REFUND_SAVE_CLOSE');
        
        JToolBarHelper::cancel('close','JTOOLBAR_CLOSE');

        $document = &JFactory::getDocument();
        
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
        $document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');
    }

}
