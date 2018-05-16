<?php
//redirect
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.multiselect');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=currencies');?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th width="10">
				<input type="checkbox" name="checkall-toggle" value=""
				title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
				onclick="Joomla.checkAll(this)" /></th>
			<th><?php echo JHtml::_('grid.sort',  'Currency Code', 'code', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort',  'Currency Name', 'currency', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) :
	$ordering	= ($listOrder == 'ordering');
	?>
		<tr>
			<td align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td align="center"><?php echo $item->code;?></td>
			<td align="center"><?php echo $item->currency;?></td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5" align="center"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
<div><input type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="filter_order"
	value="<?php echo $listOrder; ?>" /> <input type="hidden"
	name="filter_order_Dir" value="<?php echo $listDirn; ?>" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
