<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">						
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<div class="span12">
						<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th><?php echo JText::_('Date'); ?></th>																	
								<th><?php echo JText::_('Description'); ?></th>
								<th style="text-align:right;"><?php echo JText::_('Amount'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->transactions as $row):?>
							<tr>
							<td><?php echo $row->created_date; ?></td>
							<td><?php echo $row->description; ?></td>
							<td style="text-align:right;">
								<?php
									if($row->debit > 0) {
										echo '(-) $' . number_format($row->debit, 2);
									} else 
									if($row->credit > 0) {
										echo '(+) $' . number_format($row->credit, 2);
									} else {
										echo number_format(0,2);
									}
								?>
							</td>
							
						</tr>
							<?php endforeach;?>
                            <tr><td colspan="2">                                    
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>
<td><?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>