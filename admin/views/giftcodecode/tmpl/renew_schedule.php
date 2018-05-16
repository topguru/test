<?php defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<style>
th { font-weight: bold; } 
td { text-align: center; }
</style>
<script type="text/javascript">

 Joomla.submitbutton = function(task)
 {
        if (task == 'cancel' || document.formvalidator.isValid(document.id('user-form'))) {
            Joomla.submitform(task, document.getElementById('user-form'));
        }
        else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
 }
 
</script>
<?php 
//defined('_JEXEC') or die('Restricted access'); 
$categoryData =& $this->categoryData;

$color[] = "";
foreach ($categoryData as $row) {
	 $color['name'] = $row->name;
  //echo $color['name'];
}


$cat_id = JRequest::getVar("color") ? JRequest::getVar("color") : "1";
 

?>
<form action="index.php" method="post" name="adminForm"  class="form-validate" id="user-form" >
    <div id="wrapper">    
        <ul class="navigationTabs">
            <?php
                $id = JRequest::getVar('color') ? JRequest::getVar('color') : "1" ;
                foreach ($categoryData as &$row) {
                	//print_r ($row); 
                	 if(JRequest::getVar('package_id') == $row->package_id):
                    ?>
                    <li><!-- ini yg diubah -->
                      <a class="<?php echo $id == $row->id ? "active" : "" ?>" href="index.php?option=com_awardpackage&view=giftcodecode&layout=renew_schedule&color=<?php echo $row->id ?>&package_id=<?php echo JRequest::getVar('package_id');?>" id="<?php echo $row->name ?>" rel="<?php echo $row->name ?>" >
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
            foreach ($categoryData as $row)
            {
            ?>
                <div class="tab">
                   <table class="adminlist">
                        <thead>
                            <tr>
                                <th width="1"><input type="checkbox" name="toggle" onclick="Joomla.checkAll(this)" /></th>
                                <td width="49%"><B><?php echo JText::_('COM_GIFT_CODE_CATEGORY');?></B></td>
                                <td width="49%"><B><?php echo JText::_('COM_GIFT_CODE_COLLECTION_TO_BE_RENEWED_EVERY');?></B></td>                
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $day = array (
                            "Sunday",
							"Monday",
                            "Tuesday",
                            "Wednesday",
                            "Thursday",
                            "Friday",
                            "Saturday"
                        );
                        $i = 0;
                        //foreach ($day as $d) {
                            foreach(array_slice($categoryData, 0, 7) as $row){
                            ?>
                                <tr>
                                    <th width="1">
                                      <?php echo JHtml::_('grid.id', $i, $i); ?>
                                      </th>            
                                   <td width="49%"><?php echo $row->name;?></td>
                                    <td width="49%"><?php echo $day[$i]; ?></td>                
                                </tr>
                            <?php
                               $k = $i%2;
							   $i++;
                        }                        
                        ?>
                        </tbody>
                    </table>                
                </div>                
             <?php
          
            }
            ?>
        </div>
    </div>
    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">
    <input type="hidden" name="option" value="com_awardpackage" />
    <input type="hidden" name="color" value="<?php echo JRequest::getVar("color") ? JRequest::getVar("color") : "1" ?>" />    
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />    
    <input type="hidden" name="controller" value="giftcodecode" />        
</form>