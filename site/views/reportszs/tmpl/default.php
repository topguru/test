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

if(!empty($this->item->stats->daily)){
	
	$daily_data = array();
	
	foreach($this->item->stats->daily as $stat){
		
		$daily_data[] = "['".$stat->created_on."', ".$stat->responses."]";
	}

	$script = "
		google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
		google.setOnLoadCallback(drawDailyChart);
		function drawDailyChart() {
			var data = google.visualization.arrayToDataTable([['".JText::_("LBL_DATE")."','".JText::_("LBL_RESPONSES")."'], ".implode(',', $daily_data)."]);
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
	
	<table width="100%">
		<tr>
			<td width="10%" valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>	
			</td>
			<td valign="top">
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'headersz.php';?>
			
				<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	 <div class="table-responsive" id="score">
						<table class="table table-striped table-hover table-bordered table-condensed">
                        <thead>
								<tr>
									<th colspan="3"><h3><?php echo JText::_('GIFTCODE');?></h3></th>
								</tr>
							</thead>
                            <tbody>
								<tr>
                                    <th style="text-align:center;">GiftCode</th>
                                    <th style="text-align:center;">Quantity</th>
                                    </tr>
                                    
                                    <?php 
									
									foreach ($this->response as $respon) { 

									?>
       								<tr>
                                    <?php echo '<td style="width:30px;padding:20px;text-align:center;background-color:'.$respon->colour_code.'" valign="center"><span>';
                                     
									if (!empty($respon->completed))
									{echo $respon->category_id;}
                                    else
									{echo $respon->category_id;}
									?>
                                    </span>                                    
                                    </td>
                                    <td style="padding:20px;text-align:center;">
                                    <?php if ($respon->completed ==1)
									{echo $respon->complete_giftcode_quantity;}
                                     else
                                    {echo $respon->incomplete_giftcode_quantity;} 
									?>
                                    </td>
                                  
                                    </tr>

                                    <?php } ?>

                                    </tbody>
                        </table>
					</div>
                    
				<table class="table table-bordered table-striped table-hover margin-top-20">
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_COMPLETED_RESPONSES');?></td>
						<td width="15%"><?php echo $this->item->responses;?></td>
						<td width="30%">
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_consolidated_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_CONSOLIDATE_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_RESPONSES');?></td>
						<td><?php echo $this->item->stats->total_responses;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_responses_list&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_ALL_RESPONSES');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_COUNTRIES_PARTICIPATED');?></td>
						<td><?php echo count($this->item->stats->countries);?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_location_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_COUNTRY_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_BROWSERS_USED');?></td>
						<td><?php echo $this->item->stats->browsers;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_device_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_BROWSER_REPORT');?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('LBL_TOTAL_OS_USED');?></td>
						<td><?php echo $this->item->stats->oses;?></td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_os_report&id='.$this->item->id)?>">
								<?php echo JText::_('LBL_VIEW_OS_REPORT');?>
							</a>
						</td>
					</tr>
				</table>
				
				<div id="geo_chart" style="width: 100%; height: 400px;"></div>
				
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
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_response_details&id='.$this->item->id.'&rid='.$item->id.$itemid)?>">
									<?php echo JText::_('LBL_VIEW_REPORT');?>
								</a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	
</div>