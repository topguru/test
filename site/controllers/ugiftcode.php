<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUgiftcode extends JControllerLegacy {

	function __construct(){
		parent::__construct();
		AwardPackageHelper::checkUser();
	}

	function getMainPage(){
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'main_page');
		$view->display();
	}
	
	function getHistoryGiftcode(){
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'getHistoryGiftcode');
		$view->display('history');
	}

	function onSelectCategory(){
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'select_category');
		$view->display('select_category');
	}
	
	function doSave(){
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'select_category');
		$view->display('select_category');
	}


 function update_symbol(){
		$app = JFactory::getApplication();
		$users = AwardPackageHelper::getUserData();
				$packageId = $users->package_id;
				$user_id = $users->id;
				var_dump();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );
			JArrayHelper::toInteger($ids['cid']);
//			$model->update_symbol_pieces($id,$user_id)
//$model->delete_categories($ids['cid'])
			if($model->update_symbol_pieces($ids['cid'],1)){
			        $giftcode =   JRequest::getVar('giftcode');
					$button_check =  JRequest::getVar('button_check');
					$giftcodeId =   JRequest::getVar('giftcodeId');
					$prize_id =   JRequest::getVar('prizeId');
					$symbol_id =   JRequest::getVar('SymbolId');
					$model->UpdateUserGiftcodes($giftcode, $giftcodeId,$user_id);
					$model->InsertSymbolQueue($ids['cid'], $giftcode, $giftcodeId,$user_id, $prize_id, $symbol_id,1 );
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('Symbol Accepted'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('No Symbol Selected'));
		}
	}
	
	function discard_symbol(){
		$app = JFactory::getApplication();
		$users = AwardPackageHelper::getUserData();
				$packageId = $users->package_id;
				$user_id = $users->id;
				var_dump();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );
			JArrayHelper::toInteger($ids['cid']);
//			$model->update_symbol_pieces($id,$user_id)
//$model->delete_categories($ids['cid'])
			if($model->update_symbol_pieces($ids['cid'],1)){
			        $giftcode =   JRequest::getVar('giftcode');
					$button_check =  JRequest::getVar('button_check');
					$giftcodeId =   JRequest::getVar('giftcodeId');
					$prize_id =   JRequest::getVar('prizeId');
					$symbol_id =   JRequest::getVar('SymbolId');
					//$model->UpdateUserGiftcodes($giftcode, $giftcodeId,$user_id);
					$model->InsertSymbolQueue($ids['cid'], $giftcode, $giftcodeId,$user_id, $prize_id, $symbol_id, 2 );
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('Discard Symbol'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage', false), JText::_('No Symbol Selected'));
		}
	}

	function save_symbol(){
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );

		$button_check = JRequest::getVar('button_check');
		$symbol_id = JRequest::getVar('prizeId');
		$giftcode_id = JRequest::getVar('giftcodeId');
		
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'main_page');
		$view->display();
		}
		
	function onSelectPrize(){
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );

		$category = JRequest::getVar('category');
		$prize_id = JRequest::getVar('prizeId');

		$users = AwardPackageHelper::getUserData();
		$userId = $users->ap_account_id;
		$packageId = $users->package_id;

		$presCategory = $model->getPresentationCategory(JRequest::getVar('category'), $packageId, $prize_id);
		$presentations = $presCategory->presentation;

		$symbols = array();
		foreach ($presentations as $presentation){
			$symbol = new stdClass();
			$symbol->symbol_image = $presentation->symbol_image;
			$symbol->pieces = $presentation->pieces;
			$symbol->rows = $presentation->rows;
			$symbol->cols = $presentation->cols;
			$symbols[] = $symbol;
		}		
		echo '<table class="table table-hover table-striped">';
		$item = count($symbols);
		$row = $item / 5;					
		for($i = 1, $lop = 0; $i <= ($row+1); $i++, $lop = $lop + 5) {
			echo '<tr>';
			for($j = $lop; $j < ($i*5) && $j < $item; $j++) {
				$rows = $symbols;
				$row = $rows[$j];
				echo '<td class="hidden-phone" valign="top">';
				echo '<img
							src="'. SYMBOL_IMAGES_URI . $row->symbol_image . '"
							style="width:100px;" />';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';		
		die();
	}
}