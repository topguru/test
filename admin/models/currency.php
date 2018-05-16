<?php 
/**
  * @package     Joomla.Administrator
  * @subpackage  com_awardpackage
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class AwardPackageModelCurrency extends JModelAdmin{

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_awardpackage.currency', 'currency', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_banners.edit.banner.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
}
?>