<?php
//restricted
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>
<?php
$checked    = '<input type="radio" id="cb" name="cid[]" value="1" onclick="isChecked(this.checked);" />';
$view = JRoute::_( 'index.php?option=com_awardpackage&view=awardsymbol&act=view');
?>
<div id="j-main-container" class="span10">
<form action=<?php echo JRoute::_("index.php?option=com_awardpackage&view=awardsymbol");?>  method="post" name="adminForm" id="adminForm">
						<table class="table table-striped table-hover table-bordered table-condensed">
	<thead>
         <tr><td colspan="3" >                                   
<td style="text-align:right"><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th style="width: 4%;"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->data ); ?>);" /></th>
			<th style="width: 30%;">Symbol Pieces</th>
			<th style="width: 35%;">Symbol Set</th>
			<th style="width: 15%;">Action</th>
		</tr>
	</thead>	
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->data ); $i < $n; $i++)
	{
		$row =& $this->data[$i];

		$checked 	= JHTML::_('grid.id',   $i, $row->symbol_id );

		$view = JRoute::_( 'index.php?option=com_awardpackage&act=view&view=awardsymbol&gcid='. $row->symbol_id );

		$edit = JRoute::_( 'index.php?option=com_awardpackage&controller=awardsymbol&task=edit&cid[0]='. $row->symbol_id.'&package_id='.JRequest::getVar('package_id'));

		$delete = JRoute::_( 'index.php?option=com_awardpackage&controller=awardsymbol&task=remove&cid[0]='. $row->symbol_id.'&package_id='.JRequest::getVar('package_id'));
		if($row->package_id==JRequest::getVar('package_id')){
			?>
	<tr class="row<?echo $k ?>">
		<td align="center"><?php echo $checked; ?></td>
		<td>
		<table border="1px">
		<?php
			
		$segment_width = 150/$row->cols; //Determine the width of the individual segments
		$segment_height = 150/$row->rows; //Determine the height of the individual segments
		$show = '';
		for( $rownya = 0; $rownya < $row->rows; $rownya++)
		{
			$show .= '<tr>';
			for( $colnya = 0; $colnya < $row->cols; $colnya++)
			{
					
				$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
				$file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
				$show .= '<td style="padding:3px;">';
				$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
				$show .= '</td>';
			}
			$show .= '</tr>';
		}
		echo $show;
			
		?>
		</table>
		</td>
		<td>Symbol Set<br>
		<img
			src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
			style="width:150px;" /></td>
		<td align="center"><a href="<?php echo $view ?>" class="modal"
			rel="{handler: 'iframe', size: {x: 640, y: 480}}"> <?PHP echo 'View';?>
		</a> &nbsp;&nbsp;&nbsp; <a href="<?php echo $edit ?>"> <?php echo 'Edit';?></a>&nbsp;&nbsp;&nbsp;
		<a href="<?php echo $delete ?>" onclick="return confirmdelete();"> <?php  echo 'Delete';?>
		</a></td>
	</tr>
	<?php
	$k = 1 - $k;
		}
	}
	?>
     <tr><td colspan="4" style="text-align:right">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                   
                                    </td>                                   
    </tr>

</table>
<br />
<br />
<br />
<input type="hidden" name="option" value="com_awardpackage" /> <input
	type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id');?>"> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="task" value="create" /> <input type="hidden" name="controller"
	value="awardsymbol" /></form>
</div>
<script type="text/javascript">
$.noConflict();
function confirmdelete()
{
    var agree=confirm("Are you sure you wish to delete this Symbol?");
    if (agree)
    	return true ;
    else
    	return false ;
}
</script>
