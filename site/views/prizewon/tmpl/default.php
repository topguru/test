<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
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
<form method="POST" action="" name="adminForm">
	<h1><?php echo JText::_('Your prize won');?></h1>
    <table width="100%" class="adminList">
    	<thead>
        	<td align="center" style="padding:5px;"><?php echo JText::_('#');?></td>
        	<td align="center"><?php echo JText::_('Prize Name');?></td>
            <td align="center"><?php echo JText::_('Prize Value');?></td>
            <td align="center"><?php echo JText::_('Date Awarded');?></td>
            <td align="center"><?php echo JText::_('Claim Your Prize ?');?></td>
        </thead>
        <tbody>
        	<?php foreach($this->items as $i=>$item){
			$j++;
			$prize_claim = $this->model->checkClaim($item->winner_id);
			?>
            <tr class="row<?php echo $i;?>">
            	<td style="padding:5px;" align="center"><?php echo $j;?></td>
                <td style="padding:5px;">
	    		<img src="./administrator/components/com_awardpackage/asset/prize/<?php echo $item->prize_image; ?>" width="100px"/>
                <br/>
				<?php echo $item->prize_name;?></td>
                <td style="padding:5px;">$ <?php echo $item->prize_value;?></td>
                <td style="padding:5px;"><?php echo $item->awarded_date;?></td>
                <td align="center">
                <?php 
				if($prize_claim->send_status=='0' || !$prize_claim){
					if($prize_claim->claimed_status=='0' || !$prize_claim){?>
                                <a  title="Claim shopping credit" href="index.php?option=com_awardpackage&amp;view=prizewon&amp;layout=claim_dialog&amp;tmpl=component&amp;winner_id=<?php echo $item->winner_id; ?>&amp;claimed=1" class="modal" rel="{handler: 'iframe', size: {x: 300, y: 300}}">
                                Yes
                                </a>
                <?php }else{
				?>
                                <a  title="Claim shopping credit" href="index.php?option=com_awardpackage&amp;view=prizewon&amp;layout=claim_dialog&amp;tmpl=component&amp;winner_id=<?php echo $item->winner_id; ?>&amp;claimed=0" class="modal" rel="{handler: 'iframe', size: {x: 300, y: 300}}">
                                No
                                </a>
                <?php
					}
				}else{
					echo 'Your prize allready to sent';
				}
				?>
                </td>
            </tr>
            <?php }?>
        </tbody>
        <tfoot>
        	<tr>
        	<td colspan="10" style="padding:5px;"><?php echo $this->pagination->getListFooter();?></td>
            </tr>
        </tfoot>
    </table>
    <br/>
    <fieldset>
    	<legend>Sent prize</legend>
        <?php 
			$sents_prize = $this->model->getPrizeSent();
		?>
        <table width="100%" cellpadding="0" cellspacing="0">
        	<tr>
            	<td style="padding:5px;" align="center"><?php echo JText::_('No');?></td>
                <td align="center"><?php echo JText::_('Prize name');?></td>
                <td align="center"><?php echo JText::_('Prize value');?></td>
                <td align="center"><?php echo JText::_('Status');?></td>
            </tr>
            <?php
				if($sents_prize){
					foreach($sents_prize as $sent){
					$n++;
					?>
					<tr>
						<td style="padding:5px;"><?php echo $n;?></td>
						<td style="padding:5px;"><?php echo $sent->prize_name;?></td>
						<td style="padding:5px;">$<?php echo $sent->prize_value;?></td>
						<td align="center">Success</td>
					</tr>
					<?php
					}
				}
			 ?>
        </table>
    </fieldset>
</form>