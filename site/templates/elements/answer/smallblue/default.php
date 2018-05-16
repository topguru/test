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
<div class="answer" data-a="<?php echo $par->aid; ?>">
	<?php if($par->vote_state == null AND $par->votes_left > 0) {?>
	<div class="answervote">
		<a href="<?php echo $par->votebutton_link;?>" class="votingbutton <?php echo $par->votebutton_class;?>"<?php echo $par->votebutton_disabled ?>><?php echo $par->translation_vote;?></a>
	</div>
	<?php } ?>
<?php //<!--Ranking-->?>
<?php if($par->activate_ranking) { ?>
	<div class="rank"><?php echo $par->rank;?></div>
<?php } ?>
<?php //<!--VoteBox-->?>
<?php if($par->show_result) { ?>
<?php //<!--VoteCount-->?>
		<div class="count">
<?php //<!--UserList-->?>
<?php if($par->show_userlist) { ?>
			<a href="#" class="icon userlist" title="<?php echo JText::_("SHOW_USERLIST");?>"></a>
<?php } ?>
<?php //<!--Count-->?>
			<div class="vote-down"<?php if($par->uservotes == 0 || !$par->resetAllowed) {?> style="display:none;"<?php }?>><span class="point"></span><div class="reset-bub"><div><span><?php echo JText::_("RESET_VOTES");?></span></div></div><em><span class="operator">+</span><span class="ownvotes"><?php echo $par->uservotes;?></span></em></div>
			<?php
				//Auto-size votecounttext
				$style = "";
				if($par->votes >= 1000) {
					$style = "font-size: 10pt; font-weight: normal;";
				} elseif($par->votes >= 100) {
					$style = "font-size: 12pt;";
				}
			?>
			<span class="votecount" style="<?php echo $style;?>"><?php echo $par->votes;?></span>
			<p class="votecounttext"><?php echo $par->translation_votes;?></p>
		</div>
<?php } ?>
<?php //<!--AnswerBox-->?>
	<div class="answerbox">
<?php //<!--AnswerField-->?>
		<div class="text jvsclearfix">
<?php //<!--Answer-->?>
			<span class="answertext jvsclearfix">
				<div class="answeroptions">
				<?php //<!--Author-->?>
					<div class="answericons">
						<?php echo $par->comment_icon;?>
					<?php //<!--Icons-->?>
					<?php if($par->icon_trash_active) { ?>
								<a title="<?php echo JText::_("Entfernen");?>" href="<?php echo $par->icon_trash_link;?>" class="trash icon"></a>
					<?php } ?>
					<?php if($par->icon_state_show) { ?>
								<a title="<?php echo JText::_("CHANGE_STATE");?>" href="<?php echo $par->icon_state_link;?>" class="state icon <?php echo $par->icon_state_state ?>"></a>
					<?php } ?>
					<?php if($par->icon_spam_active) { ?>
								<a title="<?php echo JText::_("REPORT_SPAM");?>" href="<?php echo $par->icon_spam_link;?>" class="report icon"></a>
					<?php } ?>
					</div>
				</div>
				<?php echo $par->answer;?>
			</span>
		</div>
		
		<?php //<!--VoteButton-->?>
	
<?php //<!--Comments-->?>
<?php if($par->show_comments) { ?>
		<div class="comments"><?php echo $par->comments;?></div>
<?php } else { ?>
		<div class="comments"></div>
<?php } ?>
	</div>
</div>
