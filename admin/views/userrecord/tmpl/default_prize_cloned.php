<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
?>
<div id="j-main-container" class="span10">
<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>Prize Value</th>
			<th>Prize</th>
			<th>Symbol Pieces</th>
			<th>Clones Added</th>
			<th>Total Symbol Pieces</th>
		</tr>
	</thead>
	<tbody>	
		<?php
			$i = 0; 
			foreach ($this->items as $item) { 
		?>
			<tr>
			<td>$<?php echo number_format($item->prizeValue,2);?></td>
			<td><img
					src="./components/com_awardpackage/asset/prize/<?php echo $item->prizeImage; ?>"
					width="150px" /></td>
			<td><img
					src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $item->symbolPiece; ?>"
					width="150px" /></td>
			<td><a href="#"><?php echo $item->clonesAdd;?></a></td>
			<td><a href="#"><?php echo $item->totalSymbolPieces;?></a></td>
			</tr>
		<?php
			} 
		?>
	</tbody>	
</table>
</div>