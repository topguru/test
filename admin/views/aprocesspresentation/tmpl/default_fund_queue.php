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
		$("#expanderContent2").slideToggle();
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

</script>
	<form id="adminForm"
				action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation');?>">	
                <input type="hidden" name="process_id" id="process_id" value="<?php echo JRequest::getVar('process_id'); ?>">
<div id="cj-wrapper">

<table>                                	
<tr>
<td valign="top">
<table class="table table-striped table-hover table-bordered" style="width:400px;">                                	
                                <thead>
                                <tr>
		                                <th colspan="4" style="font-size:12px;text-align:center;">Fund receiver plan
        		                       <?php foreach ($this->receiver as $receiver){
									$plan = $receiver->title;
									}
									?>
                                        </th>
                                	</tr>  
                                <tr>
		                                <th colspan="5" style="height:30px;"><span style="font-size:12px;">Plan <?php echo $plan; ?></span>
        		                        <span style="float:right;">
											<button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddValuePieces;" id="addBtn"><i>Add</i></button>
                        		        
											<button type="button" class="btn btn-primary btn-invite-reg-groups" onclick="onAddValuePieces;" id="addBtn"><i>Delete</i></button>
                        		        </span>
                                        </th>
                                	</tr>       
                                                          	
                                    <tr style="height:50px;">
		                                <th style="text-align:center;">#
        		                        </th>
                                        <th style="text-align:center;">No
        		                        </th>
                		                <th style="text-align:center;">Fund receiver plan
                        		        </th>
                                        <th style="text-align:center;">Limit fund receiver
                        		        </th>
                                         </th>
                                        <th style="text-align:center;">Fund receiver list
                        		        </th>
                                	</tr>
                                    </thead>
                                    <?php foreach ($this->receiver as $receiver){
									$k++;
									?>
                                    <tr style="height:45px;">
                                    <td>
<input type="checkbox" id="cb<?php echo $j; ?>" name="cid1<?php echo $j; ?>[]" value="<?php echo $fundprizes->id; ?>" 																											onclick="Joomla.isChecked(this.checked);">
</td>

		                                <td><?php echo $k; ?> </td>
                                        <td><?php echo '<a href="index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.fundPrizePlan&process_id='.JRequest::getVar('process_id').'&package_id='.JRequest::getVar('package_id').'" >'.$receiver->title.'</a>'; ?></td>
                                        <td style="text-align:center;"><?php echo $receiver->filter; ?> </td>
                                       <td>
                                        <p id="expanderHead2" style="text-align:center;cursor:pointer;color:blue;">View</p></td>

                                	</tr>
                                    <?php } ?>
                                </table>
</td>

<td valign="top">

<table class="table table-striped table-hover table-bordered" style="width:900px;">                                	
                                <thead>
                                <tr>
		                                <th colspan="9" style="font-size:12px;text-align:center;">Fund receiver list
        		                       
                                        </th>
                                	</tr>  
                                <tr>
		                                <th colspan="9" style="height:30px;"><span style="font-size:12px;">Plan <?php echo $plan; ?></span>
        		                        
                                        </th>
                                	</tr>       
                                    <!--tr>
		                                <th colspan="9">
                                        <span style="font-size:12px;">From : <?php //echo $from_date;?></span>
        		                        <span style="font-size:12px;">To : <?php //echo $to_date;?></span>
                                        <span style="font-size:12px;">Durations : <?php //echo getDaysBetweenDates($from_date,$to_date).' days' ?></span>
                                        <span style="font-size:12px;">Status : <?php /*if ($fundprizes->published=='1')
										{echo 'On';}else
										{echo 'Off';}*/
										?></span>
                                        </th>
                                	</tr--->                  	
                                    <tr style="height:50px;">
		                                <th style="text-align:center;">#
        		                        </th>
                                        <th style="text-align:center;">No
        		                        </th>
                		                <th style="text-align:center;">User
                        		        </th>
                                        <th style="text-align:center;">Prize status
                        		        </th>
                                        <th style="text-align:center;">Value funded
        		                        </th>
                		                <th style="text-align:center;">Shortfall
                        		        </th>
                                        <th style="text-align:center;">%funded
                        		        </th>
                                        <th style="text-align:center;">Extracted pieces <br/>inserted into symbol queue
                        		        </th>
                                        <th style="text-align:center;">Symbol queue number <br/>for extracted pieces
                        		        </th>
                                	</tr>                                    
                                    </thead>
                                    <?php 
									foreach ($this->presentations as $presentation){
									$symbolName = $presentation->symbol_name;
									}
									
									foreach ($this->awardfund as $awardfund){
									$m++;
									?>
                                    <tr style="height:45px;display:none;" id="expanderContent2">
                                    <td>
<input type="checkbox" id="cb<?php echo $j; ?>" name="cid1<?php echo $j; ?>[]" value="<?php echo $awardfund->id; ?>" 																											onclick="Joomla.isChecked(this.checked);">
</td>
		                                <td style="text-align:center;"><?php echo $m; ?> </td>
		                                <td><?php 
										echo (!empty($symbolName ) ? $awardfund->firstname.' '.$awardfund->lastname : ''); ?> </td>
		                                <td style="text-align:center;"><?php 
										$inserted = $valueFunded/$prizevalue;
										$extrapieces = JRequest::getVar('ep'); 
										echo ( $inserted == 100 ? 'Unlocked' : 'Locked') ;?></td>
		                                <td style="text-align:center;"><?php 
										$valueFunded = $awardfund->amount;//0;
										$prizevalue = JRequest::getVar('value'); 										
										echo $valueFunded;// $awardfund->amount; ?> </td>
		                                <td style="text-align:center;"><?php echo  $valueFunded - $prizevalue; ?> </td>

		                                <td style="text-align:center;"><?php echo round(($valueFunded/$prizevalue),2) .'%'//$awardfund->rate; ?> </td>
                		                <td style="text-align:center;"><?php 
										$inserted = $valueFunded/$prizevalue;
										$extrapieces = JRequest::getVar('ep'); 
										echo ( $inserted == 100 ? $extrapieces : 0) ;?></td>
                		                <td style="text-align:center;"><?php 
										echo (!empty($symbolName ) ? $m : 0);
										 ?></td>

                                	</tr>
                                    <?php } ?>

                                </table>
</td>

</tr>
</table>

</div>
</form>
