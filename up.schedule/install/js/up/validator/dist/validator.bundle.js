/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports) {
	'use strict';

	var Validator = /*#__PURE__*/function () {
	  function Validator() {
	    babelHelpers.classCallCheck(this, Validator);
	  }
	  babelHelpers.createClass(Validator, null, [{
	    key: "escapeHTML",
	    value: function escapeHTML(text) {
	      if (!text || text === '') {
	        return '';
	      }
	      return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
	    }
	  }]);
	  return Validator;
	}();

	exports.Validator = Validator;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {})));
