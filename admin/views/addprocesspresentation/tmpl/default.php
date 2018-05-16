<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.get_fundprizeplan');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-striped table-hover table-bordered" style="width:70%;">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th width="30%"><?php echo JText::_( 'Title' ); ?></th>								
								<th width="28%" class="hidden-phone"><?php echo JText::_( 'Created' ); ?></th>
								<th><?php echo JText::_( 'Funding Value' ); ?></th>								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->fundprizes as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.create_update&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo $this->escape($row->name); ?></a></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td>
									<?php echo '$'.$row->value_from.' to $'.$row->value_to; ?>
									<input type="hidden" name="id" value="<?php echo $row->id?>">
								</td>								
							</tr>
							<?php endforeach;?>
						</tbody>
						
					</table>
					
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>