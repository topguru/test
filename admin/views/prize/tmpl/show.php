<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access'); ?>
<div id="j-main-container" class="span10">
<div id="editcell">
<div class="judul">View Prize</div>
<form action="index.php" method="post" id="adminForm" name="adminForm">
<table class="table table-striped">
	<tr style="text-align:center; background-color:#CCCCCC">
		<th style="width: 200px">Prize Image</th>
		<th style="width: 200px">Prize Detail</th>
	</tr>
	<td><img
		src="./components/com_awardpackage/asset/prize/<?php echo $this->data->prize_image; ?>"
		width="300px" height="300px" />
	<td>
	<table>
		<tr>
			<td>Prize Name</td>
			<td>:</td>
			<td><?php echo $this->data->prize_name; ?></td>
		</tr>
		<tr>
			<td>Prize Value</td>
			<td>:</td>
			<td><?php echo "$ ".$this->data->prize_value; ?></td>
		</tr>
		<tr>
			<td>Date Created</td>
			<td>:</td>
			<td><?php echo date("d-M-Y",strtotime($this->data->date_created)); ?></td>
		</tr>
		<tr>
			<td>Created By</td>
			<td>:</td>
			<td><?php echo $this->data->created_by; ?></td>
		</tr>
		<tr>
			<td>Description</td>
			<td>:</td>
			<td><?php echo $this->data->desc; ?></td>
		</tr>
	</table>
	</td>

</table>
<br>
<br>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="prize" /></form>
<br />
</div>
</div>

<div id="load"></div>
<br>
<br>
