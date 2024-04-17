window.addEventListener('load', () => {
	const tabButtonsContainers = document.querySelectorAll('.tabs');
	const tabButtons = document.querySelectorAll('.tabs .column a');
	const tabContainers = document.querySelectorAll('.tabs-content div');
	const backButton = document.getElementById('back-button-container');

	let id = window.location.hash.substring(1);

	if(id)
	{
		tabButtonsContainers.forEach(container => {
			container.classList.remove('is-active');
		});

		tabContainers.forEach(container => container.classList.remove('active'));
		document.getElementById('back-button-container').classList.add('active');
		document.getElementById(id).classList.add('active');

		const entityListDiv = document.createElement('div');
		entityListDiv.setAttribute('id', 'entity-list-app');
		document.getElementById(id).insertAdjacentElement('afterbegin', entityListDiv);
	}

	tabButtons.forEach(button => {
		button.addEventListener('click', (event) => {
			tabButtonsContainers.forEach(container => {
				container.classList.remove('is-active');
			});

			const id = event.target.hash.substring(1);
			tabContainers.forEach(container => container.classList.remove('active'));
			document.getElementById('back-button-container').classList.add('active');
			document.getElementById(id).classList.add('active');

			const entityListDiv = document.createElement('div');
			entityListDiv.setAttribute('id', 'entity-list-app');
			document.getElementById(id).insertAdjacentElement('afterbegin', entityListDiv);
		})
	})

	backButton.addEventListener('click', (event) => {
		event.preventDefault();
		window.location.hash = '';
		tabContainers.forEach(container => container.classList.remove('active'));
		tabButtonsContainers.forEach(container => {
			container.classList.add('is-active');
		});
		if(document.getElementById('entity-list-app'))
		{
			document.getElementById('entity-list-app').remove();
		}
	})
})