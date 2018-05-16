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
			<th></th>
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
	<?php foreach($this->categories as $i => $item):
	if($item->unlocked==0){
		$this->readonly = ' disabled';
	}
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td align="center">
		<input type="hidden"
			value="<?php echo $item->setting_id;?>" name="setting_id[]">	
		<input type="hidden" value="<?php echo $item->category_id;?>" name="category_id[]">	 
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
						style="text-align:center;width:80px;" value="<?php echo $item->colour_code;?>"
						name="colour_code[]">				
			<?php } else {?>
				<input class="color-picker" type="text"
						style="text-align: center;width:80px" value="<?php echo $item->colour_code;?>"
						name="colour" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
				<input type="hidden" name="colour_code[]"
						value="<?php echo $item->colour_code;?>"> 
			<?php }?>
		</td>
		<td align="center">			
			<input type="text"
			style="text-align: center;width:80px" value="<?php echo $item->category_name;?>"
			name="category_name[]" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
		</td>
		<td align="center">			
			<a
				href="<?php 
                      $task = count($this->packageuser_model->checking_award_schedule($item->category_id, JRequest::getVar("package_id"))) != 0 ? "&task=edit" : "";
                      echo JRoute::_("index.php?option=com_awardpackage&view=giftcode&layout=send_schedule&category_id=$item->category_id&package_id=".JRequest::getVar("package_id")."$task");
                      ?>"> <?php
                      echo count($this->packageuser_model->checking_award_schedule($item->category_id, JRequest::getVar("package_id"))) != 0 ? "Edit" : "New";
                      ?> </a>
		</td>
		<td align="center">			
			<?php 
			//$giftcodeTotal = $this->model->getGiftCode($category->setting_id);
			?>
			<!-- 
			<input type="text"
			style="text-align: center;width:80px" value="<?php echo $giftcodeTotal->total;?>"
			name="giftcode_quantity" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
			 -->
			 <?php // echo $giftcodeTotal->total;
			 ?>
			 <?php if($item->unlocked ==1 ){?>
			 	<input type="text"
					style="text-align: center;width:80px" value="<?php echo $item->giftcode_quantity;?>"
					name="giftcode_quantity[]" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
			 <?php } else { ?>
			 	<input type="text"
					style="text-align: center;width:80px" value="<?php echo $item->giftcode_quantity;?>"
					name="giftcode_quantity[]" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
			 <?php } ?>
		</td>
		<td align="center">			
			<?php echo JText::_('Unique Symbol');?>
		</td>
		<td align="center">	
			<?php 
			//$awardSymbolTotal = $this->model->getAwardSymbol($category->setting_id);
			?>	
			<!-- 	
			<input type="text"
			style="text-align: center;width:80px" value="<?php echo $awardSymbolTotal->total;?>"
			name="awardsymbol_pergift" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
			 -->
			 <?php //echo $awardSymbolTotal->total;
			 ?>
			 <?php echo $item->category_id; ?>
		</td>
		<td><?php
			$totalUser = $this->packageuser_model->CheckResult(JRequest::getVar('package_id'),$item->category_id);
			if($totalUser>0){
				$label = 'Edit';
			}else{
				$label = 'New';
			}
			?> <a
				href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=packageuser&package_id=".JRequest::getVar("package_id")."&category_id=".$item->category_id) ?>"><?php echo $label;?></a>
			</td>
			<td><a
				href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id=".JRequest::getVar('package_id')."&category_id=".$item->category_id) ?>">Edit</a>
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
