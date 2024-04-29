import { Tag, Type } from 'main.core';

export class CouplesList
{
	formData = {};
	daysOfWeek = {
		1: 'Понедельник',
		2: 'Вторник',
		3: 'Среда',
		4: 'Четверг',
		5: 'Пятница',
		6: 'Суббота',
	};
	entityId = undefined;
	entity = undefined;
	defaultEntity = 'group';

	constructor(options = {})
	{
		if (Type.isStringFilled(options.rootNodeId))
		{
			this.rootNodeId = options.rootNodeId;
		}
		else
		{
			throw new Error('CouplesList: options.rootNodeId required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`CouplesList: element with id = "${this.rootNodeId}" not found`);
		}

		this.extractEntityFromUrl();
		this.coupleList = [];
		this.reload();
	}

	extractEntityFromUrl()
	{
		const url = window.location.pathname;
		if (url.length === 0)
		{
			return {
				'entityId': null,
				'entity': null,
			};
		}

		const addresses = url.split('/');
		const entityIndex = addresses.findIndex((element, index, array) => {
			const needles = [
				'group',
				'teacher',
				'audience',
			];

			return needles.includes(element);
		});

		const entityIdIndex = entityIndex + 1;

		const entity = addresses[entityIndex];
		const entityId = addresses[entityIdIndex];

		this.entityId = typeof Number(entityId) === 'number' ? entityId : undefined;
		this.entity = typeof entity === 'string' ? entity : undefined;

		return {
			'entityId': this.entityId,
			'entity': this.entity,
		};
	}

	reload()
	{
		this.loadList()
			.then(coupleList => {
				this.coupleList = coupleList;

				this.render();
			});
	}

	loadList()
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.couplesList.getCouplesList',
				{
					data:
						{
							entity: (this.entity) ?? this.defaultEntity,
							id: Number(this.entityId),
						},
				},
			).then((response) => {
					const coupleList = response.data.couples;
					resolve(coupleList);
				})
				.catch((error) => {
					reject(error);
				});
		});
	}

	render()
	{
		this.rootNode.innerHTML = '';

		for (let day in this.daysOfWeek)
		{
			const dayTitleContainer = Tag.render`
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					${this.daysOfWeek[day]}
				</div>
			`;

			const dayColumnContainer = document.createElement('div');
			dayColumnContainer.className = 'column is-2';

			const dayContainer = document.createElement('div');
			dayContainer.className = 'box has-text-centered couples';

			dayContainer.appendChild(dayTitleContainer);

			for (let i = 1; i < 7; i++)
			{
				let coupleTextContainer = Tag.render`<br>`;
				const dropdownContent = Tag.render`<div class="dropdown-content"></div>`;

				if (typeof this.coupleList[day] !== 'undefined' && typeof this.coupleList[day][i] !== 'undefined')
				{
					console.log(this.coupleList);
					coupleTextContainer = Tag.render`
						<div class="couple-text">
							${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE}
							<br>
							${ this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER}
							<br>
							${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_TITLE}
							<br>
							${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME} ${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME}
						</div>
					`;

					const removeCoupleButton = Tag.render`
						<button 
						data-target="modal-js-example" type="button" id="button-remove-${day}-${i}" class="js-modal-trigger dropdown-item btn-remove-couple button is-clickable is-small is-primary is-light">
							Удалить
						</button>
					`;
					removeCoupleButton.addEventListener('click', () => {
						this.handleRemoveCoupleButtonClick();
					});

					const editCoupleButton = Tag.render`
						<button 
						data-target="modal-js-example" type="button" id="button-edit-${day}-${i}" class="js-modal-trigger dropdown-item btn-edit-couple button is-clickable is-small is-primary is-light mb-1">
							Изменить
						</button>
					`;
					editCoupleButton.addEventListener('click', () => {
						this.handleEditCoupleButtonClick();
					});

					dropdownContent.appendChild(editCoupleButton);
					dropdownContent.appendChild(removeCoupleButton);
				}
				else
				{
					const addCoupleButton = Tag.render`
						<button 
						data-target="modal-js-example" type="button" id="button-add-${day}-${i}" class="js-modal-trigger dropdown-item btn-add-couple button is-clickable is-small is-primary is-light">
							Добавить
						</button>
					`;
					addCoupleButton.addEventListener('click', () => {
						this.handleAddCoupleButtonClick(day, i);
					});

					dropdownContent.appendChild(addCoupleButton);
				}

				const coupleContainer = document.createElement('div');
				coupleContainer.className = 'box is-clickable couple m-0';

//КНОПКА
				const dropdownTrigger = Tag.render`<div class="dropdown-trigger"></div>`;
				const button = Tag.render`
					<button type="button" aria-haspopup="true" aria-controls="dropdown-menu" id="button-${day}-${i}" class="btn-dropdown-couple button is-clickable is-small is-ghost">
						...
					</button>
				`;

				button.addEventListener('click', () => {
					this.handleOpenDropdownCoupleButtonClick(day, i);
				}, { once: true });

				dropdownTrigger.appendChild(button);

				const btnContainer = Tag.render`
					<div id="dropdown-${day}-${i}" class="btn-edit-couple-container dropdown"></div>`;

				const dropdownMenu = Tag.render`<div class="dropdown-menu" id="dropdown-menu" role="menu"></div>`;
				dropdownMenu.appendChild(dropdownContent);

				btnContainer.appendChild(dropdownTrigger);
				btnContainer.appendChild(dropdownMenu);

				//coupleContainer.appendChild(some);

				coupleContainer.appendChild(btnContainer);
				coupleContainer.appendChild(coupleTextContainer);

				dayContainer.appendChild(coupleContainer);
			}
			dayColumnContainer.appendChild(dayContainer);
			this.rootNode.appendChild(dayColumnContainer);
		}
	}

	handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		console.log('open');
		const modals = document.querySelectorAll('.dropdown');
		modals.forEach((modalWindow) => {
			modalWindow.classList.remove('is-active');
		});
		const dropdown = document.getElementById(`dropdown-${numberOfDay}-${numberOfCouple}`);
		dropdown.className = 'btn-edit-couple-container dropdown is-active';

		const button = document.getElementById(`button-${numberOfDay}-${numberOfCouple}`);
		button.addEventListener('click', () => {
			this.handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
		}, { once: true });
	}

	handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		console.log('close');
		const dropdown = document.getElementById(`dropdown-${numberOfDay}-${numberOfCouple}`);
		dropdown.className = 'btn-edit-couple-container dropdown';
		const button = document.getElementById(`button-${numberOfDay}-${numberOfCouple}`);
		button.addEventListener('click', () => {
			this.handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
		}, { once: true });
	}

	handleAddCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		//console.log(numberOfDay);

		this.openCoupleModal();
		this.createAddForm(numberOfDay, numberOfCouple);
		console.log('add');
	}

	handleRemoveCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		this.openCoupleModal();
		console.log('remove');
	}

	handleEditCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		this.openCoupleModal();
		console.log('edit');
	}

	openCoupleModal()
	{
		const modal = document.getElementById('coupleModal');
		modal.classList.add('is-active');
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape')
			{
				this.closeCoupleModal();
			}
		});
		const closeButton = document.getElementById('button-close-modal');
		closeButton.addEventListener('click', () => {
			this.closeCoupleModal();
		});
	}

	createAddForm(numberOfDay, numberOfCouple)
	{
		this.fetchSubjectsForAddForm()
			.then((subjectsList) => {
				this.insertSubjectsDataForAddForm(subjectsList);
			});

		console.log(numberOfDay + ' ' + numberOfCouple);

		const submitButton = document.getElementById('submit-form-button');
		const cancelButton = document.getElementById('cancel-form-button');
		submitButton.addEventListener('click', () => {
			//console.log(numberOfDay);
			this.sendForm(numberOfDay, numberOfCouple);
		}, { once: true });

		cancelButton.addEventListener('click', () => {
			this.closeCoupleModal();
		}, { once: true });

		/*const form = document.getElementById('add-edit-form');*/

// 		`<div class="is-60-height box edit-fields">
// \t\t\t<?php if (is_array($field)): ?>
// \t\t\t\t<label class="label"><?= GetMessage($key) ?></label>
// \t\t\t\t\t<div class="control">
// \t\t\t\t\t\t<div class="select">
// \t\t\t\t\t\t\t<label>
// \t\t\t\t\t\t\t\t<select name="<?= $key ?>">
// \t\t\t\t\t\t\t\t\t<?php foreach ($field as $keyOfField => $subfield): ?>
// \t\t\t\t\t\t\t\t\t\t<option value="<?=$subfield['ID']?>">
// \t\t\t\t\t\t\t\t\t\t\t<?=$subfield['TITLE']?>
// \t\t\t\t\t\t\t\t\t\t</option>
// \t\t\t\t\t\t\t\t\t<?php
// \t\t\t\t\t\t\t\t\tendforeach; ?>
// \t\t\t\t\t\t\t\t</select>
// \t\t\t\t\t\t\t</label>
// \t\t\t\t\t\t</div>
// \t\t\t\t\t</div>
// \t\t\t<?php endif; ?>
// \t\t</div>`
	}

	sendForm(numberOfDay, numberOfCouple)
	{
		const subjectInput = document.getElementById('subject-select');
		const teacherInput = document.getElementById('teacher-select');
		const audienceInput = document.getElementById('audience-select');
		const groupInput = document.getElementById('group-select');

		if (subjectInput && teacherInput && audienceInput && groupInput)
		{
			console.log(subjectInput.value);
			const coupleInfo = {
				'GROUP_ID': groupInput.value,
				'SUBJECT_ID': subjectInput.value,
				'TEACHER_ID': teacherInput.value,
				'AUDIENCE_ID': audienceInput.value,
				'DAY_OF_WEEK': numberOfDay,
				'NUMBER_IN_DAY': numberOfCouple,
			};
			BX.ajax.runAction(
				'up:schedule.api.couplesList.addCouple',
				{
					data:
						{
							coupleInfo: coupleInfo,
						},
				},
			).then((response) => {
					console.log(response);
					this.closeCoupleModal();
					this.reload();
				})
				.catch((error) => {
					console.error(error);
				});
		}
	}

	insertSubjectsDataForAddForm(subjectsList)
	{
		let form;
		const modalBody = document.getElementById('modal-body');
		if (document.getElementById('add-edit-form'))
		{
			form = document.getElementById('add-edit-form');
			form = Tag.render`<form id="add-edit-form"></form>`;
			modalBody.innerHTML = '';
		}

		this.formData = subjectsList;

		if(subjectsList.length === 0)
		{
			if (document.getElementById('empty-form'))
			{
				form.removeChild(document.getElementById('empty-form'));
			}

			const emptyForm = Tag.render`
				<div id="empty-form">Добавлять больше нечего</div>
			`



			modalBody.appendChild(emptyForm);
			return;
		}
		const selectContainer = Tag.render`
			<select id="subject-select" name="subject"> </select>
		`;

		const option = Tag.render`
				<option selected disabled hidden></option>
			`;
		selectContainer.appendChild(option);
		subjectsList.forEach((subject) => {
			const option = Tag.render`
				<option value="${subject.subject.ID}">
					${subject.subject.TITLE}
				</option>
			`;
			selectContainer.appendChild(option);
			//console.log(subject.subject);
		});

		const container = Tag.render`<div class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">Предмет</label>`;
		const divControl = Tag.render`<div class="control"></div>`;
		const divSelect = Tag.render`<div class="select"></div>`;
		const underLabel = Tag.render`<label></label>`;

		underLabel.appendChild(selectContainer);
		divSelect.appendChild(underLabel);
		divControl.appendChild(divSelect);
		container.appendChild(label);
		container.appendChild(divControl);

		form.appendChild(container);

		modalBody.appendChild(form);
		const select = document.getElementById('subject-select');
		select.addEventListener('change', () => {
			this.insertAudiencesDataForForm(select.value);
			this.insertGroupsDataForForm(select.value);
			this.insertTeachersDataForForm(select.value);
		});
	}

	insertAudiencesDataForForm(subjectId)
	{
		const form = document.getElementById('add-edit-form');
		if (document.getElementById('audience-container'))
		{
			form.removeChild(document.getElementById('audience-container'));
		}

		const selectContainer = Tag.render`
			<select id="audience-select" name="subject"> </select>
		`;
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				subject.audiences.forEach((audience) => {
					const option = Tag.render`
						<option value="${audience.ID}">
							${audience.NUMBER}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		const container = Tag.render`<div id="audience-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">Аудитория</label>`;
		const divControl = Tag.render`<div class="control"></div>`;
		const divSelect = Tag.render`<div class="select"></div>`;
		const underLabel = Tag.render`<label></label>`;

		underLabel.appendChild(selectContainer);
		divSelect.appendChild(underLabel);
		divControl.appendChild(divSelect);
		container.appendChild(label);
		container.appendChild(divControl);

		form.appendChild(container);
	}

	insertGroupsDataForForm(subjectId)
	{
		const form = document.getElementById('add-edit-form');
		if (document.getElementById('group-container'))
		{
			form.removeChild(document.getElementById('group-container'));
		}

		const selectContainer = Tag.render`
			<select id="group-select" name="subject"> </select>
		`;
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				subject.groups.forEach((group) => {
					const option = Tag.render`
						<option value="${group.ID}">
							${group.TITLE}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		const container = Tag.render`<div id="group-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">Группа</label>`;
		const divControl = Tag.render`<div class="control"></div>`;
		const divSelect = Tag.render`<div class="select"></div>`;
		const underLabel = Tag.render`<label></label>`;

		underLabel.appendChild(selectContainer);
		divSelect.appendChild(underLabel);
		divControl.appendChild(divSelect);
		container.appendChild(label);
		container.appendChild(divControl);

		form.appendChild(container);
	}

	insertTeachersDataForForm(subjectId)
	{
		const form = document.getElementById('add-edit-form');
		if (document.getElementById('teacher-container'))
		{
			form.removeChild(document.getElementById('teacher-container'));
		}

		const selectContainer = Tag.render`
			<select id="teacher-select" name="subject"> </select>
		`;
		// console.log(this.formData);
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				subject.teachers.forEach((teacher) => {
					const option = Tag.render`
						<option value="${teacher.ID}">
							${teacher.NAME} ${teacher.LAST_NAME}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		const container = Tag.render`<div id="teacher-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">Преподаватели</label>`;
		const divControl = Tag.render`<div class="control"></div>`;
		const divSelect = Tag.render`<div class="select"></div>`;
		const underLabel = Tag.render`<label></label>`;

		underLabel.appendChild(selectContainer);
		divSelect.appendChild(underLabel);
		divControl.appendChild(divSelect);
		container.appendChild(label);
		container.appendChild(divControl);

		form.appendChild(container);
	}

	fetchSubjectsForAddForm(numberOfDay, numberOfCouple)
	{
		this.extractEntityFromUrl();
		return (new Promise((resolve, reject) => {
			BX.ajax.runAction(
					'up:schedule.api.couplesList.fetchAddCoupleData',
					{
						data:
							{
								entity: this.entity,
								id: this.entityId,
								// numberOfDay: numberOfDay,
								// numberOfCouple: numberOfCouple,
							},
					},
				)
				.then((response) => {
					console.log('Subjects:');
					console.log(response.data);
					const subjectList = response.data;
					resolve(subjectList);
				})
				.catch((error) => {
					reject(error);
				});
		}))
			;
	}

	closeCoupleModal()
	{
		const modal = document.getElementById('coupleModal');
		modal.classList.remove('is-active');
	}
}
