		<pre class="editor"><code data-language="generic"><?= htmlentities($paste['text']); ?></code></pre>

		<aside>
			<time datetime="<?= $paste['date']; ?>"><?= Paste::prettify_date(strtotime($paste['date'])); ?></time>
<?php if($paste['parent']): ?>
			<a href="<?= $paste['parent']['url']; ?>">Parent</a>
<?php endif; ?>
			<a href="<?= $paste['raw']; ?>">Raw</a>
			<a href="<?= $paste['json']; ?>">JSON</a>
			<a href="<?= Config::path('base'); ?>/fork/<?= $paste['token']; ?>">Fork</a>
			<a href="<?= Config::path('base'); ?>/help">?</a>
			<a href="<?php echo Config::path('url').Config::path('base'); ?>">&#8962;</a>
		</aside>
