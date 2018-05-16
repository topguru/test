<div style="float:left;margin:10px">
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=quiz&package_id='.JRequest::getVar('package_id'));?>';" type="button" class="btn btn-small">Backend Admin</button>
  
  <!-- setting quiz and survey for user side -->
  <button onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_awardpackage&view=userquiz&package_id='.JRequest::getVar('package_id'));?>'" type="button" class="btn btn-small">Frontend User</button>  
  <!--button onclick="document.location.href='<?php //echo JRoute::_('index.php?option=com_awardpackage&view=usersurvey&package_id='.JRequest::getVar('package_id'));?>'" type="button" class="btn btn-small">User - Survey</button>
  -->
</div>
