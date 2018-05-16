	<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * Categories Model
 */
class AwardpackageModelCategories extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery(){
		$app = &JFactory::getApplication();
		$db = JFactory::getDBO();
		$user =& JFactory::getUser();
		$db->setQuery("SELECT * FROM `#__ap_useraccounts` WHERE id = '".$user->get('id')."' LIMIT 1");
		$row = $db->loadAssoc();
		//print_r($row);
		if(count($row)<=0){
			$app->redirect('index.php?option=com_awardpackage&view=address', $msg);	
		}else{
			$package_id = $row['package_id'];
			if($package_id>0){			
				$pack_query = "SELECT * FROM `#__ap_awardpackages` WHERE package_id = '$package_id'";
				$db->setQuery($pack_query);
				$rs = $db->loadAssoc();
				if(count($rs)>0){			
					if($rs['published'] == 1 ){
						if(strtotime(date("r")) >= strtotime($rs['start_date']) AND strtotime(date("r")) <= strtotime($rs['end_date'])){
							$query = $db->getQuery(true);
							$query->select('*');
							JRequest::setVar('package_id',$package_id);
							$query->from('#__ap_categories')->where('published = 1')->where("package_id = '$package_id'")->order('category_id ASC');
								return $query;	
						}else{
							if(strtotime(date("r")) > strtotime($rs['end_date'])){
								$app->redirect('index.php?option=com_awardpackage&view=welcome', JTEXT::_('Your package award has been expired'));								
							}else{
								if(strtotime(date("r")) < strtotime($rs['start_date'])){
									$app->redirect('index.php?option=com_awardpackage&view=welcome', JTEXT::_('You may be selected from '.$rs['start_date'].' on'));																
								}
							}
						}
					}else{
						$app->redirect('index.php?option=com_awardpackage&view=welcome', JTEXT::_('Your package award is unpublished'));	
					}
				}else{
					$app->redirect('index.php?option=com_awardpackage&view=welcome', JTEXT::_('Your package is not found'));					
				}
			}else{
					$app->redirect('index.php?option=com_awardpackage&view=welcome', JTEXT::_('Your are not selected by system'));					
			}
		}
	}
	
}
