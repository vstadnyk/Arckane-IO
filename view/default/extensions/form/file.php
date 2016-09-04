<?
	$class = isset($field->class) ? ' '.$field->class : '';
	$nofile = $form->lang->get('no_file');
	$multiple = isset($field->multiple) && $field->multiple ? ' multiple' : '';
	$accept = isset($field->accept) && $field->accept ? ' accept="'.$field->accept.'"' : '';
?>
<p class="file<? echo $class; ?>">
	<? if (isset($field->label) && $field->label) { ?>
		<label><? echo $form->lang->get($field->name); ?></label>
	<? } ?>
	<span data-nofile="<? echo $nofile; ?>"><? echo $nofile; ?></span>
	<input style="display: none;"<? echo $multiple.$accept; ?> type="file" name="<? echo $field->name; ?>">
	<button type="button"><? echo $form->lang->get('select'); ?></button>
</p>