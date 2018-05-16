<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HelloWorld Controller
 */
class AwardpackageControllerUserGroupName extends JControllerForm {

    public function submit() {
        $app = JFactory::getApplication();
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $id = JRequest::getInt("id");
        $population = JFactory::getApplication()->input->get("population_name");
        $firstname = JFactory::getApplication()->input->get("firstname_name");
        $lastname = JFactory::getApplication()->input->get("lastname_name");
        $package_id = JRequest::getInt("package_id");

        $data['id'] = $id;
        $data['population'] = $population;
        $data['firstname'] = $firstname;
        $data['lastname'] = $lastname;
        $data['package_id'] = $package_id;

        $model = $this->getModel();

        if($model->checkDataExist($package_id) > 0){
            $this->setMessage(JText::sprintf('Save Failed Field Used', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }
        else if($model->checkUserIsExist($firstname . ' ' . $lastname) > 0){
            $this->setMessage(JText::sprintf('Save Failed User is not exist', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }
        else{
            $up = $model->addItem($data);
            $ug = new UserGroup();
            if (empty($id)) {
                $ug->add($package_id, $model->id, $population, "name", $firstname.' '.$lastname);
            } else {
                $ug->edit($package_id, $id, $population, "name", $firstname.' '.$lastname);
            }

            if (!$up) {
                $this->setMessage(JText::sprintf('Save Failed', $model->getError()), 'warning');
                $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
                return false;
            }

            $app->setUserState('com_awardpackage.edit.data', null);

            // Redirect to the profile screen.
            $this->setMessage(JText::_('Save Sucess'));
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
        }
    }

    function delete() {
        $id = JRequest::getInt("id");
        $package_id = JRequest::getInt("package_id");

        if(empty($id)) {
            $this->setMessage(JText::_('Delete Failed'), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }

        $model = $this->getModel();

        $up = $model->delete($id);
        $ug = new UserGroup();
        $ug->delete($id, "name");


        if (!$up) {
            $this->setMessage(JText::sprintf('Delete Failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
            return false;
        }

        $this->setMessage(JText::_('Delete Success'));
        $this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $package_id, false));
    }

}
