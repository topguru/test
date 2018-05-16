<?php
defined('_JEXEC') or die('Restricted access');

$items		= $this->getUserPieces(JRequest::getInt('user_id'));
?>
<div id="j-main-container" class="span10">
<form
	action="index.php?option=com_awardpackage&view=userrecord&package_id=<?php echo $this->package_id;?>&presentation_id=<?php echo $this->presentation_id;?>&process_id=<?php echo JRequest::getInt('process_id');?>"
	method="post" name="adminForm" id="adminForm">
<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th width="10"><?php echo JText::_('No');?></th>
			<th><?php echo JText::_('Symbol Pieces');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$j=0;
	foreach($items as $i=>$item){
		$j++;
		?>
		<tr class="row<?php echo $j%2;?>">
			<td align="center"><?php echo $j;?></td>
			<td align="center"><img
				src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $item->symbol_pieces_image;?>"
				width="100"></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<div><input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="process_id" value="<?php echo $this->process_id;?>"> <input
	type="hidden" name="presentation_id"
	value="<?php echo $this->presentation_id;?>"> <input type="hidden"
	name="package_id" value="<?php echo $this->package_id;?>"> <input
	type="hidden" name="task" value="" /></div>
</form>
</div>
