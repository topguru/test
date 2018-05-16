<?php
defined('_JEXEC') or die();
$uniq_id = md5(uniqid(rand(), true));
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th><?php echo JHTML::_( 'grid.sort', JText::_( 'Shopping Credit Plan Category' ), 'b.name', $this->lists['order_dir'], $this->lists['order']); ?></th>								
								<th width="20%"><?php echo JText::_( 'Shopping Credit Plan' ); ?></th>
								<th width="15%" class="hidden-phone"><?php echo JHTML::_( 'grid.sort', JText::_( 'Created Date Time' ), 'a.date_created', $this->lists['order_dir'], $this->lists['order']); ?></th>
								<th width="15%"><?php echo JText::_( 'Notes' ); ?></th>								
								<th width="4%"><?php echo JHTML::_( 'grid.sort', JText::_( 'Publish' ), 'a.published', $this->lists['order_dir'], $this->lists['order']); ?></th>								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->shoppings as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><?php echo $this->escape($row->name); ?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.create_update_plan&package_id='.JRequest::getVar('package_id').'&id='.$row->id . '&uniq_id=' . $uniq_id. '&plan_id=0')?>"><?php echo ($row->sc_plan == '0' ? 'New' : 'Edit');  ?></a></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.create_update_notes&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo ($row->note == '0' ? 'New' : 'Edit');  ?></a></td>
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