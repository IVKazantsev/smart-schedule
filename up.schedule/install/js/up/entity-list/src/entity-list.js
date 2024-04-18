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
			case 'subject':
				containerContent = `
					<div class="column is-11 is-60-height">
						Название
					</div>
				`;
				break;
			case 'user':
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
			case 'group':
				containerContent = `
					<div class="column is-60-height">
						Название
					</div>
				`;
				break;
			case 'audience':
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
				<a class="columns is-fullwidth is-60-height button has-text-left" href="/admin/edit/${this.entity}/${entityData.ID}/">
					${this.getEntityNodeContent(entityData)}
				</a>
			`;

			entitiesContainerNode.appendChild(entityNode);
		});

		this.rootNode.appendChild(entitiesContainerNode);
	}

	getEntityNodeContent(entityData)
	{
		switch (this.entity)
		{
			case 'subject':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column is-11">
						${entityData.TITLE}
					</div>
				`;

			case 'user':
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

			case 'group':
				return `
					<div class="column is-1">
						${entityData.ID}
					</div>
					<div class="column">
						${entityData.TITLE}
					</div>
				`;

			case 'audience':
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