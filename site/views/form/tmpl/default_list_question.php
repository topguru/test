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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper_quiz.php';

$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME);
$bbcode = $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/formfieldss.php';
$formfields = new QuizFormFields($wysiwyg, $bbcode, $content);

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
	  var page =  jQuery('select[name=quizPages]').val();
	  jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&view=form&task=form.preview&page='+parseInt(page));
	  jQuery('form#adminForm').submit();	
  }
</script>
<form name="adminForm" id="adminForm" method="post" action=""><input
	type="hidden" name="uniq_id" id="uniq_id"
	value="<?php echo $this->item->uniq_id; ?>"> <input type="hidden"
	name="package_id" id="package_id"
	value="<?php echo $this->item->package_id; ?>" />
<table>
	<tr>
		<td width="10%" valign="top">
			<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
		</td>
		<td valign="top">
			<div id="cj-wrapper">
				<div
					class="container-fluid no-space-left no-space-right surveys-wrapper">
				<div class="row-fluid">
				<div style="border: 1px solid #ccc; padding: 10px;font-size:11px; width:670px;"><br>
				<div id="accordion">
                <div class="tab-content">
												
                  </div>                                  
				<?php  
$i=0;
foreach ($this->answers as $questionId) { $i=$i+1;?>
<div style="border: 1px solid #ccc;
 margin-bottom: 2px;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	background: #f0f0f0;	
 ">
 <a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.question&id='.$questionId->id.'&uniq_id='.$questionId->uniq_key.'&questionSelectedId='.$questionId->id.'&pageId='.$questionId->page_number.'&quiz_id='.$questionId->quiz_id)?>" 
												class="tooltip-hover" title="<?php echo JText::_('LBL_EDIT_QUESTIONS');?>">
<?php echo '<h4 href="#" id="expanderHead_'.$i.'" style="text-align:center;cursor:pointer;"> Question #'.$i.' '.$questionId->title; ?>
											</a>
                                            
</div>
<?php } ?>
				
				</div>
				</div>
				</div>
		</td>
	</tr>
</table>
</form>
