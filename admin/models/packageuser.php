<?php
//redirect
defined('_JEXEC') or die('Restricted accessed');

jimport('joomla.application.component.modelform');

class AwardPackageModelPackageUser extends JModelForm {

	public function getForm($data = array(), $loadData = true) {
		// Initialise variables.
		$app = JFactory::getApplication();
		// Get the form.
		$form = $this->loadForm('com_awardpackage.usergroup', 'usergroup', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	function getItem() {
		//request table
		$row = $this->getTable('packageuser');

		//request id
		$id = JRequest::getInt('id');

		//load row
		$row->load($id);

		return $row;
	}

	protected function loadFormData() {
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	function addItem($data = array()) {
		//load row
		$row = $this->getTable();
		//bind data
		if (!$row->bind($data)) {
			return JError::raiseWarning(500, $row->getError());
			return false;
		} else {
			if (!$row->store()) {
				return JError::raiseError(500, $row->getError());
			} else {
				return true;
			}
		}

		return true;
	}

	public function delete($id){
		//load row
		$row = $this->getTable();

		//delete
		if(!$row->delete($id)){
			return JError::raiseWarning(500, $row->getError());
			return false;
		}
		return true;
	}

	public function deletenonapuser($id){
		//load row
		$row = $this->getTable('NonApUser');

		//delete
		if(!$row->delete($id)){
			return JError::raiseWarning(500, $row->getError());
			return false;
		}
		return true;
	}
	protected function preprocessForm(JForm $form, $data, $group = 'user') {
		parent::preprocessForm($form, $data, $group);
	}
}
?>