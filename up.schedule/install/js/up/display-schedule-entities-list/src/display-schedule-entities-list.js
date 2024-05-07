import { Tag, Type, Loc } from 'main.core';
import { Validator } from '../../validator/src/validator';

export class DisplayScheduleEntitiesList
{
	entityList = [];
	suitableEntityList = [];

	entity = undefined;
	entityId = undefined;

	currentEntity = undefined;
	defaultEntity = 'group';

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

		if(!options.scheduleCouplesList)
		{
			throw new Error(`CouplesList: schedule couples list in not included`);
		}
		this.scheduleCouplesList = options.scheduleCouplesList;

		if (!Type.isStringFilled(options.entity) || !Type.isStringFilled(options.entityId))
		{
			this.scheduleCouplesList.extractEntityFromUrl();
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
		this.entityList = [];
		this.suitableEntityList = [];

		this.reload();
	}

	reload(entityInfo = [], searchInput = false)
	{
		if (typeof searchInput === 'string' || searchInput instanceof String)
		{
			this.searchInList(searchInput);
			this.render(false);
			return;
		}

		if (entityInfo.length !== 0)
		{
			this.entity = entityInfo.entity;
			this.entityId = entityInfo.entityId;
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
		if (String.length === 0)
		{
			this.suitableEntityList = this.entityList;
			return;
		}
		this.entityList.forEach((entity) => {
			if (entity['NAMING'].toLowerCase().includes(searchInput.toLowerCase()))
			{
				suitableEntityList.push(entity);
			}
		});

		this.suitableEntityList = suitableEntityList;
	}

	loadList()
	{
		this.entity = (this.entity) ?? this.defaultEntity;
		this.entityId = (this.entityId) ?? 0;
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.displayEntitiesList.getDisplayEntitiesList',
				{
					data:
						{
							entity: this.entity,
							id: this.entityId,
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

	render(isStateChanged = true)
	{
		this.rootNode.innerHTML = '';
		if (this.suitableEntityList.length === 0)
		{
			const message = Tag.render`
				<div class="dropdown-item">
					${Loc.getMessage('EMPTY_ENTITY_LIST')}
				</div>
			`;
			this.rootNode.appendChild(message);

			return;
		}

		if (isStateChanged && !this.currentEntity)
		{
			document.getElementById('entity-selection-button').placeholder = Loc.getMessage('SELECT_' + this.locEntity);
			document.getElementById('entity-selection-button').value = '';
		}

		let linkPrefix = '';
		if (!this.dataSourceIsDb)
		{
			linkPrefix = '/scheduling/preview';
		}

		this.suitableEntityList.forEach((entity) => {
			let entityLink;

			if (this.currentEntity)
			{
				entityLink = Tag.render`
				<a href="${linkPrefix}/${Validator.escapeHTML(this.entity)}/${entity['ID']}/"
				class="dropdown-item ${(entity['ID'] === this.currentEntity['ID']) ? 'is-active' : ''}">
				${Validator.escapeHTML(entity['NAMING'])}
				</a>
			`;
			}
			else
			{
				entityLink = Tag.render`
				<a href="${linkPrefix}/${Validator.escapeHTML(this.entity)}/${entity['ID']}/"
				class="dropdown-item">${Validator.escapeHTML(entity['NAMING'])}
				</a>
			`;
			}

			this.rootNode.appendChild(entityLink);

			entityLink.addEventListener('click', (event) => {
				event.preventDefault();

				this.entityList.forEach((entity) => {
					if (entity['NAMING'] === entityLink.textContent)
					{
						this.currentEntity = entity;
						this.entityId = entity['ID'];
					}
				});

				const dropdowns = document.querySelectorAll('.dropdown-item');

				dropdowns.forEach((dropdown) => {
					dropdown.classList.remove('is-active');
				});
				entityLink.classList.add('is-active');

				document.getElementById('entity-selection-button').placeholder = Loc.getMessage(this.locEntity) + ' ' + Validator.escapeHTML(entityLink.textContent);
				document.getElementById('entity-selection-button').value = '';

				if (history.pushState)
				{
					const newUrl = entityLink.href;
					window.history.pushState({ path: newUrl }, '', newUrl);
				}

				this.scheduleCouplesList.extractEntityFromUrl();
				this.scheduleCouplesList.reload();
			});
		});
	}
}
