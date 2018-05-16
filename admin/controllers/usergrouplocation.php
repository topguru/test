<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HelloWorld Controller
 */
class AwardpackageControllerUserGroupLocation extends JControllerForm {

    public function submit() {
        $app = JFactory::getApplication();
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $id = JRequest::getInt("id");
        $population = JFactory::getApplication()->input->get("population_location");
        $address = JFactory::getApplication()->input->get("address_location","","string");
        $city = JFactory::getApplication()->input->get("city_location","","string");
        $zip_postal = JFactory::getApplication()->input->get("zip_postal_location");
        $state = JFactory::getApplication()->input->get("state_location","","string");
        $country = JRequest::getVar("country_location","","string");
        $package_id = JRequest::getInt("package_id");

        $data['id'] = $id;
        $data['population'] = $population;
        $data['address'] = $address;
        $data['city'] = $city;
        $data['zip_postal'] = $zip_postal;
        $data['state'] = $state;
        $data['country'] = $country;
        $data['package_id'] = $package_id;
        
        $criterias = array();
        if($data['address'] !==""){
            $criterias['address'] = $data['address'];
        }
        if($data['city'] !==""){
            $criterias['city'] = $data['city'];
        }
        if($data['zip_postal'] !==""){
            $criterias['post_code'] = $data['zip_postal'];
        }
        if($data['state'] !==""){
            $criterias['state'] = $data['state'];
        }
        if($data['country'] !==""){
            $criterias['country'] = $data['country'];
        }
        
        $model = $this->getModel();

        if($model->checkDataExist($package_id) > 0){
            $this->setMessage(JText::sprintf('Save Failed! Field Used', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }
        else{
            
            $up = $model->addItem($data);
            $ug = new UserGroup();
            if (empty($id)) {
                $ug->add($package_id, $model->id, $population, "location", $criterias);
            } else {
                $ug->edit($package_id, $id, $population, "location", $criterias);
            }


            if (!$up) {
                $this->setMessage(JText::sprintf('Save Failed!', $model->getError()), 'warning');
                $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
                return false;
            }

            $app->setUserState('com_awardpackage.edit.data', null);

            // Redirect to the profile screen.
            $this->setMessage(JText::_('Save Success!'));
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
        }
    }

    function delete() {
        $id = JRequest::getInt("id");
        $package_id = JRequest::getInt("package_id");

        $model = $this->getModel();

        $up = $model->delete($id);

        $ug = new UserGroup();
        $ug->delete($id, "location");

        if (!$up) {
            $this->setMessage(JText::sprintf('Delete Failed!', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }

        $this->setMessage(JText::_('Delete Success!'));
        $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
    }
    function centang(){
      $id = JRequest::getInt("id");
      $package_id = JRequest::getInt("package_id");
      $field=JRequest::getVar("field");
      //unset($_SESSION['listlocation'][$id]);
      ViewdbHelper::updatedb("#__ap_usergroup","is_presentation='1'","criteria_id=$id");
      //$this->setMessage(JText::_('Check Success!'));
      $this->setRedirect(JRoute::_("index.php?option=com_awardpackage&command=1&criteria_id=".JRequest::getVar('criteria_id')."&field=$field&view=usergroup&package_id=" . $package_id, false));
    }
    function uncentang(){
      $id = JRequest::getInt("id");
      $package_id = JRequest::getInt("package_id");
      $field=JRequest::getVar("field");
      //$_SESSION['listlocation'][$id]=$id;
      ViewdbHelper::updatedb("#__ap_usergroup","is_presentation=0","criteria_id=$id");
      //$this->setMessage(JText::_('UnCheck Success!'));
      $this->setRedirect(JRoute::_("index.php?option=com_awardpackage&command=1&criteria_id=".JRequest::getVar('criteria_id')."&field=$field&view=usergroup&package_id=" . $package_id, false));
    }

}
