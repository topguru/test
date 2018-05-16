<?php
defined('_JEXEC') or die();

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$filter = JRequest::getVar('filter');
?>
<script type="text/javascript">
function onSelectFilter(){
	var filter = jQuery('#cbfilter').val();
	jQuery('#filter').val(filter);
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=aprocesspresentation');
	jQuery('form#adminForm').submit();	
}
</script>
<div id="cj-wrapper">
<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
<div class="row-fluid">
<div class="span12">
<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Selected presentation</a></li>
                              <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundPrizePlan&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund prize plan - selected prizes</a></li>
	                          <li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundReceiverList&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund receiver list queue</a></li>    	
                              </ul>
                              </nav>	
<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>"
	method="post" name="adminForm">
	<input type="hidden" name="task" id="task" value="aprocesspresentation.fundReceiverList" />
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">	
	<input type="hidden" id="filter" name="filter" value="" />
    

					<table class="table table-striped table-bordered">
				<thead>
                <tr><th colspan="15" style="text-align:right;">
                Prize Status Filter
				<select name="cbfilter" id="cbfilter" style="width:200px" onchange="onSelectFilter();">
                <option value="">All</option>
				<option value="0" <?php echo ($filter == "0" ? "selected=selected" : ""); ?>>Lock</option>
				<option value="1" <?php echo ($filter == "1" ? "selected=selected" : ""); ?>>Unlock</option>
                </select>
                </th>
                <th style="text-align:right;">                                    
    <?php echo $this->pagination->getLimitBox(); ?>
    </th>
    </tr>
				<tr>
   					<th style="text-align:center;"><?php echo JText::_('No'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Fund prize plan'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Selected Prize value'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Selected Prize'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol set'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol pieces'); ?></th>
                    <th style="text-align:center;"><?php echo JText::_('Fund receiver plan'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('User'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Prize status'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Value funded'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Shortfall'); ?></th>
                    <th style="text-align:center;"><?php echo JText::_('% funded'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Extracted piece inserted into symbol queue'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol queue number for extracted piece'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Prize status history'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol set status'); ?></th>
				</tr>
			</thead>                
			<?php 
			$i=1;
			foreach ($this->result as $row):
						                $valueFunded = $row->amount;//0;
										$prizevalue = $row->prize_value; 										
		                                $shortfall = $prizevalue - $valueFunded; 
		                                $rate = round(($valueFunded/$prizevalue),2);
										$inserted = $valueFunded/$prizevalue;
										$extrapieces = $row->extra_form;//JRequest::getVar('ep'); 
										$insert2 = ( $inserted == 100 ? $extrapieces : 0) ;
										$userid = $row->useraccount_id;
										$UserSymbol = $this->model->getSymbolPrizeUser($userid);
										?>
        <tbody>
        <tr>
					<td style="text-align:center;"><?php echo $i; ?></td>
					<td style="text-align:center;">
					<?php echo JText::_('$' . number_format($row->funding_value_from, 0, '.', '')); ?> - 
                    <?php echo JText::_('$' . number_format($row->funding_value_to, 0, '.', '')); ?>
                    </td>	                   				
					<td style="text-align:center;">
					<?php echo JText::_('$' . number_format($row->prize_value, 0, '.', '')); ?>
                    </td>
					<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
					<?php echo JText::_(number_format($row->pieces, 0, '.', '')); ?>
                    </td>
					<td style="text-align:center;">
                    <a target="_blank" href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.fundReceiverList&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo JText::_($row->title); ?>
                    </a>
                    </td>	                   				
					<td class="hidden-phone"><?php echo JText::_($row->firstname.' '.$row->lastname); ?></td>
					<td class="hidden-phone"><?php if ($row->status == '1')
					{echo 'UnLocked';
					}else{
					echo 'Locked';
					}
					 ?></td>
					<td class="hidden-phone"><?php echo JText::_(number_format($valueFunded, 2, '.', '')); ?></td>
                    <td class="hidden-phone"><?php echo JText::_('$' . number_format($shortfall, 2, '.', '')); ?></td>
					<td class="hidden-phone"><?php echo JText::_(number_format($rate, 2, '.', '') . '%'); ?></td>
                    <td style="text-align:center;"><?php echo $insert2; ?></td>
                    <td style="text-align:center;"><?php echo $i; ?></td>
                    <td style="text-align:center;">
                    <a target="_blank" href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.prizeStatusHistory&prize=<?php echo $row->prize_name;?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo 'view'; ?>
                    </a>
                    </td>	
					<td style="text-align:center;"><a target="_blank" href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.symbolSetStatus&prize=<?php echo $row->prize_name;?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo $UserSymbol.'/'.$row->pieces; ?>
                     </a>
                     </td>	
				</tr>
                </tbody>
				<?php 
				$i=$i+1;
				endforeach; ?>
                <tr><td colspan="16" style="text-align:right;">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
                                   
    </tr>
			
		</table>
		
</form>
</div>
</div>

