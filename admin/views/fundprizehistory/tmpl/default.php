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
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizehistory')?>" method="post">
	<input type="hidden" name="task" id="task" value="fundprizehistory">
	<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
	<table width="100%">
		<tr>
			<td colspan="3">
				<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
					<thead>
						<tr style="text-decoration:underline;">															
							<th width="30%"><?php echo JText::_( 'Presentation Group' ); ?></th>
							<th width="30%"><?php echo JText::_( 'Presentation Users' ); ?></th>																																				
							<th><?php echo JText::_( '% of each $0.01 from all user groups to fund prize' ); ?></th>							
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</tbody>													
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"><hr/></td>
		</tr>	
		<tr>
			<td colspan="3">
				<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
					<thead>
						<tr style="text-decoration:underline;">
							<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>															
							<th width="15%"><?php echo JText::_( 'Prize Value' ); ?></th>
							<th><?php echo JText::_( '% of each $0.01 from all user groups to fund prize' ); ?></th>
							<th width="15%"><?php echo JText::_( 'Value funded' ); ?></th>							
							<th width="15%"><?php echo JText::_( 'Shortfall' ); ?></th>
							<th width="15%"><?php echo JText::_( '% funded' ); ?></th>
							<th width="15%"><?php echo JText::_( 'Prize status' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
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
			</td>			
		</tr>
	</table>
</form>