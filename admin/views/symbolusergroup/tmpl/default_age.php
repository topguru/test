<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . JRequest::getVar('symbol_pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=location'); ?>" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
    <table width="100%" cellpadding="1" cellspacing="1" class="table-striped" style="border:1px solid #cccccc;">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TITLE_AGE'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo $this->form->getLabel('population'); ?></th>
                <th><?php echo $this->form->getLabel('from_age'); ?></th>
                <th><?php echo $this->form->getLabel('to_age'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center"><?php echo $this->form->getInput('population'); ?>%</td>
                <td align="center"><?php echo $this->form->getInput('from_age'); ?></td>
                <td align="center"><?php echo $this->form->getInput('to_age'); ?></td>
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
    <table width="100%" cellpadding="1" cellspacing="1" class="table-striped" style="border:1px solid #cccccc;">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <td colspan="4" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TITLE_AGE'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_AGE'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_TO_AGE'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
            </tr>
        </thead>
        <?php
        $rows = $this->model->getDataField('age', JRequest::getVar('symbol_pricing_id'));
        ?>
        <tbody>
            <?php
            foreach ($rows as $row):
                ?>
                <tr>
                    <td align="center"><?php echo $row->population; ?> %</td>
                    <td align="center"><?php echo $row->from_age; ?>&nbsp;</td>
                    <td align="center"><?php echo $row->to_age; ?>&nbsp;</td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . JRequest::getVar('symbol_pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=age&tasktion=edit&controller=symbolusergroup&id=' . $row->criteria_id.'&presentation_id='.JRequest::getVar('presentation_id')); ?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolusergroup&symbol_pricing_id=' . JRequest::getVar('symbol_pricing_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=age&task=delete&controller=symbolusergroup&id=' . $row->criteria_id.'&presentation_id='.JRequest::getVar('presentation_id')); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="jform[package_id]" value="<?php echo JRequest::getVar('package_id'); ?>">
        <input type="hidden" name="jform[symbol_pricing_id]" value="<?php echo JRequest::getVar('symbol_pricing_id'); ?>">
        <input type="hidden" name="option" value="com_awardpackage" />
        <input type="hidden" name="task" value="save_usergroup" />
        <input type="hidden" name="controller" value="symbolusergroup" />	
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="jform[creteria_id]" value="<?php echo JRequest::getVar('id'); ?>">
        <input type="hidden" name="presentation_id" value="<?php echo JRequest::getVar('presentation_id');?>">
        <input type="hidden" name="jform[field]" value="age">
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>