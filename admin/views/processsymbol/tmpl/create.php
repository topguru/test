<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access'); ?>
<style>
.slidingDiv,.slidingDiv2,.slidingDiv3 {
	padding: 20px;
	margin-bottom: 10px;
	margin-left: 10px;
	border: 1px solid #ccc;
}

.show_hide,.show_hide2,.slidingDiv3 {
	display: none;
}
</style>
<div id="editcell">
<form action="index.php" method="post" name="adminForm">
<div style="float: left;">
<div style="border: 1px solid #CCC; padding: 10px; width: 280px;"><a
	href="javascript:void(0)" class="show_hide">Extract Symbol</a><br />
<br />
<input id="extractsymbol" type="button" name="extractsymbol"
	value="Extract" />
<div style="border: 1px solid #CCC; padding: 10px; margin: 10px 0 0 0;">
Extract <input type="text" size="2" value="1" style="font-size: 15px;" />
to <input type="text" size="2" value="3" style="font-size: 15px;" /> <input
	type="button" name="return" value="Return" /></div>
<div style="border: 1px solid #CCC; padding: 10px; margin: 1px 0 0 0;">
From prize value <input type="text" size="2" value="5" /> to prize value
<input type="text" size="2" value="50" /></div>
</div>

<div
	style="border: 1px solid #CCC; padding: 10px; width: 280px; margin: 10px 0 0 0;">
<a href="javascript:void(0)" class="show_hide2">Clone Symbol</a><br />
<br />
<input id="clonesymbol" type="button" name="clonesymbol" value="Clone" />
<div style="border: 1px solid #CCC; padding: 10px; margin: 10px 0 0 0;">
Add <input type="text" size="2" value="1" style="font-size: 15px;" /> to
<input type="text" size="2" value="3" style="font-size: 15px;" /> clones
</div>
<div style="border: 1px solid #CCC; padding: 10px; margin: 1px 0 0 0;">
From symbol <input type="text" size="2" value="5" /> to symbol <input
	type="text" size="2" value="50" /></div>
</div>

<div
	style="border: 1px solid #CCC; padding: 10px; width: 280px; margin: 10px 0 0 0;">
<a href="javascript:void(0)" class="show_hide2">Shuffle Symbol</a><br />
<br />
<input id="shufflesymbol" type="button" name="shufflesymbol"
	value="Shuffle" />
<div style="border: 1px solid #CCC; padding: 10px; margin: 10px 0 0 0;">
Shuffle <input type="text" size="2" value="1" style="font-size: 15px;" />
to <input type="text" size="2" value="3" style="font-size: 15px;" />
times</div>
<div style="border: 1px solid #CCC; padding: 10px; margin: 1px 0 0 0;">
From prize value <input type="text" size="2" value="5" /> to prize value
<input type="text" size="2" value="50" /></div>
</div>
</div>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="processSymbol" /> <input type="hidden"
	name="view" value="processSymbol" /></form>
<div style="float: left;">
<div class="slidingDiv" style="width: 700px;"><input id="extractsymbol"
	type="button" name="extractsymbol" value="Extract Symbol"
	class="show_hide" /><br />
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>Prize</th>
			<th>Prize Value</th>
			<th>Complete Award Symbol</th>
			<th>Extract No.</th>
			<th>Pieces Extracted</th>
		</tr>
	</thead>
</table>
</div>
<div class="slidingDiv2" style="width: 700px;"><input id="clonesymbol"
	type="button" name="clonesymbol" value="Clone/Shuffel Symbol"
	class="show_hide2" /><br />
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>#</th>
			<th>Prize</th>
			<th>Prize Value</th>
			<th>Symbol Piece</th>
			<th>Inserted Date / Time</th>
		</tr>
	</thead>
</table>
</div>
</div>
</div>

<script
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"
	type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){

        $(".slidingDiv").hide();
        $(".show_hide").show();

	$('.show_hide').click(function(){
	$(".slidingDiv").slideToggle();
	});  

});

$(document).ready(function(){

        $(".slidingDiv2").hide();
        $(".show_hide2").show();

	$('.show_hide2').click(function(){
	$(".slidingDiv2").slideToggle();
	});  

});

</script>
