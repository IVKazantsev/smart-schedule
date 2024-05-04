/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject, _templateObject2;
	var PopupMessage = /*#__PURE__*/function () {
	  function PopupMessage() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, PopupMessage);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('EntityList: options.rootNodeId required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("EntityList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    if (main_core.Type.isStringFilled(options.errorsMessage)) {
	      this.errorsMessage = options.errorsMessage;
	    }
	    if (main_core.Type.isStringFilled(options.successMessage)) {
	      this.successMessage = options.successMessage;
	    }
	    this.reload();
	  }
	  babelHelpers.createClass(PopupMessage, [{
	    key: "reload",
	    value: function reload() {
	      var errorsMessage = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	      var successMessage = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      if (errorsMessage !== '') {
	        this.errorsMessage = errorsMessage;
	      }
	      if (errorsMessage !== '') {
	        this.successMessage = successMessage;
	      }
	      this.render();
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      this.rootNode.innerHTML = '';
	      if (this.errorsMessage && this.errorsMessage !== '') {
	        this.clearMessages();
	        var errorsContainer = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"box errors active\" id=\"errors\">\n\t\t\t\t\t<div class=\"error-title has-background-danger has-text-white is-size-4 p-3 is-flex is-justify-content-center\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"errors-text p-3\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t"])), main_core.Loc.getMessage('ERROR'), this.errorsMessage);
	        this.rootNode.appendChild(errorsContainer);
	        this.setTimeouts();
	      }
	      if (this.successMessage && this.successMessage !== '') {
	        this.clearMessages();
	        var _errorsContainer = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"success box active has-background-success\" id=\"success\">\n\t\t\t\t\t<div class=\"is-60-height p-3 has-text-white is-size-4\">\n\t\t\t\t\t\t", ".\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t"])), this.successMessage);
	        this.rootNode.appendChild(_errorsContainer);
	        this.setTimeouts();
	      }
	    }
	  }, {
	    key: "clearMessages",
	    value: function clearMessages() {
	      if (document.getElementById('errors')) {
	        document.getElementById('errors').remove();
	      }
	      if (document.getElementById('success')) {
	        document.getElementById('success').remove();
	      }
	    }
	  }, {
	    key: "setTimeouts",
	    value: function setTimeouts() {
	      var success = document.getElementById('success');
	      var errors = document.getElementById('errors');
	      if (success) {
	        setTimeout(function () {
	          success.classList.remove('active');
	        }, 3000);
	      }
	      if (errors) {
	        setTimeout(function () {
	          errors.classList.remove('active');
	        }, 5000);
	      }
	    }
	  }]);
	  return PopupMessage;
	}();

	exports.PopupMessage = PopupMessage;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
