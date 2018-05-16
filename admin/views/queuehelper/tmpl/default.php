<?php
/*
 * Archive com_awardpackage 
 * autohor : kadeyasa@gmail.com
 * joomla components 
 */
 
//resdirect
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=queuehelper&package_id='.$this->package_id); ?>" method="post" name="adminForm" id="adminForm">
	<strong>Total Prize : <?php echo $this->getTotalPrize();?></strong>
    <br/>
	<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
    	<thead>
        	<tr style="text-align:center; background-color:#CCCCCC">
            	<th><?php echo JText::_('Prize Value');?></th>
                <th><?php echo JText::_('Prize');?></th>
                <th><?php echo JText::_('Symbol Set');?></th>
                <th><?php echo JText::_('Symbol Pieces');?></th>
            </tr>
        </thead>
        <tbody>
        <?php if($this->items):
					foreach($this->items as $i=>$item){
		?>
        <tr class="row<?php echo $i;?>">
        	<td align="right"><?php echo '$'.number_format($item->prize_value,2);?></td>
            <td align="center">
               	<img src="./components/com_awardpackage/asset/prize/<?php echo $item->prize_image; ?>" width="100px" />
                <br/>
                <?php echo $item->prize_name;?>
            </td>
            <td align="center">
                <img src="./components/com_awardpackage/asset/symbol/<?php echo $item->symbol_image; ?>" width="150px"/>
                <br/>
            	<?php echo $item->symbol_name;?>
            </td>
            <td align="center">
			<table border="1px">
			<?php
			
				$segment_width = 150/$item->cols; //Determine the width of the individual segments
				$segment_height = 150/$item->rows; //Determine the height of the individual segments
				$show = '';
				for( $rownya = 0; $rownya < $item->rows; $rownya++)
					{
					$show .= '<tr>';
						for( $colnya = 0; $colnya < $item->cols; $colnya++)
					{
					    
							$filename = substr($item->symbol_image,0,strlen( $item->symbol_image) - 4).$rownya.$colnya.".png";
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
        </tr>
        <?php
					} 
			endif; 
		?>
        </tbody>
    </table>
    	<input type="hidden" name="presentation_id" value="<?php echo $this->presentation_id;?>" />
        <input type="hidden" name="package_id" value="<?php echo $this->package_id;?>" />
        <input type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
        <input type="hidden" name="task" value="" />
</form>