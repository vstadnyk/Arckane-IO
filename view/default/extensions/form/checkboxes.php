<? if ($field->options) { ?>
	<? echo $field->p ? '<div' : ''; echo isset($field->pclass) ? ' class="'.$field->pclass.'"' : ''; echo $field->p ? '>' : ''; ?>
		<p class="align-center padding-5-0"><? echo isset($field->text) ? $field->text : ''; ?></p>
		<div class="checkboxes" data-name="elements:checkboxes" <? echo $field->multyple ? 'data-multyple="true"' : ''; ?>>
			<input type="hidden" name="<? echo $field->iname; ?>" value="0" data-name="elements:checkboxesResult">
			<? foreach ($field->options as $item) { ?>
				<label>
					<input class="hide" type="checkbox" data-name="elements:checkboxesItem" value="<? echo $item->id; ?>">
					<span>
						<i class="fa fa-check"></i>
						<? echo $item->name; ?>
					</span>
				</label>
			<? } ?>
		</div>
	<? echo $field->p ? '</div>' : ''; ?>
<? } ?>