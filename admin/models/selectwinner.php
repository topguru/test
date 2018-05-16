<?php

/**
 * @version     1.0.0
 * @package     com_shopping
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * shopping model.
 */
class AwardPackageModelSelectWinner extends JModelAdmin {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_AWARDPACKAGE';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */

	public function __construct($config = array()) {
		parent::__construct($config);
		$this->_db = &JFactory::getDbo();
		$this->presentation_id = JRequest::getVar('presentation_id');
		$this->package_id = JRequest::getVar('package_id');
	}

	public function getForm($data = array(), $loadData = true) {
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_awardpackage.shoppingrecipientgroup', 'shoppingreciepentgroup', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		$id = JRequest::getVar('id');
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__shopping_usergroup WHERE " . $db->quoteName('criteria_id') . "='" . $id . "'";
		$db->setQuery($query);
		$rows = $db->loadObject();
		if ($rows) {
			foreach ($rows as $k => $row) {
				$data[$k] = $row;
			}
		}
		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table) {
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__shopping_package');
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	protected function preprocessForm(JForm $form, $data, $group = 'user') {
		parent::preprocessForm($form, $data, $group);
	}

	public function addWinner($data){
		$query 	= $this->_db->getQuery(TRUE);
		$query->insert($this->_db->QuoteName('#__ap_winners'));
		$query->set("package_id='".$this->package_id."'");
		$query->set("presentation_id='".$this->presentation_id."'");
		$query->set("start_unlocked_prize='".$data['startunlocked']."'");
		$query->set("end_unlocked_prize='".$data['endunlocked']."'");
		$this->_db->setQuery($query);
		$this->_db->query();
		$id = $this->_db->insertId();
		return $id;
	}

	public function checkPotentialWinner($presentation_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_potential_winner_module'));
		$query->where("presentation_id='".$presentation_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function checkPrize($startunlocked,$endunlocked){
		$query 	= $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_prize'));
		$query->where("package_id='".$this->package_id."'");
		$query->where("prize_value>='".$startunlocked."'");
		$query->where("prize_value<='".$endunlocked."'");
		$query->where("status='1'");
	}

	public function checkFunding($startunlocked,$endunlocked){
		$query	= $this->_db->getQuery(TRUE);
		$query->select('a.*,b.*,c.*,d.id AS user_id');
		$query->from('#__funding AS a');
		$query->innerJoin('#__funding_presentations AS b ON a.funding_id=b.prize_funding_session_id');
		$query->innerJoin('#__ap_potential_winner_module AS c ON a.presentation_id=c.presentation_id');
		$query->innerJoin('#__users AS d ON d.email=c.email');
		$query->innerJoin('#__symbol_prize e ON e.id=b.prize_id');
		$query->where("a.presentation_id='".$this->presentation_id."'");
		$query->where("a.package_id='".$this->package_id."'");
		//$query->where("b.funding>='".$startunlocked."'");
		//$query->where("b.funding<='".$endunlocked."'");
		//$query->where("b.value>='".$startunlocked."'");
		//$query->where("b.value<='".$endunlocked."'");
		$query->where("a.funding_published='1'");

		//check prize value on range
		//$query->where("e.prize_value>='".$startunlocked."'");
		//$query->where("e.prize_value<='".$endunlocked."'");
		$this->_db->setQuery($query);
		$rows	= $this->_db->loadObjectList();
		return $rows;
	}

	function checkPrizeWinnings($user_id,$startunlocked,$endunlocked){
		$query	= $this->_db->getQuery(TRUE);
		$query->select('a.*,b.*,c.*');
		$query->from($this->_db->QuoteName('#__symbol_order').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__symbol_prize').' AS b ON a.prize_id=b.id');
		$query->innerJoin($this->_db->QuoteName('#__symbol_symbol_pieces').' AS c ON a.symbol_pieces=c.symbol_pieces_id');
		$query->where("a.user_id='".$user_id."'");
		$query->where("b.unlocked_status='0'");
		$query->where("b.prize_value>='".$startunlocked."'");
		$query->where("b.prize_value<='".$endunlocked."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getWinners(){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winners'));
		$query->where("presentation_id='".$this->presentation_id."'");
		$query->where("package_id='".$this->package_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function deleteWinner($id){
		$query = $this->_db->getQuery(TRUE);
		$query->delete();
		$query->from($this->_db->QuoteName('#__ap_winners'));
		$query->where("id='".$id."'");
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	public function addUserWinner($data){
		$date  	= JFactory::getDate();
		$setting= $this->getSetting($data['presentation_id']);
		if(!$setting){
			$query 	= $this->_db->getQuery(TRUE);
			$query->select('*');
			$query->from('#__ap_winners_user');
			$query->where("user_id='".$data['user_id']."'");
			$query->where("prize_id='".$data['prize_id']."'");
			$this->_db->setQuery($query);
			$rows 	= $this->_db->loadObjectList();
		}else{
			$rows = FALSE;
		}
		if(!$rows){
			//check user prize
			$query 	= $this->_db->getQuery(TRUE);
			$query->insert($this->_db->QuoteName('#__ap_winners_user'));
			$query->set("user_id='".$data['user_id']."'");
			$query->set("prize_id='".$data['prize_id']."'");
			$query->set("ap_winner_id='".$data['ap_winner_id']."'");
			$query->set("awarded_date='".$date->toFormat()."'");
			$query->set("prize='".$data['prize']."'");
			$query->set("prize_value='".$data['prize_value']."'");
			$this->_db->setQuery($query);
			if($this->_db->query()){
				//symbol award
				$winner_user_id = $this->_db->insertId();
				$query = $this->_db->getQuery(TRUE);
				$query->insert($this->_db->QuoteName('#__ap_winner_symbol'));
				$query->set("user_winner_id='".$winner_user_id."'");
				$query->set("pieces_id='".$data['pieces_id']."'");
				$this->_db->setQuery($query);
				if($this->_db->query()){
					$data['winner_user_id']=$winner_user_id;
					$this->InsertActualWinner($data);
					// check return symbol
					$pieces = $this->checkPrizeWinning($data['symbol_id'],$data['pieces_id']);
					foreach($pieces as $pieces_item){
						$query = $this->_db->getQuery(TRUE);
						$query->insert($this->_db->QuoteName('#__ap_winner_returned'));
						$query->set("user_winner_id='".$winner_user_id."'");
						$query->set("pieces_id='".$pieces_item->symbol_pieces_id."'");
						$this->_db->setQuery($query);
						$this->_db->query();
					}
				}
			}
		}
		else{
			$query = $this->_db->getQuery(TRUE);
			$query->delete('#__ap_winners');
			$query->where("id='".$data['ap_winner_id']."'");
			$this->_db->setQuery($query);
			$this->_db->query();
			return FALSE;
		}
	}

	public function InsertActualWinner($data){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__gc_recieve_user'));
		$query->where("user_id='".$data['user_id']."'");
		$query->where("status='1'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		//echo $rows;
		$date = JFactory::getDate();
		if($rows){
			$_query = $this->_db->getQuery(TRUE);
			$_query->insert($this->_db->QuoteName('#__ap_winners_actual'));
			$_query->set("ap_winner_id='".$data['ap_winner_id']."'");
			$_query->set("user_id='".$data['user_id']."'");
			$_query->set("prize_id='".$data['prize_id']."'");
			$_query->set("awarded_date='".$date->toFormat()."'");
			$_query->set("prize='".$data['prize']."'");
			$_query->set("prize_value='".$data['prize_value']."'");
			$_query->set("select_winner_id='".$data['winner_user_id']."'");
			//echo $_query;
			$this->_db->setQuery($_query);
			$this->_db->query();
		}
	}
	public function checkPrizeWinning($symbol_id,$pieces_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_symbol_pieces'));
		$query->where("symbol_id='".$symbol_id."'");
		$query->where("symbol_pieces_id<>'".$pieces_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getTotalUnlockedPrize($ap_winner_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__ap_winners_user AS a');
		$query->where("a.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows	= $this->_db->loadObjectList();
		$total 	=0;
		foreach($rows as $row){
			$total = $total+$row->prize_value;
		}
		return $total;
	}

	public function getPersenTotal($ap_winner_id,$package_id){
		$query	= $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__symbol_symbol_prize a');
		$query->innerJoin('#__ap_winners_user b ON a.id=b.prize_id');
		$query->where("a.presentation_id='".$this->presentation_id."'");
		$query->where("b.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows  = $this->_db->loadObjectList();
		$total = count($rows);
		$total_award = count($this->getTotalNoUnlockedPrize($ap_winner_id));
		if($total_award>0){
			$percentage = ($total_award/$total)*100;
		}
		return $percentage;
	}

	public function getSelectedWinners($ap_winner_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('b.*,c.*,a.id AS ap_user_winner_id,a.awarded_date');
		$query->from($this->_db->QuoteName('#__ap_winners_user').'AS a');
		$query->innerJoin($this->_db->QuoteName('#__users').'AS b ON a.user_id=b.id');
		$query->innerJoin($this->_db->QuoteName('#__symbol_prize'). 'AS c ON c.id=a.prize_id');
		$query->where("a.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows 	= $this->_db->loadObjectList();
		return $rows;
	}

	public function lockPrize($prize_id){
		$query	= $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__symbol_prize'));
		$query->set("unlocked_status='1'");
		$query->where("id='".$prize_id."'");
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	//get unlocked detail
	public function getUnlockedDetails($ap_winner_id){
		$query 	= $this->_db->getQuery(TRUE);
		$query->select('b.*,a.id as ap_user_winner_id,a.awarded_date');
		$query->from($this->_db->QuoteName('#__ap_winners_user').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__symbol_prize').' AS b ON a.prize_id=b.id');
		$query->where("a.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getSymbolTotal($ap_user_winner_id){
		$query	= $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winner_symbol').'  AS a');
		$query->innerJoin($this->_db->QuoteName('#__symbol_symbol_pieces').' AS b ON a.pieces_id=b.symbol_pieces_id');
		$query->where("a.user_winner_id='".$ap_user_winner_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		//echo $this->_db->getErrorMsg();
		return $rows;
	}

	public function getSymbolReturned($ap_user_winner_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winner_returned').'AS a');
		$query->innerJoin($this->_db->QuoteName('#__symbol_symbol_pieces').' AS b ON a.pieces_id=b.symbol_pieces_id');
		$query->where("a.user_winner_id='".$ap_user_winner_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getActualWinners($ap_winner_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('b.*,c.*,a.id AS ap_winner_id,a.select_winner_id AS ap_user_winner_id,a.awarded_date');
		$query->from($this->_db->QuoteName('#__ap_winners_actual').'AS a');
		$query->innerJoin($this->_db->QuoteName('#__users').'AS b ON a.user_id=b.id');
		$query->innerJoin($this->_db->QuoteName('#__symbol_prize'). 'AS c ON c.id=a.prize_id');
		$query->where("a.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows 	= $this->_db->loadObjectList();
		return $rows;
	}

	//for presentation id
	public function getTotalUnlockedPrizePresentationID(){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_symbol_prize').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__funding_presentations').'AS b ON a.id=b.prize_id');
		$query->where("a.presentation_id='".$this->presentation_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	//for total prize awarded
	public function getTotalPrizeAward(){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winners').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__ap_winners_user').' AS b ON a.id=b.ap_winner_id');
		$query->where("a.presentation_id='".$this->presentation_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	//getTotalNoUnlockedPrize
	public function getTotalNoUnlockedPrize($ap_winner_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winners').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__ap_winners_user').' AS b ON a.id=b.ap_winner_id');
		$query->where("a.presentation_id='".$this->presentation_id."'");
		$query->where("b.ap_winner_id='".$ap_winner_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function saveSetting($presentation_id,$same_person){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winner_setting'));
		$query->where($this->_db->QuoteName('presentation_id')."='".$presentation_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$query = $this->_db->getQuery(TRUE);
		if(count($rows)>0){
			$query->update($this->_db->QuoteName('#__ap_winner_setting'));
		}else{
			$query->insert($this->_db->QuoteName('#__ap_winner_setting'));
		}
		$query->set("presentation_id='".$presentation_id."'");
		$query->set("is_same_person='".$same_person."'");
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	public function getSetting($presentation_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_winner_setting'));
		$query->where($this->_db->QuoteName('presentation_id')."='".$presentation_id."'");
		$this->_db->setQuery($query);
		$row   = $this->_db->loadObject();
		return $row->is_same_person;
	}
}