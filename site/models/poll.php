<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.model');
class AwardpackageModelPoll extends JModelLegacy
{
	var $_categories;

	function get_categories($package_id) {

		if (empty($this->_categories)) {
			$this->_categories = $this->_getList("SELECT * FROM #__ap_categories
                                                    WHERE package_id = '$package_id' ORDER BY category_id ASC");
		}
		return $this->_categories;
	}
}