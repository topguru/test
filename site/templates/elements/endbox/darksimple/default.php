<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

?>
<div class="endbox">
<?php //<!--GoTo-Link-->?>
<?php if($par->goto_show) { ?>
	<a class="link" href="<?php echo $par->goto_link;?>">» <?php echo JText::_('GO_TO_POLL');?> »</a>
<?php } ?>
	<div class="votestate">
	<?php if($par->vote_state != null) { ?>
		<?php echo $par->vote_state_text; } ?>
	</div>
</div>