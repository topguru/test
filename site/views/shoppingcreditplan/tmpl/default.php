<?php
defined('_JEXEC') or die();	
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list');?>" method="post" name="adminForm">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'Shopping Credit Plan Category' ), 'b.name', $this->lists['order_dir'], $this->lists['order']); ?></th>								
								<th width="20%"><?php echo JText::_( 'Shopping Credit Plan' ); ?></th>
								<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'Created Date Time' ), 'a.date_created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%"><?php echo JText::_( 'Notes' ); ?></th>							
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->shoppings as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><?php echo $this->escape($row->name); ?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.show_plan&id='.$row->id)?>"><?php echo JText::_( 'Show' );  ?></a></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.show_notes&id='.$row->id)?>"><?php echo JText::_( 'Show' );  ?></a></td>																
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
					<input type="hidden" name="task" value="shoppingcreditplan.get_shopping_credit_plan_list" />
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>
