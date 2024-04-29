import { Tag, Type, Loc } from 'main.core';

export class DisplayScheduleEntitiesList
{
	entityList = [];
	entity = undefined;
	suitableEntityList = undefined;
	entityId = undefined;
	currentEntity = undefined;
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

		if (Type.isObject(options.entityInfo))
		{
			this.entity = options.entityInfo.entity;
			this.entityId = options.entityInfo.entityId;
		}
		else
		{
			throw new Error('CouplesList: options.entityInfo required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`CouplesList: element with id = "${this.rootNodeId}" not found`);
		}

		this.entityList = [];
		this.suitableEntityList = [];
		this.reload();
	}

	reload(entityInfo = [], searchInput = '')
	{
		if (entityInfo.length !== 0)
		{
			this.entity = entityInfo.entity;
			this.entityId = entityInfo.entityId;
		}
		if (searchInput.length !== 0)
		{
			this.searchInList(searchInput);
			this.render(false);
			return;
		}
		this.loadList()
			.then((data) => {
				this.entityList = data.entities;
				this.suitableEntityList = data.entities;

				this.currentEntity = data.currentEntity;
				this.locEntity = data.locEntity;
				this.render();
			});
	}

	searchInList(searchInput)
	{
		let suitableEntityList = [];
		this.entityList.forEach((entity) => {
			if (entity['NAMING'].toLowerCase().includes(searchInput.toLowerCase()))
			{
				suitableEntityList.push(entity);
			}
		});

		this.suitableEntityList = suitableEntityList;
		console.log(this.entityList)
		console.log(this.suitableEntityList)
	}

	loadList()
	{
		this.entity = (this.entity) ?? this.defaultEntity;
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.displayEntitiesList.getDisplayEntitiesList',
				{
					data:
						{
							entity: this.entity,
							id: Number(this.entityId),
						},
				},
			).then((response) => {
					const data = response.data;

					resolve(data);
				})
				.catch((error) => {
					reject(error);
				});
		});
	}

	render(needToChangeInputValue = true)
	{
		this.rootNode.innerHTML = '';
		if(this.suitableEntityList.length === 0)
		{
			const message = Tag.render`
				<div class="dropdown-item">
					${Loc.getMessage('EMPTY_ENTITY_LIST')}
				</div>
			`;
			this.rootNode.appendChild(message);

			return;
		}

		this.suitableEntityList.forEach((entity) => {
			let entityLink;
			if (this.currentEntity)
			{
				entityLink = Tag.render`
				<a href="/${this.entity}/${entity['ID']}/"
				class="dropdown-item ${(entity['ID'] === this.currentEntity['ID']) ? 'is-active' : ''}">
				${entity['NAMING']}
				</a>
			`;
			}
			else
			{
				if (needToChangeInputValue)
				{
					document.getElementById('entity-selection-button').placeholder = Loc.getMessage('SELECT_' + this.locEntity);
					document.getElementById('entity-selection-button').value = '';
				}
				entityLink = Tag.render`
				<a href="/${this.entity}/${entity['ID']}/"
				class="dropdown-item">${entity['NAMING']}
				</a>
			`;
			}

			this.rootNode.appendChild(entityLink);
			this.dropdownsListeners();
			entityLink.addEventListener('click', (event) => {
				event.preventDefault();
				const dropdowns = document.querySelectorAll('.dropdown-item');

				dropdowns.forEach((dropdown) => {
					dropdown.classList.remove('is-active');
				});
				entityLink.classList.add('is-active');
				if (needToChangeInputValue)
				{
					document.getElementById('entity-selection-button').placeholder = Loc.getMessage(this.locEntity) + ' ' + entityLink.textContent;
					document.getElementById('entity-selection-button').value = '';
				}
				if (history.pushState)
				{
					const newUrl = entityLink.href;
					window.history.pushState({ path: newUrl }, '', newUrl);
				}
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();
			});
		});
	}

	dropdownsListeners()
	{
		const dropdowns = document.querySelectorAll('.dropdown-item');
		dropdowns.forEach((dropdown) => {
			dropdown.addEventListener('click', (event) => {
				event.preventDefault();
				dropdowns.forEach((dropdown) => {
					dropdown.classList.remove('is-active');
				});
				dropdown.classList.add('is-active');
				document.getElementById('entity-selection-button').placeholder = Loc.getMessage(this.locEntity) + ' ' + dropdown.textContent;
				document.getElementById('entity-selection-button').value = '';
				if (history.pushState)
				{
					const newUrl = dropdown.href;
					window.history.pushState({ path: newUrl }, '', newUrl);
				}
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();
			});
		});
	}
}
