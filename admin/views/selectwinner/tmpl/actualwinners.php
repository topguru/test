<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$items = $this->model->getActualWinners($this->winner_id);
?>
<form action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=winners&package_id=' . $this->package_id); ?>" method="post" name="adminForm" id="selectwinner" class="form-validate">
    <table width="100%" class="table table-striped" cellpadding="0" cellspacing="0">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <th width="1%"><?PHP echo JText::_('#');?></th>
                <th><?PHP echo JText::_('Winner'); ?></th>
                <th><?PHP echo JText::_('Prize'); ?></th>
                <th><?PHP echo JText::_('Prize Value'); ?></th>
                <th><?PHP echo JText::_('Award Symbols'); ?></th>
                <th><?PHP echo JText::_('Symbols Returned'); ?></th>
                <th><?PHP echo JText::_('Awarded Date Time'); ?></th>
            </tr>
        </thead>
        <tbody>
        	<?PHP 
				foreach($items as $i=>$item){
				?>
                	<tr class="row<?PHP echo $i;?>">
                    	<td align="center"><?PHP echo $item->id;?></td>
                        <td align="center"><?PHP echo $item->username;?></td>
                        <td align="center"><?PHP echo $item->prize_name;?></td>
                        <td align="center"><?PHP if($item->prize_value){?> $ <?PHP echo $item->prize_value;?> <?PHP }?></td>
                        <td align="center">
                        <a href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=symbol_detail&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&ap_winner_id='.$item->ap_user_winner_id.'&winner_id='.$this->winner_id.'&back=actualwinners');?>"><?PHP echo count($this->model->getSymbolTotal($item->ap_user_winner_id));?></a>
                        </td>
                        <td align="center">
                        	<a href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=returnedsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&ap_winner_id='.$item->ap_user_winner_id.'&winner_id='.$this->winner_id.'&back=actualwinners');?>">
                        	<?PHP 
								echo count($this->model->getSymbolReturned($item->ap_user_winner_id));
							?>
                            </a>
                        </td>
                        <td align="center">
                        	<?PHP 
								echo $item->awarded_date;
							?>
                        </td>
                    </tr>
                <?PHP
				}
			?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="package_id" value="<?PHP echo $this->package_id;?>">
        <input type="hidden" name="presentation_id" value="<?PHP echo $this->presentation_id;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>