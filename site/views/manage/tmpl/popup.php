<style>
.giftcodepo{
    margin: 10px 0 0;
}
.giftcodepo {
    
}
</style>
<?php
 
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm1">
<div id="editcell">
<?php
$pieces=$this->datap;
$catid=JRequest::getVar('catid');
?>
<!--
<table class="giftcodepo">
<thead>
<td style="width: 10%; text-align: center;">#</td>
<td style="width: 45%; text-align: center;">Symbol pieces</td>
<td style="width: 45%; text-align: center;">Symbol image</td>

</thead>-->
<ul style="list-style: none;">
 <?php
  
 $i=0; 
 $floatleft='float: left; position: relative; top: 60px;';
 if($catid==1 || $catid==2)
 $floatleft='position: relative; top: 120px;';
 foreach ($pieces as &$row){ 
    ?>
     <li style=" <?php echo $floatleft;?> text-align: center;   margin: 15px;">
    <img width="100px" height="100px" style="border:2px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/pieces/<?php echo $row->symbol_pieces_image;?>"/>
    </li>
 <?php $i++; }?>	
 <!--   <tr> 
        <td  style="text-align: center;"><?php echo $i+1;?></td>
        <td style="text-align: center;"><img width="100px" height="100px" style="border:1px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/<?php echo $row->symbol_image;?>"/></th>
        <td style="text-align: center;"><img width="100px" height="100px" style="border:1px solid" src="<?php echo JURI::base(true)?>/administrator/components/com_symbol/asset/symbol/pieces/<?php echo $row->symbol_pieces_image;?>"/></th>
        <td style="text-align: center;">
       
        </td>
    </tr>

</table>-->
</ul>
</div>
<br />

<input type="hidden" name="option" value="com_giftcode" />
<input type="hidden" name="qtyass" id="qtyass" value="<?php echo $qty;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="payment" />
 

</form>
<br/>
<br/>
<?php
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
 