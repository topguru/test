<?php
//resdirect
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');
?>
<div id="j-main-container" class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<table class="table table-striped" width="100%" cellspacing="1" cellpadding="1">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('Selected User');?></th>
			<th><?php echo JText::_('Symbol Queue');?></th>
			<th><?php echo JText::_('Symbol Pieces');?></th>
			<th><?php echo JText::_('Shuffled');?></th>
			<th><?php echo JText::_('Prize Value Range');?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->users as $i=>$user){?>
		<tr class="row<?php echo $i;?>">
			<td><a href="#"><?php echo $user->firstname;?>&nbsp;<?php echo $user->lastname;?></a></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userrecord&layout=symbol_queue&package_id='.$this->package_id.'&user_id='.$user->id.'&presentation_id='.$this->presentation_id);?>&process_id=<?php echo $this->process_id;?>">View</a>
			</td>
			<td align="center"><?php echo count($this->getUserPieces($user->id));?></td>
			<td align="center"><?php echo $this->loadProcess()->shuffle_from.' To '.$this->loadProcess()->shuffle_to;?>
			Times</td>
			<td align="center"><?php echo '$'.$this->loadProcess()->prize_value_from;?>
			To <?php echo $this->loadProcess()->prize_value_to;?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div><input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="symbolqueue" /></div>
</form>
</div>
