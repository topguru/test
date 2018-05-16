<div style="text-align:center;margin-bottom:30px">
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Category settings</button>
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcode&package_id=' . JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Giftcode</button>
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Donation</button>  
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=quiz&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Quiz</button>
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Survey</button>
  
  <!-- setting quiz and survey for user side >
  <button onclick="document.location.href='<?php //echo JRoute::_('index.php?option=com_awardpackage&view=userquiz&package_id='.JRequest::getVar('package_id'));?>'" type="button" class="btn btn-small">User - Quiz</button>  
  <button onclick="document.location.href='<?php //echo JRoute::_('index.php?option=com_awardpackage&view=usersurvey&package_id='.JRequest::getVar('package_id'));?>'" type="button" class="btn btn-small">User - Survey</button>
  <!-- ------------------------------------- -->
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Free</button>
  <!-- <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=giftcode&package_id='.JRequest::getVar('package_id'));?>';">Giftcode</button> -->
</div>
