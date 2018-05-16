<?php
defined('_JEXEC') or die();

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
function onSelectPrizeStatus(){
	jQuery('form#adminForm').submit();
}
</script>
<div id="cj-wrapper">
<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
<div class="row-fluid">
<div class="span12">
<table class="table table-hover table-striped table-bordered" style="width:50%">
<thead>
	<tr>
		<th style="text-align:center;"><?php echo JText::_('Prize value'); ?></th>
   		<th style="text-align:center;"><?php echo JText::_('Prize'); ?></th>
		<th style="text-align:center;"><?php echo JText::_('Symbol set'); ?></th>
		<th style="text-align:center;"><?php echo JText::_('Symbol pieces'); ?></th>
	</tr>
</thead>
<tbody>
 <?php 
 $symbol = $this->model->getSymbolPresentation( JRequest::getVar('prize'));	
					foreach ($symbol as $simbol):
						?>
	<tr>
<td style="text-align:center;">
					                    <?php echo JText::_('$'.number_format($simbol->prize_value, 0, '.', '')); ?>
                    </td>		<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/prize/<?php echo $simbol->prize_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/symbol/<?php echo $simbol->symbol_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
					                    <?php echo JText::_(number_format($simbol->pieces, 0, '.', '')); ?>
                    </td>
	</tr>
							<?php endforeach;?>
</tbody>
</table>


<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist');?>"
	method="post" name="adminForm">
	<input type="hidden" name="task" id="task" value="apresentationlist.fundPrizeHistory" />
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">	

<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th style="text-align:center;"><?php echo JText::_('No'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('From'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('To'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Prize status'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->rows as $row):?>
				<tr>
					<td style="text-align:center;"><?php echo JText::_('$' . number_format($row->prize_value, 2, '.', '')); ?></td>					
					<td style="text-align:center;"><?php echo JText::_(number_format($row->each_fund_prize, 2, '.', '') . '%'); ?></td>
					<td style="text-align:center;"><?php echo JText::_(number_format($row->value_funded, 2, '.', '')); ?></td>
					<td style="text-align:center;"><?php echo JText::_(number_format($row->shortfall, 2, '.', '')); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			
		</table>
		
</form>
</div>
</div>
</div>

