<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');	
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');

$app = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$headerstuff = $document->getHeadData();
$jpath = JURI::root();
//$document->addStyleSheet($jpath . 'templates/beez3/css/personal.css', 'text/css');
$customstyle = "#main-container .panel h3.pane-toggler a {
    padding-bottom: 5px;
    padding-top: 6px;
}";
$document->addStyleDeclaration($customstyle);
// Add Javascript
$document->addScriptDeclaration("jQuery(document).ready(function($){
        $(function() {
            $('#main-container').find('.title').each( function () {
                var _t = $(this);
                _t.on('click', function(){
                    if( _t.hasClass('pane-toggler-down') ){
                        _t.removeClass('pane-toggler-down');
                    }
                    else{
                        _t.addClass('pane-toggler-down');
                    }
                    var container = $(this).next();
                    container.toggle();
                });
            });
            $('#collapse-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').hide();
                $(this).parents('#main-container').find('.title').removeClass('pane-toggler-down');
            });
            $('#expand-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').show();
                $(this).parents('#main-container').find('.title').addClass('pane-toggler-down');
            });
        });
    });");
?>
<script type="text/javascript">
function onAddPresentationUserGroup(package_id){
	var package_id = jQuery('#package_id').val();	
	jQuery('#task').val('apresentationlist.addusergroup');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='+package_id);
	jQuery('form#adminForm').submit();
        window.location.replace("index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id="+package_id);
}
function onAddProcessPresentation(package_id){
	jQuery('#task').val('apresentationlist.addPresentation');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist');
	jQuery('form#adminForm').submit();
}
function openModalUserGroups(package_id,criteria_id){
	//jQuery('#userGroupsModalWindow').modal('show');
	var var_id = jQuery("#var_id").val();	
	var processPresentation = jQuery("#processPresentation").val();
	window.location = "index.php?option=com_awardpackage&view=usergroup&package_id="+package_id+"&criteria_id=0&command=1&processPresentation="+processPresentation+"&var_id=" + var_id;	
}
function onCloseUserGroupModalWindow(e){
	if(jQuery(e).is(':checked')) {
		//var id = parseInt(jQuery("input[type='radio'].radioUserGroupClass").val()) + 1;
		var tr = jQuery(e).parent().parent();
		var id = jQuery(tr).find("td:eq(0)").find("input[type='hidden']").val();	
		var usergroups = jQuery(tr).find("td:eq(1)").text();
		var users = jQuery(tr).find("td:eq(3)").text();
		jQuery("#idUserGroups").text(usergroups);
		jQuery("#idUsers").text(users);
		jQuery("#idUserGroupsId").val(id);
		jQuery('#userGroupsModalWindow').modal('toggle');				    			    			   
	}
}
function onProcessPresentation(){
	var groupId = jQuery("#idUserGroupsId").val();
	var package_id = jQuery("#package_id").val();
	var var_id = jQuery("#var_id").val();
	var processPresentation = jQuery("#processPresentation").val();
	window.location = "index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id="+package_id+"&idUserGroupsId="+groupId+"&processPresentation="+processPresentation+'&var_id='+var_id;
}
</script>
<?php require(JPATH_COMPONENT_ADMINISTRATOR . '/views/bpresentationlist/tmpl/default_left.php'); ?>

<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist')?>" method="post">
	<input type="hidden" name="task" id="task" value="">
	<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
	<input type="hidden" name="var_id" id="var_id" value="<?php echo (!empty($this->var_id) ? $this->var_id : JRequest::getVar('var_id')); ?>">
	<input type="hidden" name="processPresentation" id="processPresentation" value="<?php echo (empty($this->processPresentation) ? '0' : $this->processPresentation); ?>">	
	<input type="hidden" name="pids" id="pids" value="<?php echo $this->pids; ?>">
	<input type="hidden" name="boxchecked" value="0" />
