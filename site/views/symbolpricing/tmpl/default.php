<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted Access');
$j=0;
?>
<div id="cj-wrapper">	
	<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<div class="well">
					<span style="text-decoration:underline;cursor:pointer;"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing');?>">Home</a></span>&nbsp;&nbsp;&nbsp;
					<span style="text-decoration:underline;cursor:pointer;"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcode');?>">Giftcode History</a></span>&nbsp;&nbsp;&nbsp;						
				</div>
			</div>
			<div class="span12" style="text-align:right;padding-right:10px;">
					<table>
						<tr>
							<td class="page-header margin-bottom-10 no-space-top" style="text-align:left;">
									<b><?php echo JText::_('Symbol Pieces'); ?></b>
								</td>
							<td>&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td><h5 class="page-header margin-bottom-10 no-space-top" style="text-align:left;" >
									<?php echo JText::_('Action'); ?></h5></td>
							<td><input type="text" id="act" value="" size="5" ></td>
							<td><h5 class="page-header margin-bottom-10 no-space-top" style="text-align:left;" >
									<input type="button" name="add" value="add" /></h5></td>
						</tr>
					</table>
					<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolpricing&layout=view_order'); ?>" method="post" name="adminForm" id="adminForm">
    					<table width="100%" cellpadding="0" cellspacing="0" class="adminlist" style="font-size:11px;">
        				<thead>
							<tr style="text-align:center; background-color:#CCCCCC">
								<th width="1%" class="hidden-phone">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>	
								<th align="center"></th>
								<th><?php echo JText::_('Prize');?></th>
								<th><?php echo JText::_('Symbol Set');?></th>
								<th><?php echo JText::_('Symbol Pieces');?></th>
								<th><?php echo JText::_('Symbol Prize');?></th>
								<th><?php echo JText::_('Action');?></th>
							</tr>
						</thead>
        				<tbody>
        				<?php foreach ($this->items as $i => $item) { ?>
        					<tr style="text-align:center;">
								<td align="center">
                                		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                	</td>
								<td align="center"></td>
								<th><img src="./administrator/components/com_awardpackage/asset/prize/<?php echo $item->prize_image; ?>"
															width="30px" /></th>
								<th><img src="./administrator/components/com_awardpackage/asset/symbol/<?php echo $item->symbol_image; ?>"
															width="30px" /></th>
								<th><img src="./administrator/components/com_awardpackage/asset/symbol/pieces/<?php echo $item->symbol_pieces_image; ?>"
															width="30px" /></th>
								<th><?php echo JText::_('Symbol Prize');?></th>
								<th><?php echo JText::_('Action');?></th>
							</tr>
							<?php } ?>
            			</tbody>
    </table>
</form>
</div>
</div>
</div>
