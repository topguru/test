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

?>
<script type="text/javascript">
function onAddPresentationUserGroup(package_id){
	var package_id = jQuery('#package_id').val();	
	jQuery('#task').val('apresentationlist.addusergroup');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=apresentationlist');
	jQuery('form#adminForm').submit();
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
<form name="adminForm" id="adminForm" class="survey-form" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist')?>" method="post">
	<input type="hidden" name="task" id="task" value="">
	<input type="hidden" name="package_id" id="package_id" value="<?php echo JRequest::getVar('package_id'); ?>">
	<input type="hidden" name="var_id" id="var_id" value="<?php echo (!empty($this->var_id) ? $this->var_id : JRequest::getVar('var_id')); ?>">
	<input type="hidden" name="processPresentation" id="processPresentation" value="<?php echo (empty($this->processPresentation) ? '0' : $this->processPresentation); ?>">	
	<input type="hidden" name="pids" id="pids" value="<?php echo $this->pids; ?>">
	<input type="hidden" name="boxchecked" value="0" />
	<table width="100%">
		<tr>
			<td colspan="3">
				<table class="table table-hover table-striped" border="1" 
					style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;width:50%">
					<thead>
						<tr>															
							<th width="30%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation' ); ?></th>
							<th width="30%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation User Group' ); ?></th>
							<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation Report' ); ?></th>				
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><a href="index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo (count($this->presentations) > 0 ? count($this->presentations) : 'New'); ?></a></td>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo count($this->userGroupsPresentation); ?></td>																							
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><a href="">View</a></td>							
						</tr>						
					</tbody>													
				</table>
			</td>			
		</tr>		
		<tr>
			<td colspan="3"><hr/></td>
		</tr>
		<tr>
			<td width="51%" valign="top">
				<div id="cj-wrapper"
					style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
					<div
						class="container-fluid no-space-left no-space-right surveys-wrapper">
						<div class="row-fluid">
							<div class="span10">
								<div class="clearfix">
									<table width="100%">
										<tr>
											<td width="46%">&nbsp;
												
											</td>
											<td width="1%">&nbsp;</td>
											<td align="right">
												<div class="clearfix">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddPresentationUserGroup();" id="addPresentationUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>									
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">
												<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
													<thead>
														<tr>
															<th width="60%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation User Group' ); ?></th>								
															<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation Users' ); ?></th>																							
														</tr>
													</thead>
													<tbody>
														<?php if(!empty($this->usergroups)) { ?>
														<?php //if (!empty($this->selectedGroups)) { ?>
														<?php foreach ($this->usergroups  as $group){ ?>
														<tr>
															<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
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
															<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
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
															<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span style="text-decoration:underline;cursor:pointer" 
																	id="idUserGroups" 
																	onclick="openModalUserGroups('<?php echo JRequest::getVar('package_id') ?>', '0');">New</span>
															</td>
															<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
																<span id="idUsers">&nbsp;</span>
																<input type="hidden" name="idUserGroupsId" id="idUserGroupsId" value="">
															</td>
														</tr>
														<?php } ?>														
													</tbody>													
												</table>
											</td>										
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</td>
			<td width="1%">&nbsp;</td>
			<td width="48%" valign="top">
				<div id="cj-wrapper"
					style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">
					<div
						class="container-fluid no-space-left no-space-right surveys-wrapper">
						<div class="row-fluid">
							<div class="span10">
								<div class="clearfix">
									<table width="100%">
										<tr>
											<td width="46%">&nbsp;
												
											</td>
											<td width="1%">&nbsp;</td>
											<td align="right">
												<div class="clearfix">
													<button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddProcessPresentation();" id="addProcessPresentationBtn"><i></i> <?php echo JText::_('Add');?></button>																																																			
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>										
										<tr>
											<td colspan="3">
												<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
													<thead>
														<tr>
															<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Process Presentation' ); ?></th>						
														</tr>
													</thead>
													<tbody>														
														<tr>		
															<?php 
																//if ($this->detailPresentation != null){
																if( !empty($this->processPresentation)) { 
															?>
															<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
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
															<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
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
								</div>
							</div>
						</div>
					</div>					
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3"><hr/></td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="table table-hover table-striped" border="1" style="border-width: 1px; border-style: solid; border-color: #ccc #ccc #ccc #ccc;">
					<thead>
						<tr>															
							<!-- <th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th> -->
							<th width="20" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( '#' ); ?></th>							
							<th width="15%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation User Group' ); ?></th>
							<th width="15%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Presentation Users' ); ?></th>
							<th width="15%" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Process Presentation' ); ?></th>						
							<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Total % of each $0.01 from all user groups to fund prize' ); ?></th>
							<th style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo JText::_( 'Fund Prize History' ); ?></th>																													
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 0; 
							foreach ($this->userGroupsPresentation as $row):
						?>
						<tr>
							<td style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><input type="checkbox" value="<?php echo $row->id ?>" name="cid[]" id="cb<?php echo $i; ?>" onclick="Joomla.isChecked(this.checked);"></td>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<!-- <a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='. $row->usergroup . '&processPresentation=' . $row->process_presentation );?>"> -->
								<a style="font-weight:bold;" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usergroup&package_id='.JRequest::getVar('package_id').'&criteria_id='.$row->usergroup.'&command=1&processPresentation='.$row->process_presentation)?>">
								<?php echo strtoupper($row->presentation_user_group) ; ?>
								</a>
							</td>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<?php echo $row->presentation_users; ?>								
							</td>																							
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
								<a style="font-weight:bold;" href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='.$row->usergroup.'&processPresentation='.$row->process_presentation) ?>">
								<?php
									if(!empty($row->selected_presentation)){
										$spres = $row->selected_presentation;
										echo count(explode(',', $spres));
									}									 
								?>
								</a>								
							</td>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;"><?php echo $row->funding ?></td>
							<td class="hidden-phone" style="border-width: 1px; border-style: solid; border-color: #000 #000 #000 #000;">
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