<table width="100%">
<tr>
<td>
<table class="table table-bordered" width="100%">
	<tr>
	<td style="text-align:right;" colspan="2">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>									
											</td>
    </tr>
	<tr>
    														<th><?php echo JText::_( 'Presentation User Group' ); ?></th>								
															<th><?php echo JText::_( 'Assigned Symbol Queue' ); ?></th>
	</tr>
	<tr>
    <?php if(!empty($this->usergroups)) { ?>
														<?php //if (!empty($this->selectedGroups)) { ?>
														<?php foreach ($this->usergroups  as $group){ ?>
														<tr>
															<td>
																<!-- <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo $group->criteria_id ?>');"><?php echo strtoupper($group->group_name); ?></span> 
																 -->
																 <!-- 
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '-1');"><?php echo count($this->usergroups); ?></span>
																 -->
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo JRequest::getVar('idUserGroupsId'); ?>');">
																		<?php 
																			echo strtoupper($group->group_name);																			 
																		?>
																</span>
															</td>
															<td>
																<?php
																	$totalAccounts = 0; 
																	//foreach ($this->usergroups as $group){
																	$totalAccounts = $totalAccounts + (int) $group->accounts;
																	//} 
																?>
																<!-- 
																<span id="idUsers"><?php echo $group->accounts; ?></span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="<?php echo $group->criteria_id; ?>">
																 -->
																<span id="idUsers"><?php echo $totalAccounts ?></span>
																<input type=hidden name="idUserGroupsId" 
																	id="idUserGroupsId" 
																	value="<?php echo JRequest::getVar('idUserGroupsId'); ?>">
															</td>
														</tr>
														<?php  } ?>
														<?php } else { ?>
														<tr>
															<td>
																<span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" 
																	onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '0');">New</span>
															</td>
															<!--td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span id="idUsers">&nbsp;</span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="">
															</td-->
                                                            <td><a href="index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo (count($this->presentations) > 0 ? count($this->presentations) : 'New'); ?></a></td>
														</tr>
														<?php } ?>		
	</tr>

</table>
</td>

<td>
<table class="table table-bordered" width="100%">
	<tr>
	<td style="text-align:right;" colspan="2">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>									
											</td>
    </tr>
	<tr>
    														<th><?php echo JText::_( 'Presentation User Group' ); ?></th>								
															<th><?php echo JText::_( 'Assigned Symbol Queue' ); ?></th>
	</tr>
	<tr>
    <?php if(!empty($this->usergroups)) { ?>
														<?php //if (!empty($this->selectedGroups)) { ?>
														<?php foreach ($this->usergroups  as $group){ ?>
														<tr>
															<td>
																<!-- <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo $group->criteria_id ?>');"><?php echo strtoupper($group->group_name); ?></span> 
																 -->
																 <!-- 
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '-1');"><?php echo count($this->usergroups); ?></span>
																 -->
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo JRequest::getVar('idUserGroupsId'); ?>');">
																		<?php 
																			echo strtoupper($group->group_name);																			 
																		?>
																</span>
															</td>
															<td>
																<?php
																	$totalAccounts = 0; 
																	//foreach ($this->usergroups as $group){
																	$totalAccounts = $totalAccounts + (int) $group->accounts;
																	//} 
																?>
																<!-- 
																<span id="idUsers"><?php echo $group->accounts; ?></span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="<?php echo $group->criteria_id; ?>">
																 -->
																<span id="idUsers"><?php echo $totalAccounts ?></span>
																<input type=hidden name="idUserGroupsId" 
																	id="idUserGroupsId" 
																	value="<?php echo JRequest::getVar('idUserGroupsId'); ?>">
															</td>
														</tr>
														<?php  } ?>
														<?php } else { ?>
														<tr>
															<td>
																<span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" 
																	onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '0');">New</span>
															</td>
															<!--td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span id="idUsers">&nbsp;</span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="">
															</td-->
                                                            <td><a href="index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo (count($this->presentations) > 0 ? count($this->presentations) : 'New'); ?></a></td>
														</tr>
														<?php } ?>		
	</tr>

</table>

</td>

</tr>
<td colspan="2">

