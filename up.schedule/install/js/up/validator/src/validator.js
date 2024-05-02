export class Validator
{
	constructor(options = {})
	{
	}

	static escapeHTML(text) {
		if(!text || text === '')
		{
			return '';
		}

		return text.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}
}
