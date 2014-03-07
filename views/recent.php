		<pre class="editor"><?php foreach($pastes as $paste): ?>- <a href="<?= Config::path('base'); ?>/<?= $paste['token']; ?>"><?= $paste['token']; ?></a> <?= Paste::prettify_date(strtotime($paste['date'])); ?><?php echo "\n"; endforeach; ?></pre>

		<aside>
			<a href="<?= Config::path('base'); ?>/recent/10" title="New pasts">JSON</a>
			<a href="<?= Config::path('base'); ?>/help">?</a>
			<a href="<?php echo Config::path('url').Config::path('base'); ?>">&#8962;</a>
		</aside>
