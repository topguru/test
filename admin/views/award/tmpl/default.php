<?php 
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');
?>
<?php
$checked    = '<input type="radio" id="cb" name="cid[]" value="1" onclick="isChecked(this.checked);" />';
?>
<form action="index.php" method="post" name="adminForm">
	<div id="cpanel">
		<?php
		$link = 'index.php?option=com_award&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'frontpage.png', JText::_( 'Home' ) );
		
		$link = 'index.php?option=com_award&view=awardsymbol&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'icon-48-pg-vote.png', JText::_( 'Award Symbol' ) );
		
		$link = 'index.php?option=com_award&view=prize&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'icon-48-pg-uprize.png', JText::_( 'Prize' ) );
		
		$link = 'index.php?option=com_award&view=presentation&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'icon-48-pg-vote-img.png', JText::_( 'Presentation' ) );
		
		$link = 'index.php?option=com_award&view=symbolqueue&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'icon-48-pg-theme.png', JText::_( 'Symbol Queue' ) );
		
		$link = 'index.php?option=com_award&view=userrecord&package_id='.JRequest::getVar('package_id');
		echo SymbolRenderAdmin::quickIconButton( $link, 'icon-48-pg-users.png', JText::_( 'User Record' ) );
		?>
				
	<div style="clear:both">&nbsp;</div>
	<p>&nbsp;</p>
	<div style="text-align:center;padding:0;margin:0;border:0">
		
	</div>
	</div>
<br>
<br>
<input type="hidden" name="option" value="com_award" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="create" />
<input type="hidden" name="controller" value="award" />
</form>