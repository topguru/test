<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();

$page_id = 6;
$itemid = '';
$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate','form')));
?>

<div id="cj-wrapper">
	
	<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys')?>">
		<input type="hidden" name="option" value="<?php echo S_APP_NAME;?>">
		<input type="hidden" name="view" value="surveys">
		<input type="hidden" name="task" value="list">
	</form>
	
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<div class="span12">

				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#invite-basic-details" data-toggle="tab"><?php echo JText::_('LBL_ENTER_BASIC_DETAILS')?></a></li>
						<li><a href="#invite-manually" data-toggle="tab"><?php echo JText::_('LBL_INVITE_MANUALLY')?></a></li>
						<li><a href="#invite-contact-groups" data-toggle="tab"><?php echo JText::_('LBL_INVITE_CONTACT_GROUPS')?></a></li>
						<?php if($user->authorise('core.manage', S_APP_NAME)):?>
						<li><a href="#invite-registered-groups" data-toggle="tab"><?php echo JText::_('LBL_INVITE_REGISTERED_GROUPS')?></a></li>
						<li><a href="#invite-registered-users" data-toggle="tab"><?php echo JText::_('LBL_INVITE_REGISTERED_USERS')?></a></li>
						<li><a href="#invite-acymailing-groups" data-toggle="tab"><?php echo JText::_('LBL_INVITE_ACYMAILING_GROUPS')?></a></li>
						<?php endif;?>
						<li><a href="#invite-jomsocial-groups" data-toggle="tab"><?php echo JText::_('LBL_INVITE_JOMSOCIAL_GROUPS')?></a></li>
					</ul>
					
					<div class="tab-content">
						<div id="invite-basic-details" class="tab-pane active">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('basics'); ?>
							</div>
						</div>
						<div id="invite-manually" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('manual'); ?>
							</div>
						</div>
						<div id="invite-contact-groups" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('contacts'); ?>
							</div>
						</div>
						<?php if($user->authorise('core.manage', S_APP_NAME)):?>
						<div id="invite-registered-groups" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('groups'); ?>
							</div>
						</div>
						<div id="invite-registered-users" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('users'); ?>
							</div>
						</div>
						<div id="invite-acymailing-groups" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('acymailing'); ?>
							</div>
						</div>
						<?php endif;?>
						<div id="invite-jomsocial-groups" class="tab-pane">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('jomsocial'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<input name="cjpageid" id="cjpageid" type="hidden" value="survey_invite">
		
	<div id="message-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
		</div>
		<div class="modal-body"></div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
		</div>
	</div>
	
	<div id="confirm-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="confirmModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
		</div>
		<div class="modal-body"><?php echo JText::_('MSG_CONFIRM')?></div>
		<div class="modal-footer">
			<button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <?php echo JText::_('LBL_CLOSE');?></button>
			<button class="btn btn-primary btn-confirm" aria-hidden="true"><i class="icon-thumbs-up icon-white"></i> <?php echo JText::_('LBL_CONFIRM');?></button>
		</div>
	</div>
</div>