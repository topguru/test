<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_awardpackage
 * @copyright	kadeyasa@gmail.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
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
</script>
<form method="POST" action="<?php echo JRoute::_('index.php?option=com_awardpackage&controller=useraccount&task=save');?>" name="adminForm" class="form-validate" id="user-form">
    <fieldset>
        <legend>User information</legend>
        <table class="adminlist">
            <tr class="row0">
                <td><?php echo $this->form->getLabel('nameuser');?></td>
                <td>
                    <?php echo $this->user->username;?>
                    
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo $this->form->getLabel('email');?></td>
                <td><?php echo $this->user->email;?></td>
            </tr>
            <tr class="row0">
                <td><?php echo $this->form->getLabel('firstname');?></td>
                <td>
                    <?php echo $this->form->getInput('firstname');?>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo $this->form->getLabel('lastname');?></td>
                <td>
                    <?php echo $this->form->getInput('lastname');?>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo $this->form->getLabel('birthday');?></td>
                <td><?php echo $this->form->getInput('birthday');?>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo $this->form->getLabel('gender');?></td>
                <td>
                    <table border="0">
                        <tr>
                            <td><input type="radio" value="male" name="jform[gender]"<?php if($this->item->gender=='male') echo ' checked';?>>male&nbsp;</td>
                            <td><input type="radio" value="female" name="jform[gender]"<?php if($this->item->gender=='female') echo ' checked';?>>female</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="row0">
                <td>
                    <?php echo $this->form->getLabel('street');?>
                </td>
                <td>
                    <?php echo $this->form->getInput('street');?>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo $this->form->getLabel('city');?></td>
                <td>
                    <?php echo $this->form->getInput('city');?>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo $this->form->getLabel('state');?></td>
                <td><?php echo $this->form->getInput('state');?></td>
            </tr>
            <tr class="row1">
                <td><?php echo $this->form->getLabel('post_code');?></td>
                <td>
                <input type="text" class="hasTip" title="number only" onkeypress="return isNumberKey(event)" name="jform[post_code]" value="<?php echo $this->item->post_code;?>"/>
                &nbsp;&nbsp;&nbsp;
				<?php //echo $this->form->getInput('post_code');?>
               </td>
            </tr>
            <tr class="row0">
                <td><?php echo $this->form->getLabel('country');?></td>
                <td>
                    <select name="jform[country]">
                        <?php foreach($this->country_list as $k => $v){?>
                        <option value="<?php echo $v;?>"<?php if($this->item->country==$v) echo ' selected';?>><?php echo $v;?></option>
                        <?php };?>
                    </select>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo $this->form->getLabel('phone');?></td>
                <td>
                	<input type="text" name="jform[phone]" id="jform_phone" value="<?php echo $this->item->phone;?>" size="30" onkeypress="return isNumberKey(event)" class="hasTip" title="number only"/>
                    &nbsp;&nbsp;&nbsp;
					<?php //echo $this->form->getInput('phone');?>
                </td>
            </tr>
            <tr class="row1">
            	<td><?php echo $this->form->getLabel('paypal_account');?></td>
                <td><?php echo $this->form->getInput('paypal_account');?></td>
            </tr>
            <tr class="row1">
                <td colspan="2" align="center"><button name="submit" class="validate" onclick="return validation();">Save</button></td>
            </tr>
        </table>
    </fieldset> 
    <?php echo $this->form->getInput('ap_account_id');?>
    <input type="hidden" name="jform[id]" value="<?php echo $this->user->id;?>" />
</form>