<div class="carousel"<? echo $attr; ?>>
	<a href="#" class="arrow prev disable fa fa-chevron-left"><</a>
	<div class="inner">
		<div class="mask">
			<? foreach ($items as $item) { ?>
				<div class="item">
					<? echo $item; ?>
				</div>
			<? } ?>
		</div>
	</div>
	<a href="#" class="arrow next fa fa-chevron-right">></a>
</div>