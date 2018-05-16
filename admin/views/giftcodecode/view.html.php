<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewgiftcodecode extends JViewLegacy
{
  protected $state;
  
  function display($tpl = null) {
		// Load the submenu.
		AwardpackagesHelper::addSubmenuGiftcode(JRequest::getCmd('view', 'referral'),'giftcodecollection');

		//set variable 
		$task = JRequest::getVar('task');
		$act = JRequest::getVar('layout');
		$catid = JRequest::getVar('catid');
		$color = JRequest::getVar('color');
		$this->model =  JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }
		
		//add stylesheet
		$this->addStyleSheet();
		if ($act == 'create') {  
			$this->create();
		}else if ($act == "renew_schedule") {
			$this->renew();
		}else if ($act == "edit_renew_schedule"){
			$this->edit();
		}else{
			$this->addToolBar();
		}
		parent::display($tpl);
  }
  
  function add_submenu(){
		JSubMenuHelper::addEntry(JText::_('Giftcode Collection List'), 'index.php?option=com_awardpackage');        
		JSubMenuHelper::addEntry(JText::_('Giftcode Queue List'), 'index.php?option=com_awardpackage&view=queue' );        
		JSubMenuHelper::addEntry(JText::_('Giftcode Category'), 'index.php?option=com_awardpackage&view=category' );
  }

  function create_giftcode_collection($max_number) {
		$from_char = rand(1, 20); //the selected number is the order of the character to start from md5 which is 32 characters long. min 1, max 20
		$code_length = rand(4, 7); //pull out x number of characters starting from the number selected by $from_char. min 4, max 7 characters long. 
		$code_number = $max_number; // number of gift codes to create. allow user input 
		$giftcode_collection = Array();
		for ($i = 0; $i < $code_number; $i++)
		{ 
		  $giftcode_collection[]= substr(md5(uniqid(rand(), true)), $from_char, $code_length);
		};
		return $giftcode_collection;
  }
  
  function addStyleSheet(){
		$document = JFactory::getDocument();	
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/layout.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/jquery-1.2.6.js');
		//$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/jquery.ui.all.js');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/eye.js');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/layout.js');
  }
  
  function create(){
      $cid = JRequest::getVar('cid');

      JRequest::setVar( 'hidemainmenu', 1 );
      
      $model = $this->getModel();
      $categoryModel = JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
      $categoryData = $categoryModel->getData();
      if ( $cid != '' ) {
        JToolBarHelper::title(JText::_('Edit Gift Code'),'generic.png');
        $data = $model->getDataDetail($cid[0]);        
        $is_edit = true;
      } else {
        JToolBarHelper::title(JText::_('Create Gift Code'),'generic.png');
      }
      
      JToolBarHelper::save();
      JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=giftcodecode&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
      $this->assignRef('is_edit', $is_edit);                                            
      $this->assignRef('data',$data[0]);
      $this->assignRef('categoryData', $categoryData);
      $this->setLayout('create');
  }
  
  function renew(){
      $color = JRequest::getVar('color');
     echo JRequest::getVar('color', 'post');
      $color_name = JRequest::getVar('color_name');
      JToolBarHelper::title(JText::_('Renew Giftcode Collection'),'generic.png');
      JToolBarHelper::save("save_renew");
      JToolBarHelper::cancel();
      
      $model = $this->getModel();
      $categoryModel = JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
      $categoryData = $categoryModel->getData();
      //print_r($categoryData);
      $giftcode_model = JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
      $giftcodes = $giftcode_model->get_giftcodes($color);
      //$this->add_submenu();
      $model = $this->getModel();
      $this->assignRef('categoryData', $categoryData);               
      $this->assignRef('giftcodes', $giftcodes);                     
      $this->assignRef('data',$data);            

      /*if (count($giftcodes) == 0) {
        $app = JFactory::getApplication();
        $msg = "You don't have any giftcodes for that color";
        $link = 'index.php?option=com_awardpackage&view=giftcodecode&color='.$color."&package_id=".JRequest::getVar('package_id');
        $app->redirect($link, $msg, $msgType='message');	   
      }*/
  }
  
  function edit(){
      $color = JRequest::getVar('color');
      $color_name = JRequest::getVar('color_name');
      
      JToolBarHelper::title(JText::_('Renew Giftcode Collection'),'generic.png');
      JToolBarHelper::save("update_renew");
      JToolBarHelper::cancel();
      
      $model = $this->getModel();
      $categoryModel = JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
      $categoryData = $categoryModel->getData();
      $giftcode_model = JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
      $giftcodes = $giftcode_model->get_giftcodes($color);
      $this->assignRef('renew_schedule', $renew_schedule);      
      //$this->add_submenu();
      $model = $this->getModel();
      $this->assignRef('categoryData', $categoryData);               
      $this->assignRef('giftcodes', $giftcodes);                     
      $this->assignRef('data',$data);            

      if (count($giftcodes) == 0) {
        $app = JFactory::getApplication();
        $msg = "You don't have any giftcodes for that color";
        $link = 'index.php?option=com_awardpackage&view=giftcodecode&color='.$color.'&package_id='.JRequest::getVar('package_id');
        $app->redirect($link, $msg, $msgType='message');	   
      }      
      $this->setLayout('edit_renew_schedule');      
  }
  
  public function addToolBar(){
      JToolBarHelper::title(JText::_('Giftcode Collection List'),'generic.png');
      JToolBarHelper::addNew();
      JToolBarHelper::editList();
      JToolBarHelper::custom("copy","copy","copy","Clone","");
      JToolBarHelper::deleteList();            
      JToolBarHelper::divider();                        
      JToolBarHelper::publish();                        
      JToolBarHelper::unpublish();                        
      JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
      $color = JRequest::getVar('color') == "" ? "1" : JRequest::getVar('color');
      $model = $this->getModel();
      $categoryModel = JModelLegacy::getInstance('GiftCodeCategory','AwardPackageModel');
      $categoryData = $categoryModel->getData();   
	  //var_dump($categoryData);         
      //$this->add_submenu();
      $model = $this->getModel();
      $this->assignRef('categoryData', $categoryData);
      
      $giftcodeModel = JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
      $giftcodeData = $giftcodeModel->getLastCollectionSetting();       
      $giftcodeCollectionData = $giftcodeModel->getDataGiftCollection();                   
      $giftcode_collection_setting_data = $giftcodeModel->get_data_gift_collection_setting();
      $giftcodeColorID = array();
      $giftcode_model = JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
              
      # Checking unrenewed giftcode
      $checking_unrenewed_giftcode = $giftcode_model->checking_unrenewed_giftcode($color);      
      foreach ($giftcodeCollectionData as $gcCollectionData) {      	
        $giftcodeColorID[] = $giftcodeModel->getColorID($gcCollectionData->id);                                
      }      
      # Checking renew schedule, whether there is schedule created or not
      $renew_schedule = $giftcode_model->checking_renew_schedule($color);
      
      # Get renew schedule data
      $renew_schedule_data = $giftcode_model->get_renew_schedule($color);
      
      # If there is schedule created then execute this code
      if (count($renew_schedule) == 1) {
        
        # Checking giftcodes that has not yet renewed
        $renewed_giftcodes = $giftcode_model->checking_renew_status($color);
        
        
        if (count($renewed_giftcodes) != 0) {
          
          # If there is giftcode that has not yet renewed, than execute this code
          $today = date('d-M-Y', strtotime(date('Y-m-d H:i:s')));
          $exist_renewed = $giftcode_model->checking_exist_renewed($color, $today);
          
          if (count($exist_renewed) != 1) {
            
            # If there is no renewed giftcode today then execute this code
            $checking_schedule_day = $giftcode_model->checking_schedule_day($color, strtolower(date('l')));            
            
            if (count($checking_schedule_day) == 1) {
              
              # If there is schedule to renewing giftcode then execute this code
              $unrenewed_giftcodes = array();                             
              foreach ($checking_unrenewed_giftcode as $unrenewed_giftcode) {
                $unrenewed_giftcodes[] = $unrenewed_giftcode->id;
              }

              $updating_renew_status = $giftcode_model->updating_renew_status($unrenewed_giftcodes[0]);                                                      
              $updating_shedule_created = $giftcode_model->updating_schedule_created($unrenewed_giftcodes[0], $color, $today);                                        

            }                       
          
          }
                    
        } else {
          ?>
          <script>
          alert("All Giftcode is renewed");
          </script>
          <?php                    
        }
	  }
		$giftcode_model = JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
		$giftcodes = $giftcode_model->get_giftcodes($color);                  
		$color_id = JRequest::getVar("color") == "" ? "1" : JRequest::getVar("color");
		$color_per_category = $giftcode_model->get_color_per_category($color_id);			
		$this->assignRef('unrenewed_giftcode', $checking_unrenewed_giftcode);                                             
		$this->assignRef('renew_schedule_data', $renew_schedule_data);                                       
		$this->assignRef('renew_schedule', $renew_schedule);                                 
		$this->assignRef('giftcodes', $giftcodes);                                 
		$this->assignRef('color_per_category', $color_per_category);                                         
		$this->assignRef('giftcodeData', $giftcodeData);                             
		$this->assignRef('giftcodeCollectionData', $giftcodeCollectionData);                             
		$this->assignRef('giftcodeColorID', $giftcodeColorID);                                 
		$this->assignRef('giftcode_collection_setting_data', $giftcode_collection_setting_data);                                                     
	 }
}