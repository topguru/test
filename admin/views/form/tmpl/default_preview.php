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
  jQuery(function() {
	  jQuery( "#accordion" ).accordion({ clearStyle: true, autoHeight: false });
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
<div id="cj-wrapper">
<div
	class="container-fluid no-space-left no-space-right surveys-wrapper">
<div class="row-fluid">
<div class="span12" style="text-align: left;">
<table width="100%" border="0">
	<tr>
		<td width="50%" align="left"><select name="quizPages"
			id="quizPages" style="width: 250px;" onchange="pageChange();">
			<?php for($i=0; $i < count($this->item->quizPages); $i++){
				$pages = $this->item->quizPages[$i];
				?>
			<option value="<?php echo $pages->id; ?>"
			<?php echo ($pages->id == $this->item->quizPage ? "selected=selected" : "" ); ?>>Page
				<?php echo $pages->sort_order; ?><?php echo ($pages->title != '' ? ' (' . $pages->title . ')': ''); ?></option>
				<?php } ?>
		</select></td>		
	</tr>
</table>
</div>
<br />
<br />
<div style="border: 1px solid #ccc; padding: 10px;font-size:11px; width:670px;"><br>
<div id="accordion"><?php  foreach ($this->item->questionId as $questionId) { ?>
<h3>Question <?php echo $questionId->question_id; ?></h3>
<div style="height:auto;">
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
						<th width="25%"><u><?php echo JText::_('Answer')?></u></th>
						<th><u><?php echo JText::_('Giftcode')?></u></th>
						<th><u><?php echo JText::_('Giftcode Quantity')?></u></th>
						<th><u><?php echo JText::_('Cost Per Response')?></u></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span style="color: blue;">Correct</span></td>
						<td><input type="hidden" name="categoryQuizComplete[]"
							id="categoryQuizComplete"
							value="<?php echo $questionId->complete_giftcode; ?>" /> <a
							href="#" onclick="open_modal_window(this, 0); return false;"> <?php echo $questionId->complete_giftcode; ?></a></td>
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
						<td><span style="color: red;">Incorrect</span></td>
						<td><input type="hidden" name="categoryQuizIncomplete[]"
							id="categoryQuizIncomplete"
							value="<?php echo $questionId->incomplete_giftcode; ?>" /> <a
							href="#loadQuizCategory" onclick="open_modal_window(this, 1);"> <?php echo $questionId->incomplete_giftcode; ?></a></td>
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
