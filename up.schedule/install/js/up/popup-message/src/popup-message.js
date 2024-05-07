import { Type, Tag, Loc } from 'main.core';

export class PopupMessage
{
	errorsMessage;

	successMessage;

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

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode)
		{
			throw new Error(`EntityList: element with id = "${this.rootNodeId}" not found`);
		}

		if (Type.isStringFilled(options.errorsMessage))
		{
			this.errorsMessage = options.errorsMessage;
		}
		if (Type.isStringFilled(options.successMessage))
		{
			this.successMessage = options.successMessage;
		}

		this.reload();
	}

	reload(errorsMessage = '', successMessage = '')
	{
		if(errorsMessage !== '')
		{
			this.errorsMessage = errorsMessage;
		}

		if(errorsMessage !== '')
		{
			this.successMessage = successMessage;
		}

		this.render();
	}

	render()
	{
		this.rootNode.innerHTML = '';

		if (this.errorsMessage && this.errorsMessage !== '')
		{
			this.clearMessages();

			const errorsContainer = Tag.render`
				<div class="box errors active" id="errors">
					<div class="error-title has-background-danger has-text-white is-size-4 p-3 is-flex is-justify-content-center">
						${Loc.getMessage('ERROR')}
					</div>
					<div class="errors-text p-3">
						${this.errorsMessage}
					</div>
				</div>
			`;

			this.rootNode.appendChild(errorsContainer);
			this.setTimeouts();
		}

		if (this.successMessage && this.successMessage !== '')
		{
			this.clearMessages();

			const errorsContainer = Tag.render`
				<div class="success box active has-background-success" id="success">
					<div class="is-60-height p-3 has-text-white is-size-4">
						${this.successMessage}.
					</div>
				</div>
			`;

			this.rootNode.appendChild(errorsContainer);
			this.setTimeouts();
		}
	}

	clearMessages()
	{
		if (document.getElementById('errors'))
		{
			document.getElementById('errors').remove();
		}

		if (document.getElementById('success'))
		{
			document.getElementById('success').remove();
		}
	}

	setTimeouts()
	{
		const success = document.getElementById('success');
		const errors = document.getElementById('errors');
		if(success)
		{
			setTimeout(() => { success.classList.remove('active') }, 3000);
		}

		if(errors)
		{
			setTimeout(() => { errors.classList.remove('active') }, 5000);
		}
	}
}
