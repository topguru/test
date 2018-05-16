<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewAward extends JViewLegacy
{

	function display($tpl = null)
	{

	 
		$document= &JFactory::getDocument();		
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		/*
		$document->addStyleSheet(JURI::base(true).'/components/com_giftcode/asset/thickbox.css');
		$document->addScript(JURI::base(true).'/components/com_giftcode/asset/js/jquery-1.2.6.js');
		$document->addScript(JURI::base(true).'/components/com_giftcode/asset/js/thickbox.js');
		*/
		$model =& $this->getModel();
		
		$data = $model->getData();
		
		$userInfo = $model->getInfo();
		
		$datapiecesreceive=$model->getDataPiecesReceive();
        
		$this->assignRef('data',$data);
		
		$this->assignRef('datapr',$datapiecesreceive);
                
       	$mainmodel = & JModelLegacy::getInstance('award','AwardpackageModel');
                
        $userInfo  = $mainmodel->getInfo();
                
		parent::display($tpl);
	}

}