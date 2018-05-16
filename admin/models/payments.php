<?php 
//redirect
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

//create class
class AwardPackageModelPayments extends JModelList{
	
	public function __construct($config = array()){
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'option','option',
				'date_created','date_created'
			);
		}
		parent::__construct($config);
	}
	
	public function get_payments($ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.payments.filter_order', 'filter_order', 'option', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.payments.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.payments.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by a.`' . $filter_order . '` ' . $filter_order_dir;
		
		$query = '
        		select
        			a.`id`, a.`option`, a.`date_created`
        		from
        			#__ap_payment_options a
        		where a.package_id = \'' .JRequest::getVar('package_id'). '\'      		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['payments'] = $this->_db->loadObjectList();
		
		$query = '
        		select
        			count(*)
        		from
        			#__ap_payment_options 
        		where package_id = \''.JRequest::getVar('package_id').'\'        		
        		';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;
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
		$query->from("#__ap_payment_options");
		return $query;
	}
	
	public function getTable($type = 'payment', $prefix = '', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function delete($id){
		$db			= 	&JFactory::getDbo();
		$query		=	$db->getQuery(TRUE);
		$query->delete('#__ap_payment_options');
		$query->where("id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	
	public function save_update_payment($option, $id = 0){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__ap_payment_options set `option` = \''.$option.'\' where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__ap_payment_options (`option`, date_created, `package_id`)
					values (\''.$option.'\', \''.$createdate.'\', \''.JRequest::getVar('package_id').'\')';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function get_payments_byid($id){
		$query = '
        		select
        			id, `option`
        		from
        			#__ap_payment_options a        		
    			where a.id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$payments = $this->_db->loadObjectList ();
		if(!empty($payments)) {
			$payment = $payments[0];			
		}
		return $payment;
	}
	
}
?>