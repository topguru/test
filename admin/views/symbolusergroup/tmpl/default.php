<?php
error_reporting(0);
/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
// Import CSS
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_refund/assets/css/refund.css');
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
<?php
$class = ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolusergroup&package_list_id=' . JRequest::getVar('package_list_id') . '&package_id=' . JRequest::getVar('package_id') . '&field=location&presentation_id='.JRequest::getVar('presentation_id')); ?>"
	method="post" name="adminForm" id="refundpackagelist-form"
	class="form-validate">
<table>
	<tr>
		<td width="50%" valign="top">
		<div id="tabs">
		<ul>
			<li <?php if (JRequest::getVar('field') == 'name') echo $class; ?>><a
				href="#tabs-0"><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></a></li>
			<li <?php if (JRequest::getVar('field') == 'email') echo $class; ?>><a
				href="#tabs-1"><?php echo JText::_('COM_REFUND_TAB_EMAIL'); ?></a></li>
			<li <?php if (JRequest::getVar('field') == 'age') echo $class; ?>><a
				href="#tabs-2"><?php echo JText::_('COM_REFUND_TAB_AGE'); ?></a></li>
			<li <?php if (JRequest::getVar('field') == 'gender') echo $class; ?>><a
				href="#tabs-3"><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></a></li>
			<li
			<?php if (JRequest::getVar('field') == 'location') echo $class; ?>><a
				href="#tabs-4"><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></a></li>

		</ul>
		<div id="tabs-0"><?php echo $this->loadTemplate('name'); ?></div>
		<div id="tabs-1"><?php echo $this->loadTemplate('email'); ?></div>
		<div id="tabs-2"><?php echo $this->loadTemplate('age'); ?></div>
		<div id="tabs-3"><?php echo $this->loadTemplate('gender'); ?></div>
		<div id="tabs-4"><?php echo $this->loadTemplate('location'); ?></div>
		</div>		
		</td>
		<td width="50%" valign="top">
		<fieldset><legend>symbol user group</legend>
		<table width="100%" cellpadding="1" cellspacing="1" class="table table-striped"
			style="border: 1px solid #cccccc;">
			<thead>
				<tr style="text-align:center; background-color:#CCCCCC">
					<th>Name</th>
					<th>Email</th>
					<th>Gender</th>
					<th>Location</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if (count($this->search_result) > 0) {
				foreach ($this->search_result as $result) {
					?>
				<tr>
					<td><?php echo $result->first_name . ' ' . $result->last_name; ?></td>
					<td><?php echo $result->email; ?></td>
					<td><?php echo $result->gender; ?></td>
					<td><?php echo $result->state.' '.$result->city; ?></td>
				</tr>
				<?php
				}
			}
			?>
			</tbody>
		</table>
		</fieldset>
		</td>
	</tr>
</table>

</form>
</div>
