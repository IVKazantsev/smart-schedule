document.addEventListener('DOMContentLoaded', function() {
	if (!document.getElementById('schedule-display-entity-list'))
	{
		return;
	}

	function moveIndicator()
	{
		indicator.style.top = selectedLink.offsetTop + 'px';
		indicator.style.width = selectedLink.offsetWidth + 'px';
		indicator.style.height = selectedLink.offsetHeight + 'px';
	}

	const indicator = document.querySelector('.selected-indicator');
	let selectedLink = document.querySelector('.selected-sidebar-entity');

	moveIndicator();

	const links = document.querySelectorAll('.display-entity');
	links.forEach(function(link) {
		link.addEventListener('click', function(event) {
			event.preventDefault();

			selectedLink.classList.remove('selected-sidebar-entity');
			selectedLink = this;
			selectedLink.classList.add('selected-sidebar-entity');

			moveIndicator();

			if (history.pushState)
			{
				const newUrl = link.href;
				window.history.pushState({ path: newUrl }, '', newUrl);
			}
		});
	});

	window.onresize = moveIndicator;
});