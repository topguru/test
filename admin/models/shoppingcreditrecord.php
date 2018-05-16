<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_awardpackage
 *
 * @copyright   kadeyasa@gmail.com
 * @license     GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of shopping ccredit record.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_awardpackage
 * @since       1.6
 */
class AwardPackageModelShoppingcreditrecord extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'date_recived', 'a.date_recived',
                'amount', 'a.amount',
                'unlocked', 'a.unlocked',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_awardpackage');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.date_recived', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        // Filter the items over the search string if set.
        if ($this->getState('filter.search') !== '') {
            $token = $db->Quote('%' . $db->escape($this->getState('filter.search')) . '%');
            // Compile the different search clauses.
            $searches = array();
            $searches[]='a.amount LIKE '.$token;
            $searches[]='a.date_recived LIKE '.$token;
            $query->where('('.implode(' OR ', $searches).')');
        }
        $shopping_id = JRequest::getVar('shopping_id');
        $package_id  = JRequest::getVar('package_id');
        $query->from($db->QuoteName('#__shopping_record') . 'AS a');
        $query->innerJoin('#__shopping_credit_package b ON a.shopping_credit_package_list_id = b.shopping_credit_id');
        $query->innerJoin('#__shopping_credit_package_list c ON c.shopping_id=b.shopping_package_list_id');
        //$query->innerJoin('#__shopping_credit_config d ON d.shopping_credit_id=a.shopping_credit_package_list_id');
        $query->where("c.shopping_id='$shopping_id' AND c.package_id='$package_id'");
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }
    
    
    function getUser($user_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__users WHERE id='$user_id'";
        $db->setQuery($query);
        $row    = $db->loadObject();
        return $row;
    }
    
    public function getDistributionListData($shopping_credit_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__shopping_credit_distribution_list WHERE shopping_credit_id='$shopping_credit_id'";
        $db->setQuery($query);
        $row    = $db->loadObject();
        return $row;
    }
    
    public function blockRecord($record_id){
         $db     = JFactory::getDbo();
         $date   = date('Y-m-d');
         $query  = "UPDATE #__shopping_record SET unlocked_date='$date',unlocked_status='1',is_blocked='1' WHERE shopping_record_id='$record_id'";
         $db->setQuery($query);
         if($db->query()){
             return TRUE;
         }else{
             return FALSE;
         }
    }
    
    public function getSpenShoppingCredit(){
        $db     = JFactory::getDbo();
        
        $query  = "SELECT * FROM #__shopping_claim a INNER JOIN #__shopping_record b ON a.record_id=b.shopping_record_id
                   INNER JOIN #__shopping_credit_package c ON b.shopping_credit_package_list_id=c.shopping_credit_id
                   INNER JOIN #__shopping_credit_package_list e ON e.shopping_id=c.shopping_package_list_id 
                   WHERE e.shopping_id='".JRequest::getVar('shopping_id')."' AND b.claimed_status='1'";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        return $rows;
    }
}

?>
