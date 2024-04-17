import { Type, Tag, Loc } from 'main.core';

export class EntityList
{
	constructor(options = {})
	{
		if (Type.isStringFilled(options.rootNodeId))
		{
			this.rootNodeId = options.rootNodeId;
		}
		else
		{
			throw new Error('EntityList: options.rootNodeId required');
		}

		if (Type.isStringFilled(options.entity))
		{
			this.entity = options.entity;
		}
		else
		{
			throw new Error('EntityList: options.entity required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`EntityList: element with id = "${this.rootNodeId}" not found`);
		}

		this.entityList = [];
		this.reload();
	}

	reload()
	{
		this.loadList()
			.then(entityList => {
				this.entityList = entityList;

				this.render();
			});
	}

	loadList()
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.adminPanel.get' + this.entity + 'List',
				{
					data:
						{},
				},
			).then((response) => {
					const entityList = response.data.entityList;
					resolve(entityList);
				})
				.catch((error) => {
					reject(error);
				});
		});
	}

	render()
	{
		this.rootNode.innerHTML = '';

		let containerContent;

		switch (this.entity)
		{
			case 'subjects':
				containerContent = `
					<div class="column is-11 is-60-height">
						Название
					</div>
				`;
				break;
			case 'users':
				containerContent = `
					<div class="column is-4 is-60-height">
						Имя
					</div>
					<div class="column is-4 is-60-height">
						Почта
					</div>
					<div class="column is-3 is-60-height">
						Роль
					</div>
				`;
				break;
			case 'groups':
				containerContent = `
					<div class="column is-60-height">
						Название
					</div>
				`;
				break;
			case 'audiences':
				containerContent = `
					<div class="column is-60-height">
						Номер
					</div>
					<div class="column is-60-height">
						Тип
					</div>
				`;
				break;
		}

		const entitiesContainerNode = Tag.render`
			<div class="box is-flex is-align-items-center is-flex-direction-column">
				<div class="columns is-60-height is-fullwidth title-of-table">
					<div class="column is-60-height is-1">
						ID
					</div>
					${containerContent}
				</div>
			</div>
		`;

		this.entityList.forEach(entityData => {
			const entityNode = Tag.render`
				<div class="columns is-fullwidth is-60-height button has-text-left">
					${this.getEntityNodeContent(entityData)}
				</div>
			`;

			entitiesContainerNode.appendChild(entityNode);
		});

		this.rootNode.appendChild(entitiesContainerNode);
	}

	getEntityNodeContent(entityData)
	{
		switch (this.entity)
		{
			case 'subjects':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column is-11">
						${entityData.TITLE}
					</div>
				`;

			case 'users':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column is-4">
						${entityData.NAME} ${entityData.LAST_NAME}
					</div>
					<div class="column is-4">
						${entityData.EMAIL ? entityData.EMAIL : 'Отсутствует'}
					</div>
					<div class="column is-3">
						${entityData.ROLE}
					</div>
				`;

			case 'groups':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column">
						${entityData.TITLE}
					</div>
				`;

			case 'audiences':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column">
						${entityData.NUMBER}
					</div>
					<div class="column">
						${entityData.UP_SCHEDULE_MODEL_AUDIENCE_AUDIENCE_TYPE_TITLE}
					</div>
				`;
		}
	}
}