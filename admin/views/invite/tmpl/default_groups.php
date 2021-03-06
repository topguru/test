<?php 
/**
 * @version		$Id: default_registered.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
$itemid = CJFunctions::get_active_menu_id();
?>
<div class="invite-registered-wrapper">
	<div class="margin-top-10 margin-bottom-10">
		<button type="button" class="btn btn-primary btn-invite-reg-groups"><i class="icon-share icon-white"></i> <?php echo JText::_('LBL_INVITE');?></button>
	</div>
	
	<div class="alert alert-error alert-no-user-group-selected hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_USER_GROUP_TO_CONTINUE')?></div>
	
	<?php echo JHTML::_('access.usergroups', 'group_id', array(), 'multiple="multiple" size="6"');?>
	
	<div style="display: none;">
		<span id="url_invite_registered_groups"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=invite.invite_registered_user_groups&id='.$this->item->id.$itemid)?></span>
	</div>
</div>