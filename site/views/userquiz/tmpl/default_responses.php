<?php
/**
 * @version		$Id: default_responses.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 8;
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
										<th><?php echo JText::_('LBL_DATE');?></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($this->items)):?>
									<?php foreach ($this->items as $item):?>
									<tr>
										<td><?php echo $this->pagination->get('limitstart') + ($i++);?></td>
										<td>
											<?php if($user->authorise('quiz.results', Q_APP_NAME) && $item->show_answers == 1):?>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=reports.get_quiz_results&id='.$item->id.':'.$item->alias.'&rid='.$item->response_id.$quizzes_itemid)?>">
                                         		<?php echo $this->escape($item->title);?>
											</a>
											<?php else:?>
<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_results&id='.$item->id.'&rid='.$item->response_id.$quizzes_itemid)?>">
												<?php echo $this->escape($item->title);?>
											</a>											<?php endif;?>
										</td>
										<td nowrap="nowrap"><?php echo $item->responded_on;?></td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="5">
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