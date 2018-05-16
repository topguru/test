<style>
.giftcodepo{
    margin: 10px 0 0;
}
.giftcodepo {
    
}
</style>
<?php
 
defined('_JEXEC') or die('Restricted access'); ?>
<body>
<form action="index.php" method="post" name="adminForm1">
<div id="editcell">
<?php
$pieces=$this->datapieces;
 $catid=JRequest::getVar('catid');
  
?>
<!--<table class="giftcodepo">
<thead>
<td style="width: 10%; text-align: center;">#</td>
<td style="width: 45%; text-align: center;">Symbol pieces</td>
<td style="width: 45%; text-align: center;">Symbol image</td>
<td style=" text-align: center;">Select</td>
</thead>-->
<ul style="list-style: none;">
 <?php
 $i=0; 

$floatleft='float: left; position: relative; top: 60px;';
 if($catid==1 || $catid==2 || $catid==3)
 $floatleft='position: relative; top: 120px;';
 if(count($pieces)){
 foreach ($pieces as &$row){ 
    ?>
    <li style=" <?php echo $floatleft;?> text-align: center;   margin: 15px;">
    <img width="100px" height="100px" style="border:2px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/pieces/<?php echo $row->symbol_pieces_image;?>"/>
    </li>
 <?php $i++; }}
 else{?>
 <span>The number of pieces had assigned maximum to you</span>
 <?php }
 ?>    
 </ul>
	<!--<tr> 
        <td  style="text-align: center;"><?php// echo $i+1;?></td>
        <td style="text-align: center;"><img width="100px" height="100px" style="border:1px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/<?php echo $row->symbol_image;?>"/></th>
        <td style="text-align: center;"><img width="100px" height="100px" style="border:1px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/pieces/<?php echo $row->symbol_pieces_image;?>"/></th>
        <td style="text-align: center;">
        <input type="checkbox" id="chkpieces" name="chkpieces[]" onClick="isChecked(this.checked);" value="<?php //echo $row->symbol_pieces_id;?>"/>
        </td>
    </tr>

</table>-->
</div>
<br />
<!--<input style="margin-left: 100px;" id="insertpieces" type="button" name="inertpieces" value="Insert Pieces" />-->
<input type="hidden" name="option" value="com_giftcode" />
<input type="hidden" name="qtyass" id="qtyass" value="<?php echo $qty;?>" />
<input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="payment" />
 

</form>
<br/>
<br/>
</body>
<?php
//onunload="function(){;}"
function centToDollar($val){
	if($val >= 100){
		$ret = $val / 100;
		$ret .= " Dollars";
	}else{
		$ret = $val." Cents";
	}
	return $ret;
}
?>
<script language="javascript">
 

  /* jQuery('#insertpieces').click(function(){
        var i=0;
        var j=0;
        var pglobal="";
        var qty=jQuery('#qtyass').val();
        var code=jQuery('#code').val();
        var arr= new Array();
        for(i;i<document.adminForm1.elements.length;i++)
        {  
            var elei=document.adminForm1.elements[i];
            if(elei.type == "checkbox" && elei.name == "chkpieces[]")
            {
              if(elei.checked==true)
              { 
              
                 arr[j]=elei.value;
                  j++;
                pglobal =pglobal+','+elei.value;
               
              } 
               
            }  
        }
        if(arr.length==qty){
            alert('you have selected the pieces number of limit, it must be less than or equal to'+qty);
            return false;
        }
        else{
        location.href='index.php?option=com_giftcode&controller=giftcode&task=insertpieces&piecesid='+pglobal+'&code='+code;
        }
   });
        */
     
</script>