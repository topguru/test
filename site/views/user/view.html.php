<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';

class AwardpackageViewUser extends JViewLegacy {

	function __construct($config = array()) {
		$editor 			= JFactory::getEditor();
		$this->editor		= $editor;
		parent::__construct($config);
	}

	function display($tpl = null){
		$this->form	= $this->get('Form');
		$this->item	= $this->get('item');
		$this->user	= JFactory::getUser();

		$model 	= JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		switch ($this->action){
			case 'no_package' :
				break;
			case 'registration_user' :
				break;
			case 'update_info' :
				$data = array();
				if(empty($this->user->name)){
				//isset($_SESSION['useraccount'])){
					$data = $_SESSION['useraccount'];
					$data['id'] =0;
					unset($_SESSION['useraccount']);
				}else{
					$data['firstname'] =$this->user->name;
					$data['username'] =$this->user->username;
					$data['email'] =$this->user->email;
					$data['id'] =$this->user->id;
				}
// $CountryList = AwardpackagesHelper::Countries_list(); use on php 5.5//				
$CountryList = new AwardpackagesHelper;
$countries = $CountryList->Countries_list();
				$this->assignRef('countries', $countries);
				$this->assignRef('data', $data);
				break;
			case 'new_user' :

				$data = array();
				if(isset($_SESSION['useraccount'])){
					$data = $_SESSION['useraccount'];
					unset($_SESSION['useraccount']);
				}
				//var_dump($data);
				// $CountryList = AwardpackagesHelper::Countries_list(); use on php 5.5//				
$CountryList = new AwardpackagesHelper;
$countries = $CountryList->Countries_list();
				$this->assignRef('countries', $countries);
				$this->assignRef('data', $data);
				break;
		}

		$this->addStyleSheet();
		parent::display($tpl);
	}

	function addStyleSheet(){
		$document    = JFactory::getDocument();
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/jquery.steps.js');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/jquery.steps.min.js');
		$document->addStyleSheet('administrator/templates/system/css/system.css');
		$document->addCustomTag(
			'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
			'<!--[if IE 7]>'."\n".
			'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<!--[if gte IE 8]>'."\n\n".
			'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
			);
	}
}
