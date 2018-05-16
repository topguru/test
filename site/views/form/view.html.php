<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Answers
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';

class AwardpackageViewForm extends JViewLegacy {

	protected $params;
	protected $print;
	protected $state;

	public $quiz;
	public $categories__;

	function display($tpl = null) {
			$document = & JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		CommunitySurveysHelper::initiate();
		
		$user = AwardPackageHelper::getUserData();
		
		$app = JFactory::getApplication();
		$model = $this->getModel();

		$active = $app->getMenu()->getActive();
		$page_heading = JText::_('CREATE EDIT QUIZ');
		$this->print = $app->input->getBool('print');

		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(Q_APP_NAME);
		$menuParams = new JRegistry;

		if ($active) {

			$menuParams->loadString($active->params);
		}

		$this->params = clone $menuParams;
		$this->params->merge($appparams);

		/********************************** ASSIGN *****************************/
		$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

		$categories = $poll_model->get_categories($user->package_id);		
		$this->assignRef("categories", $categories);
		$this->assignRef('item', $this->quiz);
		$this->assignRef('categories__', $this->categories__);

		if ($this->action == 'questions') {
			$this->assignRef('item', $this->questions);
			//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=form&task=form.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
		}
		if($this->action == 'preview') {
			//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=form&task=form.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
		}
		if($this->action == 'form') {
			///JToolBarHelper::custom( 'form.save_quiz', 'save', 'save', 'Save & Close', false, false );
			//JToolBarHelper::cancel('form.cancel_form');
		}
if ($this->action == 'list_question') {
			$id = JRequest::getVar('id');
			$answers = $model->get_question_list($id);

			$this->assignRef('answers', $answers);
			//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=form&task=form.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
		}
		/********************************** PARAMS *****************************/

		$id = $app->input->getInt('id', 0);
		$itemid = CJFunctions::get_active_menu_id();


		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		$title = $this->params->get('page_title', $app->getCfg('sitename'));

		parent::display($tpl);
	}
}