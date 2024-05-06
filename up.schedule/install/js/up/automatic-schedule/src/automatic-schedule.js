import { Type, Loc } from 'main.core';

export class AutomaticSchedule
{
	progress = 0;

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
				this.progress = info.progress;

				this.render();
			});
	}

	loadInfo()
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:schedule.api.automaticSchedule.getCurrentStatus',
				{},
			).then((response) => {
					const currentStatus = response.data.status;
					const progress = response.data.progress;
					const couples = response.data.couples ?? undefined;

					resolve({ status: currentStatus, progress: progress, couples: couples });
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
		if (this.status === 'inProcess' || this.status === 'started')
		{
			container.innerHTML = `
				<div class="box edit-fields">
					${Loc.getMessage('COMPILATION_IN_PROGRESS')}.
				</div>
				
				<div class="box edit-fields">
					<label class="label">${Loc.getMessage('PROGRESS')}: ${this.progress}%</label>
 
					<progress class="progress is-large" value="${this.progress}" max="100">
						${this.progress}%
					</progress>
				</div>
				
				<div class="box edit-fields">
					<label class="label">${Loc.getMessage('GENERATION_CANCELLATION')}?</label>
					<button class="button is-danger" id="button-generate-schedule" type="button">
						${Loc.getMessage('SUBMIT')}
					</button>
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
				<label class="label">${Loc.getMessage('GO_TO_PREVIEW')}?</label>
				<button class="button is-primary" id="button-finished-schedule" type="button">
					${Loc.getMessage('SUBMIT')}
				</button>
			</div>
			`;

			this.rootNode.appendChild(container);
			document.getElementById('button-finished-schedule').addEventListener('click', () => {
				window.location.assign('/scheduling/preview/');
			});
		}
		else if (this.status === 'failed')
		{
			container.innerHTML = `
			<div class="box edit-fields">
				<label class="label">${Loc.getMessage('FAILED_TO_COMPOSE')}<br>${Loc.getMessage('TRY_AGAIN')}?</label>
				<button class="button is-primary" id="button-failed-schedule" type="button">
					${Loc.getMessage('SUBMIT')}
				</button>
			</div>
			`;

			this.rootNode.appendChild(container);
			document.getElementById('button-failed-schedule').addEventListener('click', () => {
				this.progress = 0;
				this.sendRequestForMakeSchedule();
			});
		}
		else
		{
			container.innerHTML = `
				<div class="box edit-fields">
					${Loc.getMessage('AUTOMATIC_SCHEDULE')}.
				</div>
				
				<div class="box edit-fields">
					<label class="label">
						${Loc.getMessage('SCHEDULE_GENERATION')}?
					</label>
					<button class="button is-primary" id="button-generate-schedule" type="button">
						${Loc.getMessage('SUBMIT')}
					</button>
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
				if (response.data.result === true)
				{
					this.status = 'inProcess';
				}
				else
				{
					this.status = 'notInProcess';
				}
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
				{},
			)
			.then((response) => {
				this.status = 'notInProcess';
				this.progress = 0;
				this.reload();
			})
			.catch((error) => {
				console.error(error);
			});
	}
}
