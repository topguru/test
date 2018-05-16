<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
?>
<script type="text/javascript">
	function numbersOnly(e) {
		e.value = e.value.replace(/[^0-9\.]/g,'');		
	}

	function checkvalidate(){
		var prize_from	= document.getElementById('prize_from').value;
		var prize_to	= document.getElementById('prizerange_to').value;
		var prize_value = jQuery('#prize_value').val();
		if(prize_from=='' || prize_to ==''){
			if(prize_from==''){
				alert('From Prize Value Required');
				return false;
			}else if($prize_to==''){
				alert('To Prize value Required');
				return false;
			}
		}	
		if(parseInt(prize_to) > parseInt(prize_value)) {
			alert('Prize to must lower than ' + prize_value);
			return false;
		}	 
		if(parseInt(prize_to) < parseInt(prize_from)) {	
			alert('To prize must greater than from prize');
			return false;
		}	
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&task=processsymbol.prizerange',
			type: 'post',
			dataType: 'text',
			success: function(data){
				if (data == 'Data has been saved') {
					top.frames.location.reload(false);	
				} else {
					jQuery('#alertInfo').css('display','block');
					jQuery('#alertInfo').html(data);
				}
			}					
		});
		//top.frames.location.reload(false);
		//parent.SqueezeBox.close();		
	}
</script>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&task=processsymbol.prizerange'); ?>"
	method="post" name="adminForm" id="adminForm" class="validate"><input
	type="hidden" name="presentation_id"
	value="<?php echo JRequest::getInt('presentation_id');?>" /> <input
	type="hidden" name="package_id" value="<?php echo $this->package_id;?>" />	
	<input type="hidden" name="process_id" value="<?php echo $this->process_id; ?>" />
	<input type="hidden" name="prize_value" id="prize_value" value="<?php echo JRequest::getVar('prize_value'); ?>" />
<div class="modal-header">
<h3><?php echo JText::_('Prize Value Range');?></h3>
</div>
<div class="alert alert-info" id="alertInfo" style="display:none">	
</div>
<table class="table table-striped" id="surveyCategoryTable"
	style="border: 1px solid #ccc;" width="80%">
	<thead>
		<tr>
			<th width="100%"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="100%" align="right">
			<button type="button" class="btn btn-primary btn-save-page-title"
				id="updateTitleBtn" onclick="checkvalidate();"><i></i> <?php echo JText::_('Save &amp; Close');?></button>
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
			<div class="clearfix"><label><?php echo JText::_('From Prize Value');?>:
			<i class="icon-info-sign tooltip-hover"
				title="<?php echo JText::_('From Prize Value');?>"></i> </label> <input
				name="prizerange_from" id="prize_from" type="text"
				class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
			</div>
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
			<div class="clearfix"><label><?php echo JText::_('To Prize Value');?>:
			<i class="icon-info-sign tooltip-hover"
				title="<?php echo JText::_('To Prize Value');?>"></i> </label> <input
				name="prizerange_to" id="prizerange_to" type="text"
				class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
			</div>
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>
