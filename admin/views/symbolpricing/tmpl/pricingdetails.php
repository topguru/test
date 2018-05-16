<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted access');
$items = $this->model->getPricingDetails(JRequest::getVar('presentation_id'));
$details_data = $this->model->getPricingDetail(JRequest::getVar('details_id'));
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=pricingdetails&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id') . '&controller=symbolpricing'); ?>"
	method="post" name="adminForm" id="adminForm">

<div style="float: right;">
<table class="table table-striped">
	<thead>
		<tr>
			<td align="right">Price from <input type="text"
				name="price_from_form"
				value="<?php echo $details_data->price_from;?>">&nbsp;&nbsp;Price to<input
				type="text" name="price_to_form"
				value="<?php echo $details_data->price_to;?>">&nbsp;
			<button>Insert Price</button>
			</td>
		</tr>
	</thead>
</table>
</div>
<div style="clear: both;"></div>
<table class="table table-striped" width="100%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- 
			<th><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($items); ?>);" /></th>
			-->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?> 
			<th>Prize</th>
			<th>Name</th>
			<th>Value</th>
			<th>Symbol Pieces</th>
			<th>Price From</th>
			<th>Price To</th>
			<th>Virtual Price</th>
			<th>Price breakdown</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($items as $i => $item) {
		$pricing_details = $this->model->PricingDetails(JRequest::getVar('pricing_id'), $item->id, $item->symbol_id);
		?>
		<tr class="row<?php echo $i; ?>">
			<td align="center"><?php echo JHtml::_('grid.id', $i, $pricing_details ->details_id); ?></td>
			<td align="center"><input type="hidden" name="prize_id[]"
				value="<?php echo $item->id; ?>"> <img
				src="./components/com_awardpackage/asset/prize/<?php echo $item->prize_image; ?>"
				width="100px" /></td>
			<td><?php echo $item->prize_name; ?></td>
			<td><input type="hidden" name="prize_value[]"
				value="<?php echo $item->prize_value;?>"> <?php echo '$' . $item->prize_value; ?></td>
			<td><input type="hidden" name="symbol_id[]"
				value="<?php echo $item->symbol_id; ?>"> <?php echo $item->pieces; ?>
			</td>
			<td><?php if($pricing_details->price_from){ echo '$'.$pricing_details->price_from; }?>
			<input type="hidden" name="price_from[]"
				value="<?php echo $pricing_details->price_from; ?>"></td>
			<td><?php if($pricing_details->price_to){echo '$'.$pricing_details->price_to; }?>
			<input type="hidden" name="price_to[]"
				value="<?php echo $pricing_details->price_to; ?>"></td>
			<td><?php if($pricing_details->virtual_price){ echo '$'. $pricing_details->virtual_price; }?>
			<input type="hidden" name="virtual_price[]"
				value="<?php echo $pricing_details->virtual_price; ?>"></td>
				<?php

				if(count($this->model->CheckPricingBreakdown($pricing_details->details_id))<1){
					$label ='New';
				}else{
					$label ='Edit';
				}
				$link ='index.php?option=com_awardpackage&view=symbolpricing&layout=pricingbreakdown&pricing_id='.JRequest::getVar('pricing_id').'&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id').'&details_id='.$pricing_details->details_id;
				?>
			<td align="center"><a href="<?php echo JRoute::_($link);?>"><?php echo $label;?></a></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<div><?php
$task = JRequest::getVar('task');
unset($task);
?> <input type="hidden" name="details_id"
	value="<?php echo JRequest::getVar('details_id');?>"> <input
	type="hidden" name="pricing_id"
	value="<?php echo JRequest::getVar('pricing_id'); ?>"> <input
	type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id'); ?>"> <input
	type="hidden" name="presentation_id"
	value="<?php echo JRequest::getVar('presentation_id'); ?>"> <input
	type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="view"
	value="<?php echo $_REQUEST['view']; ?>" /> <input type="hidden"
	name="filter_order" value="<?php echo $listOrder ?>" /> <input
	type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
<input type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="insert_price"> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>