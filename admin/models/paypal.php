<?php 
/**
  * @package     Joomla.Administrator
  * @subpackage  com_awardpackage
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class AwardPackageModelPaypal extends JModelAdmin{

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_awardpackage.paypal', 'paypal', array('control' => 'jform', 'load_data' => $loadData));
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
	
	function getItem(){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__ap_paypal');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
    function addItem($data = array()) {

        $row = $this->getTable();

        if (!$row->bind($data)) {
            return JError::raiseWarning(500, $row->getError());
        } else {
            if (!$row->store()) {
                return JError::raiseError(500, $row->getError());
            } else {
                $db = $row->getDBO();
                $this->id = $db->insertid();
                return true;
            }
        }
        return false;
    }
}
?>