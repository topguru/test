<?php
error_reporting(0);
/**
 * @version     1.0.0
 * @package     com_awardpackage
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'refundpackagelist.cancel' || document.formvalidator.isValid(document.id('refundpackagelist-form'))) {
            Joomla.submitform(task, document.getElementById('refundpackagelist-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>
<div id="j-main-container" class="span10">
<form
	action="<?PHP echo JRoute::_('index.php?option=com_awardpackage&view=selectwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id);?>"
	method="post" name="adminForm" id="refundpackagelist-form"
	class="form-validate">
<table cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td width="50%" valign="top">
		<div class="width-50 fltlft" style="float: left; width: 700px;">
		<div id="tabs">
		<ul>
			<li <?PHP if($this->field=='name'){ echo $this->class;}?>><a
				href="#tabs-0"><?PHP echo JText::_('Name');?></a></li>
			<li <?PHP if($this->field=='email'){ echo $this->class;}?>><a
				href="#tabs-1"><?PHP echo JText::_('Email');?></a></li>
			<li <?PHP if($this->field=='age'){ echo $this->class;}?>><a
				href="#tabs-2"><?PHP echo JText::_('Age');?></a></li>
			<li <?PHP if($this->field=='gender'){ echo $this->class;}?>><a
				href="#tabs-3"><?PHP echo JText::_('Gender');?></a></li>
			<li <?PHP if($this->field=='location'){ echo $this->class;}?>><a
				href="#tabs-4"><?PHP echo JText::_('Location');?></a></li>
		</ul>
		<div id="tabs-0"><?PHP echo $this->loadTemplate('name');?></div>
		<div id="tabs-1"><?PHP echo $this->loadTemplate('email');?></div>
		<div id="tabs-2"><?PHP echo $this->loadTemplate('age');?></div>
		<div id="tabs-3"><?PHP echo $this->loadTemplate('gender');?></div>
		<div id="tabs-4"><?PHP echo $this->loadTemplate('location');?></div>
		</div>
		</div>
		</td>
		<td width="50%" valign="top">
		<div class="width-50 fltlft" style="width:540px">
		<fieldset><legend> <?PHP echo JText::_('Potential Winners');?> </legend>
		<table width="100%" class="table table-striped">
			<thead>
				<tr style="text-align:center; background-color:#CCCCCC">
					<th><?PHP echo JText::_('Email');?></th>
					<th><?PHP echo JText::_('age');?></th>
					<th><?PHP echo JText::_('Gender');?></th>
					<th><?PHP echo JText::_('City');?></th>
					<th><?PHP echo JText::_('State');?></th>
					<th><?PHP echo JText::_('Country');?></th>
				</tr>
			</thead>
			<tbody>
			<?PHP
			foreach($this->search_result as $result){
				?>
				<tr>
					<td align="center"><?PHP echo $result->email;?></td>
					<td align="center"><?PHP echo $result->age;?></td>
					<td align="center"><?PHP echo $result->gender;?></td>
					<td align="center"><?PHP echo $result->city;?></td>
					<td align="center"><?PHP echo $result->state_province;?></td>
					<td align="center"><?PHP echo $result->country;?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		</fieldset>
		</div>
		</td>
	</tr>
</table>
<div><input type="hidden" name="presentation_id"
	value="<?php echo $this->presentation_id; ?>"> <input type="hidden"
	name="package_id" value="<?php echo $this->package_id; ?>"> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="selectwinner" /> <input type="hidden" name="filter_order"
	value="<?php echo $listOrder; ?>" /> <input type="hidden"
	name="filter_order_Dir" value="<?php echo $listDirn; ?>" /> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
