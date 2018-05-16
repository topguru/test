<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
  function onCheckRandomA(e){
    e.checked ?  jQuery('#from_year').attr('disabled', 'disabled') :  jQuery('#from_year').attr('disabled', false);   
	e.checked ?  jQuery('#to_year').attr('disabled', 'disabled') :  jQuery('#to_year').attr('disabled', false);    
   }
   
  function onCheckRandomB(e){
    e.checked ?  jQuery('#from_month').attr('disabled', 'disabled') :  jQuery('#from_month').attr('disabled', false);   
	e.checked ?  jQuery('#to_month').attr('disabled', 'disabled') :  jQuery('#to_month').attr('disabled', false);    
  }
    function onCheckRandomC(e){
    e.checked ?  jQuery('#from_day').attr('disabled', 'disabled') :  jQuery('#from_day').attr('disabled', false);   
	e.checked ?  jQuery('#to_day').attr('disabled', 'disabled') :  jQuery('#to_day').attr('disabled', false);    
	}

    function onCheckRandomD(e){
    e.checked ?  jQuery('#gender').attr('disabled', 'disabled') :  jQuery('#gender').attr('disabled', false);   
	}
	
	 function onCheckRandomE(e){
    e.checked ?  jQuery('#state').attr('disabled', 'disabled') :  jQuery('#state').attr('disabled', false);   
	e.checked ?  jQuery('#city').attr('disabled', 'disabled') :  jQuery('#city').attr('disabled', false);   
    e.checked ?  jQuery('#street').attr('disabled', 'disabled') :  jQuery('#street').attr('disabled', false);   
    e.checked ?  jQuery('#country').attr('disabled', 'disabled') :  jQuery('#country').attr('disabled', false);   
    e.checked ?  jQuery('#postcode').attr('disabled', 'disabled') :  jQuery('#postcode').attr('disabled', false);   
	}


function onDeleteAge(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.onDeleteAge');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');	
	jQuery('form#adminForm').submit();
}

function onAddAge(){
//	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.onAddAge');
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.onAddAge');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');	
	jQuery('form#adminForm').submit();
}

function onDeleteAge(){
//	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.onAddAge');
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.onDeleteAge');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');	
	jQuery('form#adminForm').submit();
}

function onAddYearA(){
	var package_id = jQuery('#package_id').val();
	var title = jQuery('#title').val();
	var filter = jQuery('#filter').val();
	jQuery('#task').val('fundreceiver.onaddyear');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');
	jQuery('form#adminForm').submit();
}

function onAddMonth(){
	var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.onaddmonth');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');
	jQuery('form#adminForm').submit();
}

function onAddDay(){
		var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.onaddday');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');
	jQuery('form#adminForm').submit();
}

function onGender(){
			var package_id = jQuery('#package_id').val();
	jQuery('#task').val('fundreceiver.ongender');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundreceiver');
	jQuery('form#adminForm').submit();
}
</script>

