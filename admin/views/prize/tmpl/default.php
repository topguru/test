<?php
//restricted
defined('_JEXEC') or die('Restricted access');

//behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>
<div id="j-main-container" class="span10">
<form action="index.php?option=com_awardpackage&view=prize" method="post" name="adminForm" id="adminForm">
						<table class="table table-hover table-striped table-bordered">
	<thead>
    <tr><td colspan="4" style="text-align:right;">                                    
                                   
                                    <?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- <th style="width: 4%;"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th> -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th style="width: 30%;"><?php echo JText::_('Prize Image');?></th>
			<th style="width: 40%;"><?php echo JText::_('Prize Detail');?></th>
			<th style="width: 17%;"><?php echo JText::_('Action');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($this->items as $i=>$item){
		?>
		<tr class="row<?php echo $i;?>">
			<td align="center"><?php echo JHTML::_('grid.id',   $i, $item->id );?></td>
			<td align="center"><img
				src="./components/com_awardpackage/asset/prize/<?php echo $item->prize_image; ?>"
				width="100px" /></td>
			<td>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>Prize Name</td>
					<td>:</td>
					<td><?php echo $item->prize_name; ?></td>
				</tr>
				<tr>
					<td>Prize Value</td>
					<td>:</td>
					<td><?php echo "$ ". $item->prize_value; ?></td>
				</tr>
				<tr>
					<td>Date Created</td>
					<td>:</td>
					<td><?php echo date("d-M-Y",strtotime($item->date_created)); ?></td>
				</tr>
				<tr>
					<td>Created By</td>
					<td>:</td>
					<td><?php echo $item->created_by; ?></td>
				</tr>
				<tr>
					<td>Description</td>
					<td>:</td>
					<td><?php echo $item->desc; ?></td>
				</tr>
			</table>
			</td>
			<td align="center"><a
				href="<?php echo JRoute::_( 'index.php?option=com_awardpackage&act=view&view=prize&gcid='. $item->id.'&package_id='.$this->package_id);?>&tmpl=component"
				class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}">View</a>
			&nbsp; <?php if(!$row->unlocked_status){//if not have user winner?> <a
				href="<?php echo JRoute::_( 'index.php?option=com_awardpackage&controller=prize&task=edit&cid[0]='. $item->id.'&package_id='.JRequest::getVar('package_id') ); ?>">
			Edit </a> &nbsp; <a
				href="<?php echo JRoute::_( 'index.php?option=com_awardpackage&controller=prize&task=remove&cid[0]='. $item->id.'&package_id='.$this->package_id); ?>"
				onclick="return confirmdelete();"> <?php echo JText::_('Delete');?>
			</a> <?php 
			}else{
				?> <a
				href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&layout=prizewinners&package_id='.$this->package_id.'&id='.$row->id);?>">
				<?php echo 'Prize Winners';?> </a> <?php
			}
			?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tr><td colspan="9" style="text-align:right;">                                         
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
                                  
    </tr>
</table>
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="create" /> <input type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="controller" value="prize" /></form>
<script type="text/javascript">
    $.noConflict();
    function confirmdelete()
    {
        var agree=confirm("Are you sure you wish to delete this Prize?");
        if (agree)
            return true ;
        else
            return false ;
    }
    </script>
</form>
</div>
