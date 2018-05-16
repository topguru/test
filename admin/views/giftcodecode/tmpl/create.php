<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

if ($this->is_edit == true) {
    $cid = JRequest::getVar("cid");
    //diubah ita tgl 27
    //$giftcode_model =& JModel::getInstance('Giftcodecode','AwardpackageModel');
   // $color_name = $giftcode_model->get_color($cid[0]);    
    //$created_date = $giftcode_model->get_created_date($cid[0]);
    
    $color_name = $this->model->get_color($cid[0]);
    $created_date = $this->model->get_created_date($cid[0]);
} 

$categoryData =& $this->categoryData;
$color = array();
$color[] = "Merah";
$warna = array();
$warna[] = 1;
foreach ($categoryData as $row) {
  $color[$row->id] = $row->name;    
  $warna[$row->id] = $row->category_id;    
}

$cat_id = JRequest::getVar("color") ? JRequest::getVar("color") : "1";

?>
<script type="text/javascript">
function isNumberKey(evt)
{	
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
     	return false;
     	return true;
 }
	Joomla.submitbutton = function(task)
 {
        if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
        else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
 }
 
 
</script>
<form action="index.php?option=com_awardpackage&view=giftcodecode&task=giftcodecode.save" method="post" name="adminForm" class="form-validate" id="adminForm">
    <div id="wrapper">
            <ul class="navigationTabs">            
            <?php
                foreach ($categoryData as &$row) {
                    $id = JRequest::getVar('color') ? JRequest::getVar('color') : "1" ;
                    if(JRequest::getVar('package_id')==$row->package_id):
                    ?>
                    <li>
                      <a href="index.php?option=com_awardpackage&view=giftcodecode&layout=create&color=<?php echo $row->id; ?>&package_id=<?php echo JRequest::getVar('package_id');?>#<?php echo $row->name ?>" id="<?php echo strtolower($row->name) ?>" rel="<?php echo $row->name ?>" class="<?php echo $id == $row->id ? "active" : "" ?>">
                        <div class="kotak" style="background-color:<?php echo $row->color_code ?>;text-align:center;float:left"><?php echo $row->category_id ?></div>
                      </a>
                    </li>                    
                    <?php
                    endif;
                }
            ?>
            </ul>        
        <div class="tabsContent"> 
            <?php
            foreach ($categoryData as &$row)
            {
                ?>
                <div class="tab">
                    <h2><?php echo JText::_('COM_GIFT_CODE_CATEGORY_NAME');?> : <?php echo $color[$cat_id];?></h2>
                </div>
                <?php    
            }
            ?>            
                <div class="judul" style="padding-left: 20px;">
                    <?php echo JText::_('COM_GIFT_CODE_SETTING');?>
                </div>
                <table>
                    <tr>
                        <td style="padding-left: 20px;"><?php echo JText::_('Number of code');?></td>        
                        <td>
                            <div style="width: 20px;"></div>                        
                        </td>
                        <td><input type="text" id="max_number_of_code" name="max_number_of_code" value="<?php echo  $this->data->max_number_of_code;?>" class="required hasTip" title="Number Only"  onkeypress="return isNumberKey(event)"/></td>
                    </tr>                
                </table>
                <br />
                <br />
        </div>
        <br />
    </div>

    <?php
    if (!empty($created_date)) {
        foreach ($created_date as $cd) {
            ?>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo $cd->created_date_time; ?>"/>    
            <?php
        }    
    }
    ?>
    
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
    <input type="hidden" name="cid" id="cid" value="<?php echo $cid[0]; ?>"/>    
    <input type="hidden" name="color" value="<?php echo JRequest::getVar("color") ? JRequest::getVar("color") : "1" ?>" />    
    <input type="hidden" name="collection_setting_id" value="<?php echo $warna[$cat_id];?>" />
    <input type="hidden" name="option" value="com_awardpackage" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="giftcodecode" />
</form>
<script language="javascript">
//jQuery.noConflict();    
<?php
  foreach ($categoryData as &$row) {
    ?>
    jQuery('#<?php echo strtolower($row->name); ?>').click(function(){
      jQuery('#color').val("<?php echo $row->name; ?>");

    });
    <?php
  }
?>
</script>