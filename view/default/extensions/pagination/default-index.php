<nav class="pagination">
	<ul>
		<? if ($prev) { ?>
			<li>
				<a class="prev" href="<? echo $prev; ?>"><</a>
			</li>
		<? } ?>
		<? echo $pagination; ?>
		<? if ($dots) { ?>
			<li class="dots">
				<span>...</span>
			</li>
			<li>
				<a href="<? echo $last['href']; ?>"><? echo $last['text']; ?></a>
			</li>
		<? } ?>
		<? if ($next) { ?>
			<li>
				<a class="next" href="<? echo $next; ?>">></a>
			</li>
		<? } ?>
	</ul>
</nav>