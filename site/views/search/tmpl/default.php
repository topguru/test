<?php
/**
 * @version		$Id: default.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Answers
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 4;
?>
<div id="cj-wrapper">

	<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
	<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
	
	<div class="container-fluid no-space-left no-space-right quiz-wrapper">
		<div class="row-fluid">
			<div class="span12">
			
				<h2 class="page-header no-margin-top margin-bottom-10"><?php echo JText::_('LBL_ADVANCED_SEARCH');?></h2>
				
				<div class="alert alert-info"><i class="icon-info-sign"></i> <?php echo JText::_('TXT_ENTER_SEARCH_CRITERIA');?></div>
				
				<form action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.redirect_to_search'.$itemid);?>" method="get">
					
					<div class="well">
						<div class="row-fluid">
							<div class="span6">
								 <fieldset class="no-margin-top">
								 	<legend><?php echo JText::_('LBL_KEYWORDS');?></legend>
								 	<input name="q" type="text" placeholder="<?php echo JText::_('LBL_KEYWORDS');?>">
								 	
								 	<select name="qt" class="span9">
								 		<option value="0"><?php echo JText::_('LBL_SEARCH_TITLE_ONLY');?></option>
								 		<option value="1"><?php echo JText::_('LBL_SEARCH_TITLE_DESC');?></option>
								 	</select>
								 	
								 	<label class="checkbox"><input type="checkbox" value="1" name="all"> <?php echo JText::_('LBL_SEARCH_ALL_WORDS');?></label>
								 </fieldset>
							</div>
							
							<div class="span6">
								<fieldset class="no-margin-top">
									<legend><?php echo JText::_('LBL_USERNAME')?></legend>
									<input name="u" type="text" class="span9" placeholder="<?php echo JText::_('LBL_USERNAME');?>">
									<label class="checkbox"><input name="m" type="checkbox" value="1"> <?php echo JText::_('LBL_EXACT_MATCH');?></label>
								</fieldset>
							</div>
						</div>
					</div>
					
					<div class="well">
						<div class="row-fluid">
							<div class="span6">
								<fieldset class="no-margin-top">
									<legend><?php echo JText::_('LBL_SEARCH_OPTIONS');?></legend>
									<label><?php echo JText::_('LBL_SEARCH_IN');?></label>
									<select name="type" size="1">
										<option value="0"><?php echo JText::_('LBL_ALL_QUIZZES')?></option>
										<?php if(!$user->guest):?>
										<option value="1"><?php echo JText::_('LBL_QUIZZES_RESPONDED')?></option>
										<?php endif;?>
									</select>
									<label><?php echo JText::_('LBL_SEARCH_ORDER_BY');?></label>
									<select name="ord" size="1">
										<option value="0"><?php echo JText::_('LBL_DATE');?></option>
										<option value="2"><?php echo JText::_('LBL_RESPONSES');?></option>
										<option value="3"><?php echo JText::_('LBL_CATEGORY');?></option>
									</select>
									<label><?php echo JText::_('LBL_SEARCH_ORDER');?></label>
									<select name="dir" size="1">
										<option value="0"><?php echo JText::_('LBL_ASCENDING');?></option>
										<option value="1"><?php echo JText::_('LBL_DESCENDING');?></option>
									</select>
								</fieldset>
							</div>
							<div class="span6">
								<fieldset class="no-margin-top">
									<legend><?php echo JText::_('LBL_CATEGORIES')?></legend>
									<select name="cid[]" multiple="multiple" size="10">
										<option selected="selected"><?php echo JText::_('LBL_ALL_CATEGORIES');?></option>
										<?php foreach ($this->categories as $id=>$title):?>
										<option value="<?php echo $id;?>"><?php echo $this->escape($title);?></option>
										<?php endforeach;?>
									</select>
								</fieldset>
							</div>
						</div>
					</div>
					
					<div class="well">
						<div class="row-fluid">
							<div class="center">
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid)?>" class="btn"><?php echo JText::_('LBL_CANCEL');?></a>
								<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i> <?php echo JText::_('LBL_SEARCH');?></button>
							</div>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>