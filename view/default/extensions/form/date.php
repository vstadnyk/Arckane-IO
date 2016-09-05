<? echo $field->p ? '<p' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
	<label for="<? echo $field->name; ?>">
		<? if (isset($field->required) && $field->required) { ?>
			<sup>*</sup>
		<? } ?>
		<? echo $form->lang->get('label_'.$field->name); ?>
	</label>
	<input <? echo isset($field->required) && $field->required ? 'required' : ''; ?> type="hidden" <? echo $attr; ?>>
	<select>
		<option><? echo $this->lang->get('day'); ?></option>
		<? for ($d = 1; $d <= 31; $d++) { ?>
			<option value="<? echo $d; ?>"><? echo $d; ?></option>
		<? } ?>
	</select>
	<select>
		<option><? echo $this->lang->get('month'); ?></option>
		<? foreach ($this->lang->get('months_array') as $n => $d) { ?>
			<option value="<? echo $n; ?>"><? echo $d; ?></option>
		<? } ?>
	</select>
	<select>
		<option><? echo $this->lang->get('year'); ?></option>
		<? for ($d = 2011; $d >= 1936; $d--) { ?>
			<option value="<? echo $d; ?>"><? echo $d; ?></option>
		<? } ?>
	</select>
<? echo $field->p ? '</p>' : ''; ?>