<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=fundreceiver" method="post">
	<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
	<input type="hidden" name="id" id="id" value="<?php echo JRequest::getVar("id"); ?>"/>    
    <input type="hidden" name="task" id="task" value="">
	<div class="col100">
					<table class="table table-striped table-hover" style="width:90%;">
			<tr>
				<td style="text-align:right"><?php echo JText::_('LBL_TITLE'); ?></td><td colspan="4"><input class="text_area" type="text" name="title" id="title" style="width:350px;" value="<?php echo (!empty($this->title) ? $this->title : JRequest::getVar("title") );?>" /></td>
                
			</tr>	
            <tr>
				<td style="text-align:right"><input type="checkbox" name="receiver" id="receiver" checked disabled readonly="readonly"/></td>
                <td style="width:300px;">Select users with 'locked' prize status only</td>
                <td style="text-align:right;width:100px;">Limit Prize</td>
                <td><input class="text_area" name="filter" id="filter" type="text" value="<?php echo (!empty($this->filter) ? $this->filter : JRequest::getVar("filter") );?>"/></td>
			</tr>

           	<tr>
            <td colspan="4">
            <div id="tabs">
					<ul>
						<li <?php if ($this->field == 'age') echo $this->class; ?>><a
							href="#tabs-2">Age</a></li>
						<li <?php if ($this->field == 'gender') echo $this->class; ?>><a
							href="#tabs-3">Gender</a></li>
						<li <?php if ($this->field == 'location') echo $this->class; ?>><a
							href="#tabs-4">Location</a></li>
					</ul>
					<div id="tabs-2" style="font-size:12px;">
                    <table class="table table-striped table-hover " >
                    <tr><th>Age</th>
                    <td style="text-align:right;">
                    <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddAge();" id="onAddAgeBtn"><i></i> <?php echo JText::_('Save');?></button>		
                                                                <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDeleteAge()" id="onDeleteAgeBtn"><i></i> <?php echo JText::_('Delete');?></button>			
                    </td>           
                    </tr>
                    </table>
                    <table class="table table-striped table-hover ">
                    <tr>
                    <!-- from year-->
                    <td style="border-right:1px solid #ccc;">
                    	
                             	<table class="table table-striped table-hover table-bordered">
	                    <tr>
    	                <th>From Year
        	            </th>
            	        <th>To Year
                	    </th>
                        <tr>
                        <td><select name="from_year" id="from_year" onchange="onAddYearA()" style="width: 100px" 
						<?php echo ($this->randoma =='1' ? 'disabled' : ''); ?>>
                        <?php 
                        for ($i = 1960; $i <= 2015; $i++) { 
						$from =JRequest::getVar("from_year");
						?>                       
                        
                        <option <?php if ($i == $from ) echo 'selected'; ?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        
<?php		} ?>
                        </select>
        	            </td>
                        <td><select name="to_year" id="to_year" onchange="onAddYearA()" style="width: 100px"
                        <?php echo ($this->randoma =='1' ? 'disabled' : ''); ?>>
                       <?php 
                        for ($i = 1960; $i <= 2015; $i++) { 
						$to =JRequest::getVar("to_year");
						?>                                               
                        <option <?php if ($i == $to ) echo 'selected'; ?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        
<?php		} ?>
                        </select>
        	            </td>
                	    </tr>
                    	</table>
                        <table class="table table-striped table-hover table-bordered">
	                    <tr>
                        	<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        	            	</th>
    	                	<th>From Year
        	            	</th>
            	        	<th>To Year
                        	</th>
                	    </tr>
                        <?php foreach ($this->result as $j=>$row1):?>
                        <tr>
							<td><?php echo JHTML::_( 'grid.id', $j, $row1->criteria_id ); ?></td>
    	                	<td><?php echo $row1->from_year; ?>
        	            	</td>
    	                	<td><?php echo $row1->to_year;
							 ?>
                        	</td>
                	    	</tr>
							<?php endforeach;?>
                    	</table>

                    </td>
                                        <!-- from month-->
                    <td style="border-right:1px solid #ccc;">
                   
                             	<table class="table table-striped table-hover table-bordered">
	                    <tr>
    	                <th>From Month
        	            </th>
            	        <th>To Month
                	    </th>
                        <tr>
                        <td><select name="from_month" id="from_month" onchange="onAddMonth()" style="width: 100px"
                                                <?php echo ($this->randomb =='1' ? 'disabled' : ''); ?>>
                            <?php $from_month = JRequest::getVar("from_month"); ?>                    
<option <?php if($from_month==1) echo "selected";?> value="1">January</option>
<option <?php if($from_month==2) echo "selected";?> value="2">February</option>
<option <?php if($from_month==3) echo "selected";?> value="3">March</option>
<option <?php if($from_month==4) echo "selected";?> value="4">April</option>
<option <?php if($from_month==5) echo "selected";?> value="5">May</option>
<option <?php if($from_month==6) echo "selected";?> value="6">June</option>
<option <?php if($from_month==7) echo "selected";?> value="7">July</option>
<option <?php if($from_month==8) echo "selected";?> value="8">August</option>
<option <?php if($from_month==9) echo "selected";?> value="9">September</option>
<option <?php if($from_month==10) echo "selected";?> value="10">October</option>
<option <?php if($from_month==11) echo "selected";?> value="11">November</option>
<option <?php if($from_month==12) echo "selected";?> value="12">December</option>
                        </select>
        	            </td>
    	                <td><select name="to_month" id="to_month" onchange="onAddMonth()" style="width: 100px"
                                                                        <?php echo ($this->randomb =='1' ? 'disabled' : ''); ?>>

                            <?php $to_month = JRequest::getVar("to_month"); ?>                    
                                                                 
