<li>
	<? if ($current == $page) { ?>
		<span><? echo $page; ?></span>
	<? } else { ?>
		<a href="<? echo $href; ?>"><? echo $page; ?></a>
	<? } ?>
</li>