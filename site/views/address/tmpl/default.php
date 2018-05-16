<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form method="POST" action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=useraccount&task=edit&id='.$this->user->id);?>" name="adminForm">
    <fieldset>
        <legend>Profile</legend>
        <table class="adminlist" width="100%">
            <tr class="row0">
                <td>Username</td>
                <td><?php echo $this->user->username;?></td>
            </tr>
            <tr class="row1">
                <td nowrap>E-mail address</td>
                <td><?php echo $this->user->email;?></td>
            </tr>
            <tr class="row0">
                <td>First name</td><td><?php echo $this->info->firstname;?></td>
            </tr>
            <tr class="row1">
                <td>Last name</td><td><?php echo $this->info->lastname;?></td>
            </tr>
            <tr class="row1">
                <td>Birth date</td><td><?php echo $this->info->birthday;?></td>
            </tr>	
            <tr class="row0">
                <td>Gender</td><td><?php echo $this->info->gender;?></td>
            </tr>
            <tr class="row1">
                <td>Street</td><td><?php echo $this->info->street;?></td>
            </tr>
            <tr class="row0">
                <td>City</td><td><?php echo $this->info->city;?></td>
            </tr>
            <tr class="row1">
                <td>State/Province</td><td><?php echo $this->info->state;?></td>
            </tr>
            <tr class="row0">
                <td>Post code</td><td><?php echo $this->info->post_code;?></td>
            </tr>
            <tr class="row1"><td>Country</td><td><?php echo $this->info->country;?></td></tr>
            <tr class="row0"><td>Phone</td><td><?php echo $this->info->phone;?></td></tr>
            <tr class="row1"><td>Paypal Account</td><td><?php echo $this->info->paypal_account;?></td></tr>
            <tr><td colspan="2" align="right"><input type="submit" name="submit" value="Edit"></td></tr>
	</table>

    </fieldset>
	
</form>