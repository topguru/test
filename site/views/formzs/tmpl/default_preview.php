<?php
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');

$page_id = 6;
$itemid = '';

$user = JFactory::getUser();
$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate')));
CJFunctions::load_jquery(array('libs'=>array('validate', 'ui', 'form', 'chosen'), 'theme'=>'none'));
$categories = JHtml::_('category.categories', S_APP_NAME);

foreach ($categories as $id=>$category){
	if(!$user->authorise('core.create', S_APP_NAME.'.category.'.$category->value)) {
		unset($categories[$id]);
	}
}
if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');
} else {
	JHTML::_('behavior.mootools');
}
JHTML::_('behavior.modal');
$this->loadHelper('select');

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME);
$bbcode = $this->params->get('default_editor', 'bbcode') == 'bbcode' ? true : false;
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/formfields.php';
$formfields = new SurveyFormFields($wysiwyg, $bbcode, $content);

?>
<script type="text/javascript">
 function showHide(div){
  if(document.getElementById(div).style.display == 'none'){
    document.getElementById(div).style.display = 'block';
  }else{
    document.getElementById(div).style.display = 'none'; 
  }
}

$(document).ready(function(){
	$("#expanderHead_1").click(function(){
		$("#expanderContent_1").slideToggle();
		if ($("#expanderSign_1").text() == "+"){
			$("#expanderSign_1").html("-")
		}
		else {
			$("#expanderSign_1").text("+")
		}
	});
	$("#expanderHead_2").click(function(){
		$("#expanderContent_2").slideToggle();
		if ($("#expanderSign_2").text() == "+"){
			$("#expanderSign_2").html("-")
		}
		else {
			$("#expanderSign_2").text("+")
		}
	});
	$("#expanderHead_3").click(function(){
		$("#expanderContent_3").slideToggle();
		if ($("#expanderSign_3").text() == "+"){
			$("#expanderSign_3").html("-")
		}
		else {
			$("#expanderSign_3").text("+")
		}
	});
	$("#expanderHead_4").click(function(){
		$("#expanderContent_4").slideToggle();
		if ($("#expanderSign_4").text() == "+"){
			$("#expanderSign_4").html("-")
		}
		else {
			$("#expanderSign_4").text("+")
		}
	});
});
  function pageChange() {
	  var page =  jQuery('select[name=surveyPages]').val();
	  jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=surveysetting&task=surveysetting.preview&page='+parseInt(page));
	  jQuery('form#adminForm').submit();	
  }
</script>
<form name="adminForm" id="adminForm" method="post" action=""><input
	type="hidden" name="uniq_id" id="uniq_id"
	value="<?php echo $this->item->uniq_id; ?>"> <input type="hidden"
	name="package_id" id="package_id"
	value="<?php echo $this->item->package_id; ?>" />
<div id="cj-wrapper">
<div
	class="container-fluid no-space-left no-space-right surveys-wrapper">
<div class="row-fluid">
<div class="span12" style="text-align: right;">


<table width="100%" border="0" cellpadding="1" cellspacing="2">
	<tr>
		<td width="50%" align="left"><select name="surveyPages"
			id="surveyPages" style="width: 250px;" onchange="pageChange();">
			<?php for($i=0; $i < count($this->item->surveyPages); $i++){
				$pages = $this->item->surveyPages[$i];
				?>
			<option value="<?php echo $pages->id; ?>"
			<?php echo ($pages->id == $this->item->surveyPage ? "selected=selected" : "" ); ?>>Page
				<?php echo $pages->sort_order; ?><?php echo ($pages->title != '' ? ' (' . $pages->title . ')': ''); ?></option>
				<?php } ?>
		</select></td>
		</td>
	</tr>
</table>
</div>
<br />
<br />
<div style="border: 1px solid #ccc; padding: 10px;font-size:11px; width:670px;"><br>
<div id="accordion">
				<?php  
