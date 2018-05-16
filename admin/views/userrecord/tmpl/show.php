<?php
//print_r("WTF");exit();
defined('_JEXEC') or die('Restricted access');
$checked    = '<input type="radio" id="cb" name="cid[]" value="1" onclick="isChecked(this.checked);" />';
$view = JRoute::_( 'index.php?option=com_awardpackage&view=prize&act=view');
?>
<div id="j-main-container" class="span10">
<div class="judul">View User Record</div>
<form action="index.php" method="post" name="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th width="5">No.</th>
			<th>Symbol Pieces</th>
			<th>Symbol Set</th>

		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->data ); $i < $n; $i++)
	{

		$row =& $this->data[$i];
		?>
	<tr class="row<?php echo $k ?>">
		<td><?php echo $i + 1; ?></td>
		<td><img
			src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $row->symbol_pieces_image; ?>"
			width="50px" height="50px" /></td>
		<td><img
			src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
			width="75px" height="75px" /></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
<br />

</div>
<br />
<br />
<input
	type="hidden" name="option" value="com_awardpackage" />
<input type="hidden"
	name="boxchecked" value="0" />
<input type="hidden"
	name="task" value="" />
<input
	type="hidden" name="controller" value="symbolqueue" />
</form>
</div>
