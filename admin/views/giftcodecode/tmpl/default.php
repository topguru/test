<style>
td {
	text-align: center;
}

th {
	font-weight: bold;
}
</style>
<?php
defined('_JEXEC') or die('Restricted access');

$colorID = array ();

foreach ($this->giftcodeColorID as $gcColorID) {
	$colorID[] = $gcColorID[0]->giftcode_category_id;
}


$categoryData =& $this->categoryData;
$color = array();
$color[] = "";
$catData = $this->model->getGiftCodePackage(JRequest::getVar('package_id'));

if(JRequest::getVar('color')==""){
	foreach($catData as $row){
		$color = $row->name;
		$cat_id = $row->id;
	}
}else{
	foreach ($categoryData as $row) {
		$color[$row->id] = $row->name;
		$cat_id = JRequest::getVar("color");
	}
}
$color_per_category = $this->model->get_color_per_category($cat_id);
//$cat_id = JRequest::getVar("color") ? JRequest::getVar("color") : "1";

?>
<div id="j-main-container" class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="wrapper">
<ul class="navigationTabs">
<?php
$id = JRequest::getVar('color') ? JRequest::getVar('color') : "1" ;
foreach ($categoryData as &$row) {
	if($row->package_id==JRequest::getVar('package_id')):
	?>
	<li><a class="<?php echo $id == $row->id ? "active" : "" ?>"
		href="index.php?option=com_awardpackage&view=giftcodecode&color=<?php echo $row->id ?>&package_id=<?php echo JRequest::getVar('package_id');?>"
		id="<?php echo $row->name ?>" rel="<?php echo $row->name ?>">
	<div class="kotak" style="background-color:<?php echo $row->color_code ?>;text-align:center;float:left"><?php echo $row->category_id ?></div>
	</a></li>
	<?php
	endif;
}
?>
</ul>
<div class="tabsContent"><?php
foreach ($categoryData as &$row)
{
	?>
<div class="tab">
<table class="table table-striped"
	style="width: 860px; float: left; margin-bottom: 20px;">
	<thead>
		<tr>
			<th colspan="5" style="text-align:center; background-color:#CCCCCC"><?php echo JText::_('COM_GIFT_CODE_RENEW_GIFT_CODE_SCHEDULE');?></th>
		</tr>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('COM_GIFT_CODE_CATEGORY');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_RENEW_SCHEDULE');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_CREATED');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_MODIFIED');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_NO_OF_RENEWALS');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php
			if(JRequest::getVar('color')){
				echo $color[$cat_id];
			}else{
				echo $color;
			}
			?></td>
			<?php
			$this->renew_schedule = $this->model->checking_renew_schedule($cat_id);
			$this->renew_schedule_data = $this->model->get_renew_schedule($cat_id);
			$this->unrenewed_giftcode = $this->model->checking_unrenewed_giftcode($cat_id);
			$this->giftcodes = $this->model->get_giftcodes($cat_id);
			if (count($this->renew_schedule) == 0) {
				$renew_schedule_route = "index.php?option=com_awardpackage&view=giftcodecode&layout=renew_schedule&color=".$cat_id."&package_id=".JRequest::getVar('package_id');
				$renew_schedule_route_name = "New";
			} else {
				$renew_schedule_route = "index.php?option=com_awardpackage&view=giftcodecode&layout=edit_renew_schedule&color=".$cat_id."&package_id=".JRequest::getVar('package_id');
				$renew_schedule_route_name = "Edit";
			}
			?>
			<td><?php if (count($this->giftcodes) == 0) { ?> - <?php } else { ?>
			<a href="<?php echo JRoute::_($renew_schedule_route); ?>"><?php echo $renew_schedule_route_name; ?></a>
			<?php } ?></td>
			<!--td><?php //echo $this->renew_schedule_data->created == "" ? "-" : date("d-M-Y", strtotime($this->renew_schedule_data->created)); ?></td>
			<td><?php //echo $this->renew_schedule_data->modified == "" ? "-" : date("d-M-Y", strtotime($this->renew_schedule_data->modified)); ?></td-->
            <td><?php //echo $this->renew_schedule_data->created == "" ? "-" : date("d-M-Y", strtotime($this->renew_schedule_data->created)); ?></td>
			<td><?php //echo $this->renew_schedule_data->modified == "" ? "-" : date("d-M-Y", strtotime($this->renew_schedule_data->modified)); ?></td>
			<td><?php echo count($this->unrenewed_giftcode); ?></td>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr style="background-color:#CCCCCC; text-align:center; font-weight:bold;">
			<td width="1%" align="center"><input type="checkbox" name="checkall-toggle"
				value="on" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
				onclick="Joomla.checkAll(this)" /></td>
			<td><?php echo JText::_('COM_GIFT_CODE_CATEGORY');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_CREATED');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_MODIFIED');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_NO_OF_GIFT_CODES');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_VIEW_COLLECTION');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_RENEW_STATUS');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_RENEW_DATE');?></td>
			<td><?php echo JText::_('COM_GIFT_CODE_PUBLISHED');?></td>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($color_per_category as $cpc)
	{
		$view = JRoute::_( 'index.php?option=com_awardpackage&view=showgiftcode&gcid='.$cpc->id.'&package_id='.JRequest::getVar('package_id'));
		?>
		<tr>
			<td align="center"><?php echo JHtml::_('grid.id', $i, $cpc->id); ?></td>
			<td><?php
			if(JRequest::getVar('color')==""){ echo $color;}else{echo $color[$cpc->color_id];} ?></td>
			<td><?php echo date("d-M-Y",strtotime($cpc->created_date_time)); ?></td>
			<td><?php echo date("d-M-Y",strtotime($cpc->modified_date_time)); ?></td>
			<td><?php echo $cpc->total_giftcodes; ?></td>
			<td><a href="<?php echo $view ?>"><?php echo JText::_('COM_GIFT_CODE_VIEW');?></a>
			</td>
			<td><?php echo $cpc->renew_status != 1 ? "In Use" : "Expired" ?></td>
			<td><?php 
			//ini yang diubah ita tgl 18/06/2014//
			$this->model =  JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
			//ini yang lamanya
			//$giftcode_model =& JModel::getInstance('Giftcodecode','AwardPackageModel');
			$schedule_created = $this->model->get_schedule_created_data($cpc->color_id, $cpc->id);
			foreach ($schedule_created as $sc) { print $sc->created_date; }
			?></td>
			<td><?php echo JHtml::_('jgrid.published', $cpc->published, $i, '', 1, 'cb'); ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
		$i++;
	}
	?>
	</tbody>
</table>
</div>
	<?php
}
?></div>
</div>
<input type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="count" id="count"
	value="<?php //echo count( $this->data );?>" /> <input type="hidden"
	name="baseurl" id="baseurl" value="<?php //echo $base;?>" /> <input
	type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="color" value="<?php echo $cat_id;?>" /> <input
	type="hidden" name="color_name" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="task"
	value="create" /> <input type="hidden" name="controller"
	value="giftcodecode" /></form>
</div>
