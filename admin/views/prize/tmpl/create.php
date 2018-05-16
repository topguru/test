<?php
/**
 * @package Joomla.Administrator
 * @subpackage	com_awardpackage
 * @copyright	kadeyasa@gmail.com
 */


defined('_JEXEC') or die('Restricted access');

$editor = &JFactory::getEditor();

jimport('joomla.form.helper');
jimport('joomla.html.html.bootstrap');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<script type="text/javascript">
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
	 }
	 
    Joomla.submitbutton = function(task)
    {
        if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>
<div id="j-main-container" class="span10">
<form action="index.php" method="post" enctype="multipart/form-data"
	name="adminForm" id="adminForm">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command'); ?>">
<div id="editcell">
<p id="f1_upload_process">Loading...<br />
<?php echo JHTML::_('image',  'loader.gif', '/components/com_awardpackage/asset/img/', NULL, NULL, 'Loading' ); ?><br />
</p>
<div>
<fieldset><legend>Prize Image</legend>
<table class="table table-striped">
	<tr>
		<td colspan="3" style="width: 200px">Prize Image</td>
	</tr>
	<tr>
		<td colspan="3" style="width: 200px"><img
			src="./components/com_awardpackage/asset/prize/<?php echo $this->data->prize_image;?>"
			id="image" name="image" width="200" height="200" /></td>
	</tr>
	<tr>
		<td colspan="3" style="width: 200px"><input type="file"
			id="prize_image" name="prize_image" value="Browse" /> <input
			id="upload" type="submit" value="Upload" /></td>
	</tr>
</table>
</fieldset>
</div>
<br style="clear: both;">
<div>
<fieldset>
<table class="table table-striped">
	<tr>
		<td style="width: 50px"><label for="prize_name" class="hasTip"
			title="Prize Name">Prize Name </label></td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" name="prize_name"
			value="<?php echo $this->data->prize_name;?>" id="prize_name" /></td>
	</tr>
	<tr>
		<td style="width: 50px"><label for="prize_value" class="hasTip"
			title="Prize Value">Prize Value (USD) </label></td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" name="prize_value"
			value="<?php echo $this->data->prize_value;?>" id="prize_value"
			class="hasTip" title="number only (input in USD)"
			onkeypress="return isNumberKey(event)" /></td>
	</tr>
	<tr>
		<td style="width: 50px"><label class="hasTip" title="Create By"><?php echo JText::_('Create By');?></label>
		</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="text" name="created_by"
			readonly="true"
			value="<?php if($this->data->created_by != '') echo $this->data->created_by; else echo "admin"; ?>" /></td>
	</tr>
	<tr>
		<td style="width: 50px"><label class="hasTip" title="Created Date"><?php echo JText::_('Created Date');?></label>
		</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px"><input type="desc" name="date_created"
			readonly="true"
			value="<?php if($this->data->date_created != '') echo date("d-m-Y",strtotime($this->data->date_created)); else echo date("d-m-Y");?>" /></td>
	</tr>
	<tr>
		<td style="width: 50px"><label class="hasTip" title="Description"><?php echo JText::_('Description');?></label>
		</td>
		<td style="width: 10px">:</td>
		<td style="width: 100px">
		<?php // $editor = JFactory::getEditor();
			echo $editor->display("desc", $this->data->desc, "15", "3", "25", "10", 10, null, null, null, array('mode' => 'extended'));?>
		<?php	//echo $editor->display( 'desc',  $this->data->desc , '60%', '30', '75', '20' ,array('mode' => 'advanced')) ;?>
		</td>
	</tr>
</table>
</fieldset>
</div>
<br />
<br />
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" id="task" name="task" value="" /> <input type="hidden"
	name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
<input type="hidden" name="controller" value="prize" /> <input
	type="hidden" name="view" value="prize" /> <input type="hidden"
	name="prize_image_name" id="prize_image_name"
	value="<?php echo $this->data->prize_image;?>" /> <input type="hidden"
	name="id" value="<?php echo $this->data->id;?>" /> <iframe
	id="upload_target" name="upload_target" src="#"
	style="width: 0; height: 0; border: 0px solid #fff;"></iframe> <br />
</div>
<br />
<br />
</form>
</div>

<script type="text/javascript">
$.noConflict();
jQuery(document).ready(function() {
	jQuery('#f1_upload_process').hide();
	jQuery("#upload").click(function(){
		jQuery('#task').val('upload');
		jQuery('#f1_upload_process').show();
		jQuery('#adminForm').attr('target','upload_target');
		return true;
	});
});

function stopUpload(status,source,name){
	  if(status == 1){
		  jQuery('#f1_upload_process').hide();;
		  jQuery('#f1_upload_form').show();
		  jQuery('#image').attr('src',source);
		  jQuery('#prize_image_name').val(name);      
		  jQuery('#task').val('');
		  jQuery('#adminForm').attr('target','');
	  }
	  
	  return true;   
}

</script>
