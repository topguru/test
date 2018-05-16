<?php
/**
 * @version		$Id: default_responses.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
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
	<div class="container-fluid survey-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td width="10%" valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>	
					</td>
					<td valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>	
						<div class="span12">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo JText::_('LBL_TITLE');?></th>
										<th width="15%"><?php echo JText::_('LBL_DATE');?></th>
										<th width="15%" nowrap="nowrap"></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($this->items)):?>
									<?php foreach ($this->items as $item):?>
									<tr>
										<td><?php echo $this->pagination->get('limitstart') + ($i++);?></td>
										<td>
											<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reportszs&task=reportszs.get_survey_reports&id='.$item->id.':'.$item->alias.'&rid='.$item->response_id.$surveys_itemid)?>">
											<?php echo $this->escape($item->title);?></a>
										</td>
										<td nowrap="nowrap"><?php echo $item->responded_on;?></td>
										<td>
											<?php if($user->authorise('core.results', S_APP_NAME) && $item->public_permissions == 1):?>
											<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=response&task=view_result&id='.$item->id.':'.$item->alias.$surveys_itemid)?>">
												<?php echo JText::_('LBL_VIEW_REPORT');?>
											</a>
											<?php endif;?>
										</td>
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
					</td>
				</tr>
			</table>			
		</div>
	</div>
</div>