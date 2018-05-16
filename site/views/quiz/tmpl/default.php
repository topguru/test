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
$catparam = '';
$page_id = isset($this->page_id) ? $this->page_id : 1;
if(!empty($this->category)){
	$catparam = '&id='.$this->category['id'].':'.$this->category['alias'];
}
CJFunctions::load_jquery(array('libs'=>array('rating')));

?>
<script type="text/javascript">
function open_modal_window(e, index){		
	var row = jQuery(e).parent().parent().parent().parent().parent().parent().parent().parent().parent().index();
	jQuery('#rowQuestionText').val(row);
	jQuery('#colQuestionText').val(index);
	jQuery('#loadGiftcode'+index).modal('show');	
}
function close_modal_window(){	
	jQuery('#loadGiftcode').modal('toggle');	
}
</script>
<div id="cj-wrapper">
	<table width="100%">
		<tr>
			<td valign="top" width="10%">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
            <?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>	
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
				<div class="container-fluid no-space-left no-space-right quizzes-wrapper">
					<div class="row-fluid">
						<div class="span12">			
							<?php echo CJFunctions::load_module_position('quizzes-list-above-categories');?>				
							<div class="well">				
								<?php if($this->params->get('display_cat_list', 1) == 1 && !empty($this->categories) || !empty($this->category)):?>
								<div class="clearfix page-header no-space-top margin-bottom-10">
									<h2 class="margin-right-10 no-space-top no-space-bottom pull-left">
										<?php echo JText::_('TXT_CATEGORIES').(!empty($this->category) ? ': <small>'.$this->escape($this->category['title']).'</small>' : '');?>
									</h2>
									
									<div class="header-icons">
										<?php //if(!$user->guest && $user->authorise('quiz.subscrcat', Q_APP_NAME)):?>
										<?php if(isset($this->subscribed) && $this->subscribed):?>
										<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.unsubscribe_category_feed'.$catparam.$itemid);?>" 
											title="<?php echo JText::_('LBL_UNSUBSCRIBE')?>" class="tooltip-hover btn-unsubscribe" onclick="return false;">
											<i class="fa fa-check-circle-o"></i>
										</a>
										<?php else:?>
										<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.subscribe_category_feed'.$catparam.$itemid);?>" 
											title="<?php echo JText::_('LBL_SUBSCRIBE')?>" class="tooltip-hover btn-subscribe" onclick="return false;">
											<i class="fa fa-envelope"></i>
										</a>
										<?php endif;?>
										<?php //endif;?>
										
										<?php if($this->params->get('enable_rss_feed', 0) == '1'):?>
										<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_rss_feed&format=feed'.$catparam.$itemid);?>" 
											title="<?php echo JText::_('LBL_RSS_FEED')?>" class="tooltip-hover">
											<i class="fa fa-rss-square"></i>
										</a>
										<?php endif;?>
									</div>
								</div>					
								<?php elseif(!empty($this->page_header)):?>
								<h2 class="page-header margin-bottom-10 no-space-top"><?php echo $this->escape($this->page_header);?></h2>
								<?php endif;?>
								
								<?php if(!empty($this->page_description)):?>
								<div class="margin-bottom-10"><?php echo $this->page_description;?></div>
								<?php endif;?>
								
								<?php

								if($this->params->get('display_cat_list', 1) == 1){
									echo '<ul class="unstyled list-unstyled no-space-left">';
											foreach ($this->categories as $row){
											$url = JRoute::_($this->page_url.'&id='.$row["id"].':'.$row["alias"].$menu_id);
											echo '<li class="parent-item"><a href="'.$url.'">'.$row["title"].' ('.$row["quizzes"].')</a></li>';
											}
											echo '</ul>';
								} 
								?>
								
								<?php if($this->params->get('display_search_box', '1') == '1'):?>
								<div class="row-fluid margin-top-10">
									<div class="span12">
										<form style="text-align: center;" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.redirect_to_search'.$itemid);?>" class="no-margin-bottom" method="post">
											<div class="input-append center">
												<input type="text" class="required" name="q" placeholder="<?php echo JText::_('LBL_SEARCH');?>">
												<button type="submit" class="btn"><?php echo JText::_('LBL_SEARCH');?></button>
											</div>
											<div class="center">
												<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.redirect_to_search'.$itemid)?>" class="muted">
													<?php echo JText::_('LBL_TRY_ADVANCED_SEARCH');?>
												</a>
											</div>
											<?php if(!empty($this->category)):?>
											<input type="hidden" name="catid" value="<?php echo $this->category['id'];?>">
											<?php endif;?>
										</form>
									</div>
								</div>
								<?php endif;?>
							</div>
							
							<?php echo CJFunctions::load_module_position('quizzes-list-below-categories');?>
							
							<?php if(!empty($this->items)):?>
							<?php foreach ($this->items as $item):
									$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );


                            $giftcode = $model->get_total_giftcode($item->id);
							$totgiftcode = 0;
							foreach ($giftcode as $rows){
								$total = $rows->complete_giftcode_quantity + $rows->incomplete_giftcode_quantity;
								$totgiftcode = $totgiftcode + $total;
							} ?>
							<div class="media clearfix">
								<?php if($this->params->get('user_avatar') != 'none'):?>
								<div class="pull-left margin-right-10 avatar hidden-phone">
									<?php echo CJFunctions::get_user_avatar(
										$this->params->get('user_avatar'), 
										$item->created_by, 
										$this->params->get('user_display_name'), 
										$this->params->get('avatar_size'),
										$item->email,
										array('class'=>'thumbnail tooltip-hover', 'title'=>$item->username),
										array('class'=>'media-object', 'style'=>'height:'.$this->params->get('avatar_size').'px'));?>
								</div>
								<?php endif;?>
				
								<div class="pull-left hidden-phone thumbnail num-box">
									<h2 class="num-header"><?php echo $item->responses;?></h2>
									<span class="muted"><?php echo $item->responses == 1 ? JText::_('LBL_RESPONSE') : JText::_('LBL_RESPONSES');?></span>
								</div>
								<div class="pull-left hidden-phone thumbnail num-box">
									<h2 class="num-header">
									<?php echo '<a href="#" onclick="open_modal_window(this, '.$item->id.'); return false;">';
                                     echo $totgiftcode; 
									 //var_dump($user->authorise('quiz.edit', Q_APP_NAME));
									 ?>
                                    </a>
                                    </h2>
									<span class="muted"><?php echo $item->responses == 1 ? JText::_('Giftcodes') : JText::_('Giftcodes');?></span>
								</div>
								<div class="media-body">
									
									<?php if($this->params->get('enable_ratings', 1) == 1):?>
									<div class="pull-right hidden-phone">
							        	<div class="star-rating" data-rating-score="<?php echo empty($item->rating) ? '0' : $item->rating;?>"></div>
										<div class="rating-details"><small><?php echo JText::sprintf('LBL_RATING_NUM', empty($item->rating) ? '0' : $item->rating);?></small></div>
									</div>
									<?php endif;?>
			
									<div class="clearfix">
										<h4 class="media-heading pull-left margin-right-10 no-space-bottom" style="line-height: 20px;">
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.quiz_intro&id='.$item->id);?>"<?php echo $item->skip_intro == 1? ' rel="nofollow"' : ''?>>
												<?php echo $this->escape($item->title); ?>
											</a>
										</h4>
										
										<div class="header-icons">								
											<?php 
											if(($user->id == $item->created_by )):?>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&id='.$item->id.'&uniq_id='.$item->uniq_key)?>" 
												class="tooltip-hover" title="<?php echo JText::_('LBL_EDIT');?>">
												<i class="fa fa-edit"></i>
											</a>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.getQuestion&id='.$item->id.'&uniq_id='.$item->uniq_key.'&questionSelectedId='.$item->qid.'&pageId='.$item->page_number)?>" 
												class="tooltip-hover" title="<?php echo JText::_('LBL_EDIT_QUESTIONS');?>">
												<i class="fa fa-list"></i>
											</a>
											<?php endif;?>
											
											<?php //if(($user->id == $item->created_by) || $user->authorise('quiz.manage', Q_APP_NAME)): ?>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_reports&id='.$item->id)?>" 
												class="tooltip-hover" title="<?php echo JText::_('LBL_REPORTS');?>"><i class="fa fa-briefcase"></i></a>
											<?php //endif; ?>
										</div>
									</div>
									
									<?php if($this->params->get('display_meta_info', '1') == '1'):?>
									<div class="muted margin-bottom-5">
										<small>
										<?php 
										$category_name = JHtml::link(
											JRoute::_($this->page_url.'&id='.$item->catid.':'.$item->category_alias.$itemid),
											$this->escape($item->category_title));
										$user_name = $item->created_by > 0 
											? CJFunctions::get_user_profile_link($this->params->get('user_avatar'), $item->created_by, $this->escape($item->username))
											: $this->escape($item->username);
										$formatted_date = CJFunctions::get_formatted_date($item->created);
										
										echo JText::sprintf('TXT_LIST_ITEM_META', $user_name, $category_name, $formatted_date);
										?>
										</small>
									</div>
									<?php endif;?>
									
									<div class="tags">
										<?php foreach($item->tags as $tag):?>
										<a title="<?php echo JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text));?>" class="tooltip-hover" 
											href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=tag&id='.$tag->tag_id.':'.$tag->alias.$itemid);?>">
											<span class="label"><?php echo $this->escape($tag->tag_text);?></span>
										</a>
										<?php endforeach;?>
									</div>
								</div>
							</div>
                            <?php echo '<div id="loadGiftcode'.$item->id.'" class="modal hide fade" style="height:500px; width:500px;padding:10px;">';?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Quiz Giftcode');?></h3>
	</div>
	<table class="table table-hover table-striped" width="100%" border="0"
														id="questionTable">
													<tbody>
														<?php  
														//foreach ($this->item->question_id as $questionId) { ?>
														<tr>
															<td width="1%" align="center"><input type="hidden"
																name="questionId[]" id="questionId"
																value="<?php echo $item->question_id; ?>" /> <?php echo JHtml::_('grid.id',$i,$item->question_id); ?>
															</td>
															<td align="center">
															<div style="border: 1px solid #ccc; padding: 10px; height: 200px;">
															<div
																style="border: 1px solid #ccc; padding: 2px; float: left; text-align: left; width: 400px; margin-top: 2px;">
															<div style="width: 200px; float: left;"><b><span><?php echo JText::_('Quiz	 :	');?><span>
																		<a href="#">Question <?php echo $item->question_id; ?></a></span></span></div>
															
															</div>
															<div style="float: left;">
															<table class="table table-striped" style="border: 1px solid #ccc;">
																<thead>
																	<tr>
																		<th width="40%"><u><?php echo JText::_('Answer')?></u></th>
																		<th><u><?php echo JText::_('Giftcode')?></u></th>
																		<th><u><?php echo JText::_('Giftcode Quantity')?></u></th>
																		<th><u><?php echo JText::_('Cost Per Response')?></u></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td><span style="color: blue;">Correct</span></td>
																		<td><input type="hidden" name="categoryQuizComplete[]"
																			id="categoryQuizComplete"
																			value="<?php echo $item->complete_giftcode; ?>" /> 
																			<a href="#" >
																			<span> 
																			<?php echo $item->complete_giftcode; ?>
																			</span>
																			</a> 
																		</td>
																		<td><input type="text" name="giftcodeQuantityComplete[]"
																			style="width: 80px;"
																			value="<?php echo $item->complete_giftcode_quantity; ?>" readonly="readonly" /></td>
																		<td><input type="text" name="costPerResponseComplete[]"
																			style="width: 80px;"
																			value="<?php echo $item->complete_giftcode_cost_response; ?>" readonly="readonly" /></td>
																	</tr>
																	<tr>
																		<td><span style="color: red;">Incorrect</span></td>
																		<td><input type="hidden" name="categoryQuizIncomplete[]"
																			id="categoryQuizIncomplete"
																			value="<?php echo $item->incomplete_giftcode; ?>" /> <a
																			href="#"> <?php echo $item->incomplete_giftcode; ?></a></td>
																		<td><input type="text" name="giftcodeQuantityIncomplete[]"
																			style="width: 80px;"
																			value="<?php echo $item->incomplete_giftcode_quantity; ?>" readonly="readonly" /></td>
																		<td><input type="text" name="costPerResponseIncomplete[]"
																			style="width: 80px;"
																			value="<?php echo $item->incomplete_giftcode_cost_response; ?>" readonly="readonly"/></td>
																	</tr>
																</tbody>

															</table>
															</div>
															</div>
															</td>
														</tr>
														<?php //} ?>
													</tbody>
												</table>
