<?php
/**
 * @version		$Id: categories.php 01 2012-12-04 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerCategories extends JControllerLegacy {
	
    function __construct() {
    	
        parent::__construct();        
        $this->registerDefaultTask('get_categories');
        $this->registerTask('save', 'save_category');
        $this->registerTask('sort', 'sort_category');
        $this->registerTask('edit', 'add');
        $this->registerTask('delete', 'delete');
        $this->registerTask('move_down', 'movedown');
        $this->registerTask('move_up', 'moveup');
        $this->registerTask('refresh', 'refresh');
    }

    function get_categories() {
    	
        $view = $this->getView('categories', 'html');
        $model = $this->getModel('categories');
        $view->setModel($model, true);
        $view->setLayout('list');
        $view->display();
    }

    function sort_category(){
    	
    	$app = JFactory::getApplication();
    	$model = $this->getModel('categories');
    	
        $strid = trim($app->input->post->getString('id', ''));
        $strparent = trim($app->input->post->getString('parent', ''));
        
        $id = intval(substr($strid, strpos($strid, '-')+1));
        $new_parent = intval(substr($strparent, strpos($strparent, '-')+1));
        
        if(!$model->sort($id, $new_parent)){
        	
            echo JText::_('MSG_ERROR');
        }
        
        jexit();
    }

    function delete(){
    	
    	$app = JFactory::getApplication();
        $id = $app->input->getInt('id', 0);
        
        if(!$id){
        	
            $msg = 'Invalid category id requested';
        }else{
        	
            $model = $this->getModel('categories');
            $msg = JText::_('MSG_SUCCESS');
            
            if(!$model->delete($id)){
            	
                $msg = JText::_('MSG_ERROR');
            }
        }
        
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"), $msg);
    }

    function add(){    	
        $view = $this->getView('categories', 'html');
        $model = $this->getModel('categories');
        $view->setModel($model, true);
        $view->setLayout('add');
        $view->display();
    }

    function cancel(){
    	
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"),  'Operation cancelled.');
    }

    function save(){    	
        $model = $this->getModel('categories');
        $msg = JText::_('MSG_SUCCESS');        
        if(!$model->save()){        	
            $msg = JText::_('MSG_ERROR');
        }        
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"), $msg);
    }

    function movedown(){
    	
        $model = $this->getModel('categories');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $msg = JText::_('MSG_SUCCESS');
        
        if(!$model->movedown($id)){
        	
            $msg = JText::_('MSG_ERROR').$model->getError();
        }
        
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"), $msg);
    }

    function moveup(){
    	
        $model = $this->getModel('categories');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $msg = JText::_('MSG_SUCCESS');
        
        if(!$model->moveup($id)){
        	
            $msg = JText::_('MSG_ERROR');
        }
        
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"), $msg);
    }
    
    function refresh(){    	
        $model = $this->getModel('categories');        
        $model->rebuild_categories();
        $this->setRedirect('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.list&package_id='.JRequest::getVar("package_id"), JText::_('MSG_CATEGORIES_REFRESHED'));
    }
}
?>
