<? 
if ($field->type == 'hidden') { ?>
	<input <? echo $attr; ?>>
<? } else { ?>
	<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
		<? if (!$field->placeholder) { ?>
			<label for="<? echo $field->name; ?>">
				<? if (isset($field->required) && $field->required) { ?>
					<sup>*</sup>
				<? } ?>
				<? echo $form->lang->get('label_'.$field->name); ?>
			</label>
		<? } ?>
		<input <? echo $attr; ?>>
	<? echo $field->p ? '</p>' : ''; ?>
<? } ?>