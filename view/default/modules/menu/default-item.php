<?
	$class = $attributes = $attr = array();
	
	if ($options->use_attributes) {
		$attributes = parse_ini_string($item->attributes);
		foreach ($attributes as $key => $value) {
			$key == 'class' ? $class[] = $value : $attr[] = $key.'='.$value;
		}
	}
	
	$item->active ? $class[] = 'active' : false;
	!empty($childs) ? $class[] = 'parent' : false;
?>
<li<? echo count($class) ? ' class="'.implode(' ', $class).'"' : ''; ?>>
	<? if (!$options->no_href) { ?><a <? echo ' '.implode(' ', $attr); ?> href='<? echo $item->url; ?>'><? } ?>
		<? echo $item->name; ?>
	<? if (!$options->no_href) { ?></a><? } ?>
	<? echo $childs; ?>
</li>