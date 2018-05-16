<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationcategory&task=apresentationcategory.getListCategory');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th ><?php echo JHTML::_( 'grid.sort', JText::_( 'Presentation Category' ), 'pac.category_id', $this->lists['order_dir'], $this->lists['order']); ?></th>								
								<th ><?php echo JText::_( 'Presentation' ); ?></th>
								<th ><?php echo JHTML::_( 'grid.sort', JText::_( 'Modified' ), 'ppc.date_modify', $this->lists['order_dir'], $this->lists['order']); ?></th>																								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->categories as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->category_id );?></td>
								<td class="hidden-phone">	
									<table>
										<tr>
											<td style="padding-top:14px;width:40px;height:
													30px;text-align:center;background-color:<?php echo $row->colour_code;?>" valign="center">
											<font color="white" size="5"><b><?php echo $row->category_id; ?></b></font>
											</td>
										</tr>
									</table>
								</td>
								<td class="hidden-phone"><?php echo JText::_($row->presentations) ?></td>
								<td class="hidden-phone">
									<?php echo $row->date_modify; ?>
								</td>																
							</tr>
							<?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
					</table>
					
					<input type="hidden" name="task" value="apresentationcategory.getListCategory" />
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>