<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
$document = &JFactory::getDocument();
$document->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.min.js');
$document->addScript(JURI::base().'components/com_awardpackage/assets/js/jquery.miniColors.js');
$document->addStyleSheet(JURI::base().'components/com_awardpackage/assets/css/jquery.miniColors.css');
$document->addScript(JURI::base().'components/com_awardpackage/assets/js/cp.js' );
?>
<?php
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.DS.'shared'.DS.'button.php');
?>
<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=category&task=category&package_id='.JRequest::getVar('package_id'));?>"
	name="adminForm" id="adminForm"><input type="hidden" id="makeReadonly"
	value="Read-only" /> <input type="hidden" id="unmakeReadonly"
	value="Not Read-only" disabled="disabled" /><br />
<input type="hidden" id="disable" value="Disable" /> <input
	type="hidden" id="enable" value="Enable" disabled="disabled" /><br />
<input type="hidden" id="destroy" value="Destroy" /> <input
	type="hidden" id="create" value="Create" disabled="disabled" /><br />
<input type="hidden" id="randomize" value="Random Color" />
<table align="center" border="0" class="table table-striped" width="100%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th align="center"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->items); ?>);" /></th> -->
			<th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>	
			<th align="center">#</th>
			<th><?php echo JText::_('COM_GIFT_CODE_COLOR_CODE');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_COLOR_NAME');?></th>
			<th><?php echo JText::_('COM_GIFT_CODE_PUBLISHED');?></th>
		</tr>
	</thead>
	<?php foreach($this->items as $i => $item):
	if($item->unlocked==0){
		$this->readonly = ' disabled';
	}
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td align="center">
		<input type="hidden"
			value="<?php echo $item->setting_id;?>" name="setting_id[]">		 
		<?php echo JHtml::_('grid.id', $i, $item->setting_id); ?></td>			
		<td width="60px" align="center" height="40px">
		<table>
			<tr>
				<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $item->colour_code;?>" valign="center">
				<font color="white" size="5"><b><?php echo $item->category_id; ?></b></font>
				</td>
			</tr>
		</table>
		</td>
		<td align="center">			 
			<?php if($item->unlocked==1){?>
				<input class="color-picker" type="text"
						style="text-align: center" value="<?php echo $item->colour_code;?>"
						name="colour_code[]">				
			<?php } else {?>
				<input class="color-picker" type="text"
						style="text-align: center" value="<?php echo $item->colour_code;?>"
						name="colour" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
				<input type="hidden" name="colour_code[]"
						value="<?php echo $item->colour_code;?>"> 
			<?php }?>
		</td>
		<td align="center">			
			<input type="text"
			style="text-align: center" value="<?php echo $item->category_name;?>"
			name="category_name[]" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
		</td>
		<td align="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, '', 1,'cb'); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<div><input type="hidden" id="controller" name="controller"
	value="category" /> <input type="hidden" id="package_id"
	name="package_id" value="<?php echo JRequest::getVar('package_id');?>" />
<input type="hidden" id="task" name="task" value="" /> <input
	type="hidden" id="boxchecked" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
