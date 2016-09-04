<?
	$search = $this->extension('search');
	$result = $search->result();
	$error = is_array($result) && isset($result['error']) ? $result['error'] : false;
?>
<article class="search-page align-center padding-20-0">
	<div class="search-page-top align-center inline-block">
		<div class="align-left">
			<? echo $search->form->render(); ?>
		</div>
		<p class="search-info align-left">
			<? if (!$error) { ?>
				<b><? echo $search->lang->get('count_results') ?>: </b><? echo $search->pagination->data->count; ?>
			<? } else { ?>
				<span class="error"><? echo $search->lang->get($error) ?></span>
			<? } ?>
		</p>
	</div>
	<? if (!$error && count((array)$result)) { ?>
		<div class="search-result align-left">
			<? foreach ($result as $item) { ?>
				<div class="search-item padding-10-0">
					<a class="title color-inherit" href="<? echo $item->href; ?>"><b><? echo $item->name; ?></b></a>
					<? if (!empty($item->announce) || !empty($item->content)) { ?>
						<div class="search-content">
							<? echo !empty($item->announce) ? htmlspecialchars_decode($item->announce) : (!empty($item->content) ? htmlspecialchars_decode($item->content) : ''); ?>
						</div>
					<? } ?>
				</div>
			<? } ?>
		</div>
	<? } ?>
	<? echo $search->pagination->render(); ?>
</article>