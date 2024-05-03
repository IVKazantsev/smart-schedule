import { Type, Tag, Loc } from 'main.core';
import { Validator } from '../../validator/src/validator';

export class EntityList
{
	entityNode = {
		'subject':
			{
				'header':
					Tag.render`
					<div class="column is-11 is-60-height">
						${Loc.getMessage('TITLE')}
					</div>
					`,
				'content':
					function(entityData) {
						return Tag.render`
							<div class="column is-1 admin-entity-list-item">
							${entityData.ID}
							</div>
							<div class="column is-11 admin-entity-list-item">
								${Validator.escapeHTML(entityData.TITLE)}
							</div>
						`;
					},
			},
		'user':
			{
				'header':
					Tag.render`
					<div class="column is-4 is-60-height">
						${Loc.getMessage('NAME')}
					</div>
					<div class="column is-4 is-60-height">
						${Loc.getMessage('EMAIL')}
					</div>
					<div class="column is-3 is-60-height">
						${Loc.getMessage('ROLE')}
					</div>
					`,
				'content':
					function(entityData) {
						return Tag.render`
							<div class="column is-1 admin-entity-list-item">
							${entityData.ID}
							</div>
							<div class="column is-4 admin-entity-list-item">
								${Validator.escapeHTML(entityData.NAME)} ${Validator.escapeHTML(entityData.LAST_NAME)}
							</div>
							<div class="column is-4 admin-entity-list-item">
								${Validator.escapeHTML(entityData.EMAIL) ? Validator.escapeHTML(entityData.EMAIL) : 'Отсутствует'}
							</div>
							<div class="column is-3 admin-entity-list-item">
								${entityData.ROLE}
							</div>
						`;
					},
			},
		'group':
			{
				'header':
					Tag.render`
					<div class="column is-60-height">
						${Loc.getMessage('TITLE')}
					</div>
					`,
				'content':
					function(entityData) {
						return Tag.render`
							<div class="column is-1 admin-entity-list-item">
							${entityData.ID}
							</div>
							<div class="column admin-entity-list-item">
								${Validator.escapeHTML(entityData.TITLE)}
							</div>
						`;
					},
			},
		'audience':
			{
				'header':
					Tag.render`
					<div class="column is-60-height">
						${Loc.getMessage('NUMBER')}
					</div>
					<div class="column is-60-height">
						${Loc.getMessage('TYPE')}
					</div>
					`,
				'content':
					function(entityData) {
						return Tag.render`
							<div class="column is-1 admin-entity-list-item">
							${entityData.ID}
							</div>
							<div class="column admin-entity-list-item">
								${Validator.escapeHTML(entityData.NUMBER)}
							</div>
							<div class="column admin-entity-list-item">
								${Validator.escapeHTML(entityData.UP_SCHEDULE_MODEL_AUDIENCE_AUDIENCE_TYPE_TITLE)}
							</div>
						`;
					},
			},
		'audienceType':
			{
				'header':
					Tag.render`
					<div class="column is-60-height">
						${Loc.getMessage('TITLE')}
					</div>
					`,
				'content':
					function(entityData) {
						return Tag.render`
							<div class="column is-1 admin-entity-list-item">
							${entityData.ID}
							</div>
							<div class="column admin-entity-list-item">
								${Validator.escapeHTML(entityData.TITLE)}
							</div>
						`;
					},
			},
	};

	entity = undefined;

	rootNodeId = undefined;

	rootNode = undefined;

	entityList = undefined;

	pageNumber = undefined;

	doesNextPageExist = undefined;

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

	reload(pageNumber = 1, searchInput = '')
	{
		this.loadList(pageNumber, searchInput)
			.then((data) => {
				this.entityList = data.entityList;
				this.pageNumber = data.pageNumber;
				this.doesNextPageExist = data.doesNextPageExist;
				this.countOfEntities = data.countOfEntities;
				this.entityPerPage = data.entityPerPage;

				this.render();
			});
	}

	loadList(pageNumber = 1, searchInput = '')
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.adminPanel.getEntityList',
				{
					data:
						{
							entityName: this.entity,
							pageNumber: pageNumber,
							searchInput: searchInput,
						},
				},
			).then((response) => {
					const data = response.data;
					console.log(data);
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

		const containerContent = this.entityNode[this.entity]['header'];

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
					${this.entityNode[this.entity]['content'](entityData)}
				</a>
			`;

			entitiesContainerNode.appendChild(entityNode);
		});

		this.rootNode.appendChild(entitiesContainerNode);

		// Пагинация

		const previousPageButton = Tag.render`
			<button class="pagination-previous ${(this.pageNumber > 1) ? '' : 'is-disabled'}">&#60;</button>
		`;

		const nextPageButton = Tag.render`
			<button class="pagination-next ${(this.doesNextPageExist === true) ? '' : 'is-disabled'}">&#62;</button>
		`;

		let firstPageButton = '';
		if (this.pageNumber > 2)
		{
			firstPageButton = Tag.render`<button class="pagination-link">1</button>`;

			firstPageButton.addEventListener('click', () => {
				this.reload(1);
			});
		}

		let previousPageWithNumber = '';
		if (this.pageNumber > 1)
		{
			previousPageWithNumber = Tag.render`<button class="pagination-link">${this.pageNumber - 1}</button>`;

			previousPageButton.addEventListener('click', () => {
				this.reload(this.pageNumber - 1);
			});

			previousPageWithNumber.addEventListener('click', () => {
				this.reload(this.pageNumber - 1);
			});
		}

		let nextPageWithNumber = '';
		if (this.doesNextPageExist === true)
		{
			nextPageWithNumber = Tag.render`<button class="pagination-link">${this.pageNumber + 1}</button>`;

			nextPageButton.addEventListener('click', () => {
				this.reload(this.pageNumber + 1);
			});

			nextPageWithNumber.addEventListener('click', () => {
				this.reload(this.pageNumber + 1);
			});
		}

		const countOfPages = Math.ceil(this.countOfEntities / this.entityPerPage);

		let lastPageButton = '';
		if (this.pageNumber + 1 < countOfPages)
		{
			lastPageButton = Tag.render`<button class="pagination-link">${countOfPages}</button>`;

			lastPageButton.addEventListener('click', () => {
				this.reload(countOfPages);
			});
		}

		const paginationContainer = Tag.render`
			<nav class="pagination" role="navigation" aria-label="pagination">
				${previousPageButton}
				${nextPageButton}
				<ul class="pagination-list">
					${(firstPageButton !== '')
			? Tag.render`
						<li>
							${firstPageButton}
						</li>`
			: ''}
					
					${(this.pageNumber > 3)
			? Tag.render`
						<li>
							<span class="pagination-ellipsis">&hellip;</span>
						</li>`
			: ''}
					
					${(previousPageWithNumber !== '')
			? Tag.render`
						<li>
							${previousPageWithNumber}
						</li>`
			: ''}
					
					<li>
						<div class="pagination-link is-current" aria-current="page">${this.pageNumber}</div>
					</li>
					
					${(nextPageWithNumber !== '')
			? Tag.render`
						<li>
						${nextPageWithNumber}
						</li>`
			: ''}
					
					${(this.pageNumber + 2 < countOfPages)
			? Tag.render`<li>
						<span class="pagination-ellipsis">&hellip;</span>
					</li>`
			: ''}
					
					${(lastPageButton !== '')
			? Tag.render`
						<li>
							${lastPageButton}
						</li>`
			: ''}
				</ul>
			</nav>
		`;

		this.rootNode.appendChild(paginationContainer);
	}
}