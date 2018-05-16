<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jimport('joomla.application.component.modelform');

class AwardpackageModelPoll extends JModelForm {

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_awardpackage.address', 'profile', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
    }

    protected function loadFormData() {
        return $this->getData();
    }
    public function getData() {
        if ($this->data === null) {

            $userId = $this->getState('user.id');

            $datas = $this->info($userId);

            if (count($datas) > 0) {

                foreach ($datas as $k => $data) {

                    $this->data->$k = $data;
                }
            }
        }

        return $this->data;
    }

}

?>
