<?php
// load tooltip behavior
JHtml::_('behavior.tooltip');

//include button
include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.
DS.'shared'.DS.'button.php');
?>
<div id="j-main-container" class="span10">
<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=giftcode&package_id='.JRequest::getVar('package_id'));?>"
	name="adminForm" id="adminForm">
<table align="center" border="0" class="table table-striped" width="100%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th width="1%"><?php $count_id = count($this->giftcodes)+1;?> <input
				type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo $count_id; ?>);" /></th> -->			
			<!-- <th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?> -->
			<!-- <th>#</th> -->
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_AWARD_SYMBOL_PER_GIFTCODE');?></th>
			<th><?php echo JText::_('Published');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach($this->giftcodes as $giftcode)
	{
		$i++;
		?>
		<tr class="row<?php echo $i%2;?>">
			<td width="60px" align="center" height="40px">
				<table>
					<tr>
						<td style="padding-top:14px;width:40px;height:30px;text-align:center;background-color:<?php echo $giftcode->color_code ?>;" valign="center">
						<font color="white" size="5"><b><?php echo $giftcode->category_id; ?></b></font>
						</td>
					</tr>
				</table>
			</td>
		
			<font color="white" size="6"><b><?php echo $giftcode->category_id; ?></b></font>
			</td>
			<td align="center"><?php echo $giftcode->name; ?></td>
						<td align="center">	
						<?php 
						//$awardSymbolTotal = $this->model->getAwardSymbol($category->setting_id);
						/* Above code changed to below code by Sushil on 30-11-2015 */
						$awardSymbolTotal = $this->model->getAwardSymbol($giftcode->category_id);
						?>
						<!-- 	
						<input type="text"
						style="text-align: center;width:80px" value="<?php echo $awardSymbolTotal->total;?>"
						name="awardsymbol_pergift" <?php echo ($item->unlocked==0 ? "readonly=readonly" : "")?>>
						 -->
						 <?php echo $giftcode->category_id; ?>
			</td>
			<td align="center"><?php echo JHtml::_('jgrid.published', $giftcode->published, $i, '', 1,'cb'); ?>
			</td>
			
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<div><input type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="giftcode"> <input type="hidden"
	name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?></div>
</form>
</div>
