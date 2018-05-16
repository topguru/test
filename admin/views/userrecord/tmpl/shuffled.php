<?php
//print_r("WTF");exit();
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<div id="j-main-container" class="span10">
<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('Prize Value Range');?></th>
			<th><?php echo JText::_('Extra Pieces');?></th>
			<th><?php echo JText::_('Clone Pieces');?></th>
			<th><?php echo JText::_('Prize With Extracted Pieces');?></th>
			<th><?php echo JText::_('Prize With Cloned Pieces');?></th>
			<th><?php echo JText::_('Helper Queue');?></th>
			<th><?php echo JText::_('Shuffle all symbol pieces of all prize value in helper queue befor insert into each symbol queue');?></th>
			<th><?php echo JText::_('User Symbol queue List');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=prizerange&package_id='.$this->package_id);?>&tmpl=component"
				class="modal" rel="{size: {x: 700, y: 500}, handler:'iframe'}">$10
			to $15</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=extract&package_id='.$this->package_id);?>&tmpl=component"
				class="modal" rel="{size: {x: 700, y: 500}, handler:'iframe'}">1 to
			3</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=clone&package_id='.$this->package_id);?>&tmpl=component"
				class="modal" rel="{size: {x: 700, y: 500}, handler:'iframe'}">New</a>
			</td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=prize_extracted&package_id='.$this->package_id);?>">23
			Prizes</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=prize_cloned&package_id='.$this->package_id);?>">23
			Prizes</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=queuehelper&package_id='.$this->package_id);?>">23
			Prizes</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=shuffle&package_id='.$this->package_id);?>&tmpl=component"
				class="modal" rel="{size: {x: 700, y: 500}, handler:'iframe'}">New</a>
			</td>
		</tr>
	</tbody>
</table>
</div>
