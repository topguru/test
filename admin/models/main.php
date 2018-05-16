<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class AwardpackageModelMain extends JModelLegacy {

    public function save($data) { 
    	$fields = $this->show_fields('ap_awardpackages');
    	foreach ($data as $k => $v) {
            if (in_array($k, $fields)) {
                $input[$k] = htmlentities($v);
            }
        }
        $db = JFactory::getDBO();
        if ($input['package_id'] > 0) {
            $query = "UPDATE `#__ap_awardpackages` SET package_name = '$input[package_name]', start_date = '$input[start_date]',
			end_date = '$input[end_date]', published = '$input[published]' WHERE package_id = '$input[package_id]'";
            $db->setQuery($query);
            $db->query();
        } else {
            unset($input['package_id']);
            $query = "INSERT INTO `#__ap_awardpackages` (" . implode(',', array_keys($input)) . ") VALUES ('" . implode('\',\'', array_values($input)) . "')";
            $db->setQuery($query);
            $db->query();
            $package_id = $db->insertid();
            $this->create_setting($db->insertid());
            $this->creategiftcodecat($package_id);
        }
    }

    public function doclone($package_id) {

        $package = $this->info($package_id);

        unset($package->package_id);

        $package->package_name = $package->package_name . ' (clone)';

        $package->created = date("Y-m-d H:i:s");

        foreach ($package as $k => $v) {

            $key[] = $k;

            $value[] = $v;
        }
        $db = JFactory::getDBO();

        $query = "INSERT INTO `#__ap_awardpackages` (" . implode(",", $key) . ") VALUES ('" . implode('\',\'', $value) . "')";
		$db->setQuery($query);

        $db->query();

        $insertid = $db->insertid();
        $idpackage = $insertid;
        if ($insertid > 0) {
            $query = "SELECT * FROM `#__ap_categories` WHERE package_id = '$package_id'";
            $db->setQuery($query);
            $rs = $db->loadObjectList();
            if (count($rs) > 0) {
                foreach ($rs as $k => $v) {
                    $q = "INSERT INTO `#__ap_categories` (package_id,category_id,colour_code,category_name,donation_amount,published)
						VALUES ('$insertid','$v->category_id','$v->colour_code','$v->category_name','$v->donation_amount','$v->published')";
                    $db->setQuery($q);
                    if ($db->query()) {
                        $return = 1;
                    }
                }
            }
        }
        if ($return) {

            //create duplicate symbol prize data
            $this->clonePrize($package_id, $idpackage);

            //create duplicate giftcode category
            $this->cloneGiftcode($package_id, $idpackage);

            //create duplicate symbol presentation
            $this->cloneSymbolPresentation($package_id, $idpackage);

            //create duplicate symbol symbol
            $this->cloneSymbolSymbol($package_id, $idpackage);

            //create duplicate funding
            $this->cloneFunding($package_id, $idpackage);

            return true;
        }
    }

    private function cloneFunding($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__funding WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__funding (funding_session,funding_desc,funding_published,funding_created,funding_modify,funding_1st,package_id) VALUES ('" .
                        $row->funding_session . "','" . $row->funding_desc . "','" . $row->funding_published . "','" . $row->funding_created . "','" . $row->funding_modify . "','" .
                        $row->funding_1st . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneSymbolSymbol($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_symbol WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();


        if (count($rows) > 0) {


            foreach ($rows as $row) {

                $Query = "INSERT INTO #__symbol_symbol (date_created,symbol_name,symbol_image,pieces,rows,cols,package_id) VALUES ('" .
                        $row->date_created . "','" . $row->symbol_name . "','" . $row->symbol_image . "','" . $row->pieces . "','" . $row->rows . "','" . $row->cols . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneSymbolPresentation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_presentation WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO  #__symbol_presentation (presentation_create,presentation_modify,presentation_publish,package_id) VALUES ('" .
                        $row->presentation_create . "','" . $row->presentation_modify . "','" . $row->presentation_publish . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneGiftcode($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            $i = 0;

            $j = 0;

            foreach ($rows as $k => $data) {

                $query = "INSERT INTO #__giftcode_category (name,image,description,published,symbol_pieces_award,created_date_time,color_code,locked,package_id,category_id) VALUES ('" .
                        $data->name . "','" . $data->image . "','" . $data->description . "','" . $data->published . "','" . $data->symbol_pieces_award . "','" . $data->created_date_time . "','" .
                        $data->color_code . "','" . $data->locked . "','" . $insertid . "','" . $data->category_id . "')";

                $db->setQuery($query);

                $db->query();
            }
        }
    }

    private function clonePrize($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_prize WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $data = "";

        if (count($rows) > 0) {

            foreach ($rows as $row) {


                $query = "INSERT INTO #__symbol_prize VALUES (' ','$row->date_created','$row->prize_name','$row->prize_value','$row->prize_image','$row->created_by','$row->desc','$insertid','$row->status')";

                $db->setQuery($query);

                $db->query();
            }
        }
    }

    public function create_setting($package_id) {
        $row[] = array('package_id' => $package_id, 'category_id' => 1, 'colour_code' => '#FF0000', 'category_name' => 'RED', 'donation_amount' => 0.1, 'published' => 1, 'poll_price' => 0.1, 'survey_price' => 0.1, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 2, 'colour_code' => '#FF6600', 'category_name' => 'ORANGE', 'donation_amount' => 0.2, 'published' => 1, 'poll_price' => 0.2, 'survey_price' => 0.2, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 3, 'colour_code' => '#FFFF00', 'category_name' => 'YELLOW', 'donation_amount' => 0.3, 'published' => 1, 'poll_price' => 0.3, 'survey_price' => 0.3, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 4, 'colour_code' => '#008000', 'category_name' => 'GREEN', 'donation_amount' => 0.4, 'published' => 1, 'poll_price' => 0.4, 'survey_price' => 0.4, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 5, 'colour_code' => '#0000FF', 'category_name' => 'BLUE', 'donation_amount' => 0.5, 'published' => 1, 'poll_price' => 0.5, 'survey_price' => 0.5, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 6, 'colour_code' => '#000080', 'category_name' => 'INDIGO', 'donation_amount' => 0.6, 'published' => 1, 'poll_price' => 0.6, 'survey_price' => 0.6, 'unlocked' => 0);
        $row[] = array('package_id' => $package_id, 'category_id' => 7, 'colour_code' => '#800080', 'category_name' => 'VIOLET', 'donation_amount' => 0.7, 'published' => 1, 'poll_price' => 0.7, 'survey_price' => 0.7, 'unlocked' => 0);
        $db = JFactory::getDBO();
        for ($i = 0; $i <= count($row) - 1; $i++) {
            //echo $row[$i]['category_id'].'<br>';
            $query = "INSERT INTO `#__ap_categories` (package_id,category_id,colour_code,category_name,donation_amount,poll_price, survey_price) 
						  VALUES('" . $row[$i]['package_id'] . "','" . $row[$i]['category_id'] . "','" . $row[$i]['colour_code'] . "','" . $row[$i]['category_name'] . "','" . $row[$i]['donation_amount'] . "','" . $row[$i]['poll_price'] . "','" . $row[$i]['survey_price'] . "')";
            $db->setQuery($query);
            $db->query();
        }
    }

    function delete($data) {
        $db = JFactory::getDBO();
        $query = "DELETE FROM `#__ap_awardpackages` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();

        //delete award package categori
        $query = "DELETE FROM `#__ap_categories` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();

        //update award package account 
        $query = "UPDATE `#__ap_useraccounts` SET package_id = 0 WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();

        //delete gifcode category 
        $query = "DELETE FROM `#__giftcode_category` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
        
        //delete giftcode giftcode collection
        $query = "DELETE FROM `#__giftcode_collection` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
        
        //delete prize 
        $query = "DELETE FROM `#__symbol_prize` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
        
        //delete presentation 
        $query = "DELETE FROM `#__symbol_presentation` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
        
        //delete symbol 
        $query = "DELETE FROM `#__symbol_symbol` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
        
        //delete symbol 
        $query = "DELETE FROM `#__funding` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function publish($data) {
        $db = JFactory::getDBO();
        $query = "UPDATE `#__ap_awardpackages` SET published = 1 WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function unpublish($data) {
        $db = JFactory::getDBO();
        $query = "UPDATE `#__ap_awardpackages` SET published = 0 WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function show_fields($table) {    	    	
        $db = JFactory::getDBO();      
        $db->setQuery("SHOW FIELDS FROM #__" . $table);
        $fields = $db->loadColumn();
        return $fields;
    }

    function info($package_id, $array = false) {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__ap_awardpackages WHERE package_id = '$package_id' LIMIT 1");
        if ($array) {
            $row = $db->loadAssoc();
        } else {
            $row = $db->loadObject();
        }
        return $row;
    }

    function save_settings($post) {
        $db = & JFactory::getDBO();
        foreach ($post as $key => $value) {
            //echo $key .' => '.$value.'<br>';
            $query = "REPLACE INTO `#__ap_donation_variables` (name,value) VALUES ('$key','$value')";
            $db->setQuery($query);
            $db->query();
        }
        $message = JText::_($query);
        JFactory::getApplication()->enqueueMessage($message, 'error');
    }

    function save_categories() {
        $db = & JFactory::getDBO();
        for ($i = 0; $i <= count(JRequest::getVar('category_id')) - 1; $i++) {
            $query = "UPDATE `#__ap_categories` SET 
				colour_code = '" . $_POST['colour_code'][$i] . "',
				category_name = '" . $_POST['category_name'][$i] . "' 
				WHERE category_id = '" . $_POST['category_id'][$i] . "'";
            $db->setQuery($query);
            $db->query();
        }
    }

    function invar($name, $value) {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__ap_donation_variables` WHERE name = '$name' LIMIT 1";
        $db->setQuery($query);
        $rs = $db->loadObject();
        if ($rs->name) {
            if ($rs->value) {
                $result = $rs->value;
            } else {
                $result = $value;
            }
            return $result;
        } else {
            return $value;
        }
    }

    /* Create by kadeyasa@gmail.com */

    function creategiftcodecat($package_id) {
        $db = $this->getDBO();
        $dateYear = date('Y-m-d');
        $row[] = array('package_id' => $package_id, 'name' => 'Red', 'image' => 'red.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FF0000', 'locked' => '1', 'category_id' => '1');
        $row[] = array('package_id' => $package_id, 'name' => 'Orange', 'image' => 'orange.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FF6600', 'locked' => '1', 'category_id' => '2');
        $row[] = array('package_id' => $package_id, 'name' => 'Yellow', 'image' => 'yellow.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FFFF00', 'locked' => '1', 'category_id' => '3');
        $row[] = array('package_id' => $package_id, 'name' => 'Green', 'image' => 'green.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#008000', 'locked' => '1', 'category_id' => '4');
        $row[] = array('package_id' => $package_id, 'name' => 'Blue', 'image' => 'blue.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#0000FF', 'locked' => '1', 'category_id' => '5');
        $row[] = array('package_id' => $package_id, 'name' => 'Dark Blue', 'image' => 'dark blue.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#000080', 'locked' => '1', 'category_id' => '6');
        $row[] = array('package_id' => $package_id, 'name' => 'Purple', 'image' => 'purple.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#800080', 'locked' => '1', 'category_id' => '7');

        if (count($row) > 0) {
            for ($c = 0; $c < count($row); $c++) {
                $query = "INSERT INTO #__giftcode_category (package_id,name,image,description,published,created_date_time,color_code,locked,category_id) VALUES ('" . $row[$c]['package_id'] . "','" . $row[$c]['name'] . "','" . $row[$c]['image'] . "','" .
                        $row[$c]['description'] . "','" . $row[$c]['published'] . "','" . $row[$c]['created_date_time'] . "','" . $row[$c]['color_code'] . "','" . $row[$c]['locked'] . "','" . $row[$c]['category_id'] . "')";

                $db->setQuery($query);

                $db->query();
            }
        }
    }

    public function getGiftCode($package_id) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "' ORDER BY id ASC LIMIT 0,1";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        foreach ($rows as $row) {

            return $row;
        }
    }
	
	public function CheckPackageExpired($package_id){
		$now 	= date('Y-m-d');
		$db		= &JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		$query->select('*');
		$query->from($db->QuoteName('#__ap_awardpackages'));
		$query->where("start_date<='".$now."'");
		$query->where("end_date>='".$now."'");
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}
