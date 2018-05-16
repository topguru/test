<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<?php
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
?>
<div id="j-main-container" class="span10">
<form action="<?php echo JRoute::_('index.php?com_awardpackage&view=funding'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th width="5">
					<?php echo JText::_('ID'); ?>
				</th>
				<!--
				<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>  
				-->
				<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>			
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_FUNDING_SESSION', 'funding_session', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_GIFT_CODE_CREATED', 'funding_created', $listDirn, $listOrder); ?>
				</th>
                <th>
					<?php echo JHtml::_('grid.sort', 'COM_GIFT_CODE_MODIFIED', 'funding_modify', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHtml::_('grid.sort', 'COM_SYMBOL_PUBLISH', 'funding_published', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->items as $i => $item):
			if($item->package_id == JRequest::getVar('package_id') && $item->presentation_id==JRequest::getVar('presentation_id')):
			?>
				<?php
				$item->max_ordering = 0; 
				$canChange	= $user->authorise('core.edit.state','com_awardpackage.funding.'.$item->funding_id);
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php echo $item->funding_id; ?>
					</td>
					<td>
						<?php echo JHtml::_('grid.id', $i, $item->funding_id); ?>
					</td>
					<td>
						<?php
						if($item->funding_session == '-')
						{
							echo '<a href="index.php?option=com_awardpackage&view=fundingadd&funding_id='.$item->funding_id.'&package_id='.JRequest::getVar('package_id').'&presentation_id='.$item->presentation_id.'">New</a>';
						}else
						{
							echo '<a href="index.php?option=com_awardpackage&view=fundingadd&funding_id='.$item->funding_id.'&package_id='.JRequest::getVar('package_id').'&presentation_id='.$item->presentation_id.'">Edit</a>';
						}
						?>
					</td>
					<td>
						<?php echo $item->funding_created; ?>
					</td>
                    <td>
						<?php echo $item->funding_modify; ?>
					</td>
					<td align="center">
						<?php echo JHtml::_('jgrid.published', $item->funding_published, $i, 'funding.', $canChange); ?>
					</td>
				</tr>
			<?php
				endif;
				endforeach;
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	</table>
<input type="hidden" name="presentation_id" value="<?php echo JRequest::getVar('presentation_id');?>">
<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
<input type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="<?php echo $_REQUEST['view']; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn?>" />    
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHtml::_('form.token'); ?>
</form>
</div>
