<?php 
/**
 * @version		$Id: default_responses.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 6;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

?>
<script type="text/javascript">
<!--
if(!Joomla) var Joomla = {};
Joomla.tableOrdering = function(order, order_dir, temp){
	document.adminForm.filter_order.value = order;
	document.adminForm.filter_order_Dir.value = order_dir;
	document.adminForm.submit();
};
Joomla.checkAll = function(global){
	jQuery('#adminForm').find('table').find('input[type="checkbox"]').attr('checked', global.checked);
};
//-->
</script>
<div id="cj-wrapper">
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>
				<h2 class="page-header margin-bottom-10"><?php echo JText::_('LBL_RESPONSES').': '.$this->escape($this->item->title);?></h2>

				<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_responses_list&id='.$this->item->id)?>" method="post">
				
					<div class="form-inline margin-bottom-20 well">
						<div class="pull-right">
							<select name="limit" size="1" onchange="document.adminForm.submit();" class="input-mini margin-bottom-10">
								<option value="5"<?php echo $this->lists['limit'] == 5 ? ' selected="selected"' : '';?>>5</option>
								<option value="10"<?php echo $this->lists['limit'] == 10 ? ' selected="selected"' : '';?>>10</option>
								<option value="20"<?php echo $this->lists['limit'] == 20 ? ' selected="selected"' : '';?>>20</option>
								<option value="30"<?php echo $this->lists['limit'] == 30 ? ' selected="selected"' : '';?>>30</option>
								<option value="50"<?php echo $this->lists['limit'] == 50 ? ' selected="selected"' : '';?>>50</option>
								<option value="100"<?php echo $this->lists['limit'] == 100 ? ' selected="selected"' : '';?>>100</option>
							</select>
							<select name="state" size="1" onchange="document.adminForm.submit();" class="input-small margin-bottom-10">
								<option value="3"<?php echo $this->lists['state'] == 3 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_FILTER');?></option>
								<option value="1"<?php echo $this->lists['state'] == 1 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_COMPLETED');?></option>
								<option value="0"<?php echo $this->lists['state'] == 0 ? ' selected="selected"' : '';?>><?php echo JText::_('LBL_PENDING');?></option>
							</select>
							<input
								onchange="this.adminForm.submit();" 
								type="text" name="search" value="<?php echo $this->lists['search'];?>" 
								placeholder="<?php echo JText::_('LBL_SEARCH');?>" class="input-medium margin-bottom-10">
						</div>
						
						<div class="btn-toolbar no-margin-top">
							<div class="btn-group">
								<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_survey_reports&id='.$this->item->id)?>">
									<i class="fa fa-reply"></i> <?php echo JText::_('LBL_REPORTS');?>
								</a>
							</div>
							
							<div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('LBL_ACTIONS');?> <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li class="nav-header"><?php echo JText::_('LBL_DOWNLOAD');?></li>
									<li>
										<a class="btn-pdf-download" href="#" onclick="return false;"><i class="fa fa-download"></i> PDF</a>
									</li>
									<li>
										<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.download_csv_report&id='.$this->item->id)?>">
											<i class="fa fa-download"></i> CSV
										</a>
									</li>
									<li class="divider"></li>
									<li>
										<a class="btn-delete-responses" href="#"><i class="fa fa-minus-circle"></i> <?php echo JText::_('LBL_DELETE_SELECTED');?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="alert alert-error alert-no-selection hide"><i class="fa fa-warning"></i> <?php echo JText::_('MSG_SELECT_ROWS_TO_CONTINUE');?></div>
					
					<table class="table table-striped table-condensed table-hover">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_USERNAME' ), 'username', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_COUNTRY' ), 'cr.country_name', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_IP_ADDRESS' ), 'a.ip_address', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_DATE' ), 'r.created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_COMPLETED' ), 'r.completed', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="5%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_STATUS' ), 'r.completed', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%" nowrap="nowrap"><?php echo JText::_('LBL_REPORT');?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->responses as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->response_id );?></td>
								<td>
									<div class="clearfix">
										<?php if($this->params->get('user_avatar') != 'none'):?>
										<div class="pull-left margin-right-10 avatar hidden-phone">
										<?php echo CJFunctions::get_user_avatar(
											$this->params->get('user_avatar'), 
											$row->created_by, 
											$this->params->get('user_display_name'), 
											24,
											$row->email,
											array('class'=>'thumbnail tooltip-hover', 'title'=>$row->username),
											array('class'=>'media-object', 'style'=>'height: 24px'));?>
										</div>
										<?php endif;?>
										<?php 
										echo $row->created_by > 0
											? CJFunctions::get_user_profile_link($this->params->get('user_avatar'), $row->created_by, $this->escape($row->username))
											: $this->escape($row->username);?>
									</div>
								</td>
								<td><?php echo $this->escape($row->country_name);?></td>
								<td><a target="_blank" href="http://whois.domaintools.com/<?php echo $row->ip_address?>"><?php echo $this->escape($row->ip_address);?></a></td>
								<td class="hidden-phone"><?php echo $row->responded_on;?></td>
								<td class="hidden-phone"><?php echo $row->completed;?></td>
								<td>
									<div class="center tooltip-hover" title="<?php echo $row->finished ? JText::_('LBL_COMPLETED') : JText::_('LBL_PENDING');?>">
										<i class="<?php echo $row->finished == 1 ? 'fa fa-plus-circle success' : 'fa fa-spinner fa-spin';?>"></i>
									</div> 
								</td>
								<td>
									<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$row->response_id.$itemid)?>" target="_blank">
										<?php echo JText::_('LBL_VIEW_REPORT');?>
									</a>
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<div class="row-fluid">
						<?php 
						echo CJFunctions::get_pagination(
								'index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid, 
								$this->pagination->get('pages.start'), 
								$this->pagination->get('pages.current'), 
								$this->pagination->get('pages.total'),
								$this->pagination->get('limit'),
								true
							);
						?>
					</div>
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="task" value="responses" />
					<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists['order_dir']; ?>" />
					<input type="hidden" name="cjpageid" id="cjpageid" value="report_responses">
				</form>
			</td>
		</tr>
	</table>
</div>