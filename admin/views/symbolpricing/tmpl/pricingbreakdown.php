<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted access');
$pricing_details = $this->model->getPricingDetail(JRequest::getVar('details_id'));
$prize = $this->model->getPrizeDetails($pricing_details->prize_id);
$items = $this->model->getPieces($pricing_details->symbol_id);
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=pricingbreakdown&pricing_id=' . JRequest::getVar('pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&presentation_id=' . JRequest::getVar('presentation_id') . '&controller=symbolpricing'); ?>"
	method="post" name="adminForm" id="adminForm">
<div style="float: left;">
<table width="100%" class="table table-striped">
	<tr>
		<td width="200"><img
			src="./components/com_awardpackage/asset/prize/<?php echo $prize->prize_image; ?>"
			width="150px" style="border: 2px solid #cccccc;" /></td>
		<td align="left" valign="top"><strong>Prize Name : <?php echo $prize->prize_name; ?></strong>
		<br />
		<br />
		<strong>Created : <?php echo $prize->date_created; ?></strong> <br />
		<br />
		<strong> <input type="hidden" name="prize_value"
			value="<?php echo $prize->prize_value;?>"> Prize Value : <?php echo '$'.$prize->prize_value; ?>
		</strong></td>
	</tr>
</table>
</div>
<div style="clear: both;"></div>
<div style="float: right;">
<table class="table table-striped">
	<thead>
		<tr>
			<td align="right">Price from <input type="text"
				name="price_from_form"
				value="<?php echo $details_data->price_from; ?>">&nbsp;&nbsp;Price
			to<input type="text" name="price_to_form"
				value="<?php echo $details_data->price_to; ?>">&nbsp;
			<button>Insert Price Price</button>
			</td>
		</tr>
	</thead>
</table>
</div>
<table class="table table-striped" width="100%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- 
			<th><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($items); ?>);" /></th>
			 -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			<th>Symbol</th>
			<th>Price To</th>
			<th>Price From</th>
			<th>Virtual Price</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($items as $i => $item) {
		$dataBreakdown = $this->model->getBreakdownDetails(JRequest::getVar('details_id'), $item->symbol_pieces_id);
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td align="center"><input type="hidden" name="pieces_id[]"
				value="<?php echo $item->symbol_pieces_id;?>"> <?php echo JHtml::_('grid.id', $i, $dataBreakdown->breakdownid); ?>
			</td>
			<td align="center"><img
				src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $item->symbol_pieces_image; ?>"
				width="100"></td>
			<td><?php if($dataBreakdown->price_from){echo '$'. $dataBreakdown->price_from;} ?>
			<input type="hidden" name="price_from[]"
				value="<?php echo $dataBreakdown->price_from; ?>"></td>
			<td><?php if($dataBreakdown->price_to){echo '$'. $dataBreakdown->price_to; }?>
			<input type="hidden" name="price_to[]"
				value="<?php echo $dataBreakdown->price_to; ?>"></td>
			<td><?php if($dataBreakdown->virtual_price_breakdown){echo '$'. $dataBreakdown->virtual_price_breakdown;} ?>
			<input type="hidden" name="virtual_price[]"
				value="<?php echo $dataBreakdown->virtual_price_breakdown; ?>"></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<input type="hidden" name="details_id"
	value="<?php echo JRequest::getVar('details_id'); ?>"> <input
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
	name="task" value="insert_price_breakdown"></form>
</div>
