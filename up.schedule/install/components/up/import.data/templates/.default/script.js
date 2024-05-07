document.addEventListener('DOMContentLoaded', function() {
	document.getElementById('excel-file').onchange = function() {
		document.getElementById('send-excel-form').submit();
	};
});