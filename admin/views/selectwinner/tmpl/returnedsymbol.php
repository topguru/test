<?PHP 
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	$items = $this->model-> getSymbolReturned(JRequest::getVar('ap_winner_id'));
	
?>
<form action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&layout=returnedsymbol&package_id=' . $this->package_id); ?>" method="post" name="adminForm" id="selectwinner" class="form-validate">
 <table width="100%" class="table table-striped" cellpadding="0" cellspacing="0">
 	<thead>
        <tr style="text-align:center; background-color:#CCCCCC">
        	<th><?PHP echo JText::_('#');?></th>
            <th><?PHP echo JText::_('Symbol Pieces');?></th>
        </tr>
        </thead>
        <tbody>
        	<?PHP foreach($items as $i=>$item){?>
            	<tr class="row<?PHP echo $i;?>">
                	<td align="center"><?PHP echo $item->id;?></td>
                    <td align="center"><img src="./components/com_awardpackage/asset/symbol/pieces/<?php echo $item->symbol_pieces_image; ?>" width="100"></td>
                </tr>
            <?PHP }?>
        </tbody>
 </table>
 <div>
        <input type="hidden" name="package_id" value="<?PHP echo $this->package_id; ?>">
        <input type="hidden" name="presentation_id" value="<?PHP echo $this->presentation_id;?>" />
        <input type="hidden" name="winner_id" value="<?PHP echo $this->winner_id;?>">
        <input type="hidden" name="back" value="<?PHP echo $this->back;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>