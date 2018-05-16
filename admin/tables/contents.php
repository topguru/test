<?php

/**
 * @package		AdsManager
 * @copyright	Copyright (C) 2010-2012 JoomPROD.com. All rights reserved.
 * @license		GNU/GPL
 */
defined('_JEXEC') or die();

jimport('joomla.filesystem.file');

class AdsmanagerTableContents extends JTable {

    var $id = null;
    var $userid = null;
    var $ad_headline = null;
    var $ad_text = null;
    var $email = null;
    var $date_created = null;
    var $date_modified = null;
    var $expiration_date = null;
    var $recall_mail_sent = null;
    var $metadata_description = null;
    var $metadata_keywords = null;
    var $published = null;
    var $images = null;
    var $errors = null;
    var $data = null;
    var $package_id=null;
    var $ap_cat=null;

    function __construct(&$db) {
        parent::__construct('#__adsmanager_ads', 'id', $db);
    }

    function replaceBannedWords($text, $bannedwords, $replaceword) {
        foreach ($bannedwords as $bannedword) {
            if ($bannedword != "") {
                // preg_replace can returns NULL if bad bannedword, we should test return value before assign to $text;
                $text2 = preg_replace("/\b$bannedword\b/u", $replaceword, $text);
                if ($text2 != NULL)
                    $text = $text2;
            }
        }
        return $text;
    }

    function getErrors() {
        return $this->errors;
    }

    function getData() {
        return $this->data;
    }

