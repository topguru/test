<?php
defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" onsubmit="return false">
<?php
if(count($this->data)>0){   
?>
    <h1><?php echo JText::_('Awards');?></h1>
    <table class="symbollist" cellpadding="1" cellspacing="1">
	<thead>
	<tr>
	    <th style="width: 4%;">#</th>
	    <th style="width: 30%;">Symbol Pieces</th>
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
	</td> 
	 <td>
	    <?php echo $row->symbol_name; ?><br/>
	    <img src="./administrator/components/com_awardpackage/asset/symbol/<?php echo $row->symbol_image; ?>" width="100px"/>
	</td>
	 <td>
	    <img src="./administrator/components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>" width="100px"/>
	    <br/>
	    <b><?php echo $row->prize_name; ?></b>
	</td> 
    </tr>
    
    <?php 
    $k = 1 - $k;
    }
    
    ?>
	 
    </table>
 <?php 
    }else{
        echo 'No data found';
       //echo '<span style="color:red;">You are not selected by the system</span>';
   }   
 ?>
    <input type="hidden" name="option" value="com_awardpackage" />
    
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="award" />

</form>

