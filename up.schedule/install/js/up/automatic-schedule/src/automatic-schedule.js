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
			.then(info => {
				this.status = info.status;
				console.log(info.status, info.progress, info.couples);
				/*if (info.status === 'finished' && typeof info.couples !== 'undefined')
				{
					this.renderPreview();
				}
				else
				{*/
					this.render();
				/*}*/
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
				const progress = response.data.progress;
				const couples = response.data.couples ?? undefined;

				console.log(response.data.allFitness);

				resolve({status: currentStatus, progress: progress, couples: couples});
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
		else if (this.status === 'finished')
		{
			container.innerHTML = `
			<div class="box edit-fields">
				<label class="label">Перейти на страницу предварительного просмотра?</label>
				<button class="button is-danger" id="button-finished-schedule" type="button">Подтвердить</button>
			</div>
			`;

			this.rootNode.appendChild(container);
			document.getElementById('button-finished-schedule').addEventListener('click', () => {
				window.location.assign('/scheduling/preview/');
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
