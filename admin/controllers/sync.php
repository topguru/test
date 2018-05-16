<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerSync extends JControllerLegacy
{

	function __construct(){

		parent::__construct();
	}

	function edit(){


	}
	function sync_award_schedule()
	{
		$package_id = JRequest::getVar("package_id");
		 
		$category_1 = $_POST['category_1'];
		$category_2 = $_POST['category_2'];
		$category_3 = $_POST['category_3'];
		$category_4 = $_POST['category_4'];
		$category_5 = $_POST['category_5'];
		$category_6 = $_POST['category_6'];
		$category_7 = $_POST['category_7'];

		$categories = array();

		$category_1 != "" ? $categories[] = $category_1 : "";
		$category_2 != "" ? $categories[] = $category_2 : "";
		$category_3 != "" ? $categories[] = $category_3 : "";
		$category_4 != "" ? $categories[] = $category_4 : "";
		$category_5 != "" ? $categories[] = $category_5 : "";
		$category_6 != "" ? $categories[] = $category_6 : "";
		$category_7 != "" ? $categories[] = $category_7 : "";

		$award_schedule = $this->checking_award_schedule($categories[0], $package_id) == "" ? "EMPTY" : "NO";

		if ($award_schedule == "EMPTY") {
			$this->setRedirect("index.php?option=com_awardpackage&view=award_category&layout=free&package_id=$package_id", "Empty award schedule for this category");
		} else {
			$number_categories = array(1, 2, 3, 4, 5, 6, 7);
			$master_award_schedule = $this->checking_award_schedule($categories[0], $package_id);

			foreach ($number_categories as $number_category) {
				if ($number_category != $categories[0]) {

					$sunday = $master_award_schedule[3] == 1 ? "sunday" : "";
					$monday = $master_award_schedule[4] == 1 ? "monday" : "";
					$tuesday = $master_award_schedule[5] == 1 ? "tuesday" : "";
					$wednesday = $master_award_schedule[6] == 1 ? "wednesday" : "";
					$thursday = $master_award_schedule[7] == 1 ? "thursday" : "";
					$friday = $master_award_schedule[8] == 1 ? "friday" : "";
					$saturday = $master_award_schedule[9] == 1 ? "saturday" : "";

					$award_schedule_model =& JModelLegacy::getInstance('Award_schedule','AwardpackageModel');

					$this->removing_award_schedule($number_category, $package_id);

					$cloning_award_schedule = $award_schedule_model->save(
					$sunday, $monday, $tuesday, $wednesday, $thursday, $friday,
					$saturday, $package_id, $number_category
					);
				}
			}
			$message = "The award schedule has been sync";
			$this->setRedirect("index.php?option=com_awardpackage&view=award_category&layout=free&package_id=$package_id", $message);

		}

	}

	function sync_award_package()
	{
		$package_id = JRequest::getVar("package_id");

		$category_1 = $_POST['category_1'];
		$category_2 = $_POST['category_2'];
		$category_3 = $_POST['category_3'];
		$category_4 = $_POST['category_4'];
		$category_5 = $_POST['category_5'];
		$category_6 = $_POST['category_6'];
		$category_7 = $_POST['category_7'];

		$categories = array();

		$category_1 != "" ? $categories[] = $category_1 : "";
		$category_2 != "" ? $categories[] = $category_2 : "";
		$category_3 != "" ? $categories[] = $category_3 : "";
		$category_4 != "" ? $categories[] = $category_4 : "";
		$category_5 != "" ? $categories[] = $category_5 : "";
		$category_6 != "" ? $categories[] = $category_6 : "";
		$category_7 != "" ? $categories[] = $category_7 : "";

		$user_package = count($this->checking_user_package($categories[0], $package_id)) == 0 ? "EMPTY" : "NO";

		if ($user_package == "EMPTY") {
			$this->setRedirect("index.php?option=com_awardpackage&view=award_category&layout=free&package_id=$package_id", "Empty user package for this category");
		} else {
			$number_categories = array(1, 2, 3, 4, 5, 6, 7);
			$master_user_packages = $this->checking_user_package($categories[0], $package_id);

			foreach ($number_categories as $number_category) {
				if ($number_category != $categories[0]) {
					$this->removing_user_package($number_category, $package_id);

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

	}

	function sync_non_award_package()
	{
		echo "Sync Non Award Package";
	}

	function creating_user_package($data) {
		$db =& JFactory::getDBO();
		$query = "INSERT INTO `#__ap_user_packages` (category_id, package_id, firstname, lastname)
                  VALUES ('".$data['category_id']."', '".$data['package_id']."', '".$data['firstname']."', '".$data['lastname']."')";      

		$db->setQuery($query);
		$db->query();
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

	private function removing_user_package($category_id, $package_id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM #__ap_user_packages
              WHERE category_id = '$category_id'
              AND package_id = '$package_id'";
		$db->setQuery($query);
		$row = $db->loadRow();
		return $row;
	}

	private function checking_award_schedule($category_id, $package_id) {
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__ap_award_schedule
              WHERE category_id = '$category_id'
              AND package_id = '$package_id'";
		$db->setQuery($query);
		$row = $db->loadRow();
		return $row;
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

}
