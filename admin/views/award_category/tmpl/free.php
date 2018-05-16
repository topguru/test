<style>
th,td {
	text-align: center;
}
</style>
<?php
defined('_JEXEC') or die;
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.
DS.'shared'.DS.'button.php');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=award_category&package_id='.JRequest::getVar("package_id"));?>"
	name="adminForm" id="adminForm">
	
	<input type="hidden" id="makeReadonly"
	value="Read-only" /> <input type="hidden" id="unmakeReadonly"
	value="Not Read-only" disabled="disabled" /><br />
<input type="hidden" id="disable" value="Disable" /> <input
	type="hidden" id="enable" value="Enable" disabled="disabled" /><br />
<input type="hidden" id="destroy" value="Destroy" /> <input
	type="hidden" id="create" value="Create" disabled="disabled" /><br />
<input type="hidden" id="randomize" value="Random Color" />
<div style="float: right;">
<button type="button"
	onclick="location.href='index.php?option=com_awardpackage&view=packageuser&layout=email_history&package_id=<?php echo $this->package_id;?>'">
<?php echo JText::_('Email History');?></button>
</div>
<br />
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th width="1"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->categories); ?>);" /></th> -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>			
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SCHEDULE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_GIFTCODE_QUANTITY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_TYPE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_PER_GIFTCODE');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_PACKAGE_USERS');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_NON_AWARD_PACKAGE_USERS');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_PUBLISH');?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->categories as $i=> $category) { ?>
		<tr class="row<?php echo $i % 2;?>">
			<td width="1"><?php echo JHtml::_('grid.id', $i, $category->setting_id); ?></td>
			<td width="60px" align="center" height="40px">
				<table>
					<tr>
						<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $category->colour_code;?>" valign="center">
						<font color="white" size="5"><b><?php echo $category->category_id; ?></b></font>
						</td>
					</tr>
				</table>
			</td>
			<td><?php echo $category->category_name ?></td>
			<td><a
				href="<?php 
                      $task = count($this->packageuser_model->checking_award_schedule($category->category_id, JRequest::getVar("package_id"))) != 0 ? "&task=edit" : "";
                      echo JRoute::_("index.php?option=com_awardpackage&view=giftcode&layout=send_schedule&category_id=$category->category_id&package_id=".JRequest::getVar("package_id")."$task");
                      ?>"> <?php
                      echo count($this->packageuser_model->checking_award_schedule($category->category_id, JRequest::getVar("package_id"))) != 0 ? "Edit" : "New";
                      ?> </a></td>
			<td align="center"><?php 
			//$giftcodeTotal = $this->model->getGiftCode($category->setting_id);
			//echo $giftcodeTotal->total;
			echo $category->giftcode_quantity;
			?></td>
			<td><?php echo JText::_('Unique Symbol');?></td>
			<td><?php 
			//$awardSymbolTotal = $this->model->getAwardSymbol($category->setting_id);
			//echo $awardSymbolTotal->total;
			echo $category->category_id;
			?></td>
			<td><?php
			$totalUser = $this->packageuser_model->CheckResult(JRequest::getVar('package_id'),$category->category_id);
			if($totalUser>0){
				$label = 'Edit';
			}else{
				$label = 'New';
			}
			?> <a
				href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=packageuser&package_id=".JRequest::getVar("package_id")."&category_id=".$category->category_id)."&act=awardpackageuser" ?>"><?php echo $label;?></a>
			</td>
			<td><a
				href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id=".JRequest::getVar('package_id')."&category_id=".$category->category_id . "&act=nonawardpackageuser") ?>">Edit</a>
			</td>
			<td><?php echo JHtml::_('jgrid.published', $category->published, $i, '', 1,'cb'); ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<input type="hidden" id="controller" name="controller"
	value="category" /> <input type="hidden" id="package_id"
	name="package_id" value="<?php echo JRequest::getVar('package_id');?>" />
	<input type="hidden" name="modul" value="free"/>
<input type="hidden" id="task" name="task" value="" /> <input
	type="hidden" id="boxchecked" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
 </form>
</div>
