<?PHP 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$destination = JRoute::_('index.php?option=com_awardpackage&controller=selectwinner&tmpl=component&task=addwinner');
?>
<form action="<?php echo $destination;?>" method="POST">
<h1><?PHP echo JText::_('Start Locked Prize');?></h1>
<div style="padding-left:10px;">
<table width="100%">
	<tr>
    	<td width="150"><?PHP echo JText::_('Start Unlocked Prize');?></td>
        <td><input type="text" name="startunlocked" size="20"></td>
    </tr>
    <tr>
    	<td width="150"><?PHP echo JText::_('End Unlocked Prize');?></td>
        <td><input type="text" name="endunlocked" size="20"></td>
    </tr>
    <tr>
    	<td></td>
        <td><button class="button"><?PHP echo JText::_('Save');?></button></td>
    </tr>
</table>
</div>
<div>
	<input type="hidden" name="package_id" value="<?PHP echo JRequest::getVar('package_id');?>" />
    <input type="hidden" name="presentation_id" value="<?PHP echo JRequest::getVar('presentation_id');?>" />
    <?php echo JHtml::_('form.token'); ?>
</div>
</form>
