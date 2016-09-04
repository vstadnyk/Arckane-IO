<? if ($count) { ?>
	<div class="slider" <? echo $attr; ?>>
		<? if ($options->loader) { ?>
			<div class="loader"><span></span></div>
		<? } ?>
		<? if ($options->arrows) { ?>
			<a href="#" class="arrow prev"><</a>
		<? } ?>	
		<div class="mask">
			<? foreach ($items as $item) { ?>
				<? echo $this->get($options->template.$options->item_tmpl.'.php', array('data' => $item)); ?>
			<? } ?>
		</div>
		<? if ($options->arrows) { ?>
			<a href="#" class="arrow next">></a>
		<? } ?>	
		<? if ($options->pagination) { ?>
			<div class="pagination">
				<? for ($i = 1; $i <= $count; ++$i) { ?>
					<a href="#"<? echo !($i-1) ? ' class="active"' : ''; ?>><? echo $i; ?></a>
				<? } ?>
			</div>
		<? } ?>
	</div>
<? } ?>