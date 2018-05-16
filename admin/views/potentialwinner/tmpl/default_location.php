<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id='.$this->package_id.'&field=location'); ?>" method="post" name="adminForm" id="refundpackagelist-form" class="form-validate">
    <table width="100%" cellpadding="1" cellspacing="1" class="adminlist" style="border:1px solid #cccccc;">
        <thead>
            <tr>
                <td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo $this->form->getLabel('population'); ?></th>
                <th><?php echo $this->form->getLabel('state'); ?></th>
                <th><?php echo $this->form->getLabel('city'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center"><?php echo $this->form->getInput('population'); ?> %</td>
                <td align="center"><?php echo $this->form->getInput('state'); ?></td>
                <td align="center"><?php echo $this->form->getInput('city'); ?></td>
            </tr>
            <tr>
                <th align="center"><?php echo $this->form->getLabel('street'); ?> </th>
                <th align="center"><?php echo $this->form->getLabel('post_code'); ?></th>
                <th>Country</th>
            </tr>
            <tr>
                <td align="center"><?php echo $this->form->getInput('street'); ?></td>
                <td align="center"><?php echo $this->form->getInput('post_code'); ?></td>
                <td>
                    <select name="jform[country]">
                        <?php
                        foreach ($this->countries as $country) {
                            ?>
                        <option value="<?php echo $country;?>"><?php echo $country;?></option>
                            <?php
                        }
                        ?>
                    </select>

                </td>
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
                <td colspan="4" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
            </tr>
            <tr>
                <th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_STATE'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_CITY'); ?></th>
                <th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
            </tr>
            <?PHP 
				$rows = $this->model->getDataField($this->package_id, 'location');
			?>
            <?PHP foreach($rows as $row){?>
            	<tr>
                	<td align="center"><?PHP echo $row->population;?></td>
                    <td align="center"><?PHP echo $row->state;?></td>
                    <td align="center"><?PHP echo $row->city;?></td>
                    <td align="center">
						    <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id=' . $this->package_id . '&field=location&task=edit_potential&controller=selectwinner&presentation_id='.$this->presentation_id.'&id=' . $row->criteria_id); ?>"><?php echo JText::_('COM_REFUND_EDIT'); ?></a>&nbsp;&nbsp;
                        <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=potentialwinner&package_id=' . $this->package_id . '&field=location&task=delete_potential&controller=selectwinner&presentation_id='.$this->presentation_id.'&id=' . $row->criteria_id); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>                
                    </td>
                </tr>
            <?PHP }?>
        </thead>
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
        <input type="hidden" name="jform[field]" value="location">
        <input type="hidden" name="jform[presentation_id]" value="<?PHP echo $this->presentation_id;?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>