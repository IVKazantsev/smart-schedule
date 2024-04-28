import { Tag, Type, Loc } from 'main.core';

export class DisplayScheduleEntitiesList
{
	entitiesList = [];
	entity = undefined;
	entityId = undefined;
	currentEntity = undefined;

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

		this.entitiesList = [];
		this.reload();
	}

	reload(entityInfo = [])
	{
		if(entityInfo.length !== 0)
		{
			this.entity = entityInfo.entity;
			this.entityId = entityInfo.entityId;
		}
		this.loadList()
			.then((data) => {
				this.entityList = data.entities;
				this.currentEntity = data.currentEntity;
				this.locEntity = data.locEntity;
				this.render();
			});
	}

	loadList()
	{
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

	render()
	{
		this.rootNode.innerHTML = '';
		this.entityList.forEach((entity) => {
			let entityLink;
			if(this.currentEntity)
			{
				entityLink = Tag.render`
				<a href="/${this.entity}/${entity['ID']}/"
				class="dropdown-item ${(entity['ID'] === this.currentEntity['ID']) ? 'is-active' : ''}">
				${Loc.getMessage(this.locEntity)} ${entity['NAMING']}
				</a>
			`;
			}
			else
			{
				document.getElementById('current-entity').textContent = Loc.getMessage('SELECT_' + this.locEntity);
				entityLink = Tag.render`
				<a href="/${this.entity}/${entity['ID']}/"
				class="dropdown-item">
				${Loc.getMessage(this.locEntity)} ${entity['NAMING']}
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
				document.getElementById('current-entity').textContent=entityLink.textContent;
				if (history.pushState) {
					const newUrl = entityLink.href;
					window.history.pushState({path:newUrl},'',newUrl);
				}
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();
			})
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
				document.getElementById('current-entity').textContent=dropdown.textContent;
				if (history.pushState) {
					const newUrl = dropdown.href;
					window.history.pushState({path:newUrl},'',newUrl);
				}
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();
			})
		});
	}
}
