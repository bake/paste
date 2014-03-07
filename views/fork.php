		<form method="post" action="<?= Config::path('base'); ?>/add">
			<textarea class="editor" name="text" placeholder="Hello World" autofocus><?= htmlentities($paste['text']); ?></textarea>
			<aside>
				<input type="text" name="brobdingnagian" value="">
				<input type="hidden" name="parent" value="<?= $paste['token']; ?>">
				<input type="checkbox" name="hidden" id="hidden" value="true"><label for="hidden" title="$mod + h">Hidden</label>
				<input type="submit" name="save" value="Save" title="$mod + s">
				<a href="<?= Config::path('base'); ?>/help">?</a>
				<a href="<?php echo Config::path('url').Config::path('base'); ?>">&#8962;</a>
			</aside>
		</form>