    function bindContent($post, $files, $conf, $model, $plugins) {

        $app = JFactory::getApplication();
        
        
        $this->bind($post);
        
        if ($this->id == 0) {
            $this->new_ad = true;
        } else {
            $query = "SELECT content FROM #__adsmanager_pending_ads WHERE contentid = $this->id";
            $this->_db->setQuery($query);
            $previouscontent = $this->_db->loadResult();
            $previouscontent = @json_decode($previouscontent);
        }

        $this->data = array();
        $this->errors = array();

        if (function_exists("getMaxCats"))
            $maxcats = getMaxCats($conf->nbcats);
        else
            $maxcats = $conf->nbcats;

        if ($maxcats > 1) {
            $selected_cats = $post["selected_cats"];
            if (!is_array($selected_cats)) {
                $c = array();
                $c[0] = $selected_cats;
                $selected_cats = $c;
            }
            if (count($selected_cats) > $maxcats) {
                $selected_cats = array_slice($selected_cats, 0, $maxcats);
            }
            $this->data['categories'] = $selected_cats;
        } else {
            $category = $post["category"];
            $this->data['categories'] = array();
            $this->data['categories'][0] = intval($category);
        }
        
        $user_id = JRequest::getVar('userid');
        //get fields
        $this->_db->setQuery("SELECT * FROM #__adsmanager_fields WHERE published = 1");
        $fields = $this->_db->loadObjectList();
        if ($this->_db->getErrorNum()) {
            $this->errors[] = $this->_db->stderr();
            return false;
        }

        $query = "UPDATE #__adsmanager_ads ";

        $bannedwords = str_replace("\r", "", $conf->bannedwords);
        $bannedwords = explode("\n", $bannedwords);
        $replaceword = $conf->replaceword;

        $data['fields'] = array();
        foreach ($fields as $field) {
            if ($field->type == "multiselect") {
                $value = JRequest::getVar($field->name, array());
                $this->data['fields'][$field->name] = $value;
            } else if (($field->type == "multicheckbox") || ($field->type == "multicheckboximage")) {
                $value = $value = JRequest::getVar($field->name, array());
                $this->data['fields'][$field->name] = $value;
            } else if ($field->type == "file") {
                if (isset($files[$field->name]) and !$files[$field->name]['error']) {
                    jimport('joomla.filesystem.file');

                    $filename = $files[$field->name]['name'];

                    $extension = JFile::getExt($filename);
                    $name = md5(rand(1, 100000) . $filename);

                    if (strpos($extension, "php") !== false) {
                        $extension = 'txt';
                    }
                    $filename = $name . "." . $extension;

                    JFile::move($files[$field->name]['tmp_name'], JPATH_SITE . "/images/com_awardpackage/files/" . $filename);

                    $this->data['fields'][$field->name] = $filename;
                } else {
                    continue;
                }
            } else if ($field->type == "editor") {
                $value = JRequest::getVar($field->name, '', 'post', 'string', JREQUEST_ALLOWHTML);
                $this->data['fields'][$field->name] = $this->replaceBannedWords($value, $bannedwords, $replaceword);
            }
            //Plugins
            else if (isset($plugins[$field->type])) {
                $value = $plugins[$field->type]->onFormSave($this, $field);
                if ($value !== null)
                    $this->data['fields'][$field->name] = $value;
            }
            else {
                $value = JRequest::getVar($field->name, '');
                $this->data['fields'][$field->name] = $this->replaceBannedWords($value, $bannedwords, $replaceword);
            }
        }

        $this->data['images'] = array();
        $this->data['delimages'] = array();

        $current_images = json_decode($this->images);
        if ($current_images == null)
            $current_images = array();

        $image_index = 0;

        $pending = JRequest::getInt('pending', 0);
        if ($pending && (count($previouscontent->images) > 0)) {
            foreach ($previouscontent->images as $img) {
                $this->data['images'][] = $img;
                if ($img->index > $image_index)
                    $image_index = $img->index;
            }
        }

        $deleted_images = JRequest::getVar("deleted_images", "");
        $deleted_images = explode(',', $deleted_images);
        foreach ($current_images as $i => $img) {
            if (in_array($img->index, $deleted_images)) {
                $this->data['delimages'][] = $img;
            } else {
                if ($img->index > $image_index)
                    $image_index = $img->index;
            }
        }
        foreach ($this->data['images'] as $i => $img) {
            if (in_array($img->index, $deleted_images)) {
                JFile::delete(JPATH_SITE . "/images/com_awardpackage/ads/tmp/" . $img->image);
                JFile::delete(JPATH_SITE . "/images/com_awardpackage/ads/tmp/" . $img->thumbnail);
                unset($this->data['images'][$i]);
            }
        }

        $nb_images = count($current_images) - count($this->data['delimages']);

        $nbMaxImages = $conf->nb_images;

        $uploader_count = JRequest::getInt('uploader_count', 0);

        $targetDir = JPATH_SITE . '/tmp/plupload';
        $dir = JPATH_SITE . "/images/com_awardpackage/ads/tmp/";

        //echo $uploader_count;exit();
        $this->last_upload_index = -1;

        $orderlisttmp = explode(',', JRequest::getString('orderimages', ""));
        $orderlist = array();
        foreach ($orderlisttmp as $value) {
            $orderlist[] = str_replace('li_img_', '', $value);
        }

        for ($i = 0; $i < $uploader_count && $nb_images < $nbMaxImages; $i++) {
            $this->last_upload_index = $i;

            $uploader_tmpname = JRequest::getString('uploader_' . $i . '_tmpname', 0);
            $uploader_id = JRequest::getString('uploader_' . $i . '_id', 0);
            $uploader_name = JRequest::getString('uploader_' . $i . '_name', 0);
            $uploader_status = JRequest::getString('uploader_' . $i . '_status', 0);

            $tmpfile = date("Ymd") . "-" . uniqid();
            $thumb_tmpfile = date("Ymd") . "-" . uniqid();

            if (($uploader_status == "done") && (file_exists($targetDir . "/" . $uploader_tmpname))) {
                try {
                    $error = $model->createImageAndThumb($targetDir . "/" . $uploader_tmpname, $tmpfile, $thumb_tmpfile, $conf->max_width, $conf->max_height, $conf->max_width_t, $conf->max_height_t, $conf->tag, $dir, $uploader_name);
                    JFile::delete($targetDir . "/" . $uploader_tmpname);

                    if ($error != null) {
                        $this->errors[] = $error;
                    } else {
                        $image_index++;
                        $nb_images++;
                        $newimg = new stdClass();
                        $newimg->index = $image_index;
                        $newimg->image = $tmpfile;
                        $newimg->thumbnail = $thumb_tmpfile;
                        $this->data['images'][] = $newimg;
                        //echo $uploader_id."<br/>";
                        foreach ($orderlist as &$val) {
                            if ($val == $uploader_id) {
                                $val = $image_index;
                            }
                        }
                    }
                } catch (Exception $e) {
                    JFile::delete($targetDir . "/" . $uploader_tmpname);
                    $this->errors[] = $e->getMessage();
                }
            }
        }

        $this->data['orderimages'] = $orderlist;

        $newlistimages = array();
        foreach ($orderlist as $o) {
            foreach ($this->data['images'] as $image) {
                if ($image->index == $o)
                    $newlistimages[] = $image;
            }
        }
        $this->data['images'] = $newlistimages;

        //for Paidsystem to known number of current images
        $this->nb_images = $nb_images;
        $this->image_index = $image_index;

        $app = JFactory::getApplication();
        if (($app->isAdmin() == false) && ($this->id == 0)) {
            $this->date_created = date("Y-m-d H:i:s");
            $delta = $conf->ad_duration;
            $this->expiration_date = date("Y-m-d H:i:s", time() + ($delta * 24 * 3600));
            if ($conf->auto_publish == 1)
                $this->published = 1;
            else
                $this->published = 0;
        }

        if (count($this->errors) > 0)
            return false;
        else
            return true;
    }

