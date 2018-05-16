<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<input type="hidden" name="accountId" value="<?php echo JRequest::getVar("accountId"); ?>">
				<div class="span12">
										<table class="table table-hover table-striped table-bordered">

						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_TITLE' ), 'a.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th><?php echo JText::_( 'Description' ); ?></th>
								<th width="10%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CATEGORY' ), 'c.title', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="8%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_USERNAME' ), 'u.username', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="12%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CREATED_ON' ), 'a.created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_RESPONSES' ), 'a.responses', $this->lists['order_dir'], $this->lists['order']);?></th>								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->quizzes as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo $this->escape($row->title);?></td>
								<td><?php echo $this->escape($row->description);?></td>
								<td class="hidden-phone"><?php echo $this->escape($row->category); ?></td>
								<td class="hidden-phone"><?php echo $this->escape($row->name).' ('.$this->escape($row->username).')';?></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td class="center"><?php echo $this->escape($row->responses); ?></td>								
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<input type="hidden" name="task" value="userlist.get_quizzes" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
			</form>						
		</div>
	</div>
</div>
