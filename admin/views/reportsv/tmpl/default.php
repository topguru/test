<?php 
/**
 * @version		$Id: default_reports.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 6;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

$document = JFactory::getDocument();
$document->addScript('https://www.google.com/jsapi');

$data = array();
if(!empty($this->item->stats->daily)){
	
	foreach($this->item->stats->daily as $stat){
		
		$data[] = "['".$stat->created_on."', ".$stat->responses."]";
	}

	$script = "
		google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([['".JText::_("LBL_DATE")."','".JText::_("LBL_RESPONSES")."'], ".implode(',', $data)."]);
			var options = {width: '100%', height: 350, 'chartArea': {'width': '92%', 'height': '80%'}, 'legend': {'position': 'in'}};
			var chart = new google.visualization.LineChart(document.getElementById('daily-response-chart'));
			chart.draw(data, options);
		}";
	$document->addScriptDeclaration($script);
}

if(!empty($this->item->stats->countries)){
	
	$geo_data = array();
	
	foreach($this->item->stats->countries as $country){
		
		$geo_data[] = "['".$country->country_name."', ".$country->country_total."]";
	}
	
	$script = "
		google.load(\"visualization\", \"1\", {packages:[\"geochart\"]});
		google.setOnLoadCallback(drawGeoChart);
		function drawGeoChart() {
			var data = google.visualization.arrayToDataTable([['".JText::_("LBL_COUNTRY")."','".JText::_("LBL_RESPONSES")."'], ".implode(',', $geo_data)."]);
			var options = {width: '100%', height: 350, 'chartArea': {'width': '92%', 'height': '80%'}, 'legend': {'position': 'in'}, 'dataMode': 'regions'};
			var chart = new google.visualization.GeoChart(document.getElementById('geo_chart'));
			chart.draw(data, options);
		}";
	$document->addScriptDeclaration($script);
}
?>
<div id="cj-wrapper">

	<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	
	<div class="container-fluid no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span8">
				<div id="geo_chart" style="width: 100%; height: 400px;"></div>
			</div>
			<div class="span4">
				<h3 class="page-header no-space-top no-space-bottom"><?php echo JText::_('LBL_TOP_COUNTRIES');?></h3>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th><?php echo JText::_('LBL_COUNTRY');?></th>
							<th><?php echo JText::_('LBL_RESPONSES');?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($this->item->stats->countries)):?>
						<?php foreach ($this->item->stats->countries as $i=>$country):?>
						<tr>
							<td><?php echo $this->escape($country->country_name);?></td>
							<td><?php echo $this->escape($country->country_total);?></td>
						</tr>
						<?php 
							if($i == 10) break;
						?>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			</div>		
		</div>
	</div>
	
	<table class="table table-bordered table-striped table-hover margin-top-20">
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COMPLETED_RESPONSES');?></td>
			<td width="15%"><?php echo $this->item->responses;?></td>
			<td width="30%">
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_consolidated_report&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_CONSOLIDATE_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_RESPONSES');?></td>
			<td><?php echo $this->item->stats->total_responses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_responses_list&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_ALL_RESPONSES');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COUNTRIES_PARTICIPATED');?></td>
			<td><?php echo count($this->item->stats->countries);?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_location_report&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_COUNTRY_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_BROWSERS_USED');?></td>
			<td><?php echo $this->item->stats->browsers;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_device_report&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_BROWSER_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_OS_USED');?></td>
			<td><?php echo $this->item->stats->oses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_os_report&package_id='.JRequest::getVar('package_id').'&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_OS_REPORT');?>
				</a>
			</td>
		</tr>
	</table>
	
	<h3 class="page-header margin-bottom-10"><?php echo JText::_('LBL_DAILY_RESPONSE_CHART');?></h3>
	<div id="tab-daily-chart" style="overflow: hidden;">
		<?php if(!empty($this->item->stats->daily)):?>
		<div id="daily-response-chart" style="width: 100%; height: 350px;"></div>
		<?php else:?>
		<?php echo JText::_('MSG_NO_DATA_AVAILABLE');?>
		<?php endif;?>
	</div>
	
	<h3 class="page-header no-margin-bottom"><?php echo JText::_('LBL_LATEST_RESPONSES');?></h3>
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('LBL_USERNAME');?></th>
				<th width="20%"><?php echo JText::_('LBL_COUNTRY');?></th>
				<th width="20%"><?php echo JText::_('LBL_DATE');?></th>
				<th width="20%"><?php echo JText::_('LBL_VIEW_REPORT');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->item->stats->recent as $i=>$item):?>
			<tr>
				<td><?php echo $i + 1;?></td>
				<td><?php echo $item->created_by > 0 ? $this->escape($item->username) : JText::_('LBL_GUEST');?></td>
				<td><?php echo $this->escape($item->country_name);?></td>
				<td><?php echo $item->created;?></td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$item->id.$itemid)?>">
						<?php echo JText::_('LBL_VIEW_REPORT');?>
					</a>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsv&task=reportsv.get_survey_reports&id='.$this->item->id.':'.$this->item->alias.$itemid)?>" method="post">
		<input type="hidden" name="task" value="home">
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>"/> 
	</form>
</div>