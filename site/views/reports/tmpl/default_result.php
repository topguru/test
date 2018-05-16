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

$page_id = 8;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? true : false;
$bbcode =  $this->params->get('default_editor', 'bbcode') == 'bbcode';
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT.DS.'helpers'.DS.'qnresults.php';
$generator = new QuizQuestionResults($wysiwyg, $bbcode, $content);

if($this->params->get('enable_sharing_tools', 1) == 1){

	JFactory::getDocument()->addScript('//s7.addthis.com/js/300/addthis_widget.js');
}
?>

<div id="cj-wrapper" class="container-fluid no-space-left no-space-right">	
	<div class="<?php echo (isset($this->hide_template) && $this->hide_template == 1) ? 'full-screen' : 'row-fluid';?>">
		<table width="100%">
			<tr>
				<td width="10%" valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
				</td>
				<td valign="top">
					<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
					<?php if(!$this->print):?>
					<div class="well">
						<a class="btn pull-right" 
							onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
							href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=results&id='.$this->item->id.':'.$this->item->alias.'&rid='.$this->response_id.'&tmpl=component&print=1'.$itemid);?>">
							<i class="fa fa-print"></i> <?php echo JText::_('JGLOBAL_PRINT');?>
						</a>
					</div>
					<?php else:?>
					<script type="text/javascript">window.print();</script>
					<?php endif;?>
					
					<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
					<div class="quiz-description"><?php echo CJFunctions::process_html($this->item->description, $bbcode, $content)?></div>
					
					<div class="tags margin-bottom-20">
						<?php foreach($this->item->tags as $tag):?>
						<a title="<?php echo JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text)).' - '.$this->escape($tag->description);?>" class="tooltip-hover" 
							href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=tag&id='.$tag->tag_id.':'.$tag->alias.$itemid);?>">
							<span class="label"><?php echo $this->escape($tag->tag_text);?></span>
						</a>
						<?php endforeach;?>
					</div>
				
					<div class="well well-transperant">
						<h4 class="page-header no-margin-top margin-bottom-10"><?php echo JText::_('LBL_LEGEND');?></h4>
						<span class="margin-right-20"><i class="fa fa-hand-o-up"></i> <?php echo JText::_('LBL_YOUR_ANSWER');?></span>
						<span class="margin-right-20"><i class="fa fa-check"></i> <?php echo JText::_('LBL_CORRECT_ANSWER');?></span>
						<span class="margin-right-20"><i class="fa fa-check-square-o"></i> <?php echo JText::_('LBL_SELECTED_CORRECT');?></span>
						<span class="margin-right-20"><i class="fa fa-minus-square"></i> <?php echo JText::_('LBL_SELECTED_WRONG');?></span>
						<span class="margin-right-20"><i class="fa fa-thumbs-down"></i> <?php echo JText::_('LBL_NOT_SELECTED_CORRECT');?></span>
					</div>
					
					<div class="results-wrapper">
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
							case 11:
							case 12:
								echo $generator->get_image_question($item, $class, CQ_IMAGES_URI_2);
								break;
						}
					}
					?>
					</div>
					
                    <div class="table-responsive" id="score">
						<table class="table table-striped table-hover table-bordered table-condensed">
                        <thead>
								<tr>
									<th colspan="3"><h3><?php echo JText::_('GIFTCODE');?></h3></th>
								</tr>
							</thead>
                            <tbody>
								<tr>
                                    <th style="text-align:center;">GiftCode</th>
                                    <th style="text-align:center;">Quantity</th>
                                    </tr>
                                    
                                    <?php 
									foreach ($this->response as $respon) { ?>
       								<tr>
                                    <?php echo '<td style="width:30px;padding:20px;text-align:center;background-color:'.$respon->colour_code.'" valign="center"><span style="color:white">';
                                     
									if (!empty($respon->completed))
									{echo $respon->category_id;}
                                    else
                                    {echo $respon->category_id;}
									?>
                                    </span>                                    
                                    </td>
                                    <td style="padding:20px;text-align:center;">
                                    <?php if ($respon->completed ==1)
									{echo $respon->complete_giftcode_quantity;}
                                     else
                                    {echo $respon->incomplete_giftcode_quantity;} 
									?>
                                    </td>

                                    </tr>

                                    <?php } ?>

                                    </tbody>
                        </table>
					</div>
                    
					<div class="table-responsive" id="quiz-score">
						<table class="table table-striped table-hover table-bordered table-condensed">
							<thead>
								<tr>
									<th colspan="2"><h3><?php echo JText::_('COM_COMMUNITYQUIZ_YOUR_FINAL_REPORT');?></h3></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_TOTAL_MARKS')?></th>
									<td><label class="label label-info"><?php echo $generator->get_total();?></label></td>
								</tr>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_MARKS_SECURED')?></th>
									<td><label class="label label-success"><?php echo $generator->get_score();?></label></td>
								</tr>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_PERCENTAGE_SECURED')?></th>
									<td><label class="label label-success"><?php echo $generator->get_percentage().' %';?></label></td>
								</tr>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_CORRECT_QUESTIONS');?></th>
									<td><label class="label label-info"><?php echo $generator->get_count();?></label></td>
								</tr>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_SUCCESS_RATIO')?></th>
									<td><label class="label label-info"><?php echo $generator->get_success_ratio().' %';?></label></td>
								</tr>
								<?php if($this->item->cutoff > 0):?>
								<tr>
									<th><?php echo JText::_('COM_COMMUNITYQUIZ_LABEL_FINAL_RESULT');?></th>
									<td>
										<?php if($generator->get_score() >= $this->item->cutoff):?>
										<span class="label label-success"><?php echo JText::_('COM_COMMUNITYQUIZ_MESSAGE_PASSED');?></span>
										<?php else:?>
										<span class="label label-danger"><?php echo JText::_('COM_COMMUNITYQUIZ_MESSAGE_FAILED');?></span>
										<?php endif;?>
									</td>
								</tr>
								<?php endif;?>
							</tbody>
						</table>
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
						<?php if($this->item->multiple_responses == 1):?>
						<a class="btn" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.quiz_intro&id='.$this->item->id.$itemid)?>">
							<?php echo JText::_('LBL_TAKE_AGAIN');?>
						</a>
						<?php endif;?>
						<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid)?>">
							<?php echo JText::_('LBL_HOME');?>
						</a>
					</div>
				</td>
			</tr>
		</table>		
	</div>
</div>