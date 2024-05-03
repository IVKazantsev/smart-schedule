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
	        //console.log(info.status, info.progress, info.couples);
	        /*if (info.status === 'finished' && typeof info.couples !== 'undefined')
	        {
	        	this.renderPreview();
	        }
	        else
	        {*/
	        _this.render();
	        /*}*/
	      });
	    }
	  }, {
	    key: "loadInfo",
	    value: function loadInfo() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.automaticSchedule.getCurrentStatus', {
	          data: {}
	        }).then(function (response) {
	          var _response$data$couple;
	          var currentStatus = response.data.status;
	          var progress = response.data.progress;
	          var couples = (_response$data$couple = response.data.couples) !== null && _response$data$couple !== void 0 ? _response$data$couple : undefined;
	          console.log(currentStatus, progress);
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
	        container.innerHTML = "\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t\u0420\u0430\u0441\u043F\u0438\u0441\u0430\u043D\u0438\u0435 \u0441\u043E\u0441\u0442\u0430\u0432\u043B\u044F\u0435\u0442\u0441\u044F.\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">\u041F\u0440\u043E\u0433\u0440\u0435\u0441\u0441: ".concat(this.progress, "%</label>\n \n\t\t\t\t\t<progress class=\"progress is-large\" value=\"").concat(this.progress, "\" max=\"100\">\n\t\t\t\t\t\t").concat(this.progress, "%\n\t\t\t\t\t</progress>\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">\u041E\u0442\u043C\u0435\u043D\u0438\u0442\u044C \u0433\u0435\u043D\u0435\u0440\u0430\u0446\u0438\u044E \u0440\u0430\u0441\u043F\u0438\u0441\u0430\u043D\u0438\u044F?</label>\n\t\t\t\t\t<button class=\"button is-danger\" id=\"button-generate-schedule\" type=\"button\">\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u044C</button>\n\t\t\t\t</div>\n\t\t\t");
	        this.rootNode.appendChild(container);
	        document.getElementById('button-generate-schedule').addEventListener('click', function () {
	          _this2.sendRequestForCancelGeneratingSchedule();
	        });
	      } else if (this.status === 'finished') {
	        container.innerHTML = "\n\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t<label class=\"label\">\u041F\u0435\u0440\u0435\u0439\u0442\u0438 \u043D\u0430 \u0441\u0442\u0440\u0430\u043D\u0438\u0446\u0443 \u043F\u0440\u0435\u0434\u0432\u0430\u0440\u0438\u0442\u0435\u043B\u044C\u043D\u043E\u0433\u043E \u043F\u0440\u043E\u0441\u043C\u043E\u0442\u0440\u0430?</label>\n\t\t\t\t<button class=\"button is-primary\" id=\"button-finished-schedule\" type=\"button\">\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u044C</button>\n\t\t\t</div>\n\t\t\t";
	        this.rootNode.appendChild(container);
	        document.getElementById('button-finished-schedule').addEventListener('click', function () {
	          window.location.assign('/scheduling/preview/');
	        });
	      } else {
	        container.innerHTML = "\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t\u0417\u0434\u0435\u0441\u044C \u0412\u044B \u043C\u043E\u0436\u0435\u0442\u0435 \u0430\u0432\u0442\u043E\u043C\u0430\u0442\u0438\u0447\u0435\u0441\u043A\u0438 \u0441\u043E\u0441\u0442\u0430\u0432\u0438\u0442\u044C \u0440\u0430\u0441\u043F\u0438\u0441\u0430\u043D\u0438\u0435.\n\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t<div class=\"box edit-fields\">\n\t\t\t\t\t<label class=\"label\">\u0421\u0433\u0435\u043D\u0435\u0440\u0438\u0440\u043E\u0432\u0430\u0442\u044C \u0440\u0430\u0441\u043F\u0438\u0441\u0430\u043D\u0438\u0435?</label>\n\t\t\t\t\t<button class=\"button is-primary\" id=\"button-generate-schedule\" type=\"button\">\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u044C</button>\n\t\t\t\t</div>\n\t\t\t";
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
	        console.log(response.data.result);
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
	      BX.ajax.runAction('up:schedule.api.automaticSchedule.cancelGenerateSchedule', {
	        data: {}
	      }).then(function (response) {
	        _this4.status = 'notInProcess';
	        _this4.progress = 0;
	        console.log(response.data.result);
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
