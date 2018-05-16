<?php
defined('_JEXEC') or die();
?>
<script type="text/javascript">

function showHide(div){
  if(document.getElementById(div).style.display == 'none'){
    document.getElementById(div).style.display = 'block';
  }else{
    document.getElementById(div).style.display = 'none'; 
  }
}

$(document).ready(function(){
	$("#expanderHead").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead2").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead3").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
	$("#expanderHead4").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "+"){
			$("#expanderSign").html("-")
		}
		else {
			$("#expanderSign").text("+")
		}
	});
});

function disableForm() {
    var inputs = document.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
    	inputs[i].disabled = true;
    }
    var selects = document.getElementsByTagName("select");
    for (var i = 0; i < selects.length; i++) {
    	selects[i].disabled = true;
    }
    var textareas = document.getElementsByTagName("textarea");
    for (var i = 0; i < textareas.length; i++) {
    	textareas[i].disabled = true;
    }
    var buttons = document.getElementsByTagName("button");
    for (var i = 0; i < buttons.length; i++) {
    	buttons[i].disabled = true;
    }
}
</script>

<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage');?>" method="post" name="adminForm">

<div id="cj-wrapper">
<?php //} ?>

	<div class="container-fluid no-space-left no-space-right surveys-wrapper" >
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td width="10%" valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">

<?php 
if (!empty($this->expired)) { 
echo '<div class="is-disabled">';
 }else{  
echo '<div class="span12">';
} ?>	<br/>								
<div class="well">
								<h2 class="page-header margin-bottom-10 no-space-top">
									<?php echo JText::_('Shopping Credit'); ?>
								</h2>		
							</div>
						
                       <div style="padding: 10px;">
                         <table class="table table-bordered">
                           <tr>
                             <td rowspan="2">
                             <h5 style="">From: <?php echo $this->start_date; ?> to <?php echo $this->end_date; ?></h5>

                             <table class="table table-hover table-striped" style="border: 1px solid #ccc;" id="tableContributionRange">
																	<thead>
																		<tr>
																			<th colspan="3" align="center">
																			<?php echo JText::_( 'Contribution Range' ); ?>
																			</th>
																		</tr>																		
																	</thead>
																	<tbody>
																		<?php foreach ($this->contribution_ranges as $range){?>
																		<tr>
																			<td style="border: 1px solid #ccc;"><input type="checkbox" name="contrib_range[]" value="<?php echo $range->id; ?>"/></td>
																			<td style="border: 1px solid #ccc;">
																				<?php if($range->max_amount == 0): ?>
																					<?php echo 'Over $' . ($range->min_amount - 1); ?>
																				<?php else: ?>
																					<?php echo '$' . $range->min_amount . ' to ' . '$' . $range->max_amount; ?>
																				<?php endif; ?>
																			</td>
																			<td style="border: 1px solid #ccc;"><input type="radio" name="contrib_radio" value="<?php echo $range->id; ?>" 
																				<?php echo ((!empty($this->contrib_radio) && $this->contrib_radio == $range->id) ? "checked=checked" : ""); ?> onchange="on_select_contribution_range(this);" /></td>
																		</tr>
																		<?php } ?>
																	</tbody>																	
																</table>
                                                                <table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																	<thead>
																		<tr>
																			<th colspan="3" align="center">
																			<?php echo JText::_( 'Progress Check' ); ?>
																			</th>
																		</tr>																		
																	</thead>
																	<tbody>
																		<?php foreach ($this->progress_checkes as $pc){?>
																		<tr>
																			<td style="border: 1px solid #ccc;"><input type="checkbox" name="progress_checks[]" value="<?php echo $pc->id; ?>"/></td>
																			<td style="border: 1px solid #ccc;"><?php echo 'Every ' . $pc->every. ' ' .  $pc->type; ?></td>
																			<td style="border: 1px solid #ccc;"><input type="radio" name="progress_radio" value="<?php echo $pc->id; ?>"
																				<?php echo ((!empty($this->progress_radio) && $this->progress_radio == $pc->id) ? "checked=checked" : ""); ?> onchange="on_select_progress_check(this)" /></td>
																		</tr>
																		<?php } ?>
																	</tbody>
																	
																</table>
                             </td>
                             <td><table class="table table-hover table-striped table-bordered">
                             <?php 								
foreach ($this->sc_plan as $row){ ?>
<tr><td>
<?php if($range->max_amount == 0):?>
	Contribution range: Over $<?php echo $row->min_amount - 1; ?>
<?php else: ?>
	Contribution range: $<?php echo $row->min_amount; ?> to $<?php echo $row->max_amount; ?>
<?php endif; ?>                       

</td>
</tr>
<tr><td>
From: <?php echo $row->start_date; ?> to <?php echo $row->end_date; ?>
</td>
</tr>
<tr><td>
Progress check: every <?php echo $row->every.' '.$row->type; ?>
</td>
</tr>


            </table></td>
                           </tr>
                           <tr>
                             <td><table class="table table-hover table-striped" style="border: 1px solid #ccc;">
																	<thead>
																		<tr>
																			<th colspan="4" align="center">
																			Shopping credit from donation																			</th>
																		</tr>
																		<tr>
																			<th width="12%" style="border: 1px solid #ccc;">Fee</th>
																			<th style="border: 1px solid #ccc;">% Refund as shopping credits</th>
																			<th width="20%" style="border: 1px solid #ccc;">Unlock</th>
																			<th width="20%" style="border: 1px solid #ccc;">Expire</th>
																		</tr>																		
																	</thead>
																	<tbody>
                                                                   
																																																						<tr>
																			<td style="border: 1px solid #ccc;">$<?php echo $row->fee; ?></td>
																			<td style="border: 1px solid #ccc;"><?php echo $row->refund; ?>%</td>
																			<td style="border: 1px solid #ccc;"><?php echo $row->unlock; ?> days</td>
																			<td style="border: 1px solid #ccc;"><?php echo $row->expire; ?> days</td>
																		</tr>
                                                                        <?php } ?>
																																																					</tbody>
																</table></td>
                           </tr>
                         </table>
                       
					   
            

					  </div>
       </td>
       </tr>
       </table>
        		</div>

	</div>
</div>
<input type="hidden" name="task" value="ushoppingcreditplan.getMainPage" />
<input type="hidden" name="boxchecked" value="0" />	
<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
</form>
