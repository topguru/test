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
<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Selected presentation</a></li>
                              <li class="active"><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundPrizePlan&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund prize plan - selected prizes</a></li>
	                          <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundReceiverList&process_id=".JRequest::getVar('process_id')."&package_id=".JRequest::getVar('package_id')."");?>">Fund receiver list queue</a></li>    	
                              </ul>
                              </nav>	
                              
<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>"
	method="post" name="adminForm">
	<input type="hidden" name="task" id="task" value="aprocesspresentation.fundPrizeHistory" />
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">	

					<table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                <td colspan="15" style="text-align:right;">                                    
    <?php echo $this->pagination->getLimitBox(); ?>
    </td>
    </tr>
				<tr>
   					<th style="text-align:center;"><?php echo JText::_('No'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Fund prize plan'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Selected Prize value'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Selected Prize'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol set'); ?></th>
					<th style="text-align:center;"><?php echo JText::_('Symbol pieces'); ?></th>                    
                    <th style="text-align:center;"><?php echo JText::_('Fund receiver plan'); ?></th>
				</tr>
	</thead>                
			<?php 
			$i=1;
			foreach ($this->result as $row):
				
		?>	
		<tbody>
        <tr>
					<td style="text-align:center;">
					<?php echo $i; ?></td>
					<td style="text-align:center;">
					<?php echo JText::_('$' . number_format($row->funding_value_from, 0, '.', '')); ?> - 
                    <?php echo JText::_('$' . number_format($row->funding_value_to, 0, '.', '')); ?>
                    </td>	                   				
					<td style="text-align:center;">
					<?php echo JText::_('$' . number_format($row->prize_value, 0, '.', '')); ?></td>
					<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
                    <img src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image;?>?>" style="width:100px;" />
                    </td>
					<td style="text-align:center;">
					                    <?php echo JText::_(number_format($row->pieces, 0, '.', '')); ?>
                    </td>
                    
					<td style="text-align:center;"><a target="_blank" href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.fundReceiverList&package_id=<?php echo JRequest::getVar('package_id'); ?>">
										<?php echo JText::_(number_format($row->fund_prize_plan, 0, '.', '')); ?>
                                        </a></td>	                   				

				</tr>
                </tbody>
				<?php 
				$i=$i+1;
                				endforeach; ?>

                <tr><td colspan="15" style="text-align:right;">                                    
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

