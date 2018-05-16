<?php 
/**
 * @version		$Id: default.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.views
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access'); 

$app = JFactory::getApplication();
$params = JComponentHelper::getParams(Q_APP_NAME);

$approval_link = 'index.php?option='.Q_APP_NAME.'&view=approval&task=publish&cid[]=';
$disapproval_link = 'index.php?option='.Q_APP_NAME.'&view=approval&task=unpublish&cid[]=';
?>
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span2">
				<div class="sidebar-nav">
					<ul class="nav nav-tabs nav-stacked">
						<li class="active">
							<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=dashboardz&package_id='.JRequest::getVar("package_id"))?>">
								<i class="icon-globe"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_DASHBOARD');?>
							</a>
						</li>
						<li>
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
			<div class="span7">
				<table class="table table-striped table-hover table-bordered">
					<caption><h4><?php echo JText::_('LBL_LATEST_QUIZZES');?></h4></caption>
					<thead>
						<tr>
							<th><?php echo JText::_('LBL_TITLE')?></th>
							<th width="20%"><?php echo JText::_('LBL_USERNAME')?></th>
							<th width="20%"><?php echo JText::_('LBL_CATEGORY');?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($this->latest_quizzes)):?>
						<?php foreach ($this->latest_quizzes as $item):?>
						<tr>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&package_id='.JRequest::getVar("package_id").'&id='.$item->id);?>">
									<?php echo CJFunctions::escape($item->title);?>
								</a>
							</td>
							<td><div class="tooltip-hover" title="<?php echo $item->username;?>"><?php echo CJFunctions::escape($item->name);?></div></td>
							<td><?php echo CJFunctions::escape($item->category);?></td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			
				<table class="table table-striped table-hover table-bordered">
					<caption><h4><?php echo JText::_('LBL_PENDING_APPROVAL');?></h4></caption>
					<thead>
						<tr>
							<th><?php echo JText::_('LBL_TITLE')?></th>
							<th width="20%"><?php echo JText::_('LBL_USERNAME')?></th>
							<th width="50px"><?php echo JText::_('LBL_APPROVE');?></th>
							<th width="50px"><?php echo JText::_('LBL_DISAPPROVE');?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($this->pending_quizzes)):?>
						<?php foreach ($this->pending_quizzes as $item):?>
						<tr>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.do_edit&package_id='.JRequest::getVar("package_id").'&id='.$item->id);?>">
									<?php echo CJFunctions::escape($item->title);?>
								</a>
							</td>
							<td><div class="tooltip-hover" title="<?php echo $item->username;?>"><?php echo CJFunctions::escape($item->name);?></div></td>
							<td style="text-align: center">
								<a class="btn btn-mini btn-success" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=dashboardz&task=dashboardz.publish_list&package_id='.JRequest::getVar('package_id').'&cid[]='.$item->id);?>">
									<i class="icon-ok icon-white"></i>
								</a>
							</td>
							<td style="text-align: center">
								<a class="btn btn-mini btn-danger" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=dashboardz&task=dashboardz.unpublish_list&package_id='.JRequest::getVar('package_id').'&cid[]='.$item->id);?>">
									<i class="icon-remove icon-white"></i>
								</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			<div class="span3">
				<table class="table table-hover table-striped table-bordered">
					<caption><h4><?php echo JText::_('COM_COMMUNITYQUIZ_VERSION_INFO');?></h4></caption>
					<tbody>
						<tr>
							<td><?php echo JText::_('COM_COMMUNITYQUIZ_INSTALLED_VERSION');?>:</td>
							<td><?php echo CQ_CURR_VERSION;?></td>
						<tr>
						<?php if(!empty($this->version)):?>
						<tr>
							<td><?php echo JText::_('COM_COMMUNITYQUIZ_LATEST_VERSION');?>:</td>
							<td><?php echo $this->version['version'];?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_COMMUNITYQUIZ_VERSION_RELEASED');?>:</td>
							<td><?php echo $this->version['released'];?></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
								<?php if($this->version['status'] != 0):?>
								<a href="http://www.corejoomla.com/downloads.html" target="_blank" class="btn btn-danger">
									<i class="icon-download icon-white"></i> <span style="color: white"><?php echo JText::_('COM_COMMUNITYQUIZ_VERSION_OUTDATED');?></span>
								</a>
								<?php else:?>
								<a href="#" class="btn btn-success">
									<i class="icon-ok icon-white"></i> <span style="color: white"><?php echo JText::_('COM_COMMUNITYQUIZ_VERSION_OK');?></span>
								</a>
								<?php endif;?>
							</td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
				
				<div class="well">
					<strong>If you use Community Quiz, please post a rating and a review at the Joomla Extension Directory</strong>
					<div style="text-align: center; margin-top: 10px;">
						<a class="btn btn-primary" href="http://extensions.joomla.org/extensions/living/education-a-culture/quiz/21729" target="_blank">
							<i class="icon-share icon-white"></i> <span style="color: white">Post Your Review</span>
						</a>
					</div>
				</div>
				
				<div class="well">
					<div><strong>Credits: </strong></div>
					<div>Community Quiz is a free software released under Gnu/GPL license. Copyright© 2009-12 corejoomla.com</div>
					<div>Core Components: Bootstrap, jQuery and ofcourse Joomla®.</div>
					<div>Few icons are taken from icomoon (MIT).</div>
				</div>
			</div>
		</div>
	</div>
</div>