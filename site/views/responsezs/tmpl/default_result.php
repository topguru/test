<?php 
/**
 * @version		$Id: default_consolidated.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

define('DS', '/');

require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/formfields.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/reports.php';

$page_id = $this->params->get('hide_toolbar_responses', 0) == 1 ? -1 : 8;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT.DS.'helpers'.DS.'reports.php';
$generator = new SurveyReports($wysiwyg, $bbcode, $content);

$doc = JFactory::getDocument();
$doc->addScript('https://www.google.com/jsapi');
$doc->addScriptDeclaration('google.load("visualization", "1", {packages:["corechart"]}); google.setOnLoadCallback(SurveyFactory.draw_consolidated_charts);');
?>

<div id="cj-wrapper" class="container-fuild">
	
	<div class="<?php echo $this->hide_template == 1 ? 'full-screen' : 'row-fluid';?>">
		<table width="100%">	
			<tr>
				<td width="10%" valign="top">
					<?php include_once JPATH_COMPONENT.'/'.'helpers'.'/'.'main_header.php';?>
				</td>
				<td valign="top">
					<?php include_once JPATH_COMPONENT.'/'.'helpers'.'/'.'headersz.php';?>
		
					<?php echo CJFunctions::load_module_position('surveys-results-top');?>
					<?php if(!$this->print):?>
					<div class="well">
						<a class="btn pull-right" 
							onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no'); return false;"
							href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=consolidated&id='.$this->item->id.':'.$this->item->alias.'&tmpl=component&print=1'.$itemid);?>">
							<i class="icon-print"></i> <?php echo JText::_('JGLOBAL_PRINT');?>
						</a>
						<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid);?>">
							<i class="icon-home"></i> <?php echo JText::_('LBL_HOME');?>
						</a>
					</div>
					<?php else:?>
					<script type="text/javascript">window.print();</script>
					<?php endif;?>
				
					<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
					<div class="survey-description"><?php echo $this->item->introtext; ?></div>
					
					<div class="reports-wrapper margin-top-20">
					<?php 
					$class = '';
					foreach($this->item->questions as $item){
						switch ($item->question_type){
							case 1:
								echo $generator->get_page_header_question($item, $class);
								break;
							case 2:
							case 3:
							case 4:
							case 11:
							case 12:
								echo $generator->get_choice_question($item, $class);
								break;
							case 5:
							case 6:
								echo $generator->get_grid_question($item, $class);
								break;
						}
					}
					?>
					</div>
					<?php echo CJFunctions::load_module_position('surveys-results-bottom');?>
				</td>
			</tr>
		</table>
		
	</div>
</div>