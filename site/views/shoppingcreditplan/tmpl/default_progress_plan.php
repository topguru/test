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
								<td align="left" width="80%">
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
								<td align="right">
									<button>Graph</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<br/>
				<div class="clearfix">
					<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th width="20%"><?php echo JText::_( 'Progress Check' ); ?></th>
									<th width="20%"><?php echo JText::_( 'Total Checks' ); ?></th>																	
									<th width="20%"><?php echo JText::_( 'Already Checked' ); ?></th>
									<th width="20%"><?php echo JText::_( 'Not Checked' ); ?></th>
									<th width="20%"><?php echo JText::_( 'Benefits Awarded' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php for($i = 0; $i < $this->progress_check_every; $i++) { ?>
								<?php 
										$total_checks =  floor((int) $this->selisih / ($i+1));
										$already_checked = floor($total_checks / 2);
										$not_checked = $total_checks - $already_checked;										
								?>									
								<tr>
									<td><?php echo JText::_( ($i+1) ); ?>&nbsp;<?php echo JText::_( $this->progress_check_type ); ?></td>
									<td><?php echo JText::_( $total_checks ); ?> </td>									
									<td><?php echo JText::_( $already_checked ); ?> </td>
									<td><?php echo JText::_( $not_checked ); ?> </td>
									<td><?php echo JText::_( '0' ); ?> </td>
								</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>					