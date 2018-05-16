<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport( 'joomla.application.application' );
class AwardPackageControllerPolls extends AwardPackageController{

	public function delete(){
		$model  = $this->getModel('polls');
		$id     = JRequest::getVar('id');
		if($model->deletePoll($id)){
			$msg   = 'Delete success';
			$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=mypolls',$msg);
		}
	}

	public function save(){
		$user   = JFactory::getUser();
		$model  = $this->getModel('polls');
		$category = & VBCategory::getInstance();
		$date     = JFactory::getDate();
		$author = $model->checkUser($user->id);
		$data   = new JObject;
		$data->title=JRequest::getVar('title');
		$data->alias=JRequest::getVar('alias');
		$data->question=JRequest::getVar('question');
		$data->catid=JRequest::getVar('category');
		$data->autor_id=$author->id;
		$data->package_id = JRequest::getVar('package_id');
		$data->awardpackage_catid = JRequest::getVar('awardPackagePolls');
		$data->created = $date->toFormat();
		$data->add_comment='1';
		$data->add_comment_access='18';
		$data->allowed_votes='1';
		$data->start_time=$date->toFormat();

		if(empty($data->awardpackage_catid) ){
			$msg='Plase select award package polls first';
			$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=awardpackage_poll',$msg);
		} else {
			//check deposit
			$user_deposit = $model->checkDeposit($user->id);

			//user transaction
			$user_transaction = $model->checkVoteTransaction($user->id);

			$total_depo = $user_deposit-$user_transaction;

			$categories = $category->getApcategoriesId($data->catid);

			$answer=  JRequest::getVar('answer');

			if($categories->poll_price<$total_depo || $categories->poll_price==$total_depo){
				if(!$author->id){
					$data->autor_id=$model->InsertUser();
				}
				if($model->save($data,$answer)){
					$msg='Data has been saved';
					$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=mypolls',$msg);
				}
			}else{
				JError::raiseNotice(100, 'your deposit is low');
				$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=mypolls');
			}
		}		 
	}
	
	public function get_awardpackagetpolls() {
		$awardPackagePolls = JRequest::getVar('categorytotals');
		if(!empty($awardPackagePolls)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=addpoll&awardPackagePolls='.$awardPackagePolls);
		} else {
			$msg='Plase select award package polls first';
			$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=awardpackage_poll',$msg);	
		}		
	}

	public function save_poll(){
		$cids = JRequest::getVar('cid');
		$ad_id = JRequest::getVar('ad_id');
		$model  = $this->getModel('polls');
		$return = FALSE;
		$model->delete_ad_poll($ad_id);
		if($cids){
			foreach ($cids as $cid){
				if($model->save_ad_poll($ad_id,$cid)){
					$return = TRUE;
				}
			}
		}else{
			$msg='No poll selected';
			$this->setRedirect('index.php?option=com_awardpackage&view=polls&layout=selectpolls&ad_id='.JRequest::getVar('ad_id'),$msg);
		}
		if($return){
			$msg ='Poll has been selected';
			$this->setRedirect('index.php?option=com_awardpackage&view=myads',$msg);
		}
	}
}
?>
