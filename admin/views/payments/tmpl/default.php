<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th width="30%"><?php echo JHTML::_( 'grid.sort', JText::_( 'Payment Name' ), 'option', $this->lists['order_dir'], $this->lists['order']); ?></th>								
								<th class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'Created Date Time' ), 'date_created', $this->lists['order_dir'], $this->lists['order']); ?></th>																
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->payments as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=payments&task=payments.create_update&id='.$row->id)?>"><?php echo $this->escape($row->option); ?></a></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>																
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
					
					<input type="hidden" name="task" value="payments.get_payment_list" />
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>