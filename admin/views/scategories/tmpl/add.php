<?php
/**
 * @version		$Id: add.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=scategories" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
	<div class="col100">
		<table class="table table-hover table-striped">
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_TITLE'); ?>" for="category"><?php echo JText::_('LBL_TITLE'); ?></label></th>
				<td><input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->category['title'];?>" /></td>
			</tr>
			<tr>
				<th width="150px"><label class="hasTip" title="<?php echo JText::_('LBL_ALIAS'); ?>" for="category"><?php echo JText::_('LBL_ALIAS'); ?></label></th>
				<td><input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo $this->category['alias'];?>" /></td>
			</tr>
			<tr>
				<th><label class="hasTip" title="<?php echo JText::_('LBL_PARENT_CATEGORY'); ?>" for="category"><?php echo JText::_( 'LBL_PARENT_CATEGORY' ); ?>:</label></th>
				<td>
					<select name="category" id="category">
						<?php if(!empty($this->categories)):?>
						<?php foreach($this->categories as $catid=>$title):?>
						<option value="<?php echo $catid;?>" <?php echo ($this->category['parent_id'] == $catid) ? 'selected="selected"' : '';?>><?php echo CJFunctions::escape($title);?></option>
	                    <?php endforeach;?>
	                    <?php endif;?>
					</select>
				</td>
			</tr>
		</table>
	</div>
    <input type="hidden" name="id" value="<?php echo $this->category['id'];?>">
    <input type="hidden" name="view" value="scategories">
    <input type="hidden" name="task" value="scategories.add">
</form>