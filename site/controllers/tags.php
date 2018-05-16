<?php
/**
 * @version		$Id: tags.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class AwardPackageControllerTags extends JControllerLegacy {
	
	function __construct() {
		
		parent::__construct();
		
		$this->registerDefaultTask('get_tags_listing');
		$this->registerTask('tags', 'get_tags_listing');
		$this->registerTask('get_tag', 'get_tag_details');
		$this->registerTask('save_tag', 'save_tag_details');
		$this->registerTask('delete_tag', 'delete_tag');
	}
	
	public function get_tags_listing(){
		
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$view = $this->getView('tags', 'html');
		
		$view->setModel($model, true);
		$view->display();
	}
	
	public function get_tag_details(){

		$user = JFactory::getUser();
		
		if(!$user->authorise('quiz.manage', Q_APP_NAME)){
		
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		} else {
		
			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		
			$tag_id = $app->input->getInt('tagid');
		
			if($tag_id){
		
				$tag = $model->get_tag_details($tag_id);
				echo json_encode(array('tag'=>$tag));
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_MISSING_REQUIRED')));
			}
		}
		
		jexit();
	}

	public function delete_tag(){
	
		$user = JFactory::getUser();
	
		if($user->guest || !$user->authorise('quiz.manage', Q_APP_NAME)){
	
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		} else {
	
			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
	
			$tag_id = $app->input->getInt('tagid', 0);
	
			if($tag_id > 0){
	
				if($model->delete_tag($tag_id)){
	
					echo json_encode(array('data'=>1));
				} else {
	
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				}
			} else {
	
				echo json_encode(array('error'=>JText::_('MSG_MISSING_REQUIRED')));
			}
		}
	
		jexit();
	}
	
	public function save_tag_details(){

		$user = JFactory::getUser();
		
		if(!$user->authorise('quiz.manage', Q_APP_NAME)){
		
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		} else {
		
			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		
			$tag = new stdClass();
			
			$tag->id = $app->input->getInt('tagid', 0);
			$tag->title = $app->input->getString('name', null);
			$tag->alias = $app->input->getString('alias', '');
			$tag->description = $app->input->getString('description', '');
		
			if($tag->id && !empty($tag->title)){
		
				if($model->save_tag_details($tag)){
				
					echo json_encode(array('tag'=>1));
				} else {
					
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				}
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_MISSING_REQUIRED')));
			}
		}
		
		jexit();
	}
}