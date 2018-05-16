<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
//jimport(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'giftcode.php');
class AwardPackageModelAwardcollection extends JModelLegacy {

	var $_data;
  
function getData()
{

	if(empty($this->_data))
	{
	    $userid=&JFactory::getUser();
      	$this->_data=$this->_getList( "
        SELECT jos_symbol_symbol_prize.*,jos_symbol_prize.prize_name,jos_symbol_prize.prize_image,
        jos_symbol_symbol.symbol_name,jos_symbol_symbol.symbol_image,jos_symbol_symbol.rows,jos_symbol_symbol.cols 
        FROM (jos_symbol_symbol_prize INNER JOIN jos_symbol_prize USING (id)) INNER JOIN jos_symbol_symbol USING (symbol_id) 
        where symbol_id in(select symbol_id from jos_symbol_symbol_pieces)
        ORDER BY symbol_prize_id DESC
        " );
	/*	$this->_data=$this->_getList( "
        SELECT jos_symbol_symbol_prize.*,jos_symbol_prize.prize_name,jos_symbol_prize.prize_image,
        jos_symbol_symbol.symbol_name,jos_symbol_symbol.symbol_image,jos_symbol_symbol.rows,jos_symbol_symbol.cols 
        FROM (jos_symbol_symbol_prize INNER JOIN jos_symbol_prize USING (id)) INNER JOIN jos_symbol_symbol USING (symbol_id) 
        where symbol_id in(select symbol_id from jos_symbol_symbol_pieces where symbol_pieces_id in (".$strpie."))
        ORDER BY symbol_prize_id DESC
        " );*/
	}
    
	return $this->_data;

}
function getDataPiecesReceive(){
     $userid=&JFactory::getUser();
       $pieces_ids=$this->_getList("select pieces_id from jos_gc_receive_user where user_id=".$userid->id);
       $strpie="";
       $tmp="";
       foreach($pieces_ids as &$pie){
        $strpie.=$tmp.$pie->pieces_id;
        $tmp=",";
       }
       if($strpie!=''){
        $pieces=explode(',',$strpie);
        }
     return $pieces;
}
function getDataPieces($symbolid){
       $pieces_ids=$this->_getList("select symbol_pieces_id from jos_symbol_symbol_pieces where symbol_id=".$symbolid);
       return $pieces_ids;
}
 
}
