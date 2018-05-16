<?php 
//redirect
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

//create class
class AwardPackageModelCurrencies extends JModelList{
	
	public function __construct($config = array()){
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'code','code',
				'currency','currency'
			);
		}
		parent::__construct($config);
	}
	
	public function getListQuery(){
		$db			= 	&JFactory::getDbo();
		$query		=	$db->getQuery(TRUE);
		$query->select("*");
		$orderCol	= $this->state->get('list.ordering', 'id');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		if($orderCol){
			$query->order($db->escape($orderCol.' '.$orderDirn));
		}
		$query->from("#__ap_currencies");
		return $query;
	}
	
	public function getTable($type = 'currency', $prefix = '', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
}
?>