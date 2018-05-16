<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewAwardcollection extends JViewLegacy
{

	function display($tpl = null)
	{

	 
		$document= &JFactory::getDocument();		
		/*$document->addStyleSheet(JURI::base(true).'/components/com_giftcode/asset/style.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_giftcode/asset/thickbox.css');
		$document->addScript(JURI::base(true).'/components/com_giftcode/asset/js/jquery-1.2.6.js');
		$document->addScript(JURI::base(true).'/components/com_giftcode/asset/js/thickbox.js');
        */
		$model =& $this->getModel();	
		$data = $model->getData();
		$datapiecesreceive=$model->getDataPiecesReceive();
        
		$this->assignRef('data',$data);
		$this->assignRef('datapr',$datapiecesreceive);	
		parent::display($tpl);
	}

}