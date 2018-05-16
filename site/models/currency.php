<?PHP 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class AwardpackageModelCurrency extends JModelList{
	
	public function getListQuery(){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_currencies");
		return $query;
	}
	
	public function getPaymentOptions(){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_payment_options");
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
?>