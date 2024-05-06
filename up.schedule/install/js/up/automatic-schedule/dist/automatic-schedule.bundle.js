/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var AutomaticSchedule = /*#__PURE__*/function () {
	  function AutomaticSchedule() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, AutomaticSchedule);
	    babelHelpers.defineProperty(this, "progress", 0);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('AutomaticSchedule: options.rootNodeId required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("AutomaticSchedule: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.reload();
	  }
	  babelHelpers.createClass(AutomaticSchedule, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadInfo().then(function (info) {
	        _this.status = info.status;
	        _this.progress = info.progress;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadInfo",
	    value: function loadInfo() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.automaticSchedule.getCurrentStatus', {}).then(function (response) {
	          var _response$data$couple;
	          var currentStatus = response.data.status;
	          var progress = response.data.progress;
	          var couples = (_response$data$couple = response.data.couples) !== null && _response$data$couple !== void 0 ? _response$data$couple : undefined;
	          resolve({
	            status: currentStatus,
	            progress: progress,
	            couples: couples
	          });
	        })["catch"](function (error) {
	          reject(error);
	        });
	      });
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      var _this2 = this;
	      this.rootNode.innerHTML = '';
	      var container = document.createElement('div');
	      if (this.status === 'inProcess' || this.status === 'started') {
	        container.innerHTML = "\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t".concat(main_core.Loc.getMessage('COMPILATION_IN_PROGRESS'), ".\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">").concat(main_core.Loc.getMessage('PROGRESS'), ": ").concat(this.progress, "%</label>\n \n\t\t\t\t\t<progress class=\"progress is-large\" value=\"").concat(this.progress, "\" max=\"100\">\n\t\t\t\t\t\t").concat(this.progress, "%\n\t\t\t\t\t</progress>\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">").concat(main_core.Loc.getMessage('GENERATION_CANCELLATION'), "?</label>\n\t\t\t\t\t<button class=\"button is-danger\" id=\"button-generate-schedule\" type=\"button\">\n\t\t\t\t\t\t").concat(main_core.Loc.getMessage('SUBMIT'), "\n\t\t\t\t\t</button>\n\t\t\t\t</div>\n\t\t\t");
	        this.rootNode.appendChild(container);
	        document.getElementById('button-generate-schedule').addEventListener('click', function () {
	          _this2.sendRequestForCancelGeneratingSchedule();
	        });
	      } else if (this.status === 'finished') {
	        container.innerHTML = "\n\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t<label class=\"label\">".concat(main_core.Loc.getMessage('GO_TO_PREVIEW'), "?</label>\n\t\t\t\t<button class=\"button is-primary\" id=\"button-finished-schedule\" type=\"button\">\n\t\t\t\t\t").concat(main_core.Loc.getMessage('SUBMIT'), "\n\t\t\t\t</button>\n\t\t\t</div>\n\t\t\t");
	        this.rootNode.appendChild(container);
	        document.getElementById('button-finished-schedule').addEventListener('click', function () {
	          window.location.assign('/scheduling/preview/');
	        });
	      } else if (this.status === 'failed') {
	        container.innerHTML = "\n\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t<label class=\"label\">".concat(main_core.Loc.getMessage('FAILED_TO_COMPOSE'), "<br>").concat(main_core.Loc.getMessage('TRY_AGAIN'), "?</label>\n\t\t\t\t<button class=\"button is-primary\" id=\"button-failed-schedule\" type=\"button\">\n\t\t\t\t\t").concat(main_core.Loc.getMessage('SUBMIT'), "\n\t\t\t\t</button>\n\t\t\t</div>\n\t\t\t");
	        this.rootNode.appendChild(container);
	        document.getElementById('button-failed-schedule').addEventListener('click', function () {
	          _this2.progress = 0;
	          _this2.sendRequestForMakeSchedule();
	        });
	      } else {
	        container.innerHTML = "\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t".concat(main_core.Loc.getMessage('AUTOMATIC_SCHEDULE'), ".\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">\n\t\t\t\t\t\t").concat(main_core.Loc.getMessage('SCHEDULE_GENERATION'), "?\n\t\t\t\t\t</label>\n\t\t\t\t\t<button class=\"button is-primary\" id=\"button-generate-schedule\" type=\"button\">\n\t\t\t\t\t\t").concat(main_core.Loc.getMessage('SUBMIT'), "\n\t\t\t\t\t</button>\n\t\t\t\t</div>\n\t\t\t");
	        this.rootNode.appendChild(container);
	        document.getElementById('button-generate-schedule').addEventListener('click', function () {
	          _this2.sendRequestForMakeSchedule();
	        });
	      }
	    }
	  }, {
	    key: "sendRequestForMakeSchedule",
	    value: function sendRequestForMakeSchedule() {
	      var _this3 = this;
	      BX.ajax.runAction('up:schedule.api.automaticSchedule.generateSchedule', {
	        data: {}
	      }).then(function (response) {
	        if (response.data.result === true) {
	          _this3.status = 'inProcess';
	        } else {
	          _this3.status = 'notInProcess';
	        }
	        _this3.reload();
	      })["catch"](function (error) {
	        console.error(error);
	      });
	    }
	  }, {
	    key: "sendRequestForCancelGeneratingSchedule",
	    value: function sendRequestForCancelGeneratingSchedule() {
	      var _this4 = this;
	      BX.ajax.runAction('up:schedule.api.automaticSchedule.cancelGenerateSchedule', {}).then(function (response) {
	        _this4.status = 'notInProcess';
	        _this4.progress = 0;
	        _this4.reload();
	      })["catch"](function (error) {
	        console.error(error);
	      });
	    }
	  }]);
	  return AutomaticSchedule;
	}();

	exports.AutomaticSchedule = AutomaticSchedule;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
