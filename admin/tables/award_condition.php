<?php
 

 defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableAward_condition extends JTable
{
	var $award_id 	= null;
	var $quantity	= null;
	var $amount 		= null;
	var $unit 		= null;
	var $sch_id		= null;
	var $cat_id	= null;

	function __construct(& $db) {
		parent::__construct('#__gc_award_condition', 'award_id', $db);
	}
}
?>