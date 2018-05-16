<style>
ol#toc {
	height: 2em;
	list-style: none;
	margin: 0;
	padding: 0;
}

ol#toc a {
	background: url(tabs.gif) 100% 0;
	color: #008;
	float: left;
	line-height: 2em;
	outline: none;
	
	text-decoration: none;
	padding: 5px 10px 5px 5px;
}

ol#toc li.current {
	background-color: #48f;
	background-position: 0 -60px;
}

ol#toc li.current a {
	background-position: 100% -60px;
	color: #fff;
	font-weight: bold;
	padding: 5px 10px 5px 5px;
}

ol#toc li {
	background: #bdf url(tabs.gif);
	float: left;
	margin: 0 1px 0 0;
	padding-left: 5px;
	
}
.adminlist tr td{
    text-align: center;
} 
	
</style>
<script language="javascript">
/*	$(document).ready(function() {	
		$(".cal").datepicker({ 
			dateFormat: $.datepicker.W3C, 
			showOn: "both", 
			buttonImage: "./components/com_giftcode/asset/img/calendar.gif", 
			buttonImageOnly: true 
		});
	});*/
</script>
<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access'); 
//$checked    = '<input type="checkbox" id="cb'.$i.'" name="cid[]" value="'. $row->code_setting_id.'" onclick="isChecked(this.checked);" />';
$catid=intval(JRequest::getVar('cat_id'));
if($catid=='') $catid=1;
$schid=JRequest::getVar('sch_id'); 
$configid=JRequest::getVar('config_id');
 
?>

<form action="index.php" method="post" id="renewform" name="adminForm">
<div id="wrapper">
 
<ul class="navigationTabs">

    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=1" <?php echo $catid==1?'class="active"':'';?> id="red" rel="Red"><div class="kotak" style="background-color:#E42217;text-align:center;float:left">1</div></a></li>
    
    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=2" <?php echo $catid==2?'class="active"':'';?> id="orange" rel="Orange"><div class="kotak" style="background-color:#F88017;text-align:center;float:left">2</div></a></li>
    
    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=3" <?php echo $catid==3?'class="active"':'';?> id="yellow" rel="Yellow"><div class="kotak" style="background-color:#FBB917;text-align:center;float:left">3</div></a></li>
    
    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=4" <?php echo $catid==4?'class="active"':'';?> id="green" rel="Green"><div class="kotak" style="background-color:#437C17;text-align:center;float:left">4</div></a></li>
    
    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=5" <?php echo $catid==5?'class="active"':'';?> id="blue" rel="Blue"><div class="kotak" style="background-color:#46C7C7;text-align:center;float:left">5</div></a></li>
    
    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=6" <?php echo $catid==6?'class="active"':'';?> id="dark_blue" rel="Dark Blue"><div class="kotak" style="background-color:#15317E;text-align:center;float:left">6</div></a></li>

    <li><a href="index.php?option=com_giftcode&controller=giftcode&task=renew&config_id=<?php echo $configid;?>&sch_id=<?php echo $schid;?>&cat_id=7" <?php echo $catid==7?'class="active"':'';?> id="purple" rel="Purple"><div class="kotak" style="background-color:#8E35EF;text-align:center;float:left">7</div></a></li>
    
    </ul>
 <div class="tabsContent">
    <?php
$datacat=&$this->datacat;
foreach($datacat as &$row)
{
?>
    <div class="tab">
    <h2><?php echo $row->cat_name;?></h2>
    <table>
    <tr>
    	<td>Output Category</td>
    	<td>:</td>
    	<td><?php echo $row->cat_name;?></td>
    </tr>
    <tr>
    	<td>Output Quantity</td>
    	<td>:</td>
    	<td><?php echo $row->value;?></td>
    </tr>
    <tr>
    	<td>Symbol Type</td>
    	<td>:</td>
    	<td><?php echo $row->type;?></td>
    </tr>
    <tr>
    	<td>Comment</td>
    	<td>:</td>
    	<td><?php echo $row->comment;?></td>
    </tr>
    </table>
     </div>
 <?php    
} 
$datarenew=&$this->datarenew;
 
?>
<br/>
<br/>

 
<div>
Renew by Interval
</div>
<br/>
<!--<input type="button" value="Add" onclick="location.href='index.php?option=com_giftcode&controller=giftcode&task=addrenew&sch_id=<?php echo $schid;?>&cat_id=<?php echo $catid;?>&renewby=interval';"/>-->
<input type="button" value="Add" onclick="location.href='index.php?option=com_giftcode&controller=giftcode&task=addrenew&sch_id=<?php echo $schid;?>&cat_id=<?php echo $catid;?>&renewby=interval';" />
<input type="submit" id="deleteinterval" value="Delete" name="delete" />
<br/>
<br/>
<table class="adminlist" >
<thead>
<tr>
    <th width="10%">#</th>
    <th style="width: 10%;">Renew Every</th>
    <th >Hour, Day, Month, or Year</th>
     
