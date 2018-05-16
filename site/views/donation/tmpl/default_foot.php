<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<td align="center" colspan="8">
	<?php
		$orig = str_replace('"pagination-','"',$this->pagination->getListFooter());
		echo str_replace('"list-footer"','"pagination"',$orig);
	?></td>
</tr>