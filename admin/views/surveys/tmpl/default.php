<?php
/**
 * @version		$Id: default.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
$categories = JHtml::_('category.options', S_APP_NAME);
?>
<div id="cj-wrapper">
	<div class="container-fluid survey-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span2">
				<div class="sidebar-nav">
					<ul class="nav nav-tabs nav-stacked">
						<li>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=dashboard&package_id='.JRequest::getVar('package_id')) ?>">
								<i class="icon-globe"></i> <?php echo JText::_('COM_COMMUNITYSURVEYS_DASHBOARD');?>
							</a>
						</li>
						<li class="active">
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&package_id='.JRequest::getVar('package_id')) ?>">
								<i class="icon-list"></i> <?php echo JText::_('COM_COMMUNITYSURVEYS_SURVEYS');?>
							</a>
						</li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=scategories&task=scategories.get_categories&package_id='.JRequest::getVar('package_id')) ?>">
								<i class="icon-folder-open"></i> <?php echo JText::_('COM_COMMUNITYSURVEYS_CATEGORIES');?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="span10">			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&package_id='.$pi);?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>"/>
					<div class="well well-small clearfix">
						<div class="pull-right">
							<select name="filter_state" size="1" onchange="this.form.submit();">
								<option value="3"<?php echo $this->lists['state'] == 3 ? ' selected="selected"' : '';?>><?php echo JText::_('JALL');?></option>
								<option value="1"<?php echo $this->lists['state'] == 1 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITYSURVEYS_PUBLISHED');?></option>
								<option value="0"<?php echo $this->lists['state'] == 0 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITYSURVEYS_UNPUBLISHED');?></option>
								<option value="2"<?php echo $this->lists['state'] == 2 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_COMMUNITYSURVEYS_PENDING');?></option>
							</select>
							<?php $catid = $this->state->get('filter.category_id');?>
							<select name="filter_category_id" size="1" onchange="this.form.submit();">
								<option value="0"><?php echo JText::_('COM_COMMUNITYSURVEYS_FILTER_BY_CATEGORY');?></option>
								<?php foreach ($this->category as $category):?>
								<option value="<?php echo $category->id?>" <?php if($category->id == $catid){echo "selected";}?>>
									<?php echo $this->escape($category->title)?>
								</option>
							<?php endforeach;?>
							</select>
						</div>
						
						<div class="input-append">
							<input class="input-medium" name="search" type="text" value="<?php echo CJFunctions::escape($this->lists['search']);?>" placeholder="<?php echo JText::_('COM_COMMUNITYSURVEYS_SEARCH');?>">
							<div class="btn-group">
								<button class="btn" tabindex="-1" type="submit"><?php echo JText::_('COM_COMMUNITYSURVEYS_SEARCH');?></button>
								<button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
									&nbsp;<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a class="btn-reset" href="#" onclick="return false;"><?php echo JText::_('COM_COMMUNITYSURVEYS_RESET');?></a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_TITLE' ), 'a.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th><?php echo JText::_('COM_COMMUNITYSURVEYS_SURVEY_KEY');?></th>
								<th width="8%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_CATEGORY' ), 'c.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="8%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_USERNAME' ), 'u.username', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="8%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_CREATED_ON' ), 'a.created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="8%">
									<div><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_PUBLISH_UP' ), 'a.publish_up', $this->lists['order_dir'], $this->lists['order']); ?> /</div>
									<div><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_PUBLISH_DOWN' ), 'a.publish_down', $this->lists['order_dir'], $this->lists['order']); ?></div>
								</th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_RESPONSES' ), 'a.responses', $this->lists['order_dir'], $this->lists['order']);?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_MAXIMUM_RESPONSES' ), 'a.max_responses', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_PUBLISHED' ), 'a.published', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_PRIVATE_SURVEY' ), 'a.private_survey', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_ANONYMOUS' ), 'a.anonymous', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_PUBLIC_REPORT_ACCESS' ), 'a.published', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="20"><?php echo JText::_('COM_COMMUNITYSURVEYS_INVITE');?></th>
								<th width="20"><?php echo JText::_('COM_COMMUNITYSURVEYS_REPORTS');?></th>
								<th width="20"><?php echo JText::_('ID');?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->items as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td><a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveysetting&task=surveysetting.do_edit&id='.$row->id.'&package_id='.$row->package_id)?>"><?php echo $this->escape($row->title);?></a></td>
								<td><?php echo $row->survey_key;?></td>
								<td><?php echo $this->escape($row->category); ?></td>
								<td><?php echo $this->escape($row->name).' ('.$this->escape($row->username).')';?></td>
								<td><?php echo JHTML::Date($row->created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td>
									<div><?php echo $row->publish_up; ?></div>
									<div><?php echo $row->publish_down; ?></div>
								</td>
								<td class="center">
									<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_responses_list&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>">
										<strong><?php echo $this->escape($row->responses); ?></strong> <i class="fa fa-share"></i>
									</a>
								</td>
								<td class="center"><?php echo $this->escape($row->max_responses);?></td>
								<td class="center">
									<a 
										class="btn btn-mini <?php echo $row->published == 1 ? 'btn-success' : ($row->published == 2 ? 'btn-warning' : 'btn-danger');?> tooltip-hover btn-change-state" 
										title="<?php echo $row->published == 1 ? JText::_('COM_COMMUNITYSURVEYS_PUBLISHED') : ($row->published == 2 ? JText::_('COM_COMMUNITYSURVEYS_PENDING') : JText::_('COM_COMMUNITYSURVEYS_UNPUBLISHED'));?>"
										href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=');?>"
										onclick="return false;">
										<i class="icon <?php echo $row->published == 1 ? 'fa fa-plus-circle' : 'fa fa-minus-circle'; ?> fa fa-white"></i>
									</a>
									<input type="hidden" class="state-1" value="publish_item">
									<input type="hidden" class="state-0" value="unpublish_item">
									<input type="hidden" class="current_state" value="<?php echo $row->published == 1 ? 1 : 0?>">
								</td>
								<td class="center">
									<a 
										class="btn btn-mini <?php echo $row->private_survey == 1 ? 'btn-success' : 'btn-danger';?> tooltip-hover btn-change-state" 
										title="<?php echo $row->private_survey == 1 ? JText::_('JYES') : JText::_('JNO');?>"
										href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=');?>"
										onclick="return false;">
										<i class="icon <?php echo $row->private_survey == 1 ? 'fa fa-plus-circle' : 'fa fa-minus-circle'; ?> fa fa-white"></i>
									</a>
									<input type="hidden" class="state-1" value="set_private">
									<input type="hidden" class="state-0" value="unset_private">
									<input type="hidden" class="current_state" value="<?php echo $row->private_survey == 1 ? 1 : 0?>">
								</td>
								<td class="center">
									<a 
										class="btn btn-mini <?php echo $row->anonymous == 1 ? 'btn-success' : 'btn-danger';?> tooltip-hover btn-change-state" 
										title="<?php echo $row->anonymous == 1 ? JText::_('JYES') : JText::_('JNO');?>"
										href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=');?>"
										onclick="return false;">
										<i class="icon <?php echo $row->anonymous == 1 ? 'fa fa-plus-circle' : 'fa fa-minus-circle'; ?> fa fa-white"></i>
									</a>
									<input type="hidden" class="state-1" value="set_anonymous">
									<input type="hidden" class="state-0" value="unset_anonymous">
									<input type="hidden" class="current_state" value="<?php echo $row->anonymous == 1 ? 1 : 0?>">
								</td>
								<td class="center">
									<a 
										class="btn btn-mini <?php echo $row->public_permissions == 1 ? 'btn-success' : 'btn-danger';?> tooltip-hover btn-change-state" 
										title="<?php echo $row->public_permissions == 1 ? JText::_('JYES') : JText::_('JNO');?>"
										href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=');?>"
										onclick="return false;">
										<i class="icon <?php echo $row->public_permissions == 1 ? 'fa fa-plus-circle' : 'fa fa-minus-circle'; ?> fa fa-white"></i>
									</a>
									<input type="hidden" class="state-1" value="set_publicperms">
									<input type="hidden" class="state-0" value="unset_publicperms">
									<input type="hidden" class="current_state" value="<?php echo $row->public_permissions == 1 ? 1 : 0?>">
								</td>
								<td>
									<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=invite.get_invite_page&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>">
										<?php echo JText::_('COM_COMMUNITYSURVEYS_INVITE');?>
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_survey_reports&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>">
										<?php echo JText::_('COM_COMMUNITYSURVEYS_REPORTS');?>
									</a>
								</td>
								<td>
									<?php echo $row->id;?>
									<input type="hidden" name="survey_id" value="<?php echo $row->id?>">
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="17"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
					
					<input type="hidden" name="task" value="list" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
					<input type="hidden" name="cjsurvey_page_id" id="cjsurvey_page_id" value="surveys">
				</form>
				
				<div style="display: none;">
					<div id="url-publish-survey"></div>
					<div id="url-unpublish-survey"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=unpublish_item');?></div>
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
		</div>
	</div>
</div>