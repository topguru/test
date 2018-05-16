<?php 
	defined('_JEXEC') or die;
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	$items = $this->ug->getEmailHistory($this->package_id);
?>
						<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th width="1">
                #
            </th>
            <th><?php echo JText::_('Award Category');?></th>
            <th><?php echo JText::_('First Name');?></th>            
            <th><?php echo JText::_('Last Name');?></th>
            <th><?php echo JText::_('Email');?></th>
            <th><?php echo JText::_('Email Sent');?></th>
            <th><?php echo JText::_('Award Package Users?');?></th>                                    
        </tr>        
    </thead>
    <tbody>
    	<?php 
			foreach($items as $item):
		?>
        <tr>
            <td width="1">
                #           
            </td>            
            <td align="center"><?php echo $item->category_name;?></td>
            <td align="center"><?php echo $item->firstname;?></td>
            <td align="center"><?php echo $item->lastname;?></td>
            <td align="center"><?php echo $item->email;?></td>
            <td align="center"><?php echo $item->modified_date;?></td>
            <td><?php echo JText::_('No');?></td>            
        </tr>
        <?php 
			endforeach;
		?>
    </tbody>
</table>