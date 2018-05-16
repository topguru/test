<div class="clearfix collapsible">
    <div class="span12">
        <span>
            <strong><?php echo JText::_('Shopping Credit Plan'); ?></strong>
        </span>
        <br/><br/><br/>
        <div style="text-align:center;">
            <span style="background-color:#C8C8C8;padding:10px 40px;margin:30px 0;width:200px;height:40px;">
                From : <?php echo $this->start_date;?> to <?php echo $this->end_date;?>
            </span>
        </div>        
        <br/>
    </div>
    <div class="clearfix"></div>
    <div class="accordion" id="section1"><?php echo JText::_('Contribution Range'); ?><span></span></div>
    <div class="container span12">
        <div class="content">
            <table class="table table-hover table-striped" style="border: 1px solid #ccc;">
                <tbody>
                    <?php foreach ($this->contribution_ranges as $range) { ?>
                        <tr>
                            <td style="border: 1px solid #ccc;">
                                <input type="radio" name="contrib_radio" value="<?php echo $range->id; ?>"<?php echo (JRequest::getVar('contrib_id') == $range->id ? "checked=checked" : ""); ?> onchange="on_select_contribution_range(this);" />
                            </td>
                            <td style="border: 1px solid #ccc;">
                                <?php echo '$' . $range->min_amount . ' to ' . '$' . $range->max_amount; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <?php echo $this->pagination_contribution_range->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="accordion" id="section2"><?php echo JText::_('Progress Check'); ?><span></span></div>
    <div class="container span12">
        <div class="content">
            <table class="table table-hover table-striped" style="border: 1px solid #ccc;">
                <tbody>
                    <?php foreach ($this->progress_checkes as $pc) { ?>
                        <tr>
                            <td style="border: 1px solid #ccc;">
                                <input type="radio" name="progress_radio" value="<?php echo $pc->id; ?>" <?php echo (JRequest::getVar('progress_id') == $pc->id ? "checked=checked" : ""); ?> onchange="on_select_progress_check(this)" />
                            </td>
                            <td style="border: 1px solid #ccc;"><?php echo 'Every ' . $pc->every . ' ' . $pc->type; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <?php echo $this->pagination_progress_check->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
