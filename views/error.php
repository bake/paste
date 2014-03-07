<?php
$headline = 'Error '.$code.' :(';
$text     = $headline."\n";

for($i = 0; $i < strlen($headline); $i++)
	$text .= '='
?>
		<pre class="editor"><code data-language="generic"><?php echo $text; ?></code></pre>

		<aside>
			<a href="<?= Config::path('base'); ?>/help">?</a>
			<a href="<?php echo Config::path('url').Config::path('base'); ?>">&#8962;</a>
		</aside>
