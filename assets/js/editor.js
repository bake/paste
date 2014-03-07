key.filter = function filter(event) {
	return true;
}

key('⌘+s, ctrl+s', function(event, handler) {
	event.preventDefault();

	if(document.querySelector('textarea').value != '')
		document.querySelector('form').submit();
});

key('⌘+h, ctrl+h', function(event, handler) {
	event.preventDefault();

	document.querySelector('input[name="hidden"]').checked = !document.querySelector('input[name="hidden"]').checked;
});

key('2, 4, 8', function(event, handler) {
	if(event.target.tagName != 'TEXTAREA') {
		localStorage['tab-size'] = handler.key;
		setTabSize(handler.key);
	}
});

key('tab', function(event, handler) {
	event.preventDefault();

	var target = event.target;
	var start  = target.selectionStart;
	var end    = target.selectionEnd;

	target.value = target.value.slice(0, start) + target.value.slice(end);
	target.value = target.value.slice(0, start) + "\t" + target.value.slice(start);
	target.selectionStart = target.selectionEnd = start + 1;
});

if(document.querySelector('a[href^="/fork"]')) {
	key('⌘+o, ctrl+o, a, e', function(event, handler) {
		event.preventDefault();

		window.location.href = document.querySelector('a[href^="/fork"]').href;
	});
}

setTabSize = function(size) {
	document.querySelector('.editor, textarea').style['tab-size'] = size;
}

if(localStorage['tab-size'] != '')
	setTabSize(localStorage['tab-size']);
