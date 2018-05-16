<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access'); ?>
<div id="j-main-container" class="span10">
<div id="editcell">
<form action="index.php" method="post" id name="adminForm">
<table class="table table-striped">
	<tr>
		<td colspan="3" style="width: 200px">Prize Image</td>
	</tr>
	<tr>
		<td colspan="3" style="width: 200px"><img
			src="./components/com_awardpackage/asset/prize/prize002.jpg"
			id="image" name="image" width="200px" height="300px" /></td>
	</tr>
	<tr>
		<td colspan="3" style="width: 200px"><input type="file"
			id="prize_image" name="prize_image" value="Browse" /> <input
			id="upload" type="button" value="Upload" /></td>
	</tr>
	<tr>
		<td style="width: 50px">Prize Name</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" /></td>
	</tr>
	<tr>
		<td style="width: 50px">Prize Value</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" /></td>
	</tr>
	<tr>
		<td style="width: 50px">Create By</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" value="admin" /></td>
	</tr>
	<tr>
		<td style="width: 50px">Created Date</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text"
			value="<?echo date("d-m-Y")?>" /></td>
	</tr>
	<tr>
		<td style="width: 50px">Description</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><textarea></textarea></td>
	</tr>
</table>
<br>
<br>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="prize" /></form>
<br />
</div>
<div id="load"></div>
<br>
<br>
</div>

<script type="text/javascript">
$.noConflict();

jQuery(document).ready(function() {
	jQuery("#load").hide();
	var obj = new Object();
	obj.prize_image = jQuery("#prize_image").val();
	jQuery("#upload").click(function(){
		alert(jQuery("#prize_image").val());
		jQuery("#load").load('index.php?option=com_awardpackage&controller=prize&task=upload',obj,function (){
			var res = jQuery("#load").text();	
			alert(res)
		});
	});
});


</script>
