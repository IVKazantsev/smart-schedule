import { Tag, Type, Loc } from 'main.core';
import { Loader } from 'main.loader';
import { Validator } from '../../validator/src/validator';
import { PopupMessage } from 'up.popup-message';

export class CouplesList
{
	formData = {};
	daysOfWeek = {
		1: Loc.getMessage('DAY_1_OF_WEEK'),
		2: Loc.getMessage('DAY_2_OF_WEEK'),
		3: Loc.getMessage('DAY_3_OF_WEEK'),
		4: Loc.getMessage('DAY_4_OF_WEEK'),
		5: Loc.getMessage('DAY_5_OF_WEEK'),
		6: Loc.getMessage('DAY_6_OF_WEEK'),
	};

	entityId = undefined;
	entity = undefined;
	defaultEntity = 'group';
	isAdmin = false;

	dataSourceIsDb;

	constructor(options = {}, dataSourceIsDb = true)
	{
		if (Type.isStringFilled(options.rootNodeId))
		{
			this.rootNodeId = options.rootNodeId;
		}
		else
		{
			throw new Error('CouplesList: options.rootNodeId required');
		}

		if (!Type.isStringFilled(options.entity) || !Type.isStringFilled(options.entityId))
		{
			this.extractEntityFromUrl();
		}
		else
		{
			this.entity = options.entity;
			this.entityId = options.entityId;
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`CouplesList: element with id = "${this.rootNodeId}" not found`);
		}

