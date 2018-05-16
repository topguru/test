<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div id="j-main-container" class="span10">
    <table data-role="table" class="ui-responsive table-stripe">
        <tr>
            <td width="49%" valign="top">
                <form
                    action="<?php echo JRoute::_('index.php?option=com_awardpackage'); ?>"
                    method="post" name="adminForm" id="adminForm">
                    <input type="hidden" name="task" value="awardpackage" />
                    <input type="hidden" value="<?php echo $this->package_id; ?>" name="package_id[]">
                    <input type="hidden" name="boxchecked" value="0">	
                    <table data-role="table" class="ui-responsive table-stripe">
                        <thead>
                            <tr>
                                    <!-- <th align="center"><input type="checkbox" name="toggle" value=""
                                    onclick="checkAll(<?php echo count($this->items) ?>)">
                            </th> -->
                                <th scope="col" width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
                                </th>
                                <th scope="col" class="nowrap center">Publish</th>
                                <th scope="col" class="nowrap center">Award
                                    Package&nbsp;&nbsp;&nbsp;[&nbsp;&nbsp;<a
                                        href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=archive&layout=list'); ?>">Archive
                                        List</a>&nbsp;&nbsp;] &nbsp;</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="2"><?php echo $this->pagination->getListFooter(); ?></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($this->items as $i => $item):
                                if ($item->is_archive != 1):
                                    ?>
                                    <tr class="row<?php echo $i % 2; ?>">
                                        <td width="5%" align="center"><?php echo JHtml::_('grid.id', $i, $item->package_id); ?></td>
                                        <td align="center" width="5%" nowrap><?php if ($item->published) { ?>
                                                <img
                                                    src="<?php echo JURI::base() . 'templates/isis/images/admin/icon-16-allow.png'; ?>">
                                            <?php } else { ?> <img
                                                    src="<?php echo JURI::base() . 'templates/isis/images/admin/publish_x.png'; ?>">
                                            <?php }; ?></td>
                                        <td align="center">
                                            <div style="border: 2px solid grey; padding: 10px;">
                                                <div
                                                    style="border: 2px solid grey; padding: 2px; float: left; text-align: center; width: 500px; margin-top: 2px;">
                                                    <div style="width: 300px; float: left;"><b>Award package: <a
                                                                href="<?php echo JRoute::_('index.php?option=com_awardpackage&package_id=' . $item->package_id); ?>">
                                                                <?php echo $item->package_name; ?></a></b> Created Date : <?php echo date("Y-m-d", strtotime($item->created)); ?>
                                                    </div>
                                                    <div style="float: right; margin-left: 10px">[&nbsp;<a
                                                            href="<?php echo JRoute::_('index.php?option=com_awardpackage&task=entry&param=scUpdate&tmpl=component&package_id=' . $item->package_id); ?>" class="modal">Edit</a>&nbsp;]
                                                        &nbsp; [ <a
                                                            href="<?php echo JRoute::_('index.php?option=com_awardpackage&action=mrd&package_id=' . $item->package_id); ?>">MRD</a>]
                                                        &nbsp;</div>
                                                </div>
                                                <div style="padding: 10px; text-align: center;">
                                                    <table data-role="table" class="ui-responsive table-stripe">
                                                        <tr>
                                                            <td>Start Date</td>
                                                            <td>End Date</td>
                                                            <td>Duration</td>
                                                        </tr>
                                                        <tr>
                                                            <td>From <?php echo $item->start_date; ?></td>
                                                            <td>To <?php echo $item->end_date; ?></td>
                                                           <td>
                                                                <div style="border: 2px solid grey; padding: 2px;">
																<?php
																
                                                                    $date1 =date("Y-m-d", strtotime($item->start_date));
                                                                    $date1 = explode("-", $date1);
                                                                    
																	$date2 =date("Y-m-d", strtotime($item->end_date));
																	$date2 = explode("-", $date2);
																	
                                                                    $start = mktime(0, 0, 0, $date1[1], $date1[2], $date1[0]);
                                                                    $end = mktime(0, 0, 0, $date2[1], $date2[2], $date2[0]);
                                                                    echo floor(($end - $start) / 60 / 60 / 24) . ' days';
                                                                    ?></div>
                                                            </td>
                                                            <?php if (strtotime(date("r")) > $end) echo '<td><font color="red">expired</font></td>'; ?>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div style="text-align: right;">
                                                    <button
                                                        OnClick="javascript:location.href = '<?php echo JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id=' . $item->package_id); ?>';"
                                                        type="button">User group</button>
                                                    <button
                                                        OnClick="javascript:location.href = '<?php echo JRoute::_('index.php?option=com_awardpackage&view=category&package_id=' . $item->package_id); ?>';"
                                                        type="button">Award Category</button>
                                                    <button
                                                        OnClick="javascript:location.href = '<?php echo JRoute::_('index.php?option=com_awardpackage&task=doclone&package_id=' . $item->package_id); ?>';"
                                                        type="button">Clone</button>
                                                    <button
                                                        OnClick="javascript:return clickF(<?php echo $item->package_id; ?>);">Archive</button>

                                                </div>
                                                <div
                                                    style="border: 2px solid grey; padding: 2px; text-align: center; width: 500px; margin-top: 20px;">
                                                    Registered users : <?php echo $this->registered_users($item->package_id); ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
                <div><input type="hidden" name="option" value="com_awardpackage" /> <?php echo JHtml::_('form.token'); ?>
                </div>
            </td>
            <?php if (count($this->items) > 0) { ?>
                <td width="49%">
                    <table data-role="table" class="ui-responsive table-stripe">
                        <thead>
                            <tr>
                                <th class="nowrap center" scope="col">Award Modules</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="400px;"><?php
                                    if ($this->package_id && !$this->mrd) {
                                        ?>
                                        <div style="border: 2px solid grey; padding: 5px;"><b>Award Package
                                                : <?php echo $this->package->package_name; ?></b></div>
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Package Summary (Use SSIS Or Excel)'); ?></strong><span></span>
                                        </div>
                                        <!-- 
                                        <div class="content"><a href="#" target="_parent"><?php echo JText::_('User Statistics'); ?></a>
                                        <br />
                                        <a href="#" target="_parent"><?php echo JText::_('Prizes Statistics'); ?></a>
                                        <br />
                                        <a href="#" target="_parent"><?php echo JText::_('Donation Statistics'); ?></a>
                                        <br />
                                        <a href="#" target="_parent"><?php echo JText::_('Polls Statistics'); ?></a>
                                        </div>
                                        -->
                                        <div class="content">
                                            <a href="#" target="_parent"><?php echo JText::_('User (' . $this->registered_users(JRequest::getVar('package_id')) . ')'); ?></a>
                                            <br/>
                                            <a href="#" target="_parent"><?php echo JText::_('Quiz (' . $this->registered_quiz(JRequest::getVar('package_id')) . ')'); ?></a>
                                            <br/>	
                                            <a href="#" target="_parent"><?php echo JText::_('Survey (' . $this->registered_survey(JRequest::getVar('package_id')) . ')'); ?>
                                                <br/>
                                                <a href="#" target="_parent"><?php echo JText::_('Donation (' . $this->registered_donation(JRequest::getVar('package_id')) . ')'); ?></a>
                                                <br/>
                                                <a href="#" target="_parent"><?php echo JText::_('Prize (' . $this->registered_prize(JRequest::getVar('package_id')) . ')'); ?></a>
                                        </div>
                                        <br/>					
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Users'); ?></strong><span></span>
                                        </div>
                                        <div class="content"><a
                                                href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usersearch&task=usersearch.user_list&package_id=' . JRequest::getVar('package_id')); ?>"
                                                target="_parent"><?php echo JText::_('User List'); ?></a> <br />
                                            <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usertransaction&task=usertransaction.doGetUserTransaction&package_id=' . JRequest::getVar('package_id')); ?>" target="_parent"><?php echo JText::_('User Transaction'); ?></a>
                                        </div>
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Funding'); ?>
                                            </strong><span></span>
                                        </div>
                                        <div class="content">
<!--                                            <a href="index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=<?php echo JRequest::getVar('package_id'); ?>" target="_parent"><?php echo JText::_('Funding Methods'); ?></a>
                                            <br/>
                                            <a href="index.php?option=com_awardpackage&view=currencies&package_id=<?php echo JRequest::getVar('package_id'); ?>" target="_parent"><?php echo JText::_('Currencies'); ?></a>-->
                                            <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=payout&task=payout.get_payout&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Prize Payoutlist'); ?></a>						
                                            <br/>
                                            <a href="index.php?option=com_awardpackage&view=paypals&task=paypals.getPaypalConfigurationList&package_id=<?php echo JRequest::getVar('package_id'); ?>" target="_parent"><?php echo JText::_('Paypal Configuration'); ?></a>						
                                        </div>
                                        <br />

                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Award'); ?>
                                            </strong><span></span></div>
                                        <div class="content">
                                            <!-- Fixed me by edward issue #49-->   
                                            <!--					<a target="_parent"
                                                                                            href="<?php //echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='. JRequest::getVar('package_id'));    ?>"><?php echo JText::_('Presentation'); ?></a>-->
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Presentation Directory'); ?></a>
                                            <br />
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Presentation List'); ?></a>
                                            <br />
                                            <!-- End Of Fixed me by edward issue #49-->
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardsymbol&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Symbol List'); ?></a>
                                            <br />
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=prize&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Prize List'); ?></a>
                                        </div>
                                        <br/>

                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Giftcode'); ?>
                                            </strong><span></span></div>
                                        <div class="content"><a target="_parent"
                                                                href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=category&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Category - Category Setting'); ?></a>
                                            <br />
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=award_category&layout=free&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Category - Free Giftcode'); ?></a>
                                            <br />
                                            <a target="_parent"
                                               href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcodecode&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Giftcode Collection List'); ?></a>
                                            <br />					
                                        </div>				
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Donate'); ?></strong><span></span>
                                        </div>
                                        <div class="content"><a target="_parent"
                                                                href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=donationlist&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Category - Donation'); ?></a>
                                            <br />
	<a href="index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=<?php echo JRequest::getVar('package_id');?>" target="_parent"><?php echo JText::_('Funding Methods');?></a>                                        </div>
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Quiz'); ?></strong><span></span>
                                        </div>
                                        <div class="content">
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=quiz&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Category - Quiz'); ?></a>
                                            <br />
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=dashboardz&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Control Panel (Dashboard)'); ?></a>					
                                        </div>
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Survey'); ?></strong><span></span>
                                        </div>
                                        <div class="content">
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=survey&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Category - Survey'); ?></a>
                                            <br/>
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=dashboard&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Control Panel (Dashboard)'); ?></a>
                                        </div>	
                                        <br />
                                        <div class="page_collapsible collapse-close" id="body-section1"><strong><?php echo JText::_('Shopping Credit'); ?></strong><span></span>
                                        </div>				
                                        <div class="content">
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Shopping Credit Plan Category'); ?></a>
                                            <br />	
                                            <a target="_parent" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Shopping Credit Plan'); ?></a>					
                                        </div>
                                        <br />										
                                        <?php
                                    }//end mrd
                                    ?> <!--<iframe width="100%" name="right_frame" height="1000"></iframe> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            <?php } ?>
        </tr>
    </table>
</div>
<script type="text/javascript">
    function clickF(package_id) {
        var save = window.confirm('Are you sure?');
        if (save) {
            location.href = 'index.php?option=com_awardpackage&task=archive&package_id=' + package_id;
        }
        return false;
    }
</script>