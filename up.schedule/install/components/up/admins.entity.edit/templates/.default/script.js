document.addEventListener('DOMContentLoaded', function() {
	const buttons = document.querySelectorAll('.btnDelete');

	function handleDeleteClick(e)
	{
		const elementId = e.target.id;
		const lengthOfSubstr = 'delete_subject_'.length;
		const itemId = elementId.slice(lengthOfSubstr, elementId.length);
		const currentSubject = document.getElementById('current_subject_' + itemId);
		const hiddenInput = document.getElementsByName('current_subject_' + itemId);
		hiddenInput.item(0).name = 'delete_subject_' + itemId;
		currentSubject.id = 'delete_subject_' + itemId;
		currentSubject.style.display = 'none';
	}

	buttons.forEach((button) => {
		button.addEventListener('click', handleDeleteClick);
	});

	const openModalButton = document.getElementById('open-modal-button');
	const closeModalButton = document.getElementById('close-modal-button');
	const overlay = document.getElementById('overlay');
	const modal = document.getElementById('modal');

	openModalButton.addEventListener('click', () => {
		openModal(modal);
	});

	overlay.addEventListener('click', () => {
		closeModal(modal);
	});

	closeModalButton.addEventListener('click', () => {
		closeModal(modal);
	});

	function openModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.add('active');
		overlay.classList.add('active');
	}

	function closeModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.remove('active');
		overlay.classList.remove('active');
	}
});