<?php
/**
 * @version		$Id: list.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" id="adminForm" action="index.php?option=<?php echo Q_APP_NAME;?>&view=categories" method="post">
	<input type="hidden" name="package_id" value="<?php echo JRequest::getVar("package_id"); ?>">
	<div id="cj-wrapper">
		<div class="container-fluid no-space-left no-space-right">
			<div class="row-fluid">
				<div class="span2">
					<div class="sidebar-nav">
						<ul class="nav nav-tabs nav-stacked">
							<li>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=dashboardz&package_id='.JRequest::getVar("package_id"))?>">
									<i class="icon-globe"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_DASHBOARD');?>
								</a>
							</li>
							<li>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"))?>">
									<i class="icon-list"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_QUIZZES');?>
								</a>
							</li>
							<li>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=approval&task=approval.get_quizzes_list&package_id='.JRequest::getVar("package_id"))?>">
									<i class="icon-time"></i> <?php echo JText::_('Pending Approval');?>
								</a>
							</li>						
							<li  class="active">
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=categories&task=categories.get_categories&package_id='.JRequest::getVar("package_id"))?>">
									<i class="icon-folder-open"></i> <?php echo JText::_('COM_COMMUNITYQUIZ_CATEGORIES');?>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="span7">
					<?php
					if (count($this->categories) > 0){
					?>
					<table class="table table-hover table-condensed table-striped adminlist">
					    <thead>
					        <tr>
					        	<th width="20px">#</th>
					            <th><?php echo JText::_('LBL_CATEGORY');?></th>
					            <th width="50px"><?php echo JText::_('LBL_QUIZZES');?></th>
					            <th width="25px"></th>
					            <th width="25px"></th>
					            <th width="50px"><?php echo JText::_('LBL_EDIT');?></th>
					            <th width="50px"><?php echo JText::_('LBL_DELETE');?></th>
					            <th width="50px">ID</th>
					        </tr>
					    </thead>
					    <tbody>
					    	<?php 
					    	$db = JFactory::getDbo();
					    	$tree = new CjNestedTree($db, '#__quiz_categories');
					    	$content = '';
					    	static $row_num = 0;
					    	
					    	$base_uri = 'index.php?option='.Q_APP_NAME.'&view=categories';
					    	$fields = array();
					    		    	
					    	$fields[] = array('header'=>JText::_('LBL_CATEGORY'), 'name'=>'title', 'type'=>'category', 'align'=>'left', 
					    					'src'=>$base_uri.'&task=categories.add&package_id='.JRequest::getVar('package_id'), 'id'=>true, 'value'=>null);
					    	$fields[] = array('header'=>JText::_('LBL_QUIZZES'), 'name'=>'quizzes', 'type'=>'text', 'align'=>'center');
					    	$fields[] = array('header'=>'', 'name'=>null, 'type'=>'up', 'align'=>'center', 'src'=>$base_uri.'&task=categories.move_up&package_id='.JRequest::getVar('package_id'), 'id'=>true, 
											'value'=>'<i class="icon icon-arrow-up"></i>', 'attribs'=>array('class'=>'btn btn-mini tooltip-hover', 'title'=>JText::_('TXT_MOVE_UP')));
					    	$fields[] = array('header'=>'', 'name'=>null, 'type'=>'down', 'align'=>'center', 'src'=>$base_uri.'&task=categories.move_down&package_id='.JRequest::getVar('package_id'), 'id'=>true, 
											'value'=>'<i class="icon icon-arrow-down"></i>', 'attribs'=>array('class'=>'btn btn-mini tooltip-hover', 'title'=>JText::_('TXT_MOVE_DOWN')));
					    	$fields[] = array('header'=>JText::_('LBL_EDIT'), 'name'=>null, 'type'=>'link', 'align'=>'center', 'src'=>$base_uri.'&task=categories.add&package_id='.JRequest::getVar('package_id'), 'id'=>true, 'value'=>null);
					    	$fields[] = array('header'=>JText::_('LBL_DELETE'), 'name'=>null, 'type'=>'link', 'align'=>'center', 'src'=>$base_uri.'&task=categories.delete&package_id='.JRequest::getVar('package_id'), 'id'=>true, 'value'=>null);
					    	$fields[] = array('header'=>JText::_('ID'), 'name'=>'id', 'type'=>'text', 'align'=>'center');
					    	
//					    	echo $tree->get_tree_table($content, $this->categories, $fields);
$i=0;							
foreach ($this->categories as $cat){
$i=$i+1;
echo '<tr>';
echo '<td>'.$i.'</td>';
echo '<td><a href="'.$base_uri.'&task=categories.add&id='.$cat->id.'&package_id='.JRequest::getVar('package_id').'">'.$cat->title.'</a></td>';
echo '<td>'.$cat->quizzes.'</td>';
echo '<td></td>';
echo '<td></td>';
echo '<td><a href="'.$base_uri.'&task=categories.add&id='.$cat->id.'&package_id='.JRequest::getVar('package_id').'">Edit</a></td>';
echo '<td><a href="'.$base_uri.'&task=categories.delete&id='.$cat->id.'&package_id='.JRequest::getVar('package_id').'">Delete</a></td>';
echo '<td>'.$cat->id.'</td>';
echo '</tr>';
}
?>
					    </tbody>
					</table>
					<?php
					}else{
					    echo 'No categories found';
					}
					?>
				</div>
			</div>
		</div>
	</div>	
    <input type="hidden" name="task" value="add">
</form>
