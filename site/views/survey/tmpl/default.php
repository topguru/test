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
$catparam = '';
$page_id = isset($this->page_id) ? $this->page_id : 1;
/*if(!empty($this->category)){
	$catparam = '&catid='.$this->category->id.':'.$this->category->alias;
}
*/
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
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php'; ?>
			</td>
			<td valign="top">
            <?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>	
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>
				<div class="container-fluid no-space-left no-space-right surveys-wrapper">
					<div class="row-fluid">
						<div class="span12">			
							<?php echo CJFunctions::load_module_position('surveys-list-above-categories');?>				
							<?php if($this->params->get('display_cat_list', 1) == 1 || !empty($this->page_header)):?>
							<div class="well">					
								<?php if(!empty($this->categories) || !empty($this->category)):?>					
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('LBL_CATEGORIES').(!empty($this->category) ? ': <small>'.$this->escape($this->category->title).'</small>' : '');?>
									
									<?php if($this->params->get('enable_rss_feed', 0) == '1'):?>
									<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=feed&format=feed'.$catparam.$itemid);?>" 
										title="<?php echo JText::_('LBL_RSS_FEED')?>" class="tooltip-hover">
										<i class="cjicon-feed"></i>
									</a>
									<?php endif;?>
								</h2>
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
											$url = JRoute::_($this->page_url.'&id='.$row->id.':'.$row->alias.$menu_id);
											echo '<li class="parent-item"><a href="'.$url.'">'.$row->title.' ('.$row->surveys.')</a></li>';
											//echo '<li class="parent-item"><a href="#">'.$row->category.' ('.$row->jml.')</a></li>';
											}
											echo '</ul>';										
								} 
								?>
                                
								
								<?php if($this->params->get('dispay_search_box', 1) == 1):?>
								<div class="row-fluid margin-top-10">
									<div class="span12">
										<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=survey&task=survey.search_surveys'.$itemid);?>" style="text-align: center;" class="no-margin-bottom" method="post">
											<div class="input-append center">
												<input type="text" class="required" name="q" placeholder="<?php echo JText::_('LBL_SEARCH');?>">
												<button type="submit" class="btn"><?php echo JText::_('LBL_SEARCH');?></button>
											</div>
											<?php if(!empty($this->category)):?>
											<input type="hidden" name="catid" value="<?php echo $this->category->id;?>">
											<?php endif;?>
										</form>
									</div>
								</div>
								<?php endif;?>
								
							</div>
							<?php endif;?>
							
							<?php echo CJFunctions::load_module_position('surveys-list-below-categories');?>
							
							<?php if(!empty($this->items)):?>
							<?php foreach ($this->items as $item):
                            $giftcode = $this->survey_model->get_total_giftcode($item->id);
							$totgiftcode = 0;
							foreach ($giftcode as $rows){
							$total = $rows->complete_giftcode_quantity + $rows->incomplete_giftcode_quantity;
							$totgiftcode = $totgiftcode + $total;
							}
							
							?>
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
								
								<?php if($this->params->get('display_response_count', 1) == 1):?>
								<div class="pull-left hidden-phone thumbnail num-box">
									<h2 class="num-header"><?php echo $item->responses;?></h2>
									<span class="muted"><?php echo $item->responses == 1 ? JText::_('LBL_RESPONSE') : JText::_('LBL_RESPONSES');?></span>
								</div>
								<?php endif;?>
								<div class="pull-left hidden-phone thumbnail num-box">
									<h2 class="num-header">
									<?php echo '<a href="#" onclick="open_modal_window(this, '.$item->id.'); return false;">';
                                     echo $totgiftcode; ?>
                                    </a>
                                    </h2>
									<span class="muted"><?php echo $item->responses == 1 ?  JText::_('Giftcodes') : JText::_('Giftcodes');?></span>
								</div>
								<div class="media-body">
			
									<h4 class="media-heading">
										<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.survey_intro&id='.$item->id.'&skey='.$item->survey_key);?>"<?php echo $item->skip_intro == 1? ' rel="nofollow"' : ''?>>
											<?php echo $this->escape($item->title)?>
										</a>
										<?php if($page_id == 10): // all surveys?>
										<i 
											class="<?php echo $item->private_survey == 1 ? 'icon-eye-close' : 'icon-eye-open'?> tooltip-hover" 
											title="<?php echo $item->private_survey == 1 ? JText::_('LBL_PRIVATE_SURVEY') : JText::_('LBL_PUBLIC_SURVEY');?>"></i>
										<?php endif;?>
									</h4>
									
									<?php if($this->params->get('display_meta_info', 1) == 1):?>
									<div class="muted">
										<small>
										<?php
										
										$category_name = JHtml::link(
											JRoute::_($this->page_url.'&id='.$item->catid),
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
									
									<div class="muted admin-controls">
										<small>
											<?php 
												if(($user->id == $item->created_by && $user->authorise('core.edit.own', S_APP_NAME)) || $user->authorise('survey.manage', S_APP_NAME)):
?>
											<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=formzs&task=formzs.create_edit_survey&id='.$item->id.'&uniq_id='.$item->survey_key)?>"><?php echo JText::_('LBL_EDIT');?></a>
											<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=formzs&task=formzs.getQuestion&id='.$item->id.'&uniq_id='.$item->survey_key)?>">
                                            <?php echo JText::_('LBL_EDIT_QUESTIONS');?></a> 
<?php /**                                            
                                             echo JRoute::_('index.php?option='.S_APP_NAME.'&view=formzs&task=formzs.edit_questions&id='.$item->id.'&uniq_id='.$item->survey_key)?>"><?php echo JText::_('LBL_EDIT_QUESTIONS');?></a> **/
												endif;
											?>
											<?php 
												//if(($user->id == $item->created_by) || $user->authorise('survey.manage', S_APP_NAME)):
											?>
											<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=invite.get_invite_page&id='.$item->id.'&uniq_id='.$item->survey_key)?>"><?php echo JText::_('Invite');?></a>
											<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_survey_reports&id='.$item->id.'&uniq_id='.$item->survey_key)?>"><?php echo JText::_('LBL_REPORTS');?></a>
											<?php 
												//endif;
											?>
										</small>
									</div>
								</div>
							</div>
                            
                             <?php echo '<div id="loadGiftcode'.$item->id.'" class="modal hide fade" style="height:500px; width:500px;padding:10px;">';?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Survey Giftcode');?></h3>
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
							
							<?php echo CJFunctions::load_module_position('surveys-list-above-pagination');?>
							
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
							
							<?php echo CJFunctions::load_module_position('surveys-list-below-pagination');?>
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
		<input type="hidden" id="cjpageid" value="survey_list">
		<div id="data-rating-noratemsg"><?php echo JText::_('LBL_RATING_NORATE_HINT');?></div>
		<div id="data-rating-cancelhint"><?php echo JText::_('LBL_RATING_CANCEL_HINT');?></div>
		<div id="data-rating-hints"><?php echo JText::_('LBL_RATING_HINTS');?></div>
	</div>
   
</div>