    function savePending() {
        $row = new JObject();

        if ($this->id == 0) {
            $data = new JObject();
            $data->date_created = $this->date_created;
            $data->expiration_date = $this->expiration_date;
            $data->published = false;
            $data->userid = $this->userid;
            $this->_db->insertObject('#__adsmanager_ads', $data);
            $this->id = (int) $this->_db->insertid();

            $this->data['published'] = $this->published;
        }

        $pending = JRequest::getInt('pending', 0);
        if ($pending == 1)
            $this->data['published'] = true;

        $row->contentid = $this->id;
        $row->userid = $this->userid;
        $row->date = date("Y-m-d H:i:s");

        $this->data['metadata_description'] = $this->metadata_description;
        $this->data['metadata_keywords'] = $this->metadata_keywords;

        $row->content = json_encode($this->data);

        $query = "DELETE FROM #__adsmanager_pending_ads WHERE contentid = " . (int) $row->contentid;
        $this->_db->setQuery($query);
        $this->_db->query();

        //Insert new record.
        $this->_db->insertObject('#__adsmanager_pending_ads', $row);

        return $row->contentid;
    }

    function stringURLSafe($string) {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $string);

        $lang = & JFactory::getLanguage();
        $str = $lang->transliterate($str);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $str);

        // lowercase and trim
        $str = trim(strtolower($str));
        return $str;
    }

    function save() {
        $row = new JObject();

        if ($this->id != 0) {
            $row->id = $this->id;
        }
        
        $app = JFactory::getApplication();
        if (($app->isAdmin() == true) ||
                (($app->isAdmin() == false) && (@$this->new_ad == true))) {
            $row->date_created = $this->date_created;
            $row->expiration_date = $this->expiration_date;
            $row->published = $this->published;
        }

        $row->date_modified = date('Y-m-d H:i:s');

        $row->userid = $this->userid;

        foreach ($this->data['fields'] as $name => $value) {
            if (is_array($value))
                $v = ',' . implode(',', $value) . ',';
            else
                $v = $value;
            $row->$name = $v;
        }

        $row->metadata_description = $this->metadata_description;
        $row->metadata_keywords = $this->metadata_keywords;
        $row->package_id = JRequest::getVar('package_id');
        //Insert new record.
        if ($this->id == 0) {
            $ret = $this->_db->insertObject('#__adsmanager_ads', $row);
            $contentid = (int) $this->_db->insertid();
        } else {
            $ret = $this->_db->updateObject('#__adsmanager_ads', $row, 'id');
            $contentid = $this->id;
        }


        // Category
        $query = "DELETE FROM #__adsmanager_adcat WHERE adid = $contentid";
        $this->_db->setQuery($query);
        $this->_db->query();
        $ap_cat = JRequest::getVar('award');
        foreach ($this->data['categories'] as $cat) {
            $query = "INSERT INTO #__adsmanager_adcat(adid,catid,ap_cat) VALUES ($contentid,$cat,'$ap_cat')";
            $this->_db->setQuery($query);
            $this->_db->query();
            $this->catid = $cat;
        }

        //Images		
        $dir = JPATH_SITE . "/images/com_awardpackage/ads/tmp/";
        $dirfinal = JPATH_SITE . "/images/com_awardpackage/ads/";

        $current_images = json_decode($this->images);
        if ($current_images == null)
            $current_images = array();
        if (!is_array($current_images))
            $current_images = get_object_vars($current_images);

        foreach ($this->data['delimages'] as $image) {
            JFile::delete(JPATH_SITE . "/images/com_awardpackage/ads/" . $image->image);
            JFile::delete(JPATH_SITE . "/images/com_awardpackage/ads/" . $image->thumbnail);
            foreach ($current_images as $key => $img) {
                if ($img->index == $image->index) {
                    unset($current_images[$key]);
                    break;
                }
            }
        }

        if (!is_array($current_images))
            $current_images = get_object_vars($current_images);
        sort($current_images);

        jimport('joomla.filter.output');
        if (ADSMANAGER_SPECIAL == "abrivac") {
            $imgtitle = JFilterOutput::stringURLSafe(getMultiLangText($row->ad_headline));
        } else {
            $imgtitle = JFilterOutput::stringURLSafe($row->ad_headline);
        }
        foreach ($this->data['images'] as &$image) {
            $src = $dir . $image->image;
            $dest = $dirfinal . $imgtitle . "_" . $contentid . "_" . $image->index . ".jpg";
            JFile::move($src, $dest);
            $image->image = $imgtitle . "_" . $contentid . "_" . $image->index . ".jpg";

            $src = $dir . $image->thumbnail;
            $dest = $dirfinal . $imgtitle . "_" . $contentid . "_" . $image->index . "_t.jpg";
            ;
            JFile::move($src, $dest);
            $image->thumbnail = $imgtitle . "_" . $contentid . "_" . $image->index . "_t.jpg";

            $current_images[] = $image;
        }

        if (@$this->data['paid']['images']) {
            foreach ($this->data['paid']['images'] as $image) {
                $src = $dir . $image->image;
                $dest = $dirfinal . $imgtitle . "_" . $contentid . "_" . $image->index . ".jpg";
                JFile::move($src, $dest);
                $image->image = $imgtitle . "_" . $contentid . "_" . $image->index . ".jpg";

                $src = $dir . $image->thumbnail;
                $dest = $dirfinal . $imgtitle . "_" . $contentid . "_" . $image->index . "_t.jpg";
                ;
                JFile::move($src, $dest);
                $image->thumbnail = $imgtitle . "_" . $contentid . "_" . $image->index . "_t.jpg";

                $current_images[] = $image;
            }
        }

        $orderlist = $this->data['orderimages'];
        $newlistimages = array();
        foreach ($orderlist as $o) {
            foreach ($current_images as $image) {
                if ($image->index == $o)
                    $newlistimages[] = $image;
            }
        }

        $row = new JObject();
        $row->id = $contentid;
        $row->images = json_encode($newlistimages);
        $ret = $this->_db->updateObject('#__adsmanager_ads', $row, 'id');

        if (function_exists('savePaidAd')) {
            savePaidAd($this, $contentid);
        }

        $this->id = $contentid;
    }

    function delete($adid, $conf, $plugins) {
        $adid = (int) $adid;

        $this->_db->setQuery("SELECT * FROM #__adsmanager_ads WHERE id=$adid");
        $ad = $this->_db->loadObject();

        $this->_db->setQuery("DELETE FROM #__adsmanager_adcat WHERE adid=$adid");
        $this->_db->query();

        /* $this->_db->setQuery( "UPDATE #__adsmanager_ads SET published=0,recall_mail_sent = 0 WHERE id = $adid");
          $this->_db->query();

          $this->_db->setQuery( "INSERT INTO #__adsmanager_adcat (adid,catid) VALUES ($adid,$conf->archive_catid)");
          $this->_db->query();
         */

        $this->_db->setQuery("DELETE FROM #__adsmanager_ads WHERE id=$adid");
        $this->_db->query();

        $this->_db->setQuery("SELECT name FROM #__adsmanager_fields WHERE `type` = 'file'");
        $file_fields = $this->_db->loadObjectList();
        foreach ($file_fields as $file_field) {
            $filename = "\$ad->" . $file_field->name;
            eval("\$filename = \"$filename\";");
            JFile::delete(JPATH_SITE . "/images/com_awardpackage/files/" . $filename);
        }

        $current_images = json_decode($ad->images);
        if ($current_images == null)
            $current_images = array();

        foreach ($current_images as $img) {
            $pict = JPATH_SITE . "/images/com_awardpackage/ads/" . $img->image;
            if (file_exists($pict)) {
                JFile::delete($pict);
            }
            $pic = JPATH_SITE . "/images/com_awardpackage/ads/" . $img->thumbnail;
            if (file_exists($pic)) {
                JFile::delete($pic);
            }
        }

        foreach ($plugins as $plugin) {
            $plugin->onDelete(0, $adid);
        }

        if (function_exists('deletePaidAd')) {
            deletePaidAd($adid);
        }
    }

}
