<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
	 }
	 
	 function validNumber(req){
	 	switch(req){
			case'age':
				var from_age = document.getElementById('jform_from_age');
				var to_age = document.getElementById('jform_to_age');
				if(isNaN(from_age.value)){
					alert('From age number only');
					from_age.focus();
					return false;
				}
				if(isNaN(to_age.value)){
					alert('To Age number only');
					to_age.focus();
					return false;
				}
			break;
			case'loc':
				var post_code = document.getElementById('jform_post_code');
				if(isNaN(post_code.value)){
					alert('Post code number only');
					post_code.value='';
					post_code.focus();
					return false;
				}
			break;
		}
	 }
	 function openModalUserEmail(){
		jQuery('#userEmailModalWindow').modal('show');
	 }
	 function openModalUserName(){
		 jQuery('#userNameModalWindow').modal('show');
	 }
	 function onSelectUserEmail(){
		var user_selected = "";
		jQuery('#task').val('addNewUserEmail');
		$('input:checkbox[name=nameaccountid]').each(function(){    
		    if($(this).is(':checked'))
		      user_selected += $(this).val() + ",";
		});
		jQuery('#user_selected').val(user_selected);
		jQuery('form#defaultForm').attr('action', 'index.php?option=com_awardpackage&controller=packageuser');
		jQuery('form#defaultForm').submit();	
	 }
	 function onSelectUserName(){
		var user_selected = "";
		jQuery('#task').val('addNewUserName');
		$('input:checkbox[name=nameaccountid]').each(function(){    
		    if($(this).is(':checked'))
		      user_selected += $(this).val() + ",";
		});
		jQuery('#user_selected').val(user_selected);
		jQuery('form#defaultForm').attr('action', 'index.php?option=com_awardpackage&controller=packageuser');
		jQuery('form#defaultForm').submit();
	 }
</script>

<form method="post" name="defaultForm" id="defaultForm" action="">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
<input type="hidden" name="act" value="<?php echo $this->act; ?>">
<input type="hidden" name="task" id="task" value="save_packageuser" />								
<input type="hidden" name="category_id" value="<?php echo JRequest::getVar('category_id'); ?>" />
<input type="hidden" name="user_selected" id="user_selected" value=""/>
<div id="cj-wrapper" style="font-size: 12px;">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid" >
			<div class="span6" style="font-size: 11px;">
				<div id="tabs">
				    <ul>
				      <li<?php if ($this->field == 'name') echo $this->class; ?>><a href="#tabs-0">Name</a></li>
				      <li<?php if ($this->field == 'email') echo $this->class; ?>><a href="#tabs-1">E-mail</a></li>
				      <li<?php if ($this->field == 'age') echo $this->class; ?>><a href="#tabs-2">Age</a></li>
				      <li<?php if ($this->field == 'gender') echo $this->class; ?>><a href="#tabs-3">Gender</a></li>
				      <li<?php if ($this->field == 'location') echo $this->class; ?>><a href="#tabs-4">Location</a></li>
				    </ul>
				    <div id="tabs-0"> <?PHP echo $this->loadTemplate('name');?> </div>
				    <div id="tabs-1">
				      <?PHP echo $this->loadTemplate('email');?>
				    </div>
				    <div id="tabs-2">
				      <?PHP echo $this->loadTemplate('age');?>
				    </div>
				    <div id="tabs-3">
				      <?PHP echo $this->loadTemplate('gender');?>
				    </div>
				    <div id="tabs-4">
				      <?PHP echo $this->loadTemplate('location');?>
				    </div>
			  </div>
			</div>
			<div class="span6">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_AWARDPACKAGE_ID_FIELD' ); ?></th>
							<th><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_AWARDPACKAGE_NAME' ), 'firstname', $this->lists['order_dir'], $this->lists['order']); ?></th>							
							<th><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_AWARDPACKAGE_EMAIL' ), 'email', $this->lists['order_dir'], $this->lists['order']); ?></th>
					        <th><?php echo JText::_('Birthday'); ?></th>
					        <th><?php echo JText::_('Gender'); ?></th>
					        <th><?php echo JText::_('Street'); ?></th>
					        <th><?php echo JHTML::_( 'grid.sort', JText::_( 'City' ), 'city', $this->lists['order_dir'], $this->lists['order']); ?></th>					        
					        <th><?php echo JHTML::_( 'grid.sort', JText::_( 'State' ), 'state', $this->lists['order_dir'], $this->lists['order']); ?></th>
					        <th><?php echo JHTML::_( 'grid.sort', JText::_( 'Country' ), 'country', $this->lists['order_dir'], $this->lists['order']); ?></th>					        	
						</tr>
					</thead>
					<tbody>
				      <?php
				                        if (count($this->search_result['lists']['data']) > 0):
				                            foreach ($this->search_result['lists']['data'] as $k1 => $rs):
				                                ?>
				      <tr>
				        <td align="center"><?php echo $rs['id']; ?></td>
				        <td align="center"><?php echo $rs['firstname'] . ' ' . $rs['lastname']; ?></td>
				        <td align="center"><?php echo $rs['email']; ?></td>
				        <td align="center"><?php echo $rs['birthday']; ?></td>
				        <td align="center"><?php echo $rs['gender']; ?></td>
				        <td align="center"><?php echo $rs['street']; ?></td>
				        <td align="center"><?php echo $rs['city']; ?></td>
				        <td align="center"><?php echo $rs['state']; ?></td>
				        <td align="center"><?php echo $rs['country']; ?></td>
				      </tr>
				      <?php
				                            endforeach;
				                        endif;
				                        ?>
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo JHtml::_('form.token'); ?>
</form>