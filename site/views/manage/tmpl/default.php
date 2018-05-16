<?php
defined('_JEXEC') or die('Restricted access'); ?>
<div class="judul">
    <?php echo JText::_('Manage Gift Code');?>
    <?php $datas = $this->giftcodeModel->getList();?>
</div>
<span><a href="index.php?option=com_awardpackage&view=giftcode">Back</a></span>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" onsubmit="return false">
<div id="editcell"> 
<table class="donation" width="100%">
<thead>
    <tr>
        <th>#</th>
	<th><?php echo JText::_('Award Category');?></th>
        <th><?php echo JText::_('Giftcode');?></th>
        <th><?php echo JText::_('Received');?></th>
        <th><?php echo JText::_('Redeemed');?></th>
        <th><?php echo JText::_('Award Symbol');?></th>
    </tr>
</thead>
<tbody>
    <?php
	foreach($this->items as $item){
	    $i++;
	    $symbol = $this->giftcodeModel->getSymbol($item->category_id,$this->user_info->package_id);
	    ?>
	    <tr>
		<td align="center"><?php echo $i;?></td>
		<td><?php echo $item->name;?></td>
		<td><?php echo $item->giftcode;?></td>
		<td><?php echo $item->date_time;?></td>
		<td><?php echo $item->renew_status ==1? "Yes":"No";?></td>
		<td align="center">
		<table border="1px">
		<?php
					    if($symbol->cols !="" || $symbol->rows!=""){
					    $segment_width = 150/$symbol->cols; //Determine the width of the individual segments
					    $segment_height = 150/$symbol->rows; //Determine the height of the individual segments
					    $show = '';
					    for( $rownya = 0; $rownya < $symbol->rows; $rownya++)
						    {
						    $show .= '<tr>';
							    for( $colnya = 0; $colnya < $symbol->cols; $colnya++)
						    {
							
								    $filename = substr($symbol->symbol_image,0,strlen( $symbol->symbol_image) - 4).$rownya.$colnya.".png";
								    $file = "./administrator/components/com_awardpackage/asset/symbol/pieces/".$filename;
								    $show .= '<td style="padding:3px;">';
								    $show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
								    $show .= '</td>';
						    }
							    $show .= '</tr>';
					    }
					    echo $show;
					}
				    
				    ?>
		</table>
		</td>
	    </tr>
	    <?php
	}
    ?>
</tbody>
<tfoot>
    <tr>
	    <td colspan="20" align="center">
		<?php
			$orig = str_replace('"pagination-','"',$this->pagination->getListFooter());
						
			echo str_replace('"list-footer"','"pagination"',$orig);
		?>
	    </td>
    </tr>
</tfoot>
</table>
</div>
</form>