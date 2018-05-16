<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
class AwardPackageModelPaypals extends JModelList{
	public function __construct($config = array()){
		parent::__construct($config);
	}

	public function getPaypalConfiguration($ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.paypals.filter_order', 'filter_order', 'business', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.paypals.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.payments.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' .$filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			`id`,`business`,`currency_code`,`lc`,`is_active`
        		from
        			#__paypal_config
        		where package_id = \'' .JRequest::getVar('package_id'). '\'      		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['paypals'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__paypal_config 
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

	public function deletePaypalConfiguration($id){
		$db			= 	&JFactory::getDbo();
		$query		=	$db->getQuery(TRUE);
		$query->delete('#__paypal_config');
		$query->where("id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	public function saveUpdatePaypalConfiguration($data, $id = '0'){
		$this->_db = &JFactory::getDBO ();
		$query = "update #__paypal_config set is_active = '0' where package_id = '".$data['package_id']."' ";
		$this->_db->setQuery ( $query );
		$this->_db->query ();
				
		if(!empty($id) && $id != '0') {
			$query = "update #__paypal_config set business = '".$data['business']."', currency_code = '".$data['currency_code']."',
					lc = '".$data['lc']."', is_active = '".($data['is_active'] == 'on' ? '1' : '0')."' where id = '".$id."' ";
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = "insert into #__paypal_config (business,currency_code,lc,is_active,package_id)
				  values('".$data['business']."', '".$data['currency_code']."', '".$data['lc']."', '".($data['is_active'] == 'on' ? '1' : '0')."', '".$data['package_id']."' ) ";
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}

	public function getPaypalConfigurationById($id){
	$result='';
		$query = '
        		select
        			id, business, currency_code, lc, is_active, package_id
        		from
        			#__paypal_config         		
    			where id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList ();
		if(!empty($results)) {
			$result = $results[0];
		}
		return $result;
	}
}