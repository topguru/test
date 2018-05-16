<?php

defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

/*require_once JPATH_SITE.DS.'components'.DS.'com_awardpackage'.DS.'classes'.DS.'loader.php';*/
class AwardpackageController extends JControllerLegacy {

	function __construct() {
		parent::__construct();
	}

	function display($cachable = false, $urlparams = array()) {
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

		JRequest::setVar('view', JRequest::getCmd('view', 'Awardpackage'));
		parent::display($cachable);
	}

	function modules() {
		$package_id = JRequest::getVar('package_id');
		if ($package_id > 0) {
			JRequest::setVar('view', 'Modules');
		}
		parent::display($cachable);
	}

	function doclone() {

		$model = JModelLegacy::getInstance('main', 'AwardpackageModel');

		$package_id = JRequest::getVar('package_id');

		if ($model->doclone($package_id)) {

			$msg = "The award package has been cloned...";

			$link = 'index.php?option=com_awardpackage';
		}

		$this->setRedirect($link, $msg);
	}

	function remove() {
		$model = JModelLegacy::getInstance('main', 'AwardpackageModel');
		$model->delete(JRequest::getVar('cid'));
		$this->setRedirect('index.php?option=com_awardpackage', JTEXT::_(DELETED));
	}

	function publish() {
		$model = JModelLegacy::getInstance('main', 'AwardpackageModel');
		$model->publish(JRequest::getVar('cid'));
		//print_r(JRequest::getVar('cid'));
		$this->setRedirect('index.php?option=com_awardpackage', JTEXT::_(PUBLISHED));
	}

	function unpublish() {
		$model = JModelLegacy::getInstance('main', 'AwardpackageModel');
		$model->unpublish(JRequest::getVar('cid'));
		$this->setRedirect('index.php?option=com_awardpackage', JTEXT::_(UNPUBLISHED));
	}

	function entry($cachable = false) {
		$error = array();
		$valid = false;
		$param = JRequest::getVar('param');
		
		if(!empty($param) && $param == 'scUpdate') {
			if (JRequest::getVar('package_id')) {
				$model = JModelLegacy::getInstance('main', 'AwardpackageModel');
				$edit = $model->info(JRequest::getVar('package_id'));
				JRequest::setVar('edit', $edit);
			}
		} else {
			if (JRequest::getVar('package_name')) {
				$valid = true;
			} else {
				$error[] = "Package name is required";
				$valid = false;
			}
			if (JRequest::getVar('start_date') && JRequest::getVar('end_date')) {
				$date1 = explode("-", JRequest::getVar('start_date'));
				if (checkdate($date1[1], $date1[2], $date1[0])) {
					$valid = true;
				} else {
					$error[] = "Start date not valid";
					$valid = false;
				}
				$date2 = explode("-", JRequest::getVar('end_date'));
				if (checkdate($date2[1], $date2[2], $date2[0])) {
					$valid = true;
				} else {
					$error[] = "End date not valid";
					$valid = false;
				}
				$start = mktime(0, 0, 0, $date1[1], $date1[2], $date1[0]);
				$end = mktime(0, 0, 0, $date2[1], $date2[2], $date2[0]);
				if ($start > $end) {
					$error[] = "Start date should be less than end date";
					$valid = false;
				}
			} else {
				$error[] = "Start date and end date are required!";
				$valid = false;
			}
			$data['package_id'] = JRequest::getVar('package_id');
			$data['package_name'] = JRequest::getVar('package_name');
			$data['published'] = JRequest::getVar('published');
			$data['start_date'] = JRequest::getVar('start_date');
			$data['end_date'] = JRequest::getVar('end_date');
		}
		if ($valid == false) {			
			if (count($error) > 0) {
				JFactory::getApplication()->enqueueMessage(JText::_(implode("<br>", $error)), 'error');
			}
			JRequest::setVar('view', 'main');
			JRequest::setVar('layout', 'entry');
			parent::display($cachable);
		} else {
			$model = JModelLegacy::getInstance('main', 'AwardpackageModel');
			$model->save($data);
			if (JRequest::getVar('tmpl')) {
				echo '<script>javascript:window.parent.location="' . JRoute::_('index.php?option=com_awardpackage') . '";</script>';
			} else {
				$this->setRedirect('index.php?option=com_awardpackage', JTEXT::_(SAVED));
			}
		}
	}

	public function archive() {

		$date = JFactory::getDate(date());

		$package_id = JRequest::getVar('package_id');

		$model = JModelLegacy::getInstance('archive', 'AwardpackageModel');

		$total_user = count($model->getApUser($package_id));

		$total_prize = count($model->getPrize($package_id));

		$date_archived = $date->format("Y-m-d H:i:s");

		$date_archived = date('Y-m-d H:i:s');

		if ($model->save_archive($package_id, $total_user, $total_prize, $date_archived)) {
			if ($model->UpdateAwardPackage($package_id)) {
				$msg = 'Awardpackage has been archived';
			} else {
				$msg = 'Failed to archived';
			}
		}
		$link = JRoute::_('index.php?option=com_awardpackage');
		$this->setRedirect($link, $msg);
	}

}

?>
