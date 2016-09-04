<!doctype html>
<html>
<head>
	<? echo $this->layout('layouts/header'); ?>
</head>
<body>
	<header class="white">
		<div class="wrapper table-rows">
			<a href="/" class="logo">
				<img src="/assets/img/logo.png" alt="logo">
			</a>
			<nav class="padding-15"><? echo $this->menu; ?></nav>
			<nav class="user-menu padding-15-0 right"><? echo $this->user_menu; ?></nav>
		</div>
	</header>
	<section>

	</section>
	<footer>

	</footer>
	<? echo $this->layout('layouts/footer'); ?>
</body>
</html>