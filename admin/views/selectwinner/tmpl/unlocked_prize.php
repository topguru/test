<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<form action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=unlocked_prize&package_id=' . $this->package_id); ?>" method="post" name="adminForm" id="selectwinner" class="form-validate">
    <table width="100%" class="table table-striped" cellpadding="0" cellspacing="0">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <th width="1%">#</th>
                <th><?PHP echo JText::_('Prizes'); ?></th>
                <th><?PHP echo JText::_('Prize Value'); ?></th>
                <th><?PHP echo JText::_('Award Symbols'); ?></th>
                <th><?PHP echo JText::_('Unlocked Date'); ?></th>
            </tr>
        </thead>
        <tbody>
        	<?PHP $rows = $this->model->getUnlockedDetails(JRequest::getVar('winner_id'));?>
            <?PHP foreach($rows as $i=>$row){?>
            	<tr class="row<?PHP echo $i;?>">
                	<td><?PHP echo $row->id;?></td>
                    <td align="center"><?PHP echo $row->prize_name;?></td>
                    <td align="center">$ <?PHP echo number_format($row->prize_value);?></td>
                    <td align="center">
                    	<a href="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=symbol_detail&back=unlocked_prize&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&ap_winner_id='.$row->ap_user_winner_id.'&winner_id='.JRequest::getVar('winner_id'));?>"><?PHP echo count($this->model->getSymbolTotal($row->ap_user_winner_id));?></a></td>
                    
                    <td align="center"><?PHP echo $row->awarded_date;?></td>
                </tr>
            <?PHP }?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="package_id" value="<?PHP echo $this->package_id; ?>">
        <input type="hidden" name="presentation_id" value="<?PHP echo $this->presentation_id;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>