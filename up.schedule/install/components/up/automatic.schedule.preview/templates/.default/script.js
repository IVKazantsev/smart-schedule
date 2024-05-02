window.onload = () => {
	const dropdown = document.querySelector('.dropdown');
	dropdown.addEventListener('click', function(event) {
		event.stopPropagation();
		event._isClick = true;
		dropdown.classList.toggle('is-active');
	});

	document.body.addEventListener('click', (event) => {
		if(event._isClick === true) {
			return;
		}
		dropdown.classList.remove('is-active');
	});
}