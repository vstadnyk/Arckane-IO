<? if (count($nav)) { 
	$i = 0;
?>
	<div class="tabs">
		<nav class="align-center"><? echo implode('', $nav); ?></nav>
		<div class="tabs-body">
			<? foreach ($nav as $tab => $row) { ?>
				<div class="table-rows <? echo !$i ? 'active' : ''; ?>">
					<? foreach ($items[$tab] as $item) { ?>
						<div class="row-33"><? echo $item; ?></div>
					<? } ?>
				</div>
			<? $i++; } ?>
		</div>
	</div>
<? } ?>