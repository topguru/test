<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class AwardPackageControllerCurrencies extends JControllerAdmin {
	
	public function __construct($config = array())
	{		
		parent::__construct($config);
	}
	
	public function getModel($name = 'currency', $prefix = 'AwardPackageModel', $config = array('ignore_request' => true))
	{		
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}

?>