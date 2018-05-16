<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$color 	= JRequest::getVar('color');

$gcid	= JRequest::getVar('gcid');

$id	= JRequest::getVar('res_id');

$update = $this->model->UpdateUserRecive($gcid,$id);

$rows 	= $this->model->getGiftCodeCategoryId($color);

foreach($rows as $row){
    
   $symbol = $this->model->getSymbol($row->category_id,$this->user_info->package_id);
   
?>
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
<?php


}

?>