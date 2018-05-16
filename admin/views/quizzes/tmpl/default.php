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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span2">
				<div class="sidebar-nav">
					<ul class="nav nav-tabs nav-stacked">
						<li>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=dashboardz&package_id='.JRequest::getVar("package_id"))?>">
								<i class="icon-globe"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_DASHBOARD');?>
							</a>
						</li>
						<li  class="active">
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"))?>">
								<i class="icon-list"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_QUIZZES');?>
							</a>
						</li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=approval&task=approval.get_quizzes_list&package_id='.JRequest::getVar("package_id"))?>">
								<i class="icon-time"></i> <?php echo JText::_('Pending Approval');?>
							</a>
						</li>						
						<li>
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.get_categories&package_id='.JRequest::getVar("package_id"))?>">
								<i class="icon-folder-open"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_CATEGORIES');?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="span10">			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
					<div class="well well-small clearfix">
						<div class="pull-right">
							<select name="state" size="1" onchange="this.form.submit();">
								<option value="3"<?php echo $this->lists['state'] == 3 ? ' selected="selected"' : '';?>><?php echo JText::_('JALL');?></option>
								<option value="1"<?php echo $this->lists['state'] == 1 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_PUBLISHED');?></option>
								<option value="0"<?php echo $this->lists['state'] == 0 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_UNPUBLISHED');?></option>
								<option value="2"<?php echo $this->lists['state'] == 2 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_STATUS_PENDING');?></option>
							</select>
							<select name="catid" size="1" onchange="this.form.submit();">
								<option><?php echo JText::_('LBL_FILTER_BY_CATEGORY');?></option>
								<?php if(!empty($this->categories)):?>
								<?php foreach ($this->categories as $category):?>
								<option value="<?php echo $category->id?>" <?php if($category->id == $catid){echo "selected";}?>>
									<?php echo $this->escape($category->title)?>
								</option>
							<?php endforeach;?>
								<?php endif;?>
							</select>
						</div>
						
						<div class="input-append">
							<input class="input-medium" name="search" type="text" value="<?php echo CJFunctions::escape($this->lists['search']);?>" placeholder="<?php echo JText::_('LBL_SEARCH');?>">
							<div class="btn-group">
								<button class="btn" tabindex="-1" type="submit"><?php echo JText::_('LBL_SEARCH');?></button>
								<button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
									&nbsp;<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a class="btn-reset" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='. JRequest::getVar("package_id"));?>" onclick="return true;"><?php echo JText::_('LBL_RESET');?></a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_TITLE' ), 'a.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="5%"><?php echo JText::_('COM_COMMUNITYQUIZ_EDIT');?></th>
								<th width="5%" nowrap="nowrap"><?php echo JText::_('COM_COMMUNITYQUIZ_EDIT_QUESTIONS');?></th>
								<th width="5%" nowrap="nowrap"><?php echo JText::_('COM_COMMUNITYQUIZ_REPORTS');?></th>
								<th width="10%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CATEGORY' ), 'c.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="8%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_USERNAME' ), 'u.username', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="12%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CREATED_ON' ), 'a.created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_RESPONSES' ), 'a.responses', $this->lists['order_dir'], $this->lists['order']);?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_PUBLISHED' ), 'a.published', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="20"><?php echo JText::_('ID');?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->quizzes as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td><?php echo $this->escape($row->title);?></td>
								<td><a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo JText::_('COM_COMMUNITYQUIZ_EDIT');?></a></td>
								<td><a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo JText::_('COM_COMMUNITYQUIZ_EDIT_QUESTIONS');?></a></td>
								<td><a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_reports&id='.$row->id.'&package_id='.JRequest::getVar('package_id'))?>"><?php echo JText::_('COM_COMMUNITYQUIZ_REPORTS');?></a></td>
								<td class="hidden-phone"><?php echo $this->escape($row->category); ?></td>
								<td class="hidden-phone"><?php echo $this->escape($row->name).' ('.$this->escape($row->username).')';?></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td class="center"><?php echo $this->escape($row->responses); ?></td>
								<td class="center">
									<a 
										class="btn btn-mini <?php echo $row->published == 1 ? 'btn-success' : ($row->published == 2 ? 'btn-warning' : 'btn-danger');?> tooltip-hover btn-publish" 
										title="<?php echo $row->published == 1 ? JText::_('LBL_PUBLISHED') : ($row->published == 2 ? JText::_('LBL_STATUS_PENDING') : JText::_('LBL_UNPUBLISHED'));?>"
										href="#"
										onclick="return false;">
										<i class="icon <?php echo $row->published == 1 ? 'icon-ok' : 'icon-remove'; ?> icon-white"></i>
									</a>
									<input type="hidden" name="quiz_id" value="<?php echo $row->id?>">
								</td>
								<td><?php echo $row->id;?></td>
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
					
					<input type="hidden" name="task" value="quizzes.get_quizzes_list" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
					<input type="hidden" name="cjquiz_page_id" id="cjquiz_page_id" value="quizzes">
				</form>
				
				<div style="display: none;">
					<div id="url-publish-quiz"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=publish_item');?></div>
					<div id="url-unpublish-quiz"><?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=unpublish_item');?></div>
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