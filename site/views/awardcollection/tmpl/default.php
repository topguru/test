<style>
table.symbollist{
    background-color: #F3F3F3;
    border-spacing: 1px;
    color: #666666;
    width: 100%;
}
table.symbollist tbody tr {
    background-color: #FFFFFF;
    text-align: left;
}
table.symbollist tbody tr td {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #FFFFFF;
    text-align: center;
}
 table.symbollist tbody tr.row1 td {
    background: none repeat scroll 0 0 #F0F0F0;
    border-top: 1px solid #FFFFFF;
}
 table.symbollist tbody tr.row1:hover td,table.symbollist tbody tr.row0:hover td {
    background: #E8F6FE
}
table.symbollist th{
   text-align: center;
   padding-top: 5px;
   padding-bottom: 5px;
    background: #CFCFCF;
   
}
.adminlist td{
    text-align: center;
}
.pieceslist{
    margin: 10px;
}	
</style>

<?php
defined('_JEXEC') or die('Restricted access'); ?>

<div style="font-size: 18px; font-weight: bold;">
Symbol collected
</div>
<div id="editcell">
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" onsubmit="return false">
<?php
if(count($this->data)>0){   
?>
<table class="symbollist" >
<thead>
<tr>
    <th style="width: 4%;">#</th>
    <th style="width: 30%;padding-left: 20px;text-align: left;">Symbol Pieces</th>
    <th style="width: 30%;">Symbol Set</th>
    <th style="width: 25%;">Prize </th>
    
</tr>
</thead>
<?php
 

$k = 0;
for ($i=0, $n=count( $this->data ); $i < $n; $i++)
{ 
$row =& $this->data[$i];
 
$view = JRoute::_( 'index.php?option=com_awardpackage&act=view&view=symbol&gcid='. $row->symbol_id );
?>

<tr class="row<?php echo $k ;?>">
    <td><?php echo $i+1;?></td>

    <td>
    <?php
    $model =& $this->getModel();
    $bol=false;
    $datapr=$this->datapr;	
    $datapieces=$model->getDataPieces($row->symbol_id);
   
    if(count($datpr)>0){
    foreach($datapieces as &$dp){
        
       if(in_array($dp->symbol_pieces_id,$datapr)){
        $bol=true;
        break;
       }
    }
    }
    if($bol==true){
    ?>
    <table class="pieceslist" border="1px">
	<?php
	
		$segment_width = 100/$row->cols; //Determine the width of the individual segments
		$segment_height = 100/$row->rows; //Determine the height of the individual segments
		$tampil = '';
		for( $rownya = 0; $rownya < $row->rows; $rownya++)
			{
		        $tampil .= '<tr>';
				for( $colnya = 0; $colnya < $row->cols; $colnya++)
		        {
		            
					$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
					$file = "./administrator/components/com_awardpackage/asset/symbol/pieces/".$filename;
					$tampil .= '<td style="padding:3px;">';
					$tampil .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
					$tampil .= '</td>';
		        }
				$tampil .= '</tr>';
		}
		echo $tampil;
	
	?>
	</table>
    <?php }else{ echo 'No pieces is selected'; }?>
    </td> 
     <td>
     Symbol Name : <?php echo $row->symbol_name; ?><br/>
    <img src="./administrator/components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>" width="100px"/>
    </td>
     <td>
     Prize Name : <?php echo $row->prize_name; ?><br/>
    <img src="./administrator/components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>" width="100px"/> 
    </td> 
</tr>

<?php 
$k = 1 - $k;
}

?>
     
</table>
 <?php 
 }else{
    echo '<span style="color:red;">No pieces is selected</span>';
}   
 ?>
<input type="hidden" name="option" value="com_awardpackage" />

<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="awardcollection" />

</form>
</div>
<br/>
<br/>
<script type="text/javascript">
/*
window.addEvent('domready', function() {
	SqueezeBox.initialize({});
	
	$$('a.modal').each(function(el) {
		el.addEvent('click', function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el);
		});
	});
}); 

 
$(document).ready(function() {
	$(".countpieces").click(function(){
	   var v=$(this.children).val();
       var catid=$(this.children).get(1).value;
		tb_show('The pieces from user\'s queue','index.php?option=com_giftcode&view=manage&pieces_id='+v+'&catid='+catid+'&act=e');
	});
});*/
 
</script>