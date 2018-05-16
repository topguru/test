<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');
?>
<div id="cj-wrapper"
	style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
	<div
		class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<div class="clearfix">
					<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
						<table width="100%">
							<tr>
								<td align="left" colspan="2">
									<span style="font-weight:bold; font-size:18px;"><?php echo JText::_('Progress Check:');?></span>
								</td>
							</tr>
							<tr>
								<td align="left" width="83%">
									<span style="font-size:12px;"><?php echo JText::_('From:');?>
									&nbsp;
									<?php echo $this->start_date; ?>
									&nbsp;
									<?php echo JText::_('To:');?> 
									&nbsp;
									<?php echo $this->end_date; ?>
									&nbsp;&nbsp;&nbsp;
									<?php echo JText::_('Contribution Range:'); ?>
									&nbsp;
									<?php echo $this->contribution_range_value; ?>
									</span>
								</td>
								<td align="left">
									<table>
										<tr>
											<td>
												<div style="width:20px;height:20px;border:1px solid #000;background-color:red"></div>
											</td>
											<td>
												<?php echo JText::_('Checked'); ?>
											</td>
										</tr>
									</table>					
								</td>								
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<table>
										<tr>
											<td>
												<div style="width:20px;height:20px;border:1px solid #000;background-color:yellow"></div>
											</td>
											<td>
												<?php echo JText::_('Not Checked'); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<table>
										<tr>
											<td>
												<div style="width:20px;height:20px;border:1px solid #000;background-color:green"></div>
											</td>
											<td>
												<?php echo JText::_('Benefits Awarded'); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<?php for($i = 0; $i < $this->progress_check_every; $i++) { ?>
				<?php
						$last_date = null;
						$total_checks =  floor((int) $this->selisih / ($i+1)); 
						$already_checked = floor($total_checks / 2);
						$not_checked = $total_checks - $already_checked;
				?>
				<br/>
				<div class="clearfix">
					<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th colspan="<?php echo $total_checks; ?>"><?php echo JText::_( 'Progress Check : ' . ($i+1) . ' ' . $this->progress_check_type ); ?></th>									
								</tr>
								<tr>
									<?php for($j = 0; $j < $already_checked; $j++) { ?>	
									<?php 											
											$date = strtotime("+".($j + 1)." days", $this->start);											
    										$progress_date =  date("Y-m-d", $date);
    										$last_date = $date;
    								?>
    								<th><?php echo JText::_( $progress_date);  ?></th>
									<?php } ?>
									<?php for($j = 0; $j < $not_checked; $j++) { ?>	
									<?php 	
											$date = strtotime("+".($j + 1)." days", $last_date);
    										$progress_date =  date("Y-m-d", $date);
    								?>
    								<th><?php echo JText::_( $progress_date);  ?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php for($j = 0; $j < $already_checked; $j++) { ?>	
									<?php 											
											$date = strtotime("+".($j + 1)." days", $this->start);											
    										$progress_date =  date("Y-m-d", $date);
    										$last_date = $date;
    								?>
    								<td style="background-color:red;"><?php echo JText::_( '$0' );  ?></td>
									<?php } ?>
									<?php for($j = 0; $j < $not_checked; $j++) { ?>	
									<?php 	
											$date = strtotime("+".($j + 1)." days", $last_date);
    										$progress_date =  date("Y-m-d", $date);
    								?>
    								<td style="background-color:yellow;"><?php echo JText::_( '$0' );  ?></td>
									<?php } ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php } ?>				
			</div>
		</div>
	</div>
</div>