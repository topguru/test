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

class AwardpackageViewSelectWinner extends JViewLegacy {
    
    function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getVar('package_id');
		$this->presentation_id = JRequest::getVar('presentation_id');
		$this->winner_id = JRequest::getVar('winner_id');
        $this->layout = JRequest::getVar('layout');	
		$this->back = JRequest::getVar('back');
    }
    
    function display($tpl = null) {
		$this->form = $this->get('Form');
		$this->model  = $this->getModel();
		$this->data	  = $this->model->getWinners();
		$this->setting = $this->model->getSetting($this->presentation_id);
        if($this->layout==""){
            $this->addToolBar();
        }else if($this->layout=="unlocked_prize"){
            $this->unlockedToolBar();
        }else if($this->layout=='winners'){
            $this->winnersToolBar();
        }else if($this->layout=='actualwinners'){
			$this->actualWinnersToolBar();
		}else if($this->layout=='symbol_detail'){
			$this->symbolDetailToolBar();
		}else if($this->layout=='returnedsymbol'){
			$this->returnsymbolToolBar();
		}
        parent::display($tpl);
    }
    
    function addToolBar(){
        JToolBarHelper::title('Select Winner');
		$alt = 'Add';
		$bar=& JToolBar::getInstance( 'toolbar' );
		$bar->appendButton( 'Popup', 'new', $alt, 'index.php?option=com_awardpackage&tmpl=component&controller=selectwinner&task=addwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id, 550, 400 );
        //JToolBarHelper::addNew('selectwinner.add','Add');
		JToolBarHelper::save('selectwinner.save','Save');
        JToolBarHelper::deleteList('Are you sure ?','selectwinner.delete','Delete');
        JToolBarHelper::cancel('selectwinner.close','Close');
    }
    
    function unlockedToolBar(){
        JRequest::setVar('hidemainmenu','1');
        JToolBarHelper::title('Total of unlocked');
        JToolBarHelper::cancel('selectwinner.closeunlocked','Close');
    }
    
    function winnersToolBar(){
        JRequest::setVar('hidemainmenu','1');
        JToolBarHelper::title('Selected Winners');
        JToolBarHelper::cancel('selectwinner.closewinner','Close');
    }
	
	function actualWinnersToolBar(){
		JRequest::setVar('hidemainmenu','1');
		JToolBarHelper::title('Actual Winners');
		JToolBarHelper::cancel('selectwinner.closeactualwinner','Close');
	}
	
	function symbolDetailToolBar(){
		JRequest::setVar('hidemainmenu','1');
		JToolBarHelper::title('Symbol Details');
		JToolBarHelper::cancel('selectwinner.symboldetailclose','Close');
	}
	
	function returnsymbolToolBar(){
		JRequest::setVar('hidemainmenu','1');
		JToolBarHelper::title('Symbols Returned');
		JToolBarHelper::cancel('selectwinner.symbolreturnclose','Close');
	}
}

