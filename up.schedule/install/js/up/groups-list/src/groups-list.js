import { Type, Tag, Loc } from 'main.core';

export class GroupsList
{
	currentGroup = {};
	constructor(options = {})
	{
		if (Type.isStringFilled(options.rootNodeId))
		{
			this.rootNodeId = options.rootNodeId;
		}
		else
		{
			throw new Error('GroupsList: options.rootNodeId required');
		}

		if (Type.isStringFilled(options.groups))
		{
			this.groups = options.groups;
		}
		else
		{
			throw new Error('GroupsList: options.entity required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`GroupsList: element with id = "${this.rootNodeId}" not found`);
		}

		this.groupsList = [];
		this.reload();
	}

	reload()
	{
		this.loadList()
			.then(data => {
				this.groupsList = data.groups;
				//this.currentGroup = data.currentGroup;

				this.render();
			});
	}

	loadList()
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.groupList.getGroupList',
				{
					data: {},
				},
			)
				.then((response) => {
				const groupsList = response.data.groups;
				resolve(groupsList);
				})
				.catch((error) => {
					reject(error);
				});
		});
	}

	render()
	{
		this.rootNode.innerHTML = '';
		const currentGroupTitle = typeof this.currentGroup === undefined ? '' : this.currentGroup.title;
		const groupsContainerNode = Tag.render`
			<div class="dropdown-trigger group-selection-trigger is-60-height-child">
						<button id="group-selection-button" class="button is-fullwidth is-60-height-child" aria-haspopup="true" aria-controls="dropdown-menu">
							<span> ${currentGroupTitle} </span>
						</button>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div class="dropdown-content">
							<?php foreach ($arResult['GROUPS'] as $group): ?>
								<a href="/group/<?= $group->getId() ?>/" class="dropdown-item <?= ($group->getId() === $arResult['CURRENT_GROUP_ID']) ? 'is-active' : '' ?>"><?= htmlspecialcharsbx($group->getTitle()) ?></a>
							<?php endforeach; ?>
						</div>
					</div>
		`;
	}
}
