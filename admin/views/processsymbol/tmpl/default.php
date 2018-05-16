<?php
//print_r("WTF");exit();
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<div id="j-main-container" class="span10">
<form action="index.php" method="post" enctype="multipart/form-data"
	name="adminForm" id="adminForm">
<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th><input type="checkbox" /></th> -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			<th width="150"><?php echo JText::_('Prize Value Range');?></th>
			<th><?php echo JText::_('Extract Pieces');?></th>
			<th><?php echo JText::_('Clone Pieces');?></th>
			<th><?php echo JText::_('Prize With Extracted Pieces');?></th>
			<th><?php echo JText::_('Prize With Cloned Pieces');?></th>
			<th><?php echo JText::_('Helper Queue');?></th>
			<th><?php echo JText::_('Shuffle all symbol pieces of all prize value in helper queue befor insert into each symbol queue');?></th>
			<th><?php echo JText::_('User Symbol queue List');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($this->items as $i=>$item){
		?>
		<tr class="row<?php echo $i;?>">
			<td><input type="checkbox" /></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=prizerange&package_id='.$this->package_id .'&prize_value='.$this->presentation->prize_value);?>&tmpl=component&process_id=<?php echo $item->id;?>&presentation_id=<?php echo $this->presentation_id;?>"
				class="modal" rel="{size: {x: 600, y: 400}, handler:'iframe'}"> <?php if(!$item->prize_value_from){?>
			New <?php }else {?> <?php echo '$'.number_format($item->prize_value_from,2);?>
			to <?php echo '$'.number_format($item->prize_value_to,2);?> <?php }?>
			</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=extract&package_id='.$this->package_id .'&pieces='. $this->presentation->pieces);?>&tmpl=component&process_id=<?php echo $item->id;?>&presentation_id=<?php echo $this->presentation_id;?>"
				class="modal" rel="{size: {x: 600, y: 400}, handler:'iframe'}"> <?php if(!$item->extra_from){?>
			New <?php }else {?> <?php echo $item->extra_from;?> To <?php echo $item->extra_to;?>
			<?php }?> </a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=clone&package_id='.$this->package_id);?>&tmpl=component&process_id=<?php echo $item->id;?>&presentation_id=<?php echo $this->presentation_id;?>"
				class="modal" rel="{size: {x: 600, y: 400}, handler:'iframe'}"> <?php if(!$item->clone_from){?>
			New <?php }else{?> <?php echo $item->clone_from;?> To <?php echo $item->clone_to;?>
			<?php }?> </a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&task=userrecord.prize_extracted&layout=prize_extracted&package_id='.$this->package_id) . '&presentation_id='.$this->presentation_id.'&process_id='.$item->id ;?>"><?php echo count($this->getExtract($item->id));?>
			Prizes</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&task=userrecord.prize_cloned&layout=prize_cloned&package_id='.$this->package_id);?>&presentation_id=<?php echo $this->presentation_id;?>&process_id=<?php echo $item->id;?>"><?php echo count($this->getCloned($item->id));?>
			Prizes</a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&task=userrecord.helper_queue&layout=helper_queue&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$item->id);?>">View</a>
			</td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=shuffle&package_id='.$this->package_id);?>&tmpl=component&presentation_id=<?php echo $this->presentation_id;?>&process_id=<?php echo $item->id;?>"
				class="modal" rel="{size: {x: 600, y: 400}, handler:'iframe'}"> <?php 
				if($item->shuffle_from){
					echo $item->shuffle_from.' To '.$item->shuffle_to;
				}else{
					echo 'New';
				}
				?> </a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&task=userrecord.user_symbol&layout=user_symbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$item->id);?>">View
			List</a></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="package_id" value="<?php echo $this->package_id;?>" />
<input type="hidden" name="presentation_id"
	value="<?php echo $this->presentation_id;?>" /> <input type="hidden"
	id="task" name="task" value="" /></form>
</div>
