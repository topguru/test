<?php
defined('_JEXEC') or die();
 JHtml::_('behavior.formvalidation');
?>
<p id="f1_upload_process">Loading...<br/><?php echo JHTML::image('/components/com_awardpackage/asset/img/loader.gif', 'Loading'); ?><br/></p>
<form action="index.php" method="post" enctype="multipart/form-data" id="adminForm" name="adminForm">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command'); ?>"/>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-striped" border="0">
					<thead>
						<tr>
							<th><?php echo JText::_('Symbol Pieces');?></th>
						</tr>		
					</thead>
					<tbody>
						<tr>
							<td valign="top">
								
							    <img id="image" src="./components/com_awardpackage/asset/symbol/<?php echo $this->data->symbol_image;?>" style="<?php echo $this->data->symbol_image?'display:block;width:200px;':'display: none';?>"/></td>
							<td valign="top">&nbsp;</td>
					  </tr>
                        
                        <tr>
                        <td valign="bottom">

								<input type="file" id="symbol_image_up" name="symbol_image_up" value="Browse"/> <input id="upload" type="submit" value="Upload"/>
							</td>
                        </tr>
						<tr>
							<td >
                                                 
<table border="1px" id="sliced" >


							    <?php
								    if($this->data->symbol_id!=''){
									    $segment_width = 200/$this->data->cols; //Determine the width of the individual segments
									    $segment_height = 200/$this->data->rows; //Determine the height of the individual segments
									    $show = '';
							    $i=0;
									    for( $rownya = 0; $rownya < $this->data->rows; $rownya++)
										    {  
										       $j=0;
											    $show .= '<tr>';
											    for( $colnya = 0; $colnya < $this->data->cols; $colnya++)
											    {
												    
												    $filename = substr($this->data->symbol_image,0,strlen( $this->data->symbol_image) - 4).$rownya.$colnya.".png";
												    $file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
									 
												    $show .= '<td style="padding:3px;">';
												    //$show .= '<img id="image_'.$i.'_'.$j.'" style="left: 0px; top: 0px; width: '.$segment_width.'px; height:'.$segment_height.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
									$show .= '<img id="image_'.$i.'_'.$j.'" style="left:0px;top:0px;width:'.$segment_width.'px;height:'.$segment_height.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
												    $show .= '</td>';
									$j++;
											    }
											    $show .= '</tr>';
								    $i++;
									    }
									    echo $show;
								    }
							    ?>
                               
							    </table>
							    Slice into<br/><br/> 
							    <input name="cols" id="cols" onkeyup="hitung()" type="text"  value="<?php echo $this->data->cols;?>"/> Cols<br/><br/> 
							    <input name="rows" id="rows" onkeyup="hitung()" type="text"  value="<?php echo $this->data->rows;?>"/> Rows<br/><br/>
							    <input name="pieces" id="pieces" readonly="true" type="text" value="<?php echo $this->data->pieces;?>" /> Pieces	    
							</td>
							
						</tr>	
						<tr>
							<td>
								Symbol Name<br/><br/> 
								<input type="text" name="symbol_name" value="<?php echo $this->data->symbol_name;?>"/>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						    <td style="width:100px"><input type="button" name="slice" id="slice" value="Slice Symbol"/></td>
						    <td style="width:100px">
						    	<!-- 
						    	Symbol Name <input type="text" name="symbol_name" value="<?php echo $this->data->symbol_name;?>"/>
						    	 -->
						    </td>
					    </tr>					 
					</tbody>
				</table>
				<br/>
			    <br/>
			    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
			    <input type="hidden" name="option" value="com_awardpackage" />
			    <input type="hidden" id="task" name="task" value="" />
			    <input type="hidden" name="controller" value="awardsymbol" />
			    <input type="hidden" name="view" value="awardsymbol" />
			    <input type="hidden" name="checkedit" id="checkedit" value="<?php echo $this->data->symbol_image;?>" />
			    <input type="hidden" name="saveedit" id="checkedit" value="<?php echo $this->data->symbol_image;?>" /> 
			    <input type="hidden" name="symbol_image" id="symbol_image" class="required" value="<?php echo $this->data->symbol_image;?>"/>
			    <input type="hidden" name="symbol_id" value="<?php echo $this->data->symbol_id ;?>" />
			    <input type="hidden" name="symbol_pieces"  id="symbol_pieces"/>
                 <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

			</div>
		</div>
	</div>
</div>
<div id="load"></div>
</form>

<script type="text/javascript">
$.noConflict();

jQuery(document).ready(function() {
	jQuery('#f1_upload_process').hide();
	jQuery('#load').hide();
	jQuery("#upload").click(function(){
		jQuery('#task').val('upload');
		jQuery('#f1_upload_process').show();
		jQuery('#adminForm').attr('target','upload_target');
		return true;
	});
	
	jQuery("#slice").click(function(){
		if(jQuery('#symbol_image').val()!=''){
		   
			jQuery('#f1_upload_process').show();
			obj = new Object();
			obj.cols = new Number(jQuery('#cols').val());
			obj.rows = new Number(jQuery('#rows').val());
			obj.image = jQuery('#symbol_image').val();
            jQuery('#checkedit').val(obj.image);
			obj.symbol_pieces = jQuery('#symbol_pieces').val();
			obj.controller = 'awardsymbol';
			obj.task = 'slice'
            
            if(obj.cols==0 || obj.rows==0)
            {
                alert('Enter the cols number or rows number');
                return false;
            }
            else
            {
			 jQuery('#sliced').load('index.php?option=com_awardpackage',obj,function (){
				jQuery('#f1_upload_process').hide();
				//hasil = jQuery("#load").text();
				//jQuery('#sliced').html(hasil);
				
			});	
		  }
		}else{
			 alert("please upload the Complete Symbol first")
             return false;
		}
	});
});

function hitung(){
	var cols = new Number(jQuery('#cols').val());
	var rows = new Number(jQuery('#rows').val());
	jQuery('#pieces').val(cols * rows);
}

function stopUpload(status,source,name){
    
	  if(status == 1){  
		  jQuery('#f1_upload_process').hide();
		  jQuery('#image').attr('src',source);
		  jQuery('#image').css('display','block');
		  jQuery('#image').css('width','200px');
		  jQuery('#symbol_image').val(name);    
		  jQuery('#task').val('');
		  jQuery('#adminForm').attr('target','');
	  }
	  
	  return true;   
}
Joomla.submitbutton = function(task) {
    if(task=='save' || task=='apply')
    {
    	if ( document.formvalidator.isValid(document.id('adminForm'))) {
    	   if(jQuery('#checkedit').val()!=jQuery('#symbol_image').val())
           {
            alert('Symbol image is not slice');
           }
           else
           {
            Joomla.submitform(task, document.getElementById('adminForm'));            
           }

         } else {
            alert('Symbol image is not uploading or slice');
         }
     }
     else
     {
        Joomla.submitform(task, document.getElementById('adminForm'));
     }
}

</script> 
