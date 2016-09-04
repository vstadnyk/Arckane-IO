<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
	<label for="<? echo $field->name; ?>">
		<input <? echo $attr; ?>>
		<? if (isset($field->required) && $field->required) { ?>
			<sup>*</sup>
		<? } ?>
		<? echo $field->label; ?>
	</label>
<? echo $field->p ? '</p>' : ''; ?>