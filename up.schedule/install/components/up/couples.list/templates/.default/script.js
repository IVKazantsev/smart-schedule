document.addEventListener('DOMContentLoaded', function() {
	// Выбор сущности
	const dropdown = document.querySelector('.dropdown');
	dropdown.addEventListener('click', function(event) {
		event.stopPropagation();
		event._isClick = true;
		dropdown.classList.toggle('is-active');
	});

	document.body.addEventListener('click', (event) => {
		if (event._isClick === true)
		{
			return;
		}
		dropdown.classList.remove('is-active');
	});

	BX.ready(function() {
		// Подгрузка JavaScript-расширений
		window.ScheduleCouplesList = new BX.Up.CouplesList({
			rootNodeId: 'couples-container',
			entity: document.getElementById('entity').textContent,
			entityId: document.getElementById('entity-id').textContent,
		});

		window.DisplayEntitiesList = new BX.Up.DisplayScheduleEntitiesList({
			rootNodeId: 'dropdown-menu-container',
			entity: window.ScheduleCouplesList.entity,
			entityId: window.ScheduleCouplesList.entityId,
			scheduleCouplesList: window.ScheduleCouplesList,
		});

		// Подгрузка видов отображения
		const entityButtons = document.querySelectorAll('.display-entity');
		entityButtons.forEach((button) => {
			button.addEventListener('click', () => {
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();

				const entityId = window.ScheduleCouplesList.entityId;
				const entity = window.ScheduleCouplesList.entity;

				window.DisplayEntitiesList.reload({'entityId': entityId, 'entity': entity});
			});
		});
	});

	const entitySelectionInput = document.getElementById('entity-selection-button');
	entitySelectionInput.addEventListener('input', () => {
		window.DisplayEntitiesList.reload([], entitySelectionInput.value);
		dropdown.classList.add('is-active');
	});
});