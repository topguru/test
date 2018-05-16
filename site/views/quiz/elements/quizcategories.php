<?php
/**
 * @version		$Id: quizcategories.php 01 2012-05-08 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.form.formfield');

// CJLib includes
$cjlib = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework.php';
if(file_exists($cjlib)){

	require_once $cjlib;
}else{

	die('CJLib (CoreJoomla API Library) component not found. Please download and install it to continue.');
}
CJLib::import('corejoomla.framework.core');
CJLib::import('corejoomla.nestedtree.core');

class JFormFieldQuizCategories extends JFormField{
    
	protected $type = 'QuizCategories';
	
	protected function getInput(){

        $db = JFactory::getDBO();
        
    	$tree = new CjNestedTree($db, "#__quiz_categories");
    	$categories = $tree->get_indented_nodes(0, '--', false, array('quizzes'));

        $return = '<select name="'.$this->name.'" id="'.$this->name.'" class="text_area">';
        $return .= '<option value="0">------ All Categories -----</option>';
        
		if(!empty($categories)){
			foreach($categories as $id=>$title){
				$return = $return.'<option value="'.$id.'"'.($this->value == $id ? ' selected="selected"':'').'>'.CJFunctions::escape($title).'</option>';
			}
		}
        $return .= '</select>';

        return $return;
    }
}
?>