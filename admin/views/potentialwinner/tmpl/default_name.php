<form action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id=' . $this->package_id); ?>" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
    <table width="100%" cellpadding="1" cellspacing="1" class="adminlist" style="border:1px solid #cccccc;">
        <thead>
            <tr>
                <td colspan="3" align="center" height="50" class="td-group" style="background: #fff;"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo $this->form->getLabel('population'); ?></th>
                <th><?php echo $this->form->getLabel('firstname'); ?></th>
                <th><?php echo $this->form->getLabel('lastname'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center"><?php echo $this->form->getInput('population'); ?>%</td>
                <td align="center"><?php echo $this->form->getInput('firstname'); ?></td>
                <td align="center"><?php echo $this->form->getInput('lastname'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td align="right"><button value="Save" class="btn-add"><?php echo JText::_('COM_REFUND_SAVE'); ?></button></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <br/>
    <table width="100%" cellpadding="1" cellspacing="1" class="adminlist" style="border:1px solid #cccccc;">
        <thead>
            <tr style="background-color:#CCCCCC">
                <td colspan="4" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
                <th><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?PHP
            $rows = $this->model->getDataField($this->package_id, 'name');
            ?>
            <?php
            foreach ($rows as $row):
                ?>
                <tr>
                    <td align="center"><?php echo $row->population; ?> %</td>
                    <td align="center"><?php echo $row->firstname; ?>&nbsp;<?php echo $row->lastname; ?></td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id=' . $this->package_id . '&field=name&task=edit_potential&controller=selectwinner&presentation_id='.$this->presentation_id.'&id=' . $row->criteria_id); ?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id=' . $this->package_id . '&field=name&task=delete_potential&controller=selectwinner&presentation_id='.$this->presentation_id.'&id=' . $row->criteria_id); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="jform[package_id]" value="<?php echo $this->package_id; ?>">
        <input type="hidden" name="option" value="com_awardpackage" />
        <input type="hidden" name="task" value="save_potential" />
        <input type="hidden" name="controller" value="selectwinner" />	
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="jform[creteria_id]" value="<?php echo $this->id; ?>">
        <input type="hidden" name="jform[field]" value="name">
        <input type="hidden" name="jform[presentation_id]" value="<?PHP echo $this->presentation_id;?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>