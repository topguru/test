<?php 
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
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
					<h2 class="page-header margin-bottom-10 no-space-top" style="text-align:left;" >
						<?php echo JText::_('Giftcode History'); ?>
					</h2>	
				<form action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=giftcode&color='.JRequest::getVar('color')); ?>" method="post" name="adminForm">
				<?php
	
				$data = $this->model->getList();
				$categoryData = $this->model->getGiftCodePackageAll();
	

				?>
				<a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=manage');?>" class="manage">Manage Giftcode</a>
				
				</br></br>
				<ul class="navigationTabs">
				<?php
		  		$id = JRequest::getVar('color') ? JRequest::getVar('color') : "1" ;
		 		foreach ($categoryData as &$row) {
		    	?>
		    	<li>
		      		<a class="<?php echo $id == $row->id ? "active" : "" ?>" href="index.php?option=com_awardpackage&view=giftcode&color=<?php echo $row->id ?>" id="<?php echo $row->name ?>" rel="<?php echo $row->name ?>" >
					<div class="kotak" style="background-color:<?php echo $row->color_code ?>;text-align:center;float:left"><?php echo $row->category_id ?></div>
		      		</a>
		    	</li>                    
		    	<?php
		  		}
				?>        
	      		</ul>
	      		<div class="formbox">
					<table width="100%" cellpadding="0" cellspacing="0" class="adminlist" border='1' style="font-size:11px;">
        				<thead>
							<tr style="text-align:center; background-color:#CCCCCC">
								<th width="1%" class="hidden-phone">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>	
								<td><?php echo JText::_('&nbsp Date');?></td>
								<td><?php echo JText::_('&nbsp Giftcode');?></td>
								<th><?php echo JText::_('&nbsp Total Symbol Pieces');?></th>
								<th><?php echo JText::_('&nbsp Priced symbol pieces accepted');?></th>
								<th><?php echo JText::_('&nbsp Free symbol pieces accepted');?></th>
								<th><?php echo JText::_('&nbsp Symbol pieces discarded');?></th>
							</tr>
						</thead>
        				<tbody>
            			<?php
            				foreach ($this->giftcode_category as $i => $item) {
                		?>
                            	<tr class="row<?php echo $i % 2; ?>">
                                	<td align="center">
                                		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                	</td>
                                	<td align="center">
                                		<?php echo $item->created_date_time; ?>
                                	</td>
                                	<td align="center">
                                		<?php echo $item->name; ?>
                                	</td>
                                	<td align="center">
                                		<?php echo $item->created_date_time; ?>
                                	</td>
                                	<td align="center">
                                		<?php echo $item->created_date_time; ?>
                                	</td>
                                </tr>
                           <?php } ?>
                           </tbody>
	      				</table>
	      		</div>
	
		</form>			
		</div>
	</div>
</div>
