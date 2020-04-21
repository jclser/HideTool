function insertAtCursor(t, e) {
	var n = t.scrollTop,
		o = document.documentElement.scrollTop;
	if (document.selection) {
		t.focus();
		var s = document.selection.createRange();
		s.text = e, s.select()
	} else if (t.selectionStart || "0" == t.selectionStart) {
		var l = t.selectionStart,
			c = t.selectionEnd;
		t.value = t.value.substring(0, l) + e + t.value.substring(c, t.value.length), t.focus(), t.selectionStart = l + e.length, t.selectionEnd = l + e.length
	} else t.value += e, t.focus();
	t.scrollTop = n, document.documentElement.scrollTop = o
}
$(function() {
	0 < $("#wmd-button-row").length && ($("#wmd-button-row").append('<li class="wmd-spacer wmd-spacer1"></li><li class="wmd-button" id="wmd-hide-button" style="" title="插入回复可见">Hide</li>')), $(document).on("click", "#wmd-hide-button", function() {
		myField = document.getElementById("text"), insertAtCursor(myField, "\n\n[hide]\n\n[/hide]\n\n")
	})
});