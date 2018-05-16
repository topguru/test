<?php
//print_r("WTF");exit();
defined('_JEXEC') or die('Restricted access');
?>
<div id="editcell"><br>
<div class="judul">Extract Symbol</div>
<form action="index.php" method="post" name="adminForm"
	onsubmit="return false">
<table class="table table-striped">
	<thead>
		<tr style="text-align: center; background-color: #CCCCCC">
			<th>Prize</th>
			<th>Prize Value</th>
			<th>Complete Award Symbol</th>
			<th>Extract No.</th>
			<th>Pieces Extracted</th>
		</tr>
	</thead>
</table>
<br />

</div>
<br />
<br />
<input
	type="hidden" name="option" value="com_awardpackage" />
<input type="hidden"
	name="task" value="" />
<input
	type="hidden" name="controller" value="presentation" />
</form>
<script type="text/javascript">
//$.noConflict();
</script>
