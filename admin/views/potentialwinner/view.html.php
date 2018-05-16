<?php

/*
 * @this code write by Kadeyasa<kadeyasa@gmail.com>
 * awardpackage component select winner part 
 * 2013-01-28
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class AwardpackageViewpotentialwinner extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getVar('package_id');
        $this->field = JRequest::getVar('field');
        $this->class = ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';
        $this->id = JRequest::getVar('id');
        $this->presentation_id = JRequest::getVar('presentation_id');
    }

    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->model = $this->getModel();
        $this->search_result = $this->model->search_result();
        $this->countries = AwardpackagesHelper::Countries_list();
        $this->addToolBar();
        parent::display($tpl);
    }

    public function addToolBar() {
        JToolBarHelper::title('Potential Winner');
        JToolBarHelper::cancel('potential_cancel', 'Cancel');
        JToolBarHelper::save('potential_save', 'Save & Close');
        $document = &JFactory::getDocument();
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
        $document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');
    }

}

?>
