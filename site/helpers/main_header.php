<?php
defined('_JEXEC') or die();
$user = JFactory::getUser();
?>

<div id='cssmenu'>
<ul>
	<li
		class="<?php echo $this->page_id == "1" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uaccount&task=uaccount.getMainPage');?>"><?php echo JText::_('Account')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "2" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ufunding&task=ufunding.getMainPage');?>"><?php echo JText::_('Funds')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "3" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=udonation&task=udonation.getMainPage');?>"><?php echo JText::_('Donation')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "4" ? ' active' : '' ?>">
	<a
		href="index.php?option=com_awardpackage&view=quiz&task=quiz.get_latest_quizzes"><?php echo JText::_('Quiz')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "5" ? ' active' : '' ?>">
	<a
		href="index.php?option=com_awardpackage&view=survey&task=survey.get_all_surveys"><?php echo JText::_('Survey')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "6" ? ' active' : '' ?>">
	<a
		href="index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage"><?php echo JText::_('Giftcode')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "7" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=prize&task=prize.getMainPage');?>"><?php echo JText::_('Prizes')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "8" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage');?>"><?php echo JText::_('Shopping Credits')?></a>
	</li>
					<li class="divider-vertical"></li>
					<!--li class="dropdown">
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" ><?php echo JText::_('Logout')?></a>
					</li-->
</ul>
</div>


