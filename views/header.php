<!doctype html>
<html>
	<head>
		<title><?= Config::$site_name; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?= Config::path('assets'); ?>css/solarized-dark.css">
		<link rel="stylesheet" href="<?= Config::path('assets'); ?>css/editor.css">
<?php if(isset($paste['hidden']) && $paste['hidden'] === 'true'): ?>
		<meta name="robots" content="noindex, nofollow">
<?php endif; ?>
	</head>
	<body>
