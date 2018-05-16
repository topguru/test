<?php
/**
 * @version		$Id: categories.php 01 2012-05-08 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.form.formfield');

class JFormFieldSurveyCategories extends JFormField{
    
	protected $type = 'SurveyCategories';
	
	protected function getInput(){

		$categories = JHtml::_('category.options', 'com_communitysurveys');
		$return = '<select name="'.$this->name.'" id="'.$this->name.'" class="text_area">';
		$return .= '<option value="0">------ All Categories -----</option>';
		
		foreach ($categories as $category){
			
			$return = $return.
			'<option value="'.$category->value.'"'.
				($this->value == $category->value ? ' selected="selected"':'').
				($category->disable ? ' disabled="disabled"' : '').'>'.
				htmlspecialchars($category->text, ENT_COMPAT, 'UTF-8').
			'</option>';
		}
		
		$return .= '</select>';
		
        return $return;
    }
}
?>