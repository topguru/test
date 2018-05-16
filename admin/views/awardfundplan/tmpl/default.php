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
function onAddAwardFundPlan(){
window.location = 
			"index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.create_update&" +
			"package_id=<?php echo JRequest::getVar('package_id'); ?>";
}

function onDeleteAwardFundPlan(){
	jQuery('#task').val('awardfundplan.delete_awardfundplan');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=awardfundplan&package_id='+<?php echo JRequest::getVar('package_id'); ?>);
	jQuery('form#adminForm').submit();
}

  function onEditA(e){
     jQuery('#speed').attr('disabled', false);   
	 document.getElementById('btn_save').style.visibility = "visible";
	 document.getElementById('btn_edit').style.visibility = "hidden";	 
   }
	
function onSaveSpeed(){
	jQuery('#task').val('awardfundplan.save_speed');
	jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=awardfundplan&package_id='+<?php echo JRequest::getVar('package_id'); ?>);
	jQuery('form#adminForm').submit();
}
</script>
<?php require(JPATH_COMPONENT_ADMINISTRATOR . '/views/bpresentationlist/tmpl/default_left.php'); ?>

<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<div class="span12">
			
<form>
					
</form>
				<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardfundplan');?>" method="post" name="adminForm">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>	
					<table class="table table-striped table-hover table-bordered">
						<thead>
                        <tr>
                        <th colspan="7">Award funds plan list </th>
                        <th><button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onAddAwardFundPlan();" id="addUserGroupBtn"><i></i> <?php echo JText::_('New');?></button>					</th>
                        <th><button type="button" class="btn btn-primary btn-invite-reg-groups"
																onclick="onDeleteAwardFundPlan();" id="deleteUserGroupBtn"><i></i> <?php echo JText::_('Delete');?></button>					</th>
                        </tr>

                    <tr><th colspan="8">
                    <table class="table table-striped table-hover table-bordered" style="width:80%">
                    <tr>
                    </tr>
                   <tr>
                    <td colspan="2" style="text-align:right;">
                    <button style="visibility:hidden;" type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_save" onclick="onSaveSpeed();"> <?php 
			echo JText::_('Save');?></button>
            
        		<button type="button" class="btn btn-primary btn-invite-reg-groups"
			id="btn_edit" onclick="onEditA();"> <?php 
			echo 'Edit';?></button>
            
            </td>
                    </tr>
                    <tr>
                    <td colspan="2">Award Funds Total = $ <?php echo number_format($this->awardfundtotal,2); ?></td>
                    </tr><tr>
                    <td colspan="2">Award Funds spent total = $ <?php echo number_format($this->totalSpent,2); ?></td>
                    </tr>
                    <tr>
                    <td colspan="2">Final Award Funds Total = $ <?php echo number_format($this->totalA,2); ?></td>
                    </tr>
                     <tr>
                    <td>Award Funds Rate Total cannot exceed</td>
                    <td>					
                    <input type="text" name="speed" id="speed" value="<?php echo ( empty($this->speed) ? '90' : $this->speed); ?>" disabled />	
                    &nbsp;					
                    Percent	
                    </td>                 
                    </tr>
                    </table>
                    
                    </th>
                    <th>
                    </th>
                    </tr>
							<tr>
								<th width="20"><?php echo JText::_( '#' ); ?></th>
								<th><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
								<th style="text-align:center;"><?php echo JText::_( 'Title' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Award Funds Total' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Award Funds Rate' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Award funds for Fund Receiver list queue' ); ?></th>
								<th style="text-align:center;"><?php echo JText::_( 'Award funds spent' ); ?></th>                                
								<th style="text-align:center;"><?php echo JText::_( 'Award funds remain' ); ?></th>
                                <th style="text-align:center;"><?php echo JText::_( 'Presentation usergroup' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($this->awardfunds as $i=>$row):?>
							<tr>
								<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
								<td><?php echo JHTML::_( 'grid.id', $i, $row->id );?></td>
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.create_update&package_id='.JRequest::getVar('package_id').'&id='.$row->id)?>"><?php echo $this->escape($row->name); ?></a></td>
								<td style="text-align:center;"><?php 								
								echo (!empty($this->awardfundtotal) ? $this->awardfundtotal : number_format($row->total,2)); 
								$awardfundtotal = $this->awardfundtotal;
								$amount = ($row->rate/100) * $awardfundtotal;
								$spent = $this->spent;
								$remain = $amount - $spent;
								?></td>
								<td style="text-align:center;"><?php 
								echo number_format($row->rate,2).'%'; ?></td>
								<td style="text-align:center;"><?php 
								echo (!empty($amount ) ? number_format($amount,2)  : number_format($row->amount,2));
								?></td>
								<td style="text-align:center;"><?php 
								echo (!empty($spent ) ? number_format($spent,2)  : number_format($row->spent,2));								
								?></td>
								<td style="text-align:center;"><?php 
								echo (!empty($remain ) ? number_format($remain,2)  : number_format($row->remain,2));								
								?></td>
                                
                               
								<td class="hidden-phone"><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id').'&usergroup='.$row->usergroup)?>"><?php echo (!empty($row->usergroup) ? $row->usergroup : 'View'); ?></a>
                                </td>
							</tr>
							<?php endforeach;?>
						</tbody>
						
					</table>
					
				    <input type="hidden" name="task" id="task" value="">
					<input type="hidden" name="boxchecked" value="0" />	
					<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />				
				</form>
			</div>
		</div>
	</div>
</div>
