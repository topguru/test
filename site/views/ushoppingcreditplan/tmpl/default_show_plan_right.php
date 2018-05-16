<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
    <thead>
        <tr>
            <th colspan="4" align="center">
                <?php echo JText::_('Shopping credit from donation'); ?>
            </th>
        </tr>
        <tr>
            <th width="12%" style="border: 1px solid #ccc;"><?php echo JText::_('Fee'); ?></th>
            <th style="border: 1px solid #ccc;"><?php echo JText::_('% Refund as shopping credits'); ?></th>
            <th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Unlock'); ?></th>
            <th width="20%" style="border: 1px solid #ccc;"><?php echo JText::_('Expire'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($this->donations)) { ?>
            <?php foreach ($this->donations as $donation) { ?>
                <tr>
                    <td style="border: 1px solid #ccc;">$<?php echo $donation->fee; ?></td>
                    <td style="border: 1px solid #ccc;"><?php echo $donation->refund; ?>%</td>
                    <td style="border: 1px solid #ccc;"><?php echo $donation->unlock; ?> days</td>
                    <td style="border: 1px solid #ccc;"><?php echo $donation->expire; ?> days</td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td style="border: 1px solid #ccc;">0$</td>
                <td style="border: 1px solid #ccc;">0%</td>
                <td style="border: 1px solid #ccc;">0 days</td>
                <td style="border: 1px solid #ccc;">0 days</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!--
<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
    <thead>
        <tr>
            <th colspan="4" align="center">
                <?php //echo JText::_('Shopping credit from purchase of award symbols'); ?>
            </th>
        </tr>
    </thead>
    <tr>
        <th width="12%" style="border: 1px solid #ccc;"><?php //echo JText::_('Fee'); ?></th>
        <th style="border: 1px solid #ccc;"><?php //echo JText::_('% Refund as shopping credits'); ?></th>
        <th width="20%" style="border: 1px solid #ccc;"><?php //echo JText::_('Unlock'); ?></th>
        <th width="20%" style="border: 1px solid #ccc;"><?php //echo JText::_('Expire'); ?></th>
    </tr>
    <tbody>
        <?php //if (!empty($this->awards)) { ?>
            <?php //foreach ($this->awards as $award) { ?>
                <tr>
                    <td style="border: 1px solid #ccc;">$<?php //echo $award->fee; ?></td>
                    <td style="border: 1px solid #ccc;"><?php //echo $award->refund; ?>%</td>
                    <td style="border: 1px solid #ccc;"><?php //echo $award->unlock; ?> days</td>
                    <td style="border: 1px solid #ccc;"><?php //echo $award->expire; ?> days</td>
                </tr>
            <?php //} ?>
        <?php //} else { ?>
            <tr>
                <td style="border: 1px solid #ccc;">0$</td>
                <td style="border: 1px solid #ccc;">0%</td>
                <td style="border: 1px solid #ccc;">0 days</td>
                <td style="border: 1px solid #ccc;">0 days</td>
            </tr>
        <?php //} ?>
    </tbody>
</table>
<table class="table table-hover table-striped" style="border: 1px solid #ccc;">
    <thead>
        <tr>
            <th colspan="2" align="center">
                <?php //echo JText::_('Other benefits'); ?>
            </th>
        </tr>
        <tr>
            <th width="20%"><?php //echo JText::_('Fee'); ?></th>
            <th><?php //echo JText::_('Free giftcode for the funder'); ?></th>
        </tr>
    </thead>
    <?php
//    $giftcode = empty($this->giftcodes) ? 0 : $this->giftcodes[0];
//    $fee = empty($giftcode->fee) ? 0 : $giftcode->fee;
    ?>
    <tbody>
        <tr>
            <td>$<?php //echo $fee; ?></td>
            <td>
                <table class="table table-hover table-striped" style="border: 1px solid #ccc;">
                    <thead>
                        <tr>
                            <td><?php //echo JText::_('Giftcode'); ?></td>
                            <td><?php //echo JText::_('Quantity'); ?></td>
                            <td><?php //echo JText::_('To be Awarded'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php //foreach ($this->giftcodes as $giftcode) { ?>
                            <tr>
                                <td><?php //echo $giftcode->name; ?></td>
                                <td><?php //echo $giftcode->quantity; ?></td>
                                <td>Daily</td>
                            </tr>
                        <?php //} ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
-->