		this.dataSourceIsDb = dataSourceIsDb;
		this.coupleList = [];
		this.checkRole();
	}

	extractEntityFromUrl()
	{
		const url = window.location.pathname;
		if (url.length === 0)
		{
			return {
				'entityId': 0,
				'entity': this.defaultEntity,
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
		const entity = addresses[entityIndex];

		const entityIdIndex = entityIndex + 1;
		const entityId = addresses[entityIdIndex];

		this.entityId = typeof Number(entityId) === 'number' ? entityId : undefined;
		this.entity = typeof entity === 'string' ? entity : this.defaultEntity;

		return {
			'entityId': this.entityId,
			'entity': this.entity,
		};
	}

	checkRole()
	{
		BX.ajax.runAction(
			'up:schedule.api.userRole.isAdmin',
			{},
		).then((response) => {
				this.isAdmin = response.data;
				this.reload();
			})
			.catch((error) => {
				console.error(error);
			});
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
		const controllerFn = (dataSourceIsDb) => {
			if (dataSourceIsDb)
			{
				return 'up:schedule.api.couplesList.getCouplesList';
			}
			else
			{
				return 'up:schedule.api.automaticSchedule.getCouplesList';
			}
		};

		const controller = controllerFn(this.dataSourceIsDb);
		const entity = (this.entity) ?? this.defaultEntity;
		const entityId = Number(this.entityId);

		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				controller,
				{
					data:
						{
							entity: entity,
							id: entityId,
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

		if (this.isAdmin === true && !this.dataSourceIsDb)
		{
			this.rootNode.classList.add('is-flex', 'column', 'columns', 'is-flex-direction-column', 'is-align-items-center');

			const previewMenuContainer = document.createElement('div');
			previewMenuContainer.classList.add('box', 'columns', 'column', 'is-half', 'is-flex', 'is-flex-direction-column', 'is-align-items-center');
			previewMenuContainer.id = 'preview-menu-container';

			const buttonsPreviewContainer = document.createElement('div');
			buttonsPreviewContainer.classList.add('is-flex', 'column', 'columns', 'is-full', 'is-justify-content-space-evenly', 'is-flex-direction-row', 'mb-2');
			buttonsPreviewContainer.id = 'buttons-preview-container';

			const label = Tag.render`
				<label class="label column m-2">Сохранить изменения?</label>
			`;

			const submitButton = Tag.render`
							<button 
							type="button" id="button-preview-submit" class="column  is-two-fifths button is-clickable is-medium is-primary">
								Подвердить
							</button>
						`;
			submitButton.addEventListener('click', () => {
				this.handleSubmitScheduleButtonClick();
			});

			const separator = Tag.render`<div class="column is-fifth"> </div>`;

			const cancelButton = Tag.render`
							<button 
							type="button" id="button-preview-cancel" class="column  is-two-fifths button is-danger is-clickable is-medium">
								Отменить
							</button>
						`;
			cancelButton.addEventListener('click', () => {
				this.handleCancelScheduleButtonClick();
			});

			buttonsPreviewContainer.appendChild(submitButton);
			buttonsPreviewContainer.appendChild(cancelButton);
			previewMenuContainer.appendChild(label);
			previewMenuContainer.appendChild(buttonsPreviewContainer);

			this.rootNode.appendChild(previewMenuContainer);
		}
		const couplesContainer = document.createElement('div');
		couplesContainer.className = 'column columns';
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
					let marginClassText = '';
					if (!this.isAdmin || !this.dataSourceIsDb)
					{
						marginClassText = 'class = "mt-3"';
					}

					coupleTextContainer = Tag.render`
						<div class="couple-text is-fullheight ${(this.isAdmin !== true) ? 'pt-2' : ''}">
							<p ${Validator.escapeHTML(marginClassText)}>${Validator.escapeHTML(this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE)}</p>
							<p hidden id="subjectId-${day}-${i}">${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID}</p>
							<p>${Validator.escapeHTML(this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER)}</p>
							<p hidden id="audienceId-${day}-${i}">${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_ID}</p>
							<p>${Validator.escapeHTML(this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_TITLE)}</p>
							<p hidden id="groupId-${day}-${i}">${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_ID}</p>
							<p>${Validator.escapeHTML(this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME)} ${Validator.escapeHTML(this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME)}</p>
							<p hidden id="teacherId-${day}-${i}">${this.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_ID}</p>
						</div>
					`;

					if (this.isAdmin === true && this.dataSourceIsDb)
					{
						const removeCoupleButton = Tag.render`
							<button 
							data-target="modal-js-example" type="button" id="button-remove-${day}-${i}" class="js-modal-trigger dropdown-item btn-remove-couple button is-clickable is-small is-primary is-light">
								${Loc.getMessage('DELETE')}
							</button>
						`;
						removeCoupleButton.addEventListener('click', () => {
							this.handleRemoveCoupleButtonClick(day, i);
						});

						const editCoupleButton = Tag.render`
							<button 
							data-target="modal-js-example" type="button" id="button-edit-${day}-${i}" class="js-modal-trigger dropdown-item btn-edit-couple button is-clickable is-small is-primary is-light mb-1">
								${Loc.getMessage('EDIT')}
							</button>
						`;
						editCoupleButton.addEventListener('click', () => {
							this.handleEditCoupleButtonClick();
						});

						dropdownContent.appendChild(editCoupleButton);
						dropdownContent.appendChild(removeCoupleButton);
					}
				}
				else
				{
					if (this.isAdmin === true && this.dataSourceIsDb)
					{
						const addCoupleButton = Tag.render`
							<button 
							data-target="modal-js-example" type="button" id="button-add-${day}-${i}" class="js-modal-trigger dropdown-item btn-add-couple button is-clickable is-small is-primary is-light">
								${Loc.getMessage('ADD')}
							</button>
						`;

						addCoupleButton.addEventListener('click', () => {
							this.handleAddCoupleButtonClick(day, i);
						});

						dropdownContent.appendChild(addCoupleButton);
					}
				}

				const coupleContainer = document.createElement('div');
				coupleContainer.className = 'box is-clickable couple m-0';

				if (this.isAdmin && this.dataSourceIsDb)
				{
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

					coupleContainer.appendChild(btnContainer);
				}
				coupleContainer.appendChild(coupleTextContainer);

				dayContainer.appendChild(coupleContainer);
			}
			dayColumnContainer.appendChild(dayContainer);
			couplesContainer.appendChild(dayColumnContainer);
		}
		this.rootNode.appendChild(couplesContainer);
	}

	handleSubmitScheduleButtonClick()
	{
		console.log('submit');
		BX.ajax.runAction(
				'up:schedule.api.automaticSchedule.setGeneratedSchedule',
				{
					data:
						{},
				},
			)
			.then(() => {
				window.location.replace('/');
			})
			.catch((error) => {
				console.log(error);
			});
	}

	handleCancelScheduleButtonClick()
	{
		console.log('cancel');
		BX.ajax.runAction(
				'up:schedule.api.automaticSchedule.cancelGeneratedSchedule',
				{
					data:
						{},
				},
			)
			.then(() => {
				window.location.replace('/');
			})
			.catch((error) => {
				console.log(error);
			});
	}

	handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple)
	{
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
		const dropdown = document.getElementById(`dropdown-${numberOfDay}-${numberOfCouple}`);
		dropdown.className = 'btn-edit-couple-container dropdown';
		const button = document.getElementById(`button-${numberOfDay}-${numberOfCouple}`);
		button.addEventListener('click', () => {
			this.handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
		}, { once: true });
	}

	handleAddCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		this.openCoupleModal();
		this.createAddForm(numberOfDay, numberOfCouple);
	}

	handleRemoveCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		this.removeCouple(numberOfDay, numberOfCouple);
	}

	handleEditCoupleButtonClick(numberOfDay, numberOfCouple)
	{
		this.openCoupleModal();
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
		if (this.isValidInput === false)
		{
			return;
		}
		else
		{
			this.deleteEmptyForm();
		}

		const submitButton = document.getElementById('submit-form-button');
		const cancelButton = document.getElementById('cancel-form-button');
		submitButton.addEventListener('click', () => {
			this.sendForm(numberOfDay, numberOfCouple, 'add');
		});

		cancelButton.addEventListener('click', () => {
			this.closeCoupleModal();
		}, { once: true });
	}

	sendForm(numberOfDay, numberOfCouple, typeOfRequest)
	{
		const subjectInput = document.getElementById('subject-select');
		const teacherInput = document.getElementById('teacher-select');
		const audienceInput = document.getElementById('audience-select');
		const groupInput = document.getElementById('group-select');

		if (subjectInput && teacherInput && audienceInput && groupInput)
		{
			const coupleInfo = {
				'GROUP_ID': groupInput.value,
				'SUBJECT_ID': subjectInput.value,
				'TEACHER_ID': teacherInput.value,
				'AUDIENCE_ID': audienceInput.value,
				'DAY_OF_WEEK': numberOfDay,
				'NUMBER_IN_DAY': numberOfCouple,
			};
			BX.ajax.runAction(
				'up:schedule.api.couplesList.' + typeOfRequest + 'Couple',
				{
					data:
						{
							coupleInfo: coupleInfo,
						},
				},
			).then((response) => {
					this.sendMessage('', 'Пара успешно добавлена');
					this.closeCoupleModal();
					this.reload();
				})
				.catch((error) => {
					this.sendMessage(error.data.errors);

					console.error(error);
				});
		}
	}

	removeCouple(numberOfDay, numberOfCouple)
	{
		const subject = document.getElementById(`subjectId-${numberOfDay}-${numberOfCouple}`).innerText;
		const teacher = document.getElementById(`teacherId-${numberOfDay}-${numberOfCouple}`).innerText;
		const audience = document.getElementById(`audienceId-${numberOfDay}-${numberOfCouple}`).innerText;
		const group = document.getElementById(`groupId-${numberOfDay}-${numberOfCouple}`).innerText;

		if (subject && teacher && audience && group)
		{
			const coupleInfo = {
				'GROUP_ID': group,
				'SUBJECT_ID': subject,
				'TEACHER_ID': teacher,
				'AUDIENCE_ID': audience,
				'DAY_OF_WEEK': numberOfDay,
				'NUMBER_IN_DAY': numberOfCouple,
			};
			BX.ajax.runAction(
				'up:schedule.api.couplesList.deleteCouple',
				{
					data:
						{
							coupleInfo: coupleInfo,
						},
				},
			).then((response) => {
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

		if (subjectsList.length === 0)
		{
			this.isValidInput = false;

			this.fillEmptyForm('SUBJECTS');

			return;
		}
		else
		{
			this.deleteEmptyForm();
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
					${Validator.escapeHTML(subject.subject.TITLE)}
				</option>
			`;
			selectContainer.appendChild(option);
		});

		const container = Tag.render`<div class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">${Loc.getMessage('SUBJECT')}</label>`;
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

		selectContainer.addEventListener('change', () => {
			this.isValidInput = true;

			this.insertAudiencesDataForForm(selectContainer.value);
			this.insertGroupsDataForForm(selectContainer.value);
			this.insertTeachersDataForForm(selectContainer.value);
		});
	}

	insertAudiencesDataForForm(subjectId)
	{
		if (!this.isValidInput)
		{
			return;
		}

		const form = document.getElementById('add-edit-form');
		if (document.getElementById('audience-container'))
		{
			document.getElementById('audience-container').remove();
		}

		const selectContainer = Tag.render`
			<select id="audience-select" name="subject"> </select>
		`;
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				if (subject.audiences.length === 0)
				{
					this.isValidInput = false;

					this.fillEmptyForm('AUDIENCES');
					if (document.getElementById('group-container'))
					{
						document.getElementById('group-container').remove();
					}
					if (document.getElementById('teacher-container'))
					{
						document.getElementById('teacher-container').remove();
					}

					return;
				}
				else
				{
					this.deleteEmptyForm();
				}

				subject.audiences.forEach((audience) => {
					const option = Tag.render`
						<option value="${audience.ID}">
							${Validator.escapeHTML(audience.NUMBER)}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		if (!this.isValidInput)
		{
			return;
		}

		const container = Tag.render`<div id="audience-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">${Loc.getMessage('AUDIENCE')}</label>`;
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
		if (!this.isValidInput)
		{
			return;
		}

		const form = document.getElementById('add-edit-form');
		if (document.getElementById('group-container'))
		{
			document.getElementById('group-container').remove();
		}

		const selectContainer = Tag.render`
			<select id="group-select" name="subject"> </select>
		`;
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				if (subject.groups.length === 0)
				{
					this.isValidInput = false;

					this.fillEmptyForm('GROUPS');
					if (document.getElementById('teacher-container'))
					{
						document.getElementById('teacher-container').remove();
					}

					return;
				}
				else
				{
					this.deleteEmptyForm();
				}

				subject.groups.forEach((group) => {
					const option = Tag.render`
						<option value="${group.ID}">
							${Validator.escapeHTML(group.TITLE)}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		if (!this.isValidInput)
		{
			return;
		}

		const container = Tag.render`<div id="group-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">${Loc.getMessage('GROUP')}</label>`;
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
		if (!this.isValidInput)
		{
			return;
		}

		const form = document.getElementById('add-edit-form');
		if (document.getElementById('teacher-container'))
		{
			document.getElementById('teacher-container').remove();
		}

		const selectContainer = Tag.render`
			<select id="teacher-select" name="subject"> </select>
		`;
		this.formData.forEach((subject) => {
			if (subject.subject.ID === subjectId)
			{
				if (subject.teachers.length === 0)
				{
					this.isValidInput = false;

					this.fillEmptyForm('TEACHERS');

					return;
				}
				else
				{
					this.deleteEmptyForm();
				}

				subject.teachers.forEach((teacher) => {
					const option = Tag.render`
						<option value="${teacher.ID}">
							${Validator.escapeHTML(teacher.NAME)} ${Validator.escapeHTML(teacher.LAST_NAME)}
						</option>
					`;
					selectContainer.appendChild(option);
				});
			}
		});

		if (!this.isValidInput)
		{
			return;
		}

		const container = Tag.render`<div id="teacher-container" class="is-60-height box edit-fields"></div>`;

		const label = Tag.render`<label class="label">${Loc.getMessage('TEACHERS')}</label>`;
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
							},
					},
				)
				.then((response) => {
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

	fillEmptyForm(entity)
	{
		const modalBody = document.getElementById('modal-body');

		this.deleteEmptyForm();

		const emptyForm = Tag.render`
						<div id="empty-form" class="has-text-danger">${Loc.getMessage('EMPTY_' + entity + '_MESSAGE')}</div>
					`;

		modalBody.appendChild(emptyForm);
	}

	deleteEmptyForm()
	{
		if (document.getElementById('empty-form'))
		{
			document.getElementById('empty-form').remove();
		}
	}

	sendMessage(errorMessage = '', successMessage = '')
	{
		BX.ready(function() {
			let PopupMessages = new BX.Up.Schedule.PopupMessage({
				rootNodeId: 'messages',
				errorsMessage: errorMessage,
				successMessage: successMessage,
			});
		});
	}
}
