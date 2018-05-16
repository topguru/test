<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<input type="hidden" name="accountId" value="<?php echo JRequest::getVar("accountId"); ?>">
				<div class="span12">
					<table class="table table-hover table-striped">
						<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th style="text-align:center;"><?php echo JText::_('Date');?></th>
								<th style="text-align:center;"><?php echo JText::_('Giftcode');?></th>
								<th style="text-align:center;"><?php echo JText::_('Total symbol pieces');?></th>
								<th style="text-align:center;"><?php echo JText::_('Price symbol pieces accepted');?></th>	
								<th style="text-align:center;"><?php echo JText::_('Free symbol pieces accepted');?></th>	
								<th style="text-align:center;"><?php echo JText::_('Symbol pieces discarded');?></th>																	
															
							</tr>
						</thead>
						<tbody>
							<?php //var_dump($this->giftcodes);
							foreach($this->giftcodes as $giftcode)
							{
								$i++;
								?>
<tr>
												<td style="padding-top:14px;height:30px;text-align:center;background-color:<?php echo $giftcode->colour_code ?>;" valign="center">
												<font color="white" ><b><?php echo $giftcode->date_time; ?></b></font>
												
									</td>
									<td ><?php echo $giftcode->category_name; ?></td>
									<td style="text-align:center;"><?php echo $giftcode->total; ?></td>
                                    <td style="text-align:center;">	<?php echo $giftcode->jml; ?></td>
									<td style="text-align:center;">	<?php 
									echo ( $giftcode->jml == 0  ? 0 : $giftcode->total - $giftcode->jml); ?></td>
									<td style="text-align:center;"><?php echo $giftcode->total - $giftcode->jml; ?></td>

								</tr>
								<?php
							}
							?>
						</tbody>						
					</table>
				</div>				
			</form>						
		</div>
	</div>
</div>
