<?php
defined('_JEXEC') or die('Restricted Access');
?>
<script src="<?php echo JURI::ROOT(); ?>media/system/js/modal.js" type="text/javascript"></script>
<script>	
    window.addEvent('domready', function() {
        SqueezeBox.initialize({});
        SqueezeBox.assign($$('a.modal'), {
            parse: 'rel'
        });
    });
</script>
<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcredit&layout=shoppingrecord'); ?>" method="post">
    <h1>Shopping record</h1>
    <fieldset>
        <legend>Search Shopping Credits</legend>
        <div>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo JRequest::getVar('filter_search'); ?>" title="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
        </div>
    </fieldset>
    <fieldset>
        <legend>Unlock and claim your shopping credits</legend>
        <table width="100%" cellpadding="0" cellspacing="0" class="adminlist">
            <thead>
                <tr>
                    <th><?php echo JText::_('#'); ?></th>
                    <th><?php echo JText::_('Date Recieved'); ?></th>
                    <th><?php echo JText::_('Description'); ?></th>
                    <th><?php echo JText::_('Amount'); ?></th>
                    <th><?php echo JText::_('Unlocked'); ?></th>
                    <th><?php echo JText::_('Claim your shopping credits'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->data as $i => $item) {
                    $j++;
                    $row = $j % 2;
                    ?>  
                    <tr class="row<?php echo $row; ?>">
                        <td align="center"><?php echo $j; ?></td>
                        <td align="center"><?php echo $item->date_recived; ?></td>
                        <td align="center"><?php echo $item->description.' '.$this->row->ready_for_use; ?></td>
                        <td align="right"><?php echo '$ ' . number_format($item->amount); ?></td>
                        <td align="right">
                            <?php
                            if ($item->unlocked_status == '1') {
                                echo '$' . $item->unlocked;
                            }
                            ?>
                        </td>
                        <td align="center">
                            <?php if (!$item->claimed_status && !$item->is_blocked) { ?>
                                <a  title="Claim shopping credit" href="index.php?option=com_awardpackage&amp;view=shoppingcredit&amp;layout=claim_dialog&amp;tmpl=component&amp;record_id=<?php echo $item->shopping_record_id; ?>&amp;claimed=1" class="modal" rel="{handler: 'iframe', size: {x: 300, y: 300}}"">
                                    Yes
                                </a>
                                <?php
                            } else {
                                if ($item->claimed_status && $item->is_blocked != '1') {
                                    ?>
                                    <a  title="Claim shopping credit" href="index.php?option=com_awardpackage&amp;view=shoppingcredit&amp;layout=claim_dialog&amp;tmpl=component&amp;record_id=<?php echo $item->shopping_record_id; ?>&amp;claimed=0" class="modal" rel="{handler: 'iframe', size: {x: 300, y: 300}}"">
                                        No
                                    </a>
                                    <?php
                                } else {
                                    echo'can not to claim';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>

        </table>
    </fieldset>
    <fieldset>
        <legend>Spend your shopping credits</legend>
        <table class="adminlist">
            <thead>
                <tr>
                    <th><?php echo JText::_('#'); ?></th>
                    <th><?php echo JText::_('Date'); ?></th>
                    <th><?php echo JText::_('Description'); ?></th>
                    <th><?php echo JText::_('Amount'); ?></th>
                    <th><?php echo JText::_('Total'); ?></th>
                </tr>
            </thead>

            <?php
            $spends = $this->model->getSpenShoppingCredit();
            
            ?>
            <tbody>
                <?php
                $total = 0;
                foreach ($spends as $spend) {
                    $s++;
                    $row = $s % 2;
                    $total = $total + $spend->amount;
                    ?>
                    <tr class="row<?php echo $row; ?>">
                        <td align="center"><?php echo $s; ?></td>
                        <td align="center"><?php echo $spend->date_claimed; ?></td>
                        <td align="center"><?php echo 'Shopping credit unlocked and ready for use'; ?></td>
                        <td align="right"><?php echo'$' . number_format($spend->amount); ?></td>
                        <td align="right"><?php echo'$' . $total; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </fieldset>
</form>