<option value="1" <?PHP if($to_month==1) echo "selected";?>>January</option>
<option value="2" <?PHP if($to_month==2) echo "selected";?>>February</option>
<option value="3" <?PHP if($to_month==3) echo "selected";?>>March</option>
<option value="4" <?PHP if($to_month==4) echo "selected";?>>April</option>
<option value="5" <?PHP if($to_month==5) echo "selected";?>>May</option>
<option value="6" <?PHP if($to_month==6) echo "selected";?>>June</option>
<option value="7" <?PHP if($to_month==7) echo "selected";?>>July</option>
<option value="8" <?PHP if($to_month==8) echo "selected";?>>August</option>
<option value="9" <?PHP if($to_month==9) echo "selected";?>>September</option>
<option value="10" <?PHP if($to_month==10) echo "selected";?>>October</option>
<option value="11" <?PHP if($to_month==11) echo "selected";?>>November</option>
<option value="12" <?PHP if($to_month==12) echo "selected";?>>December</option>                        
                        </select>
        	            </td>
                	    </tr>
                    	</table>
                        <table class="table table-striped table-hover table-bordered">
	                    <tr>
                        	<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        	            	</th>
    	                	<th>From Month
        	            	</th>
            	        	<th>To Month
                        	</th>
                	    </tr>
                        <?php foreach ($this->result as $k=>$row):?>
                        <tr>
							<td><?php echo JHTML::_( 'grid.id', $k, $row->criteria_id );?></td>
    	                	<td>
							<?php 							
							switch ($row->from_month) {
    case 1:
        echo "January";
        break;
    case 2:
        echo "February";
        break;
    case 3:
        echo "March";
        break;
    case 4:
        echo "April";
        break;
    case 5:
        echo "May";
        break;
    case 6:
        echo "June";
        break;
    case 7:
        echo "July";
        break;
    case 8:
        echo "August";
        break;
    case 9:
        echo "September";
        break;
    case 10:
        echo "October";
        break;
    case 11:
        echo "November";
        break;
    case 12:
        echo "December";
        break;
		
}
							 ?>
        	            	</td>
    	                	<td>
							<?php  
							switch ($row->to_month) {
    case 1:
        echo "January";
        break;
    case 2:
        echo "February";
        break;
    case 3:
        echo "March";
        break;
    case 4:
        echo "April";
        break;
    case 5:
        echo "May";
        break;
    case 6:
        echo "June";
        break;
    case 7:
        echo "July";
        break;
    case 8:
        echo "August";
        break;
    case 9:
        echo "September";
        break;
    case 10:
        echo "October";
        break;
    case 11:
        echo "November";
        break;
    case 12:
        echo "December";
        break;
		
}
							 ?>
        	            	</td>
                	    	</tr>
							<?php endforeach;?>
                    	</table>
                    </td>
                                        <!-- from day-->
                    <td style="border-right:1px solid #ccc;">
                  
                             	<table class="table table-striped table-hover table-bordered">
	                    <tr>
    	                <th>From Day
        	            </th>
            	        <th>To Day
                	    </th>
                        <tr>
                        <td><select name="from_day" id="from_day" onchange="onAddDay()" style="width: 100px"
                                                                        <?php echo ($this->randomc =='1' ? 'disabled' : ''); ?>>
                        <?php 
                        for ($i = 1; $i <= 31; $i++) {
					    $from_day =JRequest::getVar("from_day");
						?>
                        <option <?php if ($i == $from_day ) echo 'selected'; ?> value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php } ?>
                        </select>
        	            </td>
                        <td><select name="to_day" id="to_day" onchange="onAddDay()" style="width: 100px"
                                                                        <?php echo ($this->randomc =='1' ? 'disabled' : ''); ?>>
                        <?php 
                        for ($i = 1; $i <= 31; $i++) {
					    $to_day =JRequest::getVar("to_day");
						?>
                        <option <?php if ($i == $to_day ) echo 'selected'; ?> value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php } ?>
                        </select>
        	            </td>
                	    </tr>
                    	</table>
                         <table class="table table-striped table-hover table-bordered">
	                    <tr>
                        	<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        	            	</th>
    	                	<th>From Day
        	            	</th>
            	        	<th>To Day
                        	</th>
                	    </tr>
                        <?php foreach ($this->result as $l=>$row2):?>
                        <tr>
							<td><?php echo JHTML::_( 'grid.id', $l, $row2->criteria_id );?></td>
    	                	<td>
							<?php echo $row2->from_day; ?>
        	            	</td>
    	                	<td>
							<?php echo $row2->to_day; ?>
                        	</td>
                	    	</tr>
							<?php endforeach;?>
                    	</table>
                    </td>
                    </tr>                                 
                    </table>                                                                
                    </div>
                    <!-- gender-->
					<div id="tabs-3" style="font-size:12px;">
                    <table class="table table-striped table-hover">
                    <tr><th>Gender</th>
                    <td style="text-align:right;">
                    <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddAge();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Save');?></button>		
                                                                <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDeleteAge();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Delete');?></button>			
                    </td>           
                    </tr>
                    </table>
                    <table class="table table-striped table-hover " style="width:50%;">
                    <tr>
                    <!-- from year-->
                    <td style="border-right:1px solid #ccc;">
                    	
                             	<table class="table table-striped table-hover table-bordered">
	                    <tr>
    	                <th>Gender
        	            </th>            	       
                        <tr>
                        <td><select name="gender" id="gender" onchange="onGender()" style="width: 100px">
