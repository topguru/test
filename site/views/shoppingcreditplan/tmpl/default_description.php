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
									<span style="font-weight:bold; font-size:18px;"><?php echo JText::_('Shopping Credit Account - Descriptions');?></span>
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td valign="top">
									<div style="width:auto;height:auto;border:1px solid #000;">
										<?php echo JText::_('Total shopping credits earned: 100');?>
										<br />
										<?php echo JText::_('Total shopping credits unlocked: 50');?>
										<br />
										<?php echo JText::_('Total shopping credits spent: 40');?>
										<br />
										<?php echo JText::_('Total shopping credits expired: 10');?>
									</div>
								</td>
								<td valign="top">
									&nbsp;
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
									<th><?php echo JText::_( 'Shopping Credit'); ?></th>									
									<th><?php echo JText::_( 'Awarded On'); ?></th>
									<th><?php echo JText::_( 'Contribution Range'); ?></th>
									<th><?php echo JText::_( 'Progress Check'); ?></th>
									<th><?php echo JText::_( 'Unlocked On'); ?></th>
									<th><?php echo JText::_( 'Days to Unlock'); ?></th>
									<th><?php echo JText::_( 'Expired On'); ?></th>
									<th><?php echo JText::_( 'Days to Expire'); ?></th>
									<th><?php echo JText::_( 'Status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo JText::_( '25'); ?></td>
									<td><?php echo JText::_( '25-07-2014'); ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo JText::_( '25-07-2015'); ?></td>
									<td><?php echo JText::_( '3'); ?></td>
									<td><?php echo JText::_( '25-07-2016'); ?></td>
									<td><?php echo JText::_( '2'); ?></td>
									<td><?php echo JText::_( 'Locked'); ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_( '10'); ?></td>
									<td><?php echo JText::_( '25-07-2013'); ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo JText::_( '25-08-2013'); ?></td>
									<td><?php echo JText::_( ''); ?></td>
									<td><?php echo JText::_( '25-08-2013'); ?></td>
									<td><?php echo JText::_( ''); ?></td>
									<td><?php echo JText::_( 'Expired'); ?></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
