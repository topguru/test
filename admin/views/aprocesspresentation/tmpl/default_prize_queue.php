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
<div
	class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
<div class="row-fluid">
<h2>Prize Value <?php echo '$'.$this->prize_value; ?></h2>
<form id="adminForm"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>"
	method="post" name="adminForm"><input type="hidden" name="package_id"
	value="<?php echo  JRequest::getVar("package_id"); ?>" /> <input
	type="hidden" name="index"
	value="<?php echo  JRequest::getVar("index"); ?>" /> <input
	type="hidden" name="idUserGroupsId"
	value="<?php echo  JRequest::getVar("idUserGroupsId"); ?>" /> <input
	type="hidden" name="position"
	value="<?php echo  JRequest::getVar("position"); ?>" /> <input
	type="hidden" name="var_id"
	value="<?php echo  JRequest::getVar("var_id"); ?>" /> <input
	type="hidden" name="selectedPresentation"
	value="<?php echo  JRequest::getVar("selectedPresentation"); ?>" /> <input
	type="hidden" name="task" id="task"
	value="aprocesspresentation.onPrizeQueue" />
	<input type="hidden" name="presentation_id" value="<?php echo JRequest::getVar('presentation_id'); ?>">
	<input type="hidden" name="processPresentation" value="<?php echo JRequest::getVar('processPresentation'); ?>">
    <input type="hidden" name="process_id" id="process_id" value="<?php echo JRequest::getVar('process_id'); ?>">
    

<table>
	<tr>
		<td width="20%" valign="top"
			style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
			<div class="span3" >
				<table width="100%">
					<tr>
						<td width="100%" valign="top">
							<div class="control-group">
								<label class="control-label">
									<?php echo JText::_('Criteria for setting Distribute prize queue');?>:
								<!-- 
								<label class="control-label">
									<?php echo JText::_('Prize Status');?>:										
								</label>								
								<div class="controls">
									<select name="prizeStatus" onchange="onSelectPrizeStatus();">
										<option value="locked" <?php echo (!empty($this->prizeStatus) && 'locked' == $this->prizeStatus ? "selected=selected" : ""); ?> >Locked</option>
										<option value="unlocked" <?php echo (!empty($this->prizeStatus) && 'unlocked' == $this->prizeStatus ? "selected=selected" : ""); ?> >Unlocked</option>
										<option value="won" <?php echo (!empty($this->prizeStatus) && 'won' == $this->prizeStatus ? "selected=selected" : ""); ?> >Won</option>
									</select>
								</div>
								 -->
							</div>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td width="2%" style="">&nbsp;</td>
		<td valign="top">
		<div class="span12">
		<table class="table table-hover table-striped">
			<thead>
				<tr>
					<th valign="top"><?php echo JText::_('Distribute prize queue number'); ?></th>
					<th valign="top"><?php echo JText::_('Distribute prize queue history'); ?></th>
					<th valign="top"><?php echo JText::_('User'); ?></th>
					<th valign="top"><?php echo JText::_('Prize status'); ?></th>
					<th valign="top"><?php echo JText::_('Extracted pieces inserted into symbol queue'); ?></th>
					<th valign="top"><?php echo JText::_('Symbol queue number for extracted pieces'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->rows as $row):?>
				<tr>
					<td class="hidden-phone"><?php echo JText::_($row->number); ?></td>
					<td class="hidden-phone">
						<a href="index.php?option=com_awardpackage&view=aprocesspresentation&
							task=aprocesspresentation.onDistributionPrizeQueueHistory&package_id=<?php echo JRequest::getVar('package_id'); ?>">View</a></td>
					<td class="hidden-phone"><?php echo JText::_($row->users); ?></td>
					<td class="hidden-phone"><span style="color:blue;text-decoration:underline;"><?php echo JText::_($row->status); ?></span></td>
					<td class="hidden-phone">
						<img
							src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $row->einserted; ?>?>"
							style="width:50px;" />
					</td>
					<td class="hidden-phone"><?php echo JText::_($row->symbolq); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="11"><?php echo (!empty($this->pagination) ? $this->pagination->getListFooter() : '') ?></td>
				</tr>
			</tfoot>
		</table>
		</div>
		</td>
	</tr>
</table>
</form>
</div>
</div>
</div>

