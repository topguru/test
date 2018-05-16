<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
$user = JFactory::getUser();
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');	
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');

$app = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$headerstuff = $document->getHeadData();
$jpath = JURI::root();
//$document->addStyleSheet($jpath . 'templates/beez3/css/personal.css', 'text/css');
$customstyle = "#main-container .panel h3.pane-toggler a {
    padding-bottom: 5px;
    padding-top: 6px;
}";
$document->addStyleDeclaration($customstyle);
// Add Javascript
$document->addScriptDeclaration("jQuery(document).ready(function($){
        $(function() {
            $('#main-container').find('.title').each( function () {
                var _t = $(this);
                _t.on('click', function(){
                    if( _t.hasClass('pane-toggler-down') ){
                        _t.removeClass('pane-toggler-down');
                    }
                    else{
                        _t.addClass('pane-toggler-down');
                    }
                    var container = $(this).next();
                    container.toggle();
                });
            });
            $('#collapse-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').hide();
                $(this).parents('#main-container').find('.title').removeClass('pane-toggler-down');
            });
            $('#expand-alls').on('click', function(){
                $(this).parents('#main-container').find('.panelform').show();
                $(this).parents('#main-container').find('.title').addClass('pane-toggler-down');
            });
        });
    });");
?>
<style>
	.ui-widget-content {
		border-right: 5px solid #000000;
		background: #FFFFFF;		
	}
  #resizable { width: 620px; padding: 0.5em; }
  #resizable_1 { width: 510px; padding: 0.5em; }  
  #resizable_2 { width: 600px; padding: 0.5em; }
  #resizable_3 { width: 570px; padding: 0.5em; }
  #resizable_4 { width: 500px; padding: 0.5em; }
  #resizable_5 { width: 400px; padding: 0.5em; }
</style>

<script type="text/javascript">
function onAddAwardFundPrice(){
window.location = 
			"index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.add_fundprizeplan&" +
			"package_id=<?php echo JRequest::getVar('package_id'); ?>";
}

function onDeleteAwardFundPrice(){
	jQuery('#task').val('fundprizeplan.delete_fundprizeplan');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.delete_fundprizeplan&package_id='+<?php echo JRequest::getVar('package_id'); ?>);
	jQuery('form#adminForm').submit();
}
</script>
<?php require(JPATH_COMPONENT_ADMINISTRATOR . '/views/bpresentationlist/tmpl/default_left.php'); ?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.get_fundprizeplan');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>				
					<table class="table table-striped table-hover table-bordered" style="width:70%;">
						<thead>
                        <tr>
                        <th colspan="3">Funds prize plan list </th>
                        <th><button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddAwardFundPrice();" id="addUserGroupBtn"><i></i> <?php echo JText::_('Add');?></button>					</th>
                        <th><button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDeleteAwardFundPrice();" id="deleteUserGroupBtn"><i></i> <?php echo JText::_('Delete');?></button>					</th>
                        
                        </tr>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th width="30%"><?php echo JText::_( 'Title' ); ?></th>								
								<th width="28%" class="hidden-phone"><?php echo JText::_( 'Created' ); ?></th>
								<th><?php echo JText::_( 'Funding Value' ); ?></th>								
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->fundprizes as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.create_update&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo $this->escape($row->name); ?></a></td>
								<td class="hidden-phone"><?php echo JHTML::Date($row->date_created, JText::_('DATE_FORMAT_LC2')); ?></td>
								<td>
									<?php echo '$'.$row->value_from.' to $'.$row->value_to; ?>
									<input type="hidden" name="id" value="<?php echo $row->id?>">
								</td>								
							</tr>
							<?php endforeach;?>
						</tbody>
						
					</table>
					
					<input type="hidden" name="task" value="fundprizeplan.delete_fundprizeplan" />
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>