<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = $this->params->get('hide_toolbar_responses', 0) == 1 ? -1 : 1;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();
CJFunctions::load_jquery(array('libs'=>array('rating')));

$bbcode = $user->authorise('quiz.wysiwyg', Q_APP_NAME) && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

if($this->params->get('enable_sharing_tools', 1) == 1){
	
	JFactory::getDocument()->addScript('//s7.addthis.com/js/300/addthis_widget.js');
}
?>

<div id="cj-wrapper" class="container-fluid no-space-left no-space-right">
	
	<div class="<?php echo $this->hide_template == 1 ? 'full-screen' : 'row-fluid';?>">
		
		<table width="100%">
			<tr>
				<td width="10%" valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
				</td>
				<td valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
	
					<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
					<div class="quiz-description"><?php echo $this->item->description; ?></div>
					
					<div class="tags margin-top-20">
						<?php 
						
						foreach($this->item->tags as $tag):?>
						<a title="<?php echo JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text)).' - '.$this->escape($tag->description);?>" class="tooltip-hover" 
							href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_tagged_quizzes&id='.$tag->tag_id.$itemid);?>">
							<span class="label"><?php echo $this->escape($tag->tag_text);?></span>
						</a>
						<?php endforeach;?>
					</div>
                    
					<?php if($this->params->get('enable_sharing_tools', 1) == 1):?>
					<div class="well well-small social-sharing no-pad-left margin-top-20">
						<p><?php echo JText::_('LBL_QUIZ_SOCIAL_SHARING_DESC');?></p>
						<div class="addthis_toolbox addthis_default_style ">
							<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
							<a class="addthis_button_tweet"></a>
							<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
							<a class="addthis_counter addthis_pill_style"></a>
						</div>
					</div>
					<?php endif;?>
					
					<div class="well center margin-top-20">
						<a class="btn" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid)?>">
							<?php echo JText::_('LBL_CANCEL');?>
						</a>
						<a class="btn btn-primary" data-loading-text="<?php echo JText::_('TXT_LOADING');?>" onclick="jQuery(this).button('loading');" 
							href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.response_form&id='.$this->item->id);?>">
							<i class="icon-hand-right icon-white"></i> <?php echo JText::_('LBL_CONTINUE');?>
						</a>
					</div>
				</td>
			</tr>
		</table>
		
	</div>
</div>