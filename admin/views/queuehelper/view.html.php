<?php
//restricted
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AwardpackageViewQueuehelper extends JViewLegacy {

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
    public function __construct($config = array()) {
	
		$this->package_id 		= JRequest::getInt('package_id');
		$this->presentation_id	= JRequest::getInt('presentation_id');
		parent::__construct($config);
	}
	
    function display($tpl = null) {
		//load sub menu
        AwardpackagesHelper::addSubmenuAward(JRequest::getCmd('view', 'referral'));
		
		$model			= & JModelLegacy::getInstance('awardprogress','AwardpackageModel');
		
		$this->items 	= $this->get('Items');
		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
		{
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
		
		$this->addToolBar();
        // Display the template
        parent::display($tpl);
    }
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
    protected  function addToolbar() {
		$layout = JRequest::getVar('layout');
		if($layout=='shuffled'){
			JToolBarHelper::title(JText::_('Process Symbol #1 ( Prize Value : $10 to $2345)'));
			JToolBarHelper::cancel('helperqueue.close','Close');
		}else{
			JToolBarHelper::title(JText::_('Queue Helper'));
			JToolBarHelper::cancel('queuehelper.close','Close');
		}
    }
	
	public function getTotalPrize(){
	
		$model			= & JModelLegacy::getInstance('awardprogress','AwardpackageModel');
		
		$process_id		= JRequest::getInt('process_id');
		
		if($process_id){
			$total_prize	= count($model->getPrize($process_id));
		}
		return $total_prize;
	}

}