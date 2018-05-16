<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * DonationList Model
 */
class AwardpackageModelDonation extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.	
		$user =& JFactory::getUser();		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('*');
		
		$query->from('#__ap_donation_transactions')->order('dated ASC')->where('user_id = '.(int) $user->get('id'));
		return $query;
	}
        
        public function SelectGiftCode(){
            
            $db             = &JFactory::getDBO();
            
            $categories     = JRequest::getVar('setting_id');
            
            $User           = JFactory::getUser();
            
            $date           = &JFactory::getDate();
            
            $now            = $date->ToFormat();
            
            foreach($categories as $category){
                
               $Query  = "SELECT * FROM ".$db->QuoteName('#__giftcode_giftcode')." WHERE ".$db->QuoteName('giftcode_category_id')."='".$category."' AND published='1' AND renew_status<>'1'";
                
               $db->setQuery($Query);
                
               $rows = $db->loadObjectList();
               
               foreach($rows as $row){
                
                    if(!$this->CheckGiftCode($User->id,$row->id)){
                        
                        $Query2 ="INSERT INTO ".$db->QuoteName('#__gc_recieve_user')." (user_id,category_id,date_time,status) VALUES ('".$User->id."','".$category."','".$now."','0')";
                            
                        $db->setQuery($Query2);
                            
                        if($db->query()){
                            
                            break;
                        
                        }else{
                            
                            next;

                        }
                    }
               }
                
            }
            
        }
        
        public function CheckGiftCode($userId,$gcid){
            
            $db             = &JFactory::getDBO();
            
            $Query          = "SELECT * FROM ".$db->QuoteName('#__gc_recieve_user')." WHERE user_id='".$userId."' AND gcid='".$gcid."'";
            
            $db->setQuery($Query);
            
            $db->query();
            
            if($db->getNumRows()>0){
                return true;
            }else{
                return false;
            }
        }
	
}
