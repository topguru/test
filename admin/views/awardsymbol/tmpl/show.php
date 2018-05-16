<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access'); ?>
<div id="editcell">
<div class="judul">
View Award Symbol
</div>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist" >
<tr>
    <th style="width:100px">Symbol Name</th>
    <th style="width:200px">Symbol Pieces</th>
	<th style="width:200px">Symbol Set</th>
</tr>
<tr>
	<td><?php echo $this->data->symbol_name; ?></td>
    <td>
	<table border="1px">
	<?php
	
		$segment_width = 200/$this->data->cols; //Determine the width of the individual segments
		$segment_height = 200/$this->data->rows; //Determine the height of the individual segments
		$show = '';
		for( $rownya = 0; $rownya < $this->data->rows; $rownya++)
			{
		        $show .= '<tr>';
				for( $colnya = 0; $colnya < $this->data->cols; $colnya++)
		        {
		            
					$filename = substr($this->data->symbol_image,0,strlen( $this->data->symbol_image) - 4).$rownya.$colnya.".png";
					$file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
					$show .= '<td style="padding:3px;">';
					$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px; height:'.$segment_height.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
					$show .= '</td>';
		        }
				$show .= '</tr>';
		}
		echo $show;
	
	?>
	</table>
	</td>
<td>
	Symbol Set<br>
	<img src="./components/com_awardpackage/asset/symbol/<?php echo $this->data->symbol_image; ?>" width="200px" height="200px"/>
</td>
</tr>

</table>
<br/>
<br/>
<input type="hidden" name="publish_setting_id" value="<?php echo $this->data->publish_setting_id?>" />
<input type="hidden" name="code_setting_id" value="<?php echo $this->data->code_setting_id?>" />
<input type="hidden" name="option" value="com_awardpackage" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="award" />
</form>
<br />
</div>
<br/>
<br/>