</div>

							<?php endforeach;?>
							
							<?php echo CJFunctions::load_module_position('quizzes-list-above-pagination');?>
							
							<div class="row-fluid">
								<div class="span12">
									<?php 
									echo CJFunctions::get_pagination(
											$this->page_url.$catparam, 
											$this->pagination->get('pages.start'), 
											$this->pagination->get('pages.current'), 
											$this->pagination->get('pages.total'),
											$this->pagination->get('limit'),
											true
										);
									?>
								</div>
							</div>
					
							<?php else:?>
							<div class="alert alert-info"><i class="icon-info-sign"></i> <?php echo JText::_('MSG_NO_RESULTS')?></div>
							<?php endif;?>
							
							<?php echo CJFunctions::load_module_position('quizzes-list-below-pagination');?>
						</div>
					</div>
					
					<div id="message-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h3 id="myModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	
	
	<div style="display: none;">
		<input type="hidden" id="cjpageid" value="quiz_list">
		<div id="data-rating-noratemsg"><?php echo JText::_('LBL_RATING_NORATE_HINT');?></div>
		<div id="data-rating-cancelhint"><?php echo JText::_('LBL_RATING_CANCEL_HINT');?></div>
		<div id="data-rating-hints"><?php echo JText::_('LBL_RATING_HINTS');?></div>
	</div>
</div>
