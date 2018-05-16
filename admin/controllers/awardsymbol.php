<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class AwardPackageControllerawardsymbol extends AwardPackageController  
{
	function display() {
		parent::display();
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
	}
	
	function add() {
		
		JRequest::setVar('view', 'awardsymbol');
		
		JRequest::setVar('layout', 'create');
		
		JRequest::setVar('package_id',JRequest::getVar('package_id'));
		
		parent::display();
	}
	
	function upload(){
	  
		$symbolimg=JRequest::getVar('symbol_image','','post',string);
		
		$result = 0;
		
		$photo = basename( $_FILES['symbol_image_up']['name']);
		
		$c = strrpos($photo,".");
		
		$name =  date("Ymdhis");
		
		$ext = strtolower(substr($photo,$c+1,strlen($photo)-$c));
		
		$target_path = "./components/com_awardpackage/asset/symbol/".$name.".".$ext;
		
		$saveedit=JRequest::getVar('saveedit','','post',string);
		
		if($saveedit=='')
		{
			if($symbolimg!='')
			
			unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.DS.'asset'.DS.'symbol'.DS.$symbolimg);
		}    
			
			if(@move_uploaded_file($_FILES['symbol_image_up']['tmp_name'], $target_path)) {
			
				$result = 1;
			
			}
		    
		sleep(1);
		
		echo "<script language=\"javascript\" type=\"text/javascript\">window.top.window.stopUpload('".$result."','".$target_path."','".$name.".".$ext."');</script>";     
		
		exit();
	}
	
	function slice(){
		
		$numrows = JRequest::getVar('rows');
		
		$numcols = JRequest::getVar('cols');
         
		$image = JRequest::getVar('image');
		
		$symbol_pieces = JRequest::getVar('symbol_pieces');
		
		if($symbol_pieces !=''){
		
			$allpieces = explode('~',$symbol_pieces);
		
			foreach($allpieces  as $roysuryo){			 	
		
				if($roysuryo!=''){
		
					unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.DS.'asset'.DS.'symbol'.DS.'pieces'.DS.$roysuryo);
		
				}
			}  
           
			
		}
		
		$info = pathinfo($image);
		
		switch(strtolower($info['extension'])){
		
			case 'jpg':
						$source = imagecreatefromjpeg(JPATH_ROOT.DS."administrator".DS."components".DS."com_awardpackage".DS."asset".DS."symbol".DS.$image);
						break;
			case 'jpeg':
						$source = imagecreatefromjpeg(JPATH_ROOT.DS."administrator".DS."components".DS."com_awardpackage".DS."asset".DS."symbol".DS.$image);
						break;
			case 'png':
						$source = imagecreatefrompng(JPATH_ROOT.DS."administrator".DS."components".DS."com_awardpackage".DS."asset".DS."symbol".DS.$image);
						break;
			case 'gif':
						$source = imagecreatefromgif(JPATH_ROOT.DS."administrator".DS."components".DS."com_awardpackage".DS."asset".DS."symbol".DS.$image);
						break;
			case 'bmp':
						$source = imagecreatefromwbmp(JPATH_ROOT.DS."administrator".DS."components".DS."com_awardpackage".DS."asset".DS."symbol".DS.$image);
						break;
			default:
						$source = '';
						break;
           
		}
		$width = imagesx( $source ); //Find the width
		
		$height = imagesy( $source ); //F
		
		$segment_width = $width/$numcols; //Determine the width of the individual segments
		
		$segment_height = $height/$numrows; //Determine the height of the individual segments
		
		$filenya = '';
		
		for( $row = 0; $row < $numrows; $row++)
			{
		        $show .= '<tr>';
				for( $col = 0; $col < $numcols; $col++)
		        {
		            $fn = sprintf( "img%02d_%02d.jpg", $col, $row );
			    
			    $im = @imagecreatetruecolor( $segment_width, $segment_height );
			    
			    imagecopyresized($im, $source, 0, 0, $col * $segment_width, $row * $segment_height, $segment_width, $segment_height, $segment_width, $segment_height );
					$filename = substr($image,0,strlen($image) - 4).$row.$col.".png";
					$file = "./components/com_awardpackage/asset/symbol/pieces/".$filename;
					imagepng($im,$file);
					$i++;
					$show .= '<td style="padding:3px;">';
					$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.($segment_width*(200/$width)).'px; height:'.($segment_height*(200/$width)).'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
					$show .= '</td>';
					$filenya 	.= $filename."~";
		        }
				$show .= '</tr>';
		}
		echo $show;
		echo "<script language=\"javascript\" type=\"text/javascript\">jQuery(\"#symbol_pieces\").val('".$filenya."');</script>";
		exit();
	}
	
	function edit() {
		
		JRequest::setVar('view', 'awardsymbol');
		
		JRequest::setVar('layout', 'create');
		
		parent::display();
	}
	
	function publish() {
	}
	
	function preview() {
		
		JRequest::setVar('layout', 'create');
		
		parent::display();
	}
	
	function viewgiftcode() {
		JRequest::setVar('view', 'viewgiftcode');
		JRequest::setVar('layout', 'default');
		parent::display();
	}
	
	function remove() {
		
		$ids = JRequest::getVar('cid');
        
		$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');	
		
		$bool=$model->delete($ids);
		
		if($bool==true)
			$msg = 'Award Symbol Deleted';
		else
			$msg = 'Award Symbol Delete false';
		
		$this->setRedirect('index.php?option=com_awardpackage&view=awardsymbol&package_id='.JRequest::getVar('package_id'), $msg);		
	}
	
		
	function save(){
		
		if(JRequest::getVar('symbol_id')==''){  
		
			$this->do_save(false);
		
			$msg = 'Award Symbol Created';
		}else{
		
			$this->do_save(true);
		
			$msg = 'Award Symbol Edited';
		}
		if(JRequest::getVar('command') == '1') {
			$this->setRedirect('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id'), 'Update symbol set successful');	
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=awardsymbol&package_id='.JRequest::getVar('package_id'), $msg);	
		}				
	}
	
	function apply(){
		
		if(JRequest::getVar('symbol_id')==''){
		
			$ngewa = $this->do_save(false);
		
			$msg = 'Award Symbol Created';
		}else{
		
			$ngewa = $this->do_save(true);
		
			$msg = 'Award Symbol Edited';
		
		}
		
		$this->setRedirect('index.php?option=com_awardpackage&controller=awardsymbol&task=edit&cid[0]='.$ngewa.'&package_id='.JRequest::getVar('package_id'),$msg);		
	}
	
	function do_save($edit){
	 	$data = array(
			'symbol_id' => JRequest::getVar('symbol_id'),
			'date_created' => date('Y-m-d'),
			'symbol_name' => JRequest::getVar('symbol_name'),
			'symbol_image' => JRequest::getVar('symbol_image'),
			'pieces' => JRequest::getVar('pieces'),
			'rows' => JRequest::getVar('rows'),
			'cols' => JRequest::getVar('cols'),
			'package_id'=>JRequest::getVar('package_id'),
			'status'=>'1'			
		);
		$pieces = explode('~',JRequest::getVar('symbol_pieces'));
		
		$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');
		
		$ngewi = $model->saveData($data,$pieces);
		
		return $ngewi;
	}
	
	public function awardsymbolclose(){
		
		$link = 'index.php?option=com_awardpackage';
		
		$this->setRedirect($link);
	
	}
     
}

?>