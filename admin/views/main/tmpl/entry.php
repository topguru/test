<style>
.fn {font-size: 9pt; font-weight: bold}
.btn {font-size: 9pt; font-weight: bold}
</style>
<script>
	$(function() {
		var dates = $('#start_date, #end_date').datepicker({
			showOn: "button",
			buttonImage: "<?php echo JURI::base().'components/com_awardpackage/assets/css/images/calendar.gif';?>",		
			dateFormat: 'yy-mm-dd',		
			numberOfMonths: 1,
			beforeShow: function(selectedDate) {
				var opposite = this.id == 'start_date' ? 'maxDate' : 'minDate';
				$(this).datepicker('option', opposite, dates.not(this).datepicker('getDate'));
			},
			onSelect: function(selectedDate) {
				var option = this.id == 'start_date' ? 'minDate' : 'maxDate';
				dates.not(this).datepicker('option', option, $(this).datepicker('getDate'));
			}
		});
	});
</script>
<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_awardpackage/assets/css/jquery.ui.all.css');
$doc->addStyleSheet(JURI::base().'components/com_awardpackage/assets/css/demos.css');
$doc->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.min.js');
$doc->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.ui.datepicker.js');
$doc->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.ui.core.js');
$doc->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.ui.widget.js');
if(JRequest::getVar('tmpl')){
	$destination = JRoute::_('index.php?option=com_awardpackage&tmpl=component&task=entry');
}else{
	$destination = JRoute::_('index.php?option=com_awardpackage&task=entry');
}
?>
<table align="center">
<tr><td>
<fieldset style="width:30%"><legend>Award Package</legend>
	<form action="<?php echo $destination;?>" method="POST">
	<?php if(isset($this->package_id)){?>
		<input type="hidden" value="<?php echo $this->package_id;?>" name="package_id">
	<?php };?>
		<table align="center" border="0">
			<tr>
				<td class="fn" nowrap>Package name</td>
				<td><input type="text" value="<?php echo @$this->package_name;?>" style="font-size:9pt" size="30" id="package_name" name="package_name"></td>
			</tr>
			<tr><td class="fn" nowrap>Start date</td><td>
			<input type="text" value="<?php echo @$this->start_date;?>"  style="height:17px;width:80px;font-size:9pt" id="start_date" name="start_date"></td>
			</tr>
			<tr><td class="fn" nowrap>End date</td><td>
			<input style="height:17px;width:80px;font-size:9pt" type="text" value="<?php echo @$this->end_date;?>"  id="end_date" class="datepicker" name="end_date"></td></tr>
			<tr><td class="fn" nowrap>Status</td><td>
			<table>
			<tr><td>
			<input type="radio" value="1" name="published"<?php if(@$this->published) echo 'checked';?>></td>
			<td>published</td>
			<td><input type="radio" value="0" name="published"<?php if(!@$this->published) echo 'checked';?>></td>
			<td>unpublished</td>
			</tr>
			</table>
			</td></tr>
			<tr><td colspan="2" align="center"><input class="btn" type="submit" value="Save" name="submit"></td></tr>
		</table>
	</form>
</fieldset>
</td></tr>
</table>