<table class="table table-bordered" width="100%">
	<tr>
	<td style="text-align:right;" colspan="2">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>									
											</td>
    </tr>
	<tr>
    														<th><?php echo JText::_( 'Presentation User Group' ); ?></th>								
															<th><?php echo JText::_( 'Assigned Symbol Queue' ); ?></th>
	</tr>
	<tr>
    <?php if(!empty($this->usergroups)) { ?>
														<?php //if (!empty($this->selectedGroups)) { ?>
														<?php foreach ($this->usergroups  as $group){ ?>
														<tr>
															<td>
																<!-- <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo $group->criteria_id ?>');"><?php echo strtoupper($group->group_name); ?></span> 
																 -->
																 <!-- 
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '-1');"><?php echo count($this->usergroups); ?></span>
																 -->
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo JRequest::getVar('idUserGroupsId'); ?>');">
																		<?php 
																			echo strtoupper($group->group_name);																			 
																		?>
																</span>
															</td>
															<td>
																<?php
																	$totalAccounts = 0; 
																	//foreach ($this->usergroups as $group){
																	$totalAccounts = $totalAccounts + (int) $group->accounts;
																	//} 
																?>
																<!-- 
																<span id="idUsers"><?php echo $group->accounts; ?></span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="<?php echo $group->criteria_id; ?>">
																 -->
																<span id="idUsers"><?php echo $totalAccounts ?></span>
																<input type=hidden name="idUserGroupsId" 
																	id="idUserGroupsId" 
																	value="<?php echo JRequest::getVar('idUserGroupsId'); ?>">
															</td>
														</tr>
														<?php  } ?>
														<?php } else { ?>
														<tr>
															<td>
																<span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" 
																	onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '0');">New</span>
															</td>
															<!--td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span id="idUsers">&nbsp;</span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="">
															</td-->
                                                            <td><a href="index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo (count($this->presentations) > 0 ? count($this->presentations) : 'New'); ?></a></td>
														</tr>
														<?php } ?>		
	</tr>

</table>

