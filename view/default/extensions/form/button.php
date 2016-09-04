<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
	<button <? echo $attr; ?>><? echo $field->value; ?></button>
<? echo $field->p ? '</p>' : ''; ?>