<?php $gender = JRequest::getVar("gender"); ?>                    
                                                                 
<option value="1" <?PHP if($gender==1) echo "selected";?>>Male</option>
<option value="2" <?PHP if($gender==2) echo "selected";?>>Female</option>

                        </select>
        	            </td>
                        
                	    </tr>
                    	</table>
                        <table class="table table-striped table-hover table-bordered">
	                    <tr>
                        	<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        	            	</th>
    	                	<th>Gender
        	            	</th>
            	        	
                	    </tr>
                       <?php foreach ($this->result as $i=>$row):?>
                        <tr>
							<td><?php echo JHTML::_( 'grid.id', $i, $row->criteria_id );?></td>
    	                	<td>							
							<?php 
							switch ($row->gender){
							case 1 : echo 'Male';break;
							case 2 : echo 'Female';break;
							}
							
							 ?>
        	            	</td>    	                	
                	    	</tr>
							<?php endforeach;?>
                    	</table>
                    </td>
                     </tr>                                 
                    </table>  
                    
                    </div>
                                        <!-- location-->
					<div id="tabs-4" style="font-size:12px;">
                    <table class="table table-striped table-hover">
                    <tr><th>Location</th>
                    <td style="text-align:right;">
                    <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddAge();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Save');?></button>		
                                                                <button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDeleteAge();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Delete');?></button>			
                    </td>           
                    </tr>
                    </table>
                    <table class="table table-striped table-hover " style="width:50%;">
                    <tr>
                    <!-- from year-->
                    <td style="border-right:1px solid #ccc;">
                    	<table class="table table-striped table-hover">
	                    
                        </table>
                             	<table class="table table-striped table-hover table-bordered">
	                    		<tr>
		    	                <th>State
        			            </th> 
                		         <th>City
        	            		</th> 
		                        <th>Street
		        	            </th>            	       
		                        <tr>
        		                <td><input class="text_area" type="text" name="state" id="state" value="<?php echo $this->shopping->state;?>" />
        	    		        </td>
                        		<td><input class="text_area" type="text" name="city" id="city" value="<?php echo $this->shopping->city;?>" />
		        	            </td>
        		                <td><input class="text_area" type="text" name="street" id="street" value="<?php echo $this->shopping->street;?>" />
        	    		        </td>
                	    </tr>
	                    		<tr>
		    	                <th>Post Code
        			            </th> 
                		         <th>Country
        	            		</th> 
		                        <th>
		        	            </th>            	       
		                        <tr>
        		                <td><input class="text_area" type="text" name="postcode" id="postcode" value="<?php echo $this->shopping->postcode;?>" />
        	    		        </td>
                        		<td><select name="country" id="country" style="width: 150px;" >
				<?php
				foreach ($this->countries as $country) {
					?>
				<option value="<?php echo $country;?>"><?php echo $country;?></option>
				<?php
				}
				?>
			</select>
		        	            </td>
        		                <td>
        	    		        </td>
                	    </tr>

                    	</table>
                        <table class="table table-striped table-hover table-bordered">
	                    <tr>
                        	<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        	            	</th>
    	                	<th>State
        	            	</th>
    	                	<th>City
        	            	</th>
    	                	<th>Street
        	            	</th>
    	                	<th>Postcode
        	            	</th>
    	                	<th>Country
        	            	</th>
            	        	
                	    </tr>
                        <?php foreach ($this->result as $i=>$row):?>
                        <tr>
							<td><?php echo JHTML::_( 'grid.id', $i, $row->criteria_id );?></td>
    	                	<td>
                            <?php echo $row->state; ?>
        	            	</td>
    	                	<td>
                            <?php echo $row->city; ?>
        	            	</td>
    	                	<td>
                            <?php echo $row->sreet; ?>
        	            	</td>
    	                	<td>
                            <?php echo $row->postcode; ?>
        	            	</td>
    	                	<td>
                            <?php echo $row->country; ?>
        	            	</td>
            	        	
                	    	</tr>
							<?php endforeach;?>
                    	</table>
                    </td>
                     </tr>                                 
                    </table>  
                    </div>
				</div>
            </td>
            </tr>
		</table>
	</div>	


</form>