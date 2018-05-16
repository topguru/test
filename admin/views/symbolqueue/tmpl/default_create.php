<?php

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_awardpackage&view=symbolqueue" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>"/>
	<input type="hidden" name="processId" value="<?php echo  JRequest::getVar("processId"); ?>"/>	
    	<input type="hidden" name="groupId" value="<?php echo  JRequest::getVar("groupId"); ?>"/>	                        
                        
	<div class="col100">
					<table class="table table-striped table-hover table-bordered" style="width:35%;">
			<tr>
				<td><b>Create</b></td>
				<td><input class="text_area" type="text" name="jml" id="jml" size="30" maxlength="250" value="" /></td>
                <td><b>symbol queue</b></td>
			</tr>	
            		
		</table>
	</div>	
    <input type="hidden" name="view" value="symbolqueue">
    <input type="hidden" name="task" value="symbolqueue.save_create">   
</form>