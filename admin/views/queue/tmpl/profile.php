<?php defined('_JEXEC') or die('Restricted access'); ?>
<style>
td { text-align: center; }
th { font-weight: bold; }
</style>
<div style="border: 2px solid grey; padding: 10px; text-align: center; float: left; width: 25% ;">
    User : <?php foreach ($this->user as $user) { echo $user->name; } ?>
</div>
<div style="padding-top: 50px;">
    <table class="table table-striped">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <th><?php echo JText::_('COM_GIFT_CODE_CATEGORY');?></th>
                <th><?php echo JText::_('COM_GIFT_CODE_GIFT_CODE');?></th>
                <th><?php echo JText::_('COM_GIFT_CODE_REDEEMED');?></th>
                <th><?php echo JText::_('COM_GIFT_CODE_AWARD_SYMBOL_PIECES');?></th>                
            </tr>
        </thead>
        <tbody>
        <?php 
        $categoryData =& $this->categoryData;    
        $color = array();
        $color[] = "";
        $category = array();
        $category="";
        foreach ($categoryData as $row) {
            $color[$row->id] = $row->name;
            
        }
        $giftcode_data = $this->giftcode_data;
        foreach ($giftcode_data as $gd) {
        $cat_number = $this->model->getGiftCodeCat($gd->giftcode_category_id);
            ?>
            <tr>
                <td>
                    <?php $symbol = $this->model->getSymbolPices($cat_number->category_id);?>
                    <?php echo $color[$gd->giftcode_category_id]; ?></td>
                <td><?php echo $gd->giftcode; ?></td>
                <td>-</td>
                <td>
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
							$file = "./components/com_award/asset/symbol/pieces/".$filename;
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
    </table>
</div>