</tr>
</thead>
<?php
for ($i=0, $n=count( $datarenew); $i < $n; $i++)
{ 
     $row=&$datarenew[$i];
     if($row->renewby=='interval'){
        $checked 	= JHTML::_('grid.id',   $i, $row->id );
?>
        <tr >
            <td><?php echo $checked ; ?></td>
            <td><?php echo $row->intervalvalue;?></td>
            <td><?php echo $row->name;?></td>
            
        </tr>
<?php }}?>
</table>
<br/>
<br/>
<div>
Renew by Weekday
</div>
<br/>
<select name="weekday" id="weekday">
	<option value="1">Monday</option>
	<option value="2">Tuesday</option>
	<option value="3">Wednesday</option>
	<option value="4">Thursday</option>
	<option value="5">Friday</option>
	<option value="6">Saturday</option>
	<option value="7">Sunday</option>
</select>
<input type="submit" id="addweekday" value="Add" name="add"   />
<input type="submit" id="deleteweekday" value="Delete" name="delete" />
<br/>
<br/>
<table class="adminlist" >
<thead>
<tr>
    <th width="5px">#</th>
    <th style="width: 90%;">Weekday</th>
     
</tr>
</thead>
<?php
for ($i=0, $n=count( $datarenew); $i < $n; $i++)
{ 
     $row=&$datarenew[$i];
     if($row->renewby=='weekday'){
         $checked 	= JHTML::_('grid.id',   $i, $row->id );
?>
        <tr>
            <td><?php echo $checked ;?></td>
            <td><?php echo $row->name; ?></td>
            
        </tr>
<?php }}?>
 

</table>
<br>
<br>
<div>
Renew by Calendar Dates
</div>
<br>
<input type="text" name="calendar" class="cal"/>
<input type="submit" id="addcalendar" value="Add" name="add" />
<input type="submit" id="deletecalendar" value="Delete" name="delete"  />
<br>
<br>
<table class="adminlist" >
<thead>
<tr>
    <th width="5px">#</th>
    <th style="width: 90%;">Renew on Every Calendar Date</th>
    
</tr>
</thead>
<?php
for ($i=0, $n=count( $datarenew); $i < $n; $i++)
{ 
     $row=&$datarenew[$i];
     if($row->renewby=='calendar'){
          $checked 	= JHTML::_('grid.id',   $i, $row->id );
         // $name=date('Y-M-d',$row->name);
?>
        <tr>
            <td><?php echo $checked ;?></td>
            <td><?php echo date("d-M-Y",strtotime($row->name)); ?></td>
            
        </tr>
<?php }}?>
 

</table>
</div>
<br/>
<br/>
<input type="hidden" name="publish_setting_id" value="<?php echo $this->data->publish_setting_id;?>" />
<input type="hidden" name="code_setting_id" value="<?php echo $this->data->code_setting_id;?>" />
<input type="hidden" name="option" value="com_giftcode" />
<input type="hidden" name="task" value="saverenew" />
<input type="hidden" id="submitact" name="submitact" value="" />
<input type="hidden" name="controller" value="giftcode" />
<input type="hidden" name="action" value="saverenew" />
<input type="hidden" name="configid" id="configid" value="<?php echo $configid;?>" />
<input type="hidden" name="schid" id="schid" value="<?php echo $schid;?>" />
<input type="hidden" name="catid" id="catid" value="<?php echo $catid;?>" />

<br />
</div>
</form>
<br>
<br>
<script type="text/javascript">
$(document).ready(function() {
 	jQuery(".cal").datepicker({ 
		dateFormat: jQuery.datepicker.W3C, 
		showOn: "both", 
		buttonImage: "./components/com_giftcode/asset/img/calendar.gif", 
		buttonImageOnly: true 
	});
        
});
jQuery('#deleteinterval').click(function(){
     jQuery('#submitact').val('deleteinterval');
});
jQuery('#deleteweekday').click(function(){
     jQuery('#submitact').val('deleteweekday');
});
jQuery('#deletecalendar').click(function(){
     jQuery('#submitact').val('deletecalendar');
});
jQuery('#addweekday').click(function(){
     jQuery('#submitact').val('addweekday');
});
jQuery('#addcalendar').click(function(){
     jQuery('#submitact').val('addcalendar');
});
 
 </script>