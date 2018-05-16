<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * QueueHelper Model
 */
class AwardpackageModelQueueHelper extends JModelList {

	function __construct($config = array()) {
		parent::__construct($config);
		$this->package_id = JRequest::getInt('package_id');
		$this->presentation_id = JRequest::getInt('presentation_id');
	}

	public function getListQuery(){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process_prize AS a ");

		$query->innerJoin("#__symbol_prize AS b ON a.prize_id=b.id");

		$query->innerJoin("#__symbol_symbol AS c ON a.symbol_id=c.symbol_id");

		$query->innerJoin("#__ap_symbol_process AS d ON a.process_id=d.id");

		$query->where("d.presentation_id='".$this->presentation_id."'");

		return $query;
	}
}
?>