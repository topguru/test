<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HelloWorld Controller
 */
class AwardpackageControllerUserGroupGender extends JControllerForm {

    public function submit() {
        $app = JFactory::getApplication();
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $id = JRequest::getInt("id");
        $population = JFactory::getApplication()->input->get("population_gender");
        $gender = JFactory::getApplication()->input->get("gender_gender");

        $package_id = JRequest::getInt("package_id");

        $data['id'] = $id;
        $data['population'] = $population;
        $data['gender'] = $gender;
        $data['package_id'] = $package_id;

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
                $ug->add($package_id, $model->id, $population, "gender", $gender);
            } else {
                $ug->edit($package_id, $id, $population, "gender", $gender);
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

        $ug->delete($id, "gender");



        if (!$up) {
            $this->setMessage(JText::sprintf('Delete Failed!', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }

        $this->setMessage(JText::_('Delete Success!'));
        $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
    }

}
