<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 7;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT.DS.'helpers'.DS.'qnresults.php';
$generator = new SurveyQuestionResults($wysiwyg, $bbcode, $content);
?>

<div id="cj-wrapper">
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>
				<?php if(!$this->print):?>
				<div class="well">
					<a class="btn pull-right" 
						onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
						href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$this->response_id.'&tmpl=component&print=1'.$itemid);?>">
						<i class="fa fa-print"></i> <?php echo JText::_('JGLOBAL_PRINT');?>
					</a>
					<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid);?>">
						<i class="fa fa-reply"></i> <?php echo JText::_('LBL_RESPONSES');?>
					</a>
					<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$this->item->id.':'.$this->item->alias.$itemid);?>">
						<i class="fa fa-home"></i> <?php echo JText::_('LBL_REPORTS');?>
					</a>
				</div>
				<?php else:?>
				<script type="text/javascript">window.print();</script>
				<?php endif;?>
				
				<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
				
				<div class="well margin-top-20 clearfix">
					<?php if($this->params->get('user_avatar') != 'none'):?>
					<div class="pull-left margin-right-10 avatar hidden-phone">
						<?php echo CJFunctions::get_user_avatar(
								$this->params->get('user_avatar'),
								$this->item->response->created_by,
								$this->params->get('user_display_name'),
								$this->params->get('avatar_size'),
								$this->item->response->email,
								array('class'=>'thumbnail tooltip-hover', 'title'=>$this->item->response->username),
								array('class'=>'media-object', 'style'=>'height:'.$this->params->get('avatar_size').'px'));?>
					</div>
					<?php endif;?>
			
					<div class="form-inline">
						<label><?php echo JText::_('LBL_USERNAME');?>: </label>
						<?php echo 
						$this->item->response->created_by > 0
							? CJFunctions::get_user_profile_link(
								$this->params->get('user_avatar'), $this->item->response->created_by, $this->escape($this->item->response->username))
							: $this->escape($this->item->response->username);;?>
					</div>
					<div class="form-inline">
						<label><?php echo JText::_('LBL_DATE');?>: </label> 
						<?php echo $this->escape($this->item->response->created);?>
					</div>
				</div>
				
				<div class="results-wrapper margin-top-20">
				<?php 
				$class = '';
				foreach($this->item->questions as $item){
					switch ($item->question_type){
						case 1:
							echo $generator->get_page_header_question($item, $class);
							break;
						case 2:
						case 3:
						case 4:
							echo $generator->get_choice_question($item, $class);
							break;
						case 5:
						case 6:
							echo $generator->get_grid_question($item, $class);
							break;
						case 7:
						case 8:
						case 9:
							echo $generator->get_text_question($item, $class);
							break;
						case 10:
							echo $generator->get_text_question($item, $class, false);
							break;
						case 11:
						case 12:
							echo $generator->get_image_question($item, $class, S_IMAGES_URI);
							break;
						case 13: // name
							echo $generator->get_user_name_question($item, $class);
							break;
						case 14: // email
							echo $generator->get_email_question($item, $class);
							break;
						case 15: // calendar
							echo $generator->get_calendar_question($item, $class);
							break;
						case 16: // address
							echo $generator->get_address_question($item, $class);
							break;
					}
				}
				?>
				</div>
			</td>
		</tr>
	</table>
		
</div>