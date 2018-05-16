<?php
/**
 * @version		$Id: default.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 7;
$i = 1;
?>
<div id="cj-wrapper">	
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
				<div class="container-fluid no-space-left no-space-right quiz-wrapper">
					<div class="row-fluid">
						<div class="span12">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo JText::_('LBL_QUIZ');?></th>
										<th><?php echo JText::_('LBL_RESPONSES');?></th>
										<th><?php echo JText::_('LBL_DATE');?></th>
										<th><?php echo JText::_('LBL_EDIT');?></th>
										<th><?php echo JText::_('LBL_EDIT_QUESTIONS');?></th>
										<th><?php echo JText::_('LBL_STATUS');?></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($this->items)):?>
									<?php foreach ($this->items as $item):?>
									<tr>
										<td><?php echo $this->pagination->get('limitstart') + ($i++);?></td>
										<td>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_responses&id='.$item->id.':'.$item->alias.$quizzes_itemid)?>">
												<?php echo $this->escape($item->title);?>
											</a>
										</td>
										<td><?php echo $item->responses;?></td>
										<td nowrap="nowrap"><?php echo CJFunctions::get_localized_date($item->created);?></td>
										<td>
											<?php if($user->authorise('core.edit.own', Q_APP_NAME)):?>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&package_id='.$item->package_id.'&id='.$item->id.$quizzes_itemid)?>"><?php echo JText::_('LBL_EDIT_QUESTIONS');?></a>
											<?php endif;?>
										</td>
										<td align="center" class="center">
											<button class="btn btn-mini <?php echo $item->published == 1 ? 'btn-success' : ($item->published == 2 ? 'btn-warning' : 'btn-danger')?> tooltip-hover" 
												title="<?php echo $item->published == 1 ? JText::_('LBL_PUBLISHED') : ($item->published == 2 ? JText::_('LBL_PENDING') : JText::_('LBL_UNPUBLISHED'));?>">
												<i class="<?php echo $item->published == 1 ? 'icon-ok-circle' : 'icon-ban-circle';?> icon-white"></i>
											</button>
										</td>
										<td align="center" class="center">
											<button class="btn btn-mini <?php echo $item->published == 1 ? 'btn-success' : ($item->published == 2 ? 'btn-warning' : 'btn-danger')?> tooltip-hover" 
												title="<?php echo $item->published == 1 ? JText::_('LBL_PUBLISHED') : ($item->published == 2 ? JText::_('LBL_PENDING') : JText::_('LBL_UNPUBLISHED'));?>">
												<i class="<?php echo $item->published == 1 ? 'icon-ok-circle' : 'icon-ban-circle';?> icon-white"></i>
											</button>
										</td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="7">
											<?php 
											echo CJFunctions::get_pagination(
													$this->page_url, 
													$this->pagination->get('pages.start'), 
													$this->pagination->get('pages.current'), 
													$this->pagination->get('pages.total'),
													JFactory::getApplication()->getCfg('list_limit', 20),
													true
												);
											?>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>	
</div>