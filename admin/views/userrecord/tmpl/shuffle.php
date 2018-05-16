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
		var shuffle_from	= document.getElementById('shuffle_from').value;
		var shuffle_to	= document.getElementById('shuffle_to').value;
		if(shuffle_from=='' || shuffle_to==''){
			if(shuffle_from==''){
				alert('Shuffle From Required');
				return false;
			}else if(shuffle_to==''){
				alert('Shuffle to Required');
				return false;
			}
		}
		if(parseInt(shuffle_to) < parseInt(shuffle_from)) {	
			alert('shuffle to must greater than shuffle from');
			return false;
		}
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&task=processsymbol.shuffeled',
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
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&task=processsymbol.shuffeled'); ?>"
	method="post" name="adminForm" id="adminForm" class="validate">
<input type="hidden" name="presentation_id" value="<?php echo JRequest::getInt('presentation_id');?>" /> 
<input type="hidden" name="package_id" value="<?php echo JRequest::getInt('package_id');?>" /> 
<input type="hidden" name="process_id" value="<?php echo JRequest::getInt('process_id');?>" />
<div class="modal-header">
<h3><?php echo JText::_('Shuffle Symbol');?></h3>
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
				<?php echo JText::_('Shuffle all symbol pieces of all prize values in helper queue before insert into each symbol queue.');?>				
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
				<div class="clearfix"><label><?php echo JText::_('Shuffle');?>:
				<i class="icon-info-sign tooltip-hover"
					title="<?php echo JText::_('Shuffle');?>"></i> </label> 
					<input
					name="shuffle_from" id="shuffle_from" type="text"
					class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
				</div>				
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
				<div class="clearfix"><label><?php echo JText::_('To');?>:
				<i class="icon-info-sign tooltip-hover"
					title="<?php echo JText::_('To');?>"></i> </label> 
					<input
					name="shuffle_to" id="shuffle_to" type="text"
					class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
				</div>				
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
				<?php echo JText::_('times');?>				
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>
