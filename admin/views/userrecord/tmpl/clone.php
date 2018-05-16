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
		var clone_from	= document.getElementById('clone_from').value;
		var clone_to = document.getElementById('clone_to').value;
		if(clone_from == '' || clone_to == ''){
			if(clone_from == ''){
				alert('Clone From Required');
				return false;
			}else if(clone_to == ''){
				alert('Clone To Required');
				return false;
			}
		}
		if(parseInt(clone_to) < parseInt(clone_from)) {	
			alert('Clone to must greater than clone from');
			return false;
		}
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&task=processsymbol.cloned',
			type: 'post',
			dataType: 'text',
			success: function(data){
				top.frames.location.reload(false);	 
			}					
		});
	}
</script>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&task=processsymbol.cloned'); ?>"
	method="post" name="adminForm" id="adminForm" class="validate">
<input type="hidden" name="presentation_id" value="<?php echo JRequest::getInt('presentation_id');?>" />
<input type="hidden" name="package_id" value="<?php echo $this->package_id;?>" />
<input type="hidden" name="prize_from" value="<?php echo $this->loadProcess()->prize_value_from;?>" />
<input type="hidden" name="prize_to" value="<?php echo $this->loadProcess()->prize_value_to;?> " />
<input type="hidden" name="process_id" value="<?php echo JRequest::getInt('process_id');?>" />
<div class="modal-header">
<h3><?php echo JText::_('Clone Pieces');?></h3>
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
			<div class="clearfix"><label><?php echo JText::_('From');?>:
			<i class="icon-info-sign tooltip-hover"
				title="<?php echo JText::_('From');?>"></i> </label> <input
				name="clone_from" id="clone_from" type="text"
				class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
			</div>
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
			<div class="clearfix"><label><?php echo JText::_('To');?>:
			<i class="icon-info-sign tooltip-hover"
				title="<?php echo JText::_('To');?>"></i> </label> <input
				name="clone_to" id="clone_to" type="text"
				class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
			</div>
			</td>
		</tr>
		<tr>
			<td width="100%" align="left">
			Clones to symbol pieces from prize value $ <?php echo number_format($this->loadProcess()->prize_value_from,2);?>
			to prize value $<?php echo number_format($this->loadProcess()->prize_value_to,2);?>
			</td>
		</tr>
	</tbody>
</table> 
</form>
</div>
