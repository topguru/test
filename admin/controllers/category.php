<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of Donation component
 */
class AwardpackageControllerCategory extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function __construct(){
		parent::__construct();
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
	}

	function display($cachable = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'Category'));

		// call parent behavior
		parent::display($cachable);
	}


	function edit(){
		JRequest::setVar('layout','form');
		parent::display($cachable);
	}

	function unlock(){
		$model 	=& JModelLegacy::getInstance('category','AwardpackageModel');
		$cids	=JRequest::getVar('cid');

		if(count($cids)>0){
			$post = array();
			$i = 0;
			foreach($cids as $cid){
				$post[$i]['id'] = $cid;
				$post[$i]['unlock'] = 1;
				$post[$i]['islocked'] = 1;
				$i++;
			}
			foreach ($post as $p) {
				$model->save_settings($p);
			}
			$message = JTEXT::_('DATA_UNLOCKED');
		}else{
			$message = JTEXT::_('Error to unlock');
		}

		$this->setRedirect('index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'), $message);
	}

	function unlock_2(){
		$model 	=& JModelLegacy::getInstance('category','AwardpackageModel');
		$cids	=JRequest::getVar('cid');
		//die();
		if(count($cids)>0){
			$post = array();
			$i = 0;
			foreach($cids as $cid){
				$post[$i]['id'] = $cid;
				$post[$i]['unlock'] = 1;
				$post[$i]['islocked'] = 1;
				$i++;
			}
			foreach ($post as $p) {
				$model->save_settings($p);
			}
			$message = JTEXT::_('DATA_UNLOCKED');
		}else{
			$message = JTEXT::_('Error to unlock');
		}

		$this->setRedirect('index.php?option=com_awardpackage&view=award_category&package_id='.JRequest::getVar('package_id'), $message);
	}


	function save_categories(){

		$model	 =& JModelLegacy::getInstance('category','AwardpackageModel');

		$cids 	 = JRequest::getVar('cid');

		$i	 = 0;

		$setting_ids = JRequest::getVar('setting_id');

		$colour_code 	= JRequest::getVar('colour_code');

		$category_name 	= JRequest::getVar('category_name');

		if(count($setting_ids)>0){

			foreach ($setting_ids as $setting_id){

				$data['colour_code'] 	= $colour_code[$i];

				$data['category_name']	= $category_name[$i];

				if($model->save_categories($data,$setting_id)){

					$return = 1;

					$post['id']	= $setting_id;

					$data['id']	= $setting_id;

					$post['unlock'] = 0;

					$post['islocked'] = 0;

					$model->update_giftcode($data, $setting_id);

					$model->save_settings($post);

				}
				$i++;
			}
		}else{
			$msg = "Select category to save";
		}

		if($return){

			$msg	= "Category has been saved";

		}
		$this->setRedirect('index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'),$msg);
	}
	function save_categories_2(){

		$model	 =& JModelLegacy::getInstance('category','AwardpackageModel');

		$cids 	 = JRequest::getVar('cid');

		$i	 = 0;

		$setting_ids = JRequest::getVar('setting_id');

		$colour_code 	= JRequest::getVar('colour_code');

		$category_name 	= JRequest::getVar('category_name');
		
		$giftcode_quantity = JRequest::getVar('giftcode_quantity');

		if(count($setting_ids)>0){

			foreach ($setting_ids as $setting_id){

				$data['colour_code'] 	= $colour_code[$i];

				$data['category_name']	= $category_name[$i];
				
				$data['giftcode_quantity'] = $giftcode_quantity[$i];

				if($model->save_categories($data,$setting_id)){

					$return = 1;

					$post['id']	= $setting_id;

					$data['id']	= $setting_id;

					$post['unlock'] = 0;

					$post['islocked'] = 0;

					$model->update_giftcode($data, $setting_id);

					$model->save_settings($post);

				}
				$i++;
			}
		}else{
			$msg = "Select category to save";
		}

		if($return){
			$msg	= "Category has been saved";
		}
		$this->setRedirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar('package_id'),$msg);
	}


	function publish() {
		$modul = JRequest::getVar('modul');
		$cid = JRequest::getVar("cid");
		switch($cid) {
			case 0 :
				$msg = "No Category Published";
				break;
			default :
				$msg = count($cid)." Categories Published";
				break;
		}
		foreach ($cid as $id) {
			$model =& JModelLegacy::getInstance('category','AwardpackageModel');
			$update = $model->published($id);
		}
		if($modul == 'free') {
			$this->setRedirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar('package_id'),$msg);
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'),$msg);	
		}
		
	}
	
	function unpublish() {
		$modul = JRequest::getVar("modul"); 
		$cid = JRequest::getVar("cid");
			
		switch($cid) {
			case 0 :
				$msg = "No Category Unpublished";
				break;
			default :
				$msg = count($cid)." Categories Unpublished";
				break;
		}
			
		foreach ($cid as $id) {
			$model =& JModelLegacy::getInstance('category','AwardpackageModel');
			$update = $model->unpublished($id);
		}
		
		if($modul == 'free') {
			$this->setRedirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar('package_id'),$msg);
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'),$msg);	
		}
		
	}

	function sync_award_schedule() {
		$package_id = JRequest::getVar("package_id");
		$categories = JRequest::getVar('category_id');
		foreach ($categories as $category) {
			$sunday = "sunday";
			$monday = "monday";
			$tuesday = "tuesday";
			$wednesday = "wednesday";
			$thursday = "thursday";
			$friday = "friday";
			$saturday = "saturday";
			$award_schedule_model =& JModelLegacy::getInstance('Award_schedule','AwardpackageModel');
			$this->removing_award_schedule($category, $package_id);
			$cloning_award_schedule = $award_schedule_model->save(
			$sunday, $monday, $tuesday, $wednesday, $thursday, $friday,
			$saturday, $package_id, $category
			);
		}
		$message = "The award schedule has been sync";
		$this->setRedirect("index.php?option=com_awardpackage&view=award_category&package_id=".$package_id, $message);
	}

	function sync_award_package() {
		$package_id = JRequest::getVar('package_id');
		$categories = JRequest::getVar('category_id');

		$master_user_packages = $this->checking_user_package($categories[0], $package_id);

		foreach ($categories as $category) {
			if ($category != $categories[0]) {
				$this->removing_user_package($category, $package_id);

				foreach ($master_user_packages as $master_user_package) {
					$user_package = array(
			              "package_id" => $master_user_package->package_id,
			              "category_id" => $number_category,
			              "population" => $master_user_package->population,
			              "firstname" => $master_user_package->firstname,
			              "lastname" => $master_user_package->lastname,
			              "email" => $master_user_package->email,
			              "from_age" => $master_user_package->from_age,
			              "to_age" => $master_user_package->to_age,
			              "gender" => $master_user_package->gender,
			              "street" => $master_user_package->street,
			              "city" => $master_user_package->city,
			              "state" => $master_user_package->state,
			              "post_code" => $master_user_package->post_code,
			              "country" => $master_user_package->country                                                                      
					);
					$this->creating_user_package($user_package);
				}
			}
		}

		$message = "The award package has been sync";
		$this->setRedirect("index.php?option=com_awardpackage&view=award_category&layout=free&package_id=$package_id", $message);



	}

	private function removing_award_schedule($category_id, $package_id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM #__ap_award_schedule
              WHERE category_id = '$category_id'
              AND package_id = '$package_id'";
		$db->setQuery($query);
		$row = $db->loadRow();
		return $row;
	}

	private function checking_user_package($category_id, $package_id) {
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__ap_user_packages
              WHERE category_id = '$category_id'
              AND package_id = '$package_id'";
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}

	function creating_user_package($data) {
		$db =& JFactory::getDBO();
		$query = "INSERT INTO `#__ap_user_packages` (category_id, package_id, firstname, lastname)
                  VALUES ('".$data['category_id']."', '".$data['package_id']."', '".$data['firstname']."', '".$data['lastname']."')";      

		$db->setQuery($query);
		$db->query();
	}

	private function removing_user_package($category_id, $package_id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM #__ap_user_packages
              WHERE category_id = '$category_id'
              AND package_id = '$package_id'";
		$db->setQuery($query);
		$row = $db->loadRow();
		return $row;
	}

	function sync_non_award_package(){
		$package_id = JRequest::getVar('package_id');
		$message = "The award package has been sync";
		$this->setRedirect("index.php?option=com_awardpackage&view=award_category&layout=free&package_id=$package_id", $message);
	}

	function giftcode(){
		$this->setRedirect('index.php?option=com_awardpackage&view=giftcode&package_id=' . JRequest::getVar('package_id'),$vName == 'donation', $message);
	}
}
