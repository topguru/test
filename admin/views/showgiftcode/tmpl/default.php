<?php
defined('_JEXEC') or die('Restricted access');

$categoryData =& $this->categoryData;

$color = array();

$color[] = "Merah";

foreach ($categoryData as $row) {
	$color[$row->id] = $row->name;
}

?>
<style>
td {
	text-align: center;
}
</style>
<div id="j-main-container" class="span10">
<form action="index.php" method="post" name="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>#</th>
			<th>Giftcode Category</th>
			<th>Created</th>
			<th>Gift Code</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->data ); $i < $n; $i++)
	{
		$row =& $this->data[$i];
		?>
	<tr class="row<?echo $k ?>">
		<td><?php echo $i+1; ?></td>
		<td><?php echo $color[$row->giftcode_category_id]; ?></td>
		<td><?php echo date("d-M-Y",strtotime($row->created_date_time)); ?></td>
		<td><?php echo $row->giftcode; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
<input type="hidden" name="option" value="com_giftcode" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="create" /> <input type="hidden" name="controller"
	value="giftcode" /></form>
</div>
