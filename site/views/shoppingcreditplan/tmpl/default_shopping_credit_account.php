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
								<td align="left">
									<span style="font-weight:bold; font-size:18px;"><?php echo JText::_('Shopping Credit Account');?></span>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<br />
				<div class="clearfix">
					<div class="span12" style="border: 1px solid #ccc; padding: 10px;left:0px;height:auto;width:auto;">
						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th width="20%"><?php echo JText::_( 'Date'); ?></th>									
									<th width="20%"><?php echo JText::_( 'Description'); ?></th>
									<th width="20%"><?php echo JText::_( 'Amount'); ?></th>
									<th width="20%"><?php echo JText::_( 'Total (excl locked)'); ?></th>
									<th width="20%"><?php echo JText::_( 'Total (incl locked)'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo JText::_( '20-07-2014'); ?></td>
									<td><?php echo JText::_( 'Shopping credits - earned and locked'); ?></td>
									<td><?php echo JText::_( '+$40'); ?></td>
									<td><?php echo JText::_( '$0'); ?></td>
									<td><?php echo JText::_( '+$40'); ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_( '22-07-2014'); ?></td>
									<td><?php echo JText::_( 'Shopping credits - earned and unlocked'); ?></td>
									<td><?php echo JText::_( '+$10'); ?></td>
									<td><?php echo JText::_( '+$10'); ?></td>
									<td><?php echo JText::_( '+$50'); ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_( '22-07-2014'); ?></td>
									<td><?php echo JText::_( 'Shopping credits - expired'); ?></td>
									<td><?php echo JText::_( '-$5'); ?></td>
									<td><?php echo JText::_( '+$5'); ?></td>
									<td><?php echo JText::_( '+$45'); ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_( '30-07-2014'); ?></td>
									<td><?php echo JText::_( 'Shopping credits - spent - purchased goods from amazon'); ?></td>
									<td><?php echo JText::_( '-$10'); ?></td>
									<td><?php echo JText::_( '-$5'); ?></td>
									<td><?php echo JText::_( '+$35'); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>