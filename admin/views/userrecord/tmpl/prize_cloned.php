<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div id="j-main-container" class="span10">
<form
	action="index.php?option=com_awardpackage&view=processsymbol&package_id=<?php echo $this->package_id;?>&presentation_id=<?php echo $this->presentation_id;?>"
	method="post" name="adminForm" id="adminForm">
<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>Prize Value</th>
			<th>Prize</th>
			<th>Clones Added</th>
			<th>Total Symbol Pieces</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->prizes as $prize){?>
		<tr>
			<td align="center">$<?php echo number_format($prize->prize_value,2);?></td>
			<td align="center"><?php echo $prize->prize_name;?></td>
			<td align="center"><a href="#"><?php echo count($this->getClone($prize->clone_id));?></a></td>
			<td align="center"><a href="#"><?php echo count($this->getPieces($prize->symbol_id));?></a></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<div><input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="symbolqueue" /></div>
</form>
</div>
