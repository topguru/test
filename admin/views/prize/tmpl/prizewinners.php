<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JToolBarHelper::title('Prize Winners');
JRequest::setVar( 'hidemainmenu', 1 );
JToolBarHelper::cancel('prizewinnerclose','Cancel');
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&layout=prizewinners');?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('Prize Name');?></th>
			<th><?php echo JText::_('Prize Value');?></th>
			<th><?php echo JText::_('Winner');?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->prizewinners as $i=>$winner){?>
		<tr class="row<?php echo $i;?>">
			<td align="center"><?php echo $winner->prize_name;?></td>
			<td align="center">$<?php echo $winner->prize_value;?></td>
			<td align="center"><?php echo $winner->username;?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="create" /> <input type="hidden" name="package_id"
	value="<?php echo $this->package_id;?>"> <input type="hidden"
	name="controller" value="prize" /></form>
</div>
