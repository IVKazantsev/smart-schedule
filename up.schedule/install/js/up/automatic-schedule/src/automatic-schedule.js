import {Type, Tag} from 'main.core';

export class AutomaticSchedule
{
	constructor(options = {})
	{
		if (Type.isStringFilled(options.rootNodeId))
		{
			this.rootNodeId = options.rootNodeId;
		}
		else
		{
			throw new Error('AutomaticSchedule: options.rootNodeId required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`AutomaticSchedule: element with id = "${this.rootNodeId}" not found`);
		}

		this.reload();
	}

	reload()
	{
		this.loadInfo()
			.then(currentStatus => {
				this.status = currentStatus;
				console.log(currentStatus);
				this.render();
			});
	}

	loadInfo()
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.automaticSchedule.getCurrentStatus',
				{
					data:
						{
						},
				},
			).then((response) => {
				const currentStatus = response.data.status;
				console.log(response.data.progress);
				resolve(currentStatus);
			})
				.catch((error) => {
					reject(error);
				});
		});
	}

	render()
	{
		this.rootNode.innerHTML = '';

		const container = document.createElement('div');
		if (this.status === 'inProcess')
		{
			container.innerHTML = `
				<div class="box edit-fields">
					Расписание составляется.
				</div>
				
				<div class="box edit-fields">
					Прогресс.
				</div>
				
				<div class="box edit-fields">
					<label class="label">Отменить генерацию расписания?</label>
					<button class="button is-danger" id="button-generate-schedule" type="button">Подтвердить</button>
				</div>
			`;
			this.rootNode.appendChild(container);

			document.getElementById('button-generate-schedule').addEventListener('click', () => {
				this.sendRequestForCancelGeneratingSchedule();
			});
		}
		else
		{
			container.innerHTML = `
				<div class="box edit-fields">
					Здесь Вы можете автоматически составить расписание.
				</div>
				
				<div class="box edit-fields">
					<label class="label">Сгенерировать расписание?</label>
					<button class="button" id="button-generate-schedule" type="button">Подтвердить</button>
				</div>
			`;
			this.rootNode.appendChild(container);

			document.getElementById('button-generate-schedule').addEventListener('click', () => {
				this.sendRequestForMakeSchedule();
			});
		}

	}



	sendRequestForMakeSchedule()
	{
		BX.ajax.runAction(
			'up:schedule.api.automaticSchedule.generateSchedule',
			{
				data: {},
			},
		).then((response) => {
			console.log(response.data.result)
			this.reload();
		})
			.catch((error) => {
				console.error(error);
			});
	}

	sendRequestForCancelGeneratingSchedule()
	{
		BX.ajax.runAction(
			'up:schedule.api.automaticSchedule.cancelGenerateSchedule',
			{
				data: {},
			},
		)
			.then((response) => {
				console.log(response.data.result)
				this.reload();
			})
			.catch((error) => {
				console.error(error);
			});
	}
}
