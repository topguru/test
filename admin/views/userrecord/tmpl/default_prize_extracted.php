<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
?>
<script type="text/javascript"><!--
	function showDetailExtractedPieces(extractId){
		jQuery.ajax({
	        url: "index.php?option=com_awardpackage&view=userrecord&task=userrecord.prize_extracted_detail&extract_id=" + extractId,
	        success: function(data) {
				jQuery('#bodyDataDetail').html(data);
				jQuery('#loadDataDetail').modal('show');
				
	        }
		});		
	}
</script>
<div id="j-main-container" class="span10">
<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th>Prize Value</th>
			<th>Prize</th>
			<th>Symbol Set</th>
			<th>Extracted Pieces</th>
		</tr>
	</thead>	
	<tbody>	
		<?php
			$i = 0; 
			foreach ($this->items as $item) { 
		?>
		<tr>
			<td>$<?php echo number_format($item->prizeValue,2);?></td>
			<td><img
					src="./components/com_awardpackage/asset/prize/<?php echo $item->prizeImage; ?>"
					width="150px" /></td>
			<td><img
			src="./components/com_awardpackage/asset/symbol/<?php echo $item->symbolSet; ?>"
			width="150px" /></td>
			<td align="center">
			<?php if ($item->detailExtractedPieces != null) { ?>
			<a href="#" onclick="showDetailExtractedPieces('<?php echo $item->extractId; ?>');"><?php echo $item->extractedPieces; ?></a>
			<?php } else  { ?>
			<?php echo $item->extractedPieces; ?>
			<?php } ?>
			</td>
		</tr>
		<?php
				$i++; 
			} 
		?>
	</tbody>
</table>
</div>
<div id="loadDataDetail" class="modal hide fade" style="height:500px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Extracted Pieces');?></h3>
	</div>
	<table class="table table-striped" id="dataDetailTable"
		style="border: 1px solid #ccc;">
		<thead>
			<tr>
				<th><u><?php echo JText::_('Symbol Pieces')?></u></th>				
			</tr>
		</thead>
		<tbody id="bodyDataDetail">			
		</tbody>
	</table>
</div>
