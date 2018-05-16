<?php	
defined('_JEXEC') or die();
?>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationcategory');?>" method="post" name="adminForm">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
				<?php
					$ppc = null;
					$pres = array();					
					if(!empty($this->ppc)) {
						$ppc = $this->ppc[0];
						foreach ($this->ppc as $pr) {							
							$pres[] = $pr->presentation_id;
						}						
					}
				?>
				<div class="clearfix">
					<label><?php echo JText::_('Presentation Category');?>:</label>
					<select name="category">
						<?php foreach ($this->categories as $row){ ?>
						<option value="<?php echo $row->category_id ?>" <?php echo (null != $ppc && $row->category_id == $ppc->category_id ? "selected=selected" : "") ?> ><?php echo $row->category_name ?></option>
						<?php } ?>
					</select>	
				</div>
				<div class="clearfix">
					<label><?php echo JText::_('Description'); ?>:</label>
					<?php echo CJFunctions::load_editor($editor, 'description', 'description', (null != $ppc ? $ppc->description : ''), '5', '40', '99%', '200px', '', 'width: 99%;'); ?>
					<span class="help-block"><?php echo JText::_('Enter the detailed description');?></span>
				</div>
			</div>
			<br/>
			<div class="span12">			
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th width="20"><?php echo JText::_( '#' ); ?></th>
							<th width="20">
								<!-- 
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
								 -->
							</th>
							<th ><?php echo JText::_( 'Symbol Pieces' ); ?></th>								
							<th ><?php echo JText::_( 'Symbol Set' ); ?></th>
							<th ><?php echo JText::_( 'Prize' ); ?></th>																								
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->presentations as $i=>$row):?>
						<tr>
							<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
							<td>							
								<?php 
									//echo JHTML::_( 'grid.id', $i, $row->presentation_id );
								?>
								<input type="checkbox" id="cb0" name="cid[]" value="<?php echo $row->presentation_id; ?>" onclick="Joomla.isChecked(this.checked);" <?php echo (in_array($row->presentation_id, $pres) ? "checked=checked" : ""); ?>>
							</td>
							<td class="hidden-phone">	
								<table border="1px">
								<?php
									
								$segment_width = 100/$row->cols; //Determine the width of the individual segments
								$segment_height = 100/$row->rows; //Determine the height of the individual segments
								$show = '';
								for( $rownya = 0; $rownya < $row->rows; $rownya++)
								{
									$show .= '<tr>';
									for( $colnya = 0; $colnya < $row->cols; $colnya++)
									{
											
										$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
										$file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
										$show .= '<td style="padding:3px;">';
										$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
										$show .= '</td>';
									}
									$show .= '</tr>';
								}
								echo $show;
									
								?>
								</table>
							</td>
							<td class="hidden-phone">
								<img
								src="./components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>"
								style="width:100px;" />
							</td>
							<td class="hidden-phone">
								<img
								src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>"
								width="100px" />
							</td>																
						</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
						</tr>
					</tfoot>
				</table>				
				<input type="hidden" name="task" value="apresentationcategory.saveCreateUpdate" />
				<input type="hidden" name="boxchecked" value="0" />	
				<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />			
			</div>
		</div>
	</div>
</div>
</form>