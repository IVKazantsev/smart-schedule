function getEntityAddUrl()
{
	const anchor = window.location.hash;
	const entity = anchor.slice(1, anchor.length);
	return '/admin/add/' + entity + '/';
}

const waitForElement = (selector, callback) => {
	const observer = new MutationObserver(mutations => {
		mutations.forEach(mutation => {
			mutation.addedNodes.forEach(node => {
				if (node.matches && node.matches(selector))
				{
					callback(node);
				}
			});
		});
	});

	observer.observe(document.body, { childList: true, subtree: true });
};

document.addEventListener('DOMContentLoaded', function() {
	const addButton = document.getElementById('add-button');
	addButton.addEventListener('click', (event) => {
		addButton.href = getEntityAddUrl();
	});

	const tabButtonsContainers = document.querySelectorAll('.tabs');
	const tabButtons = document.querySelectorAll('.tabs .column a');
	const tabContainers = document.querySelectorAll('.tabs-content div');
	const backButton = document.getElementById('back-button');

	const entity = window.location.hash.substring(1);

	waitForElement('#entity-list-app', () => {
		BX.ready(function() {
			window.ScheduleEntityList = new BX.Up.EntityList({
				rootNodeId: 'entity-list-app',
				entity: document.getElementById('entity-list-app').parentElement.id,
			});

			const searchButton = document.getElementById('search-button');
			const searchInput = document.getElementById('search-input');

			searchInput.addEventListener('keypress', function(event) {
				if (event.key === 'Enter')
				{
					event.preventDefault();
					searchButton.click();
				}
			});

			searchButton.addEventListener('click', (event) => {
				event.preventDefault();

				window.ScheduleEntityList.reload(1, searchInput.value);
			});

			backButton.addEventListener('click', () => {
				searchInput.value = '';
			});
		});
	});

	if(entity)
	{
		tabButtonsContainers.forEach(container => {
			container.classList.remove('is-active');
		});

		tabContainers.forEach(container => container.classList.remove('active'));
		document.getElementById('admin-buttons-container').classList.add('active');
		document.getElementById(entity).classList.add('active');

		const entityListDiv = document.createElement('div');
		entityListDiv.setAttribute('id', 'entity-list-app');
		document.getElementById(entity).insertAdjacentElement('afterbegin', entityListDiv);
	}

	tabButtons.forEach(button => {
		button.addEventListener('click', (event) => {
			tabButtonsContainers.forEach(container => {
				container.classList.remove('is-active');
			});

			const id = event.target.hash.substring(1);
			tabContainers.forEach(container => container.classList.remove('active'));
			document.getElementById('admin-buttons-container').classList.add('active');
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