$i=0;
foreach ($this->item->questionId as $questionId) { ?>
 <?php 
 $i=$i+1;
 echo '<div style="border: 1px solid #ccc;
 margin-bottom: 2px;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	background: #ccc;	
 ">
 <h4 href="#" id="expanderHead_'.$i.'" style="text-align:center;cursor:pointer;">Question #'.$questionId->question_id.'
 </h4>
  </div>'; 
 echo '<div id="expanderContent_'.$i.'" style="display:none">' ;?>

<table class="table table-hover table-striped" width="100%"
	id="questionTable">
	<tbody>
		<tr>
			<td align="center">
			<div style="border: 1px solid #ccc; padding: 10px; height: 190px;">
			<div
				style="border: 1px solid #ccc; padding: 2px; float: left; text-align: left; width: 615px; margin-top: 2px;">
			<div style="width: 620px; float: left;"><b><span><?php echo JText::_('Survey :	');?><a
				href="#" onclick="open_question(this);">Question <?php echo $questionId->question_id; ?></a></span></div>
			</div>
			<div style="width: 620px; float: left;">
			<table class="table table-striped" style="border: 1px solid #ccc;">
				<thead>
					<tr>
						<th width="40%"><u><?php echo JText::_('Answer')?></u></th>
						<th><u><?php echo JText::_('Giftcode')?></u></th>
						<th><u><?php echo JText::_('Giftcode Quantity')?></u></th>
						<th><u><?php echo JText::_('Cost Per Response')?></u></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span style="color: blue;">Complete</span></td>
						<td><input type="hidden" name="categorySurveyComplete[]"
							id="categorySurveyComplete"
							value="<?php echo $questionId->complete_giftcode; ?>" /> <a
							href="#" onclick="return false;"> <?php echo $questionId->complete_giftcode; ?></a></td>
						<td><input type="text" name="giftcodeQuantityComplete[]"
							style="width: 80px;"
							value="<?php echo $questionId->complete_giftcode_quantity; ?>"
							readonly="readonly" /></td>
						<td><input type="text" name="costPerResponseComplete[]"
							style="width: 80px;"
							value="<?php echo $questionId->complete_giftcode_cost_response; ?>"
							readonly="readonly" /></td>
					</tr>
					<tr>
						<td><span style="color: red;">Incomplete</span></td>
						<td><input type="hidden" name="categorySurveyIncomplete[]"
							id="categorySurveyIncomplete"
							value="<?php echo $questionId->incomplete_giftcode; ?>" /> <a
							href="#loadSurveyCategory" onclick="return false;"> <?php echo $questionId->incomplete_giftcode; ?></a></td>
						<td><input type="text" name="giftcodeQuantityIncomplete[]"
							style="width: 80px;"
							value="<?php echo $questionId->incomplete_giftcode_quantity; ?>"
							readonly="readonly" /></td>
						<td><input type="text" name="costPerResponseIncomplete[]"
							style="width: 80px;"
							value="<?php echo $questionId->incomplete_giftcode_cost_response; ?>"
							readonly="readonly" /></td>
					</tr>
				</tbody>
			</table>
			</div>
			</div>
			</td>
		</tr>
	</tbody>
</table>
				<?php
				if(!empty($questionId->questions)) {
					foreach ($questionId->questions as $qid=>$question){
						switch($question->question_type){
							case 1:
								echo $formfields->get_page_header_question($question, $class);
								break;
							case 2:
								echo $formfields->get_radio_question($question, $class);
								break;
							case 3:
								echo $formfields->get_checkbox_question($question, $class);
								break;
							case 4:
								echo $formfields->get_select_question($question, $class);
								break;
							case 5:
								echo $formfields->get_grid_radio_question($question, $class);
								break;
							case 6:
								echo $formfields->get_grid_checkbox_question($question, $class);
								break;
							case 7:
								echo $formfields->get_single_line_textbox_question($question, $class);
								break;
							case 8:
								echo $formfields->get_multiline_textarea_question($question, $class);
								break;
							case 9:
								echo $formfields->get_password_textbox_question($question, $class);
								break;
							case 10:
								echo $formfields->get_rich_textbox_question($question, $class);
								break;
							case 11:
								echo $formfields->get_image_radio_question($question, $class, CQ_IMAGES_URI_2);
								break;
							case 12:
								echo $formfields->get_image_checkbox_question($question, $class, CQ_IMAGES_URI_2);
								break;
							case 13:
								echo $formfields->get_user_name_question($question, $class);
								break;
							case 14:
								echo $formfields->get_user_email_question($question, $class);
								break;
							case 15:
								echo $formfields->get_calendar_question($question, $class);
								break;
							case 16:
								echo $formfields->get_address_question($question, $class);
								break;
							default: break;
						}
					}
				}
				?></div>
<?php } ?></div>

</div>
</div>
</div>

</form>