</td>
<tr>
</tr>

    </table>
    
    
					<table class="table table-bordered" style="width:100%;">
		
		<tr>
			<td>
				
										<tr>
											<td style="text-align:right;">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>									
											</td>
										</tr>
										
										<tr>
											<td colspan="3">
					<table class="table table-bordered" style="width:100%;" border="1">
														<tr>
															<th><?php echo JText::_( 'Presentation User Group' ); ?></th>								
															<th><?php echo JText::_( 'Presentation Users' ); ?></th>																							
														</tr>
														<?php if(!empty($this->usergroups)) { ?>
														<?php //if (!empty($this->selectedGroups)) { ?>
														<?php foreach ($this->usergroups  as $group){ ?>
														<tr>
															<td>
																<!-- <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo $group->criteria_id ?>');"><?php echo strtoupper($group->group_name); ?></span> 
																 -->
																 <!-- 
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '-1');"><?php echo count($this->usergroups); ?></span>
																 -->
																 <span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '<?php echo JRequest::getVar('idUserGroupsId'); ?>');">
																		<?php 
																			echo strtoupper($group->group_name);																			 
																		?>
																</span>
															</td>
															<td>
																<?php
																	$totalAccounts = 0; 
																	//foreach ($this->usergroups as $group){
																	$totalAccounts = $totalAccounts + (int) $group->accounts;
																	//} 
																?>
																<!-- 
																<span id="idUsers"><?php echo $group->accounts; ?></span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="<?php echo $group->criteria_id; ?>">
																 -->
																<span id="idUsers"><?php echo $totalAccounts ?></span>
																<input type=hidden name="idUserGroupsId" 
																	id="idUserGroupsId" 
																	value="<?php echo JRequest::getVar('idUserGroupsId'); ?>">
															</td>
														</tr>
														<?php  } ?>
														<?php } else { ?>
														<tr>
															<td>
																<span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" 
																	onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '0');">New</span>
															</td>
															<!--td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span id="idUsers">&nbsp;</span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="">
															</td-->
                                                            <td><a href="index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo (count($this->presentations) > 0 ? count($this->presentations) : 'New'); ?></a></td>
														</tr>
														<?php } ?>														
												</table>
											</td>										
										</tr>
									</table>							
			</td>
			<td>
					<table class="table table-bordered" style="width:100%;">
										<tr>
											<td>&nbsp;</td>
											<td align="right">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddProcessPresentation();" id="addProcessPresentationBtn"><i></i> <?php echo JText::_('Add');?></button>																																																			
											</td>
										</tr>
																			
										<tr>
											<td colspan="3">
					<table class="table table-bordered" style="width:100%;">
													<thead>
														<tr>
															<th><?php echo JText::_( 'Process Presentation' ); ?></th>						
														</tr>
													</thead>
													<tbody>														
														<tr>		
															<?php 
																//if ($this->detailPresentation != null){
																if( !empty($this->processPresentation)) { 
															?>
															<td>
																<span style="text-decoration:underline;cursor:pointer;" onclick="onProcessPresentation();">
																	<?php 
																		//echo $this->process_symbol_id;
																		//echo $this->detailPresentation->presentations;
																		echo $this->jml_pres;																																					 
																	?>																	
																</span>	
																<input type="hidden" name="process_symbol_id" id="process_symbol_id" value="<?php echo $this->process_symbol_id; ?>"/>														
															</td>
															<?php } else { ?>
															<td>
																<span style="text-decoration:underline;cursor:pointer;" onclick="onProcessPresentation();">
																	New
																</span>	
																<input type="hidden" name="process_symbol_id" id="process_symbol_id" value=""/>														
															</td>
															<?php } ?>																																																				
														</tr>														
													</tbody>													
												</table>
											</td>										
										</tr>
									</table>
							
			</td>
		</tr>
		
		<tr>
			<td colspan="3">
					<table class="table table-striped table-hover table-bordered" style="width:100%;">
					<thead>
						<tr>															
							<!-- <th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th> -->
							<th><?php echo JText::_( '#' ); ?></th>							
							<th><?php echo JText::_( 'Presentation User Group' ); ?></th>
							<th><?php echo JText::_( 'Presentation Users' ); ?></th>
							<th><?php echo JText::_( 'Process Presentation' ); ?></th>						
							<th><?php echo JText::_( 'Total % of each $0.01 from all user groups to fund prize' ); ?></th>
							<th><?php echo JText::_( 'Fund Prize History' ); ?></th>																													
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 0; 
							foreach ($this->userGroupsPresentation as $row):
						?>
						<tr>
							<td><input type="checkbox" value="<?php echo $row->id ?>" name="cid[]" id="cb<?php echo $i; ?>" onclick="Joomla.isChecked(this.checked);"></td>
							<td>
								<!-- <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='. $row->usergroup . '&processPresentation=' . $row->process_presentation );?>"> -->
								<a style="font-weight:bold;" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id='.JRequest::getVar('package_id').'&criteria_id='.$row->usergroup.'&command=1&processPresentation='.$row->process_presentation)?>">
								<?php echo strtoupper($row->presentation_user_group) ; ?>
								</a>
							</td>
							<td>
								<?php echo $row->presentation_users; ?>								
							</td>																							
							<td>
								<a style="font-weight:bold;" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='.$row->usergroup.'&processPresentation='.$row->process_presentation) ?>">
								<?php
									if(!empty($row->selected_presentation)){
										$spres = $row->selected_presentation;
										echo count(explode(',', $spres));
									}									 
								?>
								</a>								
							</td>
							<td><?php echo $row->funding ?></td>
							<td>
								<a href="index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.fundPrizeHistory&package_id=<?php echo JRequest::getVar('package_id'); ?>"><b>View</b></a></td>
						</tr>
						<?php
							$i++; 
							endforeach;
						?>
					</tbody>													
				</table>
			</td>			
		</tr>
	</table>
</form>
<div id="userGroupsModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User Groups');?></h3>
	</div>
	<table class="table table-striped" id="userGroupTable"
		style="border: 1px solid #ccc;">
		<thead>
			<tr style="background-color:#CCCCCC">
				<th width="5%">&nbsp;</th>
				<th><u><?php echo JText::_('Group Name')?></u></th>
				<th><u><?php echo JText::_('Group Field')?></u></th>
				<th><u><?php echo JText::_('User Accounts')?></u></th>				
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0; 
			foreach ($this->usergroups as $usergroup){ ?>
			<tr>
				<td>
					<input type="radio" name="radio_prize" class="radioUserGroupClass" 
					value="<?php echo $i; ?>" onclick="onCloseUserGroupModalWindow(this);"/>
					<input type="hidden" value="<?php echo JText::_($usergroup->criteria_id); ?>">
				</td>
				<td>
					<?php echo JText::_($usergroup->group_name); ?>
				</td>
				<td>
					<?php echo JText::_( strtoupper($usergroup->field)); ?>
				</td>
				<td>
					<?php echo $usergroup->accounts; ?>
				</td>				
			</tr>		
			<?php
			$i++;
			} 
			?>
		</tbody>
	</table>	
</div>