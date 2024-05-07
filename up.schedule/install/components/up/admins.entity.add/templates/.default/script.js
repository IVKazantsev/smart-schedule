document.addEventListener('DOMContentLoaded', function() {
	const buttons = document.querySelectorAll('.btnDelete');

	function handleDeleteClick(e)
	{
		const elementId = e.target.id;
		const lengthOfSubstr = 'delete_subject_'.length;
		const itemId = elementId.slice(lengthOfSubstr, elementId.length);
		const currentSubject = document.getElementById('current_subject_' + itemId);
		currentSubject.remove();
	}

	buttons.forEach((button) => {
		button.addEventListener('click', handleDeleteClick);
	});

	let roleSelect = document.querySelector('[name=\'ROLE\']');

	if (roleSelect)
	{
		let groupSelect = document.querySelector('[name=\'GROUP\']');
		let groupContainer = groupSelect.closest('.edit-fields');

		let addSubject = document.getElementById('addSubject');
		let subjectsContainer = addSubject.closest('.edit-fields');

		roleDisplayingBySelectValue();

		roleSelect.addEventListener('change', () => {
			roleDisplayingBySelectValue();
		});
	}

	function roleDisplayingBySelectValue()
	{
		if (roleSelect.value === 'Администратор')
		{
			groupContainer.style.display = 'none';
			subjectsContainer.style.display = 'none';
		}
		else if (roleSelect.value === 'Преподаватель')
		{
			groupContainer.style.display = 'none';
			subjectsContainer.style.display = 'block';
		}
		else if (roleSelect.value === 'Студент')
		{
			groupContainer.style.display = 'block';
			subjectsContainer.style.display = 'none';
		}
	}
});