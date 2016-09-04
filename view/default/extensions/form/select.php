<? if ($field->options) { ?>
	<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
		<select <? echo $attr; ?>>
			<? foreach ($field->options as $item) {
				echo $form->render_field($item->name, (array)$item);
			} ?>
		</select>
	<? echo $field->p ? '</p>' : ''; ?>
<? } else { ?>
	<input type="hidden" name="<? echo $field->name; ?>" value="0">
<? } ?>