
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
		var extract_from	= document.getElementById('extract_from').value;
		var extract_to	= document.getElementById('extract_to').value;
		var pieces = jQuery('#pieces').val();
		if(extract_from == '' || extract_to == ''){
			if(extract_from == ''){
				alert('Extract from Required');
				return false;
			}else if(extract_to == ''){
				alert('Extract to Required');
				return false;
			}
		}
		if(parseInt(extract_to) < parseInt(extract_from)) {	
			alert('extract to must greater than extract from');
			return false;
		}
		if(parseInt(extract_to) > parseInt(pieces)) {
			alert('extract to must lower than ' + pieces);	
		}	
		jQuery('form#adminForm').ajaxSubmit({
			url: 'index.php?option=com_awardpackage&task=processsymbol.extractpieces',
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
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&task=processsymbol.extractpieces'); ?>"
	method="post" name="adminForm" id="adminForm" class="validate">
<input type="hidden" name="presentation_id" value="<?php echo JRequest::getInt('presentation_id');?>" />
<input type="hidden" name="package_id" value="<?php echo $this->package_id;?>" />
<input type="hidden" name="prize_from" value="<?php echo $this->loadProcess()->prize_value_from;?>" />
<input type="hidden" name="prize_to" value="<?php echo $this->loadProcess()->prize_value_to;?> " />
<input type="hidden" name="process_id" value="<?php echo JRequest::getInt('process_id');?>" />
<input type="hidden" name="pieces" id="pieces" value="<?php echo JRequest::getInt('pieces'); ?>"/>
<div class="modal-header">
<h3><?php echo JText::_('Extract Pieces');?></h3>
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
				<div class="clearfix"><label><?php echo JText::_('Extract Symbol Pieces');?>:
				<i class="icon-info-sign tooltip-hover"
					title="<?php echo JText::_('Extract Symbol Pieces');?>"></i> </label> 
					<input
					name="extract_from" id="extract_from" type="text"
					class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
				</div>				
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" valign="center">
				<div class="clearfix"><label><?php echo JText::_('To Symbol Pieces');?>:
				<i class="icon-info-sign tooltip-hover"
					title="<?php echo JText::_('To Symbol Pieces');?>"></i> </label> 
					<input
					name="extract_to" id="extract_to" type="text"
					class="input-xxlarge required" value="" onkeyup="numbersOnly(this);">
				</div>				
			</td>
		</tr>
	</tbody>	
</table>
</form>
</div>