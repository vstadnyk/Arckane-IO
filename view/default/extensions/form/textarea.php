<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
	<? if (!$field->placeholder) { ?>
		<label for="<? echo $field->name; ?>">
			<? echo $form->lang->get('label_'.$field->name); ?>
			<? if (false && isset($field->required) && $field->required) { ?>
				<sup>*</sup>
			<? } ?>
		</label>
	<? } ?>
	<textarea <? echo $attr; ?>><? echo $field->text; ?></textarea>
<? echo $field->p ? '</p>' : ''; ?>