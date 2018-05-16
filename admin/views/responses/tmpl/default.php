<?php
/**
 * @version		$Id: survey_responses.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="cj-wrapper">
	<div class="container-fluid survey-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="index.php?option=<?php echo S_APP_NAME;?>&view=surveys&id=<?php echo $this->survey->id;?>" method="post" name="adminForm">
					<?php if(!empty($this->responses)):?>
					<table class="table table-striped table-hover">
					    <thead>
					        <tr>
					            <th width="20"><?php echo JText::_( '#' ); ?></th>
					            <th width="20"><?php echo JText::_( 'ID' ); ?></th>
					            <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->responses ); ?>);" /></th>
					            <th class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_USERNAME' ), 'a.created_by', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					            <th width="15%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_CREATED_ON' ), 'finished', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					            <th width="10%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_COMMUNITYSURVEYS_COMPLETED' ), 'a.created', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					        </tr>
					    </thead>
					    <tbody>
						    <?php
						    $k = 0;
						    $i=0;
						    foreach($this->responses as $row){
						    $checked    = JHTML::_( 'grid.id', $i, $row->id );
						    ?>
						    <tr class="<?php echo "row$k"; ?>">
						        <td>
						            <?php echo $this->pagination->getRowOffset( $i ); ?>
						        </td>
						        <td>
						            <?php echo $row->id; ?>
						        </td>
						        <td>
						            <?php echo $checked; ?>
						        </td>
						        <td>
						            <?php echo ($row->created_by > 0) ? $this->escape($row->username) : JText::_('COM_COMMUNITYSURVEYS_GUEST'); ?>
						        </td>
						        <td>
						            <?php echo $this->escape($row->created); ?>
						        </td>
						        <td>
									<a 
										class="btn btn-mini <?php echo $row->finished == 1 ? 'btn-success' : 'btn-danger';?> tooltip-hover" 
										title="<?php echo $row->finished == 1 ? JText::_('COM_COMMUNITYSURVEYS_COMPLETED') : JText::_('COM_COMMUNITYSURVEYS_PENDING');?>"
										href="#"
										onclick="return false;">
										<i class="icon <?php echo $row->finished == 1 ? 'icon-ok' : 'icon-remove'; ?> icon-white"></i>
									</a>
						        </td>
						    </tr>
						    <?php
						    $k = 1 - $k;
						    $i++;
							}
							?>
					    </tbody>
					    <tfoot>
					        <tr>
					            <td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
					        </tr>
					    </tfoot>
					</table>
					<?php else: ?>
						<?php echo JText::_('COM_COMMUNITY_SURVEYS_NO_RESULTS_FOUND');?>
					<?php endif;?>
					
					<input type="hidden" name="option" value="<?php echo S_APP_NAME;?>" />
					<input type="hidden" name="task" value="responses" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="Itemid" value="0" />
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_dir" value="<?php if($this->lists['order_Dir']) echo $this->lists['order_Dir']; ?>" />
				</form>
				
			</div>
		</div>
	</div>
</div>