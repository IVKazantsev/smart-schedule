/* eslint-disable */
this.BX = this.BX || {};
(function (exports,main_core) {
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

	var _templateObject$1, _templateObject2$1, _templateObject3, _templateObject4, _templateObject5, _templateObject6, _templateObject7, _templateObject8, _templateObject9, _templateObject10, _templateObject11, _templateObject12, _templateObject13, _templateObject14, _templateObject15, _templateObject16, _templateObject17, _templateObject18, _templateObject19, _templateObject20, _templateObject21, _templateObject22, _templateObject23, _templateObject24, _templateObject25, _templateObject26, _templateObject27, _templateObject28, _templateObject29, _templateObject30, _templateObject31, _templateObject32, _templateObject33, _templateObject34, _templateObject35, _templateObject36, _templateObject37, _templateObject38, _templateObject39, _templateObject40, _templateObject41, _templateObject42, _templateObject43;
	var CouplesList = /*#__PURE__*/function () {
	  function CouplesList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    var dataSourceIsDb = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
	    babelHelpers.classCallCheck(this, CouplesList);
	    babelHelpers.defineProperty(this, "formData", {});
	    babelHelpers.defineProperty(this, "daysOfWeek", {
	      1: main_core.Loc.getMessage('DAY_1_OF_WEEK'),
	      2: main_core.Loc.getMessage('DAY_2_OF_WEEK'),
	      3: main_core.Loc.getMessage('DAY_3_OF_WEEK'),
	      4: main_core.Loc.getMessage('DAY_4_OF_WEEK'),
	      5: main_core.Loc.getMessage('DAY_5_OF_WEEK'),
	      6: main_core.Loc.getMessage('DAY_6_OF_WEEK')
	    });
	    babelHelpers.defineProperty(this, "entityId", undefined);
	    babelHelpers.defineProperty(this, "entity", undefined);
	    babelHelpers.defineProperty(this, "defaultEntity", 'group');
	    babelHelpers.defineProperty(this, "isAdmin", false);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('CouplesList: options.rootNodeId required');
	    }
	    if (!main_core.Type.isStringFilled(options.entity) || !main_core.Type.isStringFilled(options.entityId)) {
	      this.extractEntityFromUrl();
	    } else {
	      this.entity = options.entity;
	      this.entityId = options.entityId;
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("CouplesList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.dataSourceIsDb = dataSourceIsDb;
	    this.coupleList = [];
	    this.checkRole();
	  }
	  babelHelpers.createClass(CouplesList, [{
	    key: "extractEntityFromUrl",
	    value: function extractEntityFromUrl() {
	      var url = window.location.pathname;
	      if (url.length === 0) {
	        return {
	          'entityId': 0,
	          'entity': this.defaultEntity
	        };
	      }
	      var addresses = url.split('/');
	      var entityIndex = addresses.findIndex(function (element, index, array) {
	        var needles = ['group', 'teacher', 'audience'];
	        return needles.includes(element);
	      });
	      var entity = addresses[entityIndex];
	      var entityIdIndex = entityIndex + 1;
	      var entityId = addresses[entityIdIndex];
	      this.entityId = typeof Number(entityId) === 'number' ? entityId : undefined;
	      this.entity = typeof entity === 'string' ? entity : this.defaultEntity;
	      return {
	        'entityId': this.entityId,
	        'entity': this.entity
	      };
	    }
	  }, {
	    key: "checkRole",
	    value: function checkRole() {
	      var _this = this;
	      BX.ajax.runAction('up:schedule.api.userRole.isAdmin', {}).then(function (response) {
	        _this.isAdmin = response.data;
	        _this.reload();
	      })["catch"](function (error) {
	        console.error(error);
	      });
	    }
	  }, {
	    key: "reload",
	    value: function reload() {
	      var _this2 = this;
	      this.loadList().then(function (coupleList) {
	        _this2.coupleList = coupleList;
	        _this2.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      var _this$entity;
	      var controllerFn = function controllerFn(dataSourceIsDb) {
	        if (dataSourceIsDb) {
	          return 'up:schedule.api.couplesList.getCouplesList';
	        } else {
	          return 'up:schedule.api.automaticSchedule.getCouplesList';
	        }
	      };
	      var controller = controllerFn(this.dataSourceIsDb);
	      var entity = (_this$entity = this.entity) !== null && _this$entity !== void 0 ? _this$entity : this.defaultEntity;
	      var entityId = Number(this.entityId);
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction(controller, {
	          data: {
	            entity: entity,
	            id: entityId
	          }
	        }).then(function (response) {
	          var coupleList = response.data.couples;
	          resolve(coupleList);
	        })["catch"](function (error) {
	          reject(error);
	        });
	      });
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      var _this3 = this;
	      this.rootNode.innerHTML = '';
	      if (this.isAdmin === true && !this.dataSourceIsDb) {
	        this.rootNode.classList.add('is-flex', 'column', 'columns', 'is-flex-direction-column');
	        var previewMenuContainer = document.createElement('div');
	        previewMenuContainer.classList.add('box', 'columns', 'column', 'is-half', 'is-flex', 'is-flex-direction-column', 'is-align-items-center', 'ml-auto', 'mr-auto');
	        previewMenuContainer.id = 'preview-menu-container';
	        var buttonsPreviewContainer = document.createElement('div');
	        buttonsPreviewContainer.classList.add('is-flex', 'column', 'columns', 'is-full', 'is-justify-content-space-evenly', 'is-flex-direction-row', 'mb-2');
	        buttonsPreviewContainer.id = 'buttons-preview-container';
	        var label = main_core.Tag.render(_templateObject$1 || (_templateObject$1 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<label class=\"label column m-2\">", "?</label>\n\t\t\t"])), main_core.Loc.getMessage('SAVE_CHANGES'));
	        var submitButton = main_core.Tag.render(_templateObject2$1 || (_templateObject2$1 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<button \n\t\t\t\t\t\t\ttype=\"button\" id=\"button-preview-submit\" class=\"column  is-two-fifths button is-clickable is-medium is-primary\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</button>\n\t\t\t\t\t\t"])), main_core.Loc.getMessage('SUBMIT'));
	        submitButton.addEventListener('click', function () {
	          _this3.handleSubmitScheduleButtonClick();
	        }, {
	          once: true
	        });
	        var cancelButton = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<button \n\t\t\t\t\t\t\ttype=\"button\" id=\"button-preview-cancel\" class=\"column  is-two-fifths button is-danger is-clickable is-medium\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</button>\n\t\t\t\t\t\t"])), main_core.Loc.getMessage('CANCEL'));
	        cancelButton.addEventListener('click', function () {
	          _this3.handleCancelScheduleButtonClick();
	        }, {
	          once: true
	        });
	        buttonsPreviewContainer.appendChild(submitButton);
	        buttonsPreviewContainer.appendChild(cancelButton);
	        previewMenuContainer.appendChild(label);
	        previewMenuContainer.appendChild(buttonsPreviewContainer);
	        this.rootNode.appendChild(previewMenuContainer);
	      }
	      var couplesContainer = document.createElement('div');
	      couplesContainer.className = 'column columns';
	      var _loop = function _loop(day) {
	        var dayTitleContainer = main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center\">\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t"])), _this3.daysOfWeek[day]);
	        var dayColumnContainer = document.createElement('div');
	        dayColumnContainer.className = 'column is-2';
	        var dayContainer = document.createElement('div');
	        dayContainer.className = 'box has-text-centered couples';
	        dayContainer.appendChild(dayTitleContainer);
	        var _loop2 = function _loop2(i) {
	          var editCoupleButton = main_core.Tag.render(_templateObject5 || (_templateObject5 = babelHelpers.taggedTemplateLiteral(["<div></div>"])));
	          var coupleTextContainer = main_core.Tag.render(_templateObject6 || (_templateObject6 = babelHelpers.taggedTemplateLiteral(["<br>"])));
	          if (typeof _this3.coupleList[day] !== 'undefined' && typeof _this3.coupleList[day][i] !== 'undefined') {
	            var marginClassText = '';
	            if (!_this3.isAdmin || !_this3.dataSourceIsDb) {
	              marginClassText = 'class = "mt-3"';
	            }
	            coupleTextContainer = main_core.Tag.render(_templateObject7 || (_templateObject7 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<div class=\"couple-text is-fullheight pt-2\">\n\t\t\t\t\t\t\t<p ", " class=\"subject-of-couple\">", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"subjectId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"audienceId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"groupId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", " ", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"teacherId-", "-", "\">", "</p>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t"])), Validator.escapeHTML(marginClassText), Validator.escapeHTML(_this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE), day, i, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID, Validator.escapeHTML(_this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER), day, i, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_ID, Validator.escapeHTML(_this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_TITLE), day, i, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_ID, Validator.escapeHTML(_this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME), Validator.escapeHTML(_this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME), day, i, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_ID);
	            if (_this3.isAdmin === true && _this3.dataSourceIsDb) {
	              editCoupleButton = main_core.Tag.render(_templateObject8 || (_templateObject8 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<button \n\t\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-remove-", "-", "\" class=\"has-text-white has-background-danger couple-remove-button couple-edit-button is-size-6 button pb-0 pt-0 is-size-7 pl-2 pr-2\">\n\t\t\t\t\t\t\t\t-\n\t\t\t\t\t\t\t</button>\n\t\t\t\t\t\t"])), day, i);
	              editCoupleButton.addEventListener('click', function () {
	                _this3.handleRemoveCoupleButtonClick(day, i);
	              }, {
	                once: true
	              });
	            }
	          } else {
	            if (_this3.isAdmin === true && _this3.dataSourceIsDb) {
	              editCoupleButton = main_core.Tag.render(_templateObject9 || (_templateObject9 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<button \n\t\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-add-", "-", "\" class=\"has-text-white has-background-success couple-add-button couple-edit-button button is-size-7 pb-0 pt-0 pl-2 pr-2\">\n\t\t\t\t\t\t\t\t+\n\t\t\t\t\t\t\t</button>\n\t\t\t\t\t\t"])), day, i);
	              editCoupleButton.addEventListener('click', function () {
	                _this3.handleAddCoupleButtonClick(day, i);
	              });
	            }
	          }
	          var coupleContainer = document.createElement('div');
	          coupleContainer.className = 'box couple m-0';
	          if (_this3.isAdmin && _this3.dataSourceIsDb) {
	            var btnContainer = main_core.Tag.render(_templateObject10 || (_templateObject10 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<div id=\"dropdown-", "-", "\" class=\"btn-edit-couple-container dropdown\"></div>"])), day, i);
	            btnContainer.appendChild(editCoupleButton);
	            coupleContainer.appendChild(btnContainer);
	          }
	          coupleContainer.appendChild(coupleTextContainer);
	          dayContainer.appendChild(coupleContainer);
	        };
	        for (var i = 1; i <= 7; i++) {
	          _loop2(i);
	        }
	        dayColumnContainer.appendChild(dayContainer);
	        couplesContainer.appendChild(dayColumnContainer);
	      };
	      for (var day in this.daysOfWeek) {
	        _loop(day);
	      }
	      this.rootNode.appendChild(couplesContainer);
	    }
	  }, {
	    key: "handleSubmitScheduleButtonClick",
	    value: function handleSubmitScheduleButtonClick() {
	      BX.ajax.runAction('up:schedule.api.automaticSchedule.setGeneratedSchedule', {}).then(function () {
	        window.location.replace('/');
	      })["catch"](function (error) {
	        console.log(error);
	      });
	    }
	  }, {
	    key: "handleCancelScheduleButtonClick",
	    value: function handleCancelScheduleButtonClick() {
	      BX.ajax.runAction('up:schedule.api.automaticSchedule.cancelGeneratedSchedule', {}).then(function () {
	        window.location.replace('/');
	      })["catch"](function (error) {
	        console.error(error);
	      });
	    }
	  }, {
	    key: "handleAddCoupleButtonClick",
	    value: function handleAddCoupleButtonClick(numberOfDay, numberOfCouple) {
	      this.openCoupleModal();
	      this.createAddForm(numberOfDay, numberOfCouple);
	    }
	  }, {
	    key: "handleRemoveCoupleButtonClick",
	    value: function handleRemoveCoupleButtonClick(numberOfDay, numberOfCouple) {
	      this.removeCouple(numberOfDay, numberOfCouple);
	    }
	  }, {
	    key: "openCoupleModal",
	    value: function openCoupleModal() {
	      var _this4 = this;
	      var modal = document.getElementById('coupleModal');
	      modal.classList.add('is-active');
	      document.addEventListener('keydown', function (event) {
	        if (event.key === 'Escape') {
	          _this4.closeCoupleModal();
	        }
	      });
	      var closeButton = document.getElementById('button-close-modal');
	      closeButton.addEventListener('click', function () {
	        _this4.closeCoupleModal();
	      }, {
	        once: true
	      });
	    }
	  }, {
	    key: "createAddForm",
	    value: function createAddForm(numberOfDay, numberOfCouple) {
	      var _this5 = this;
	      this.fetchSubjectsForAddForm().then(function (subjectsList) {
	        _this5.insertSubjectsDataForAddForm(subjectsList);
	      });
	      if (this.isValidInput !== false) {
	        this.deleteEmptyForm();
	      }
	      var coupleAddButtonsContainer = document.getElementById('couple-add-buttons-container');
	      var submitButton = main_core.Tag.render(_templateObject11 || (_templateObject11 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<button id=\"submit-form-button\" type=\"button\" class=\"button is-success\">", "</button>\n\t\t"])), main_core.Loc.getMessage('SAVE'));
	      var cancelButton = main_core.Tag.render(_templateObject12 || (_templateObject12 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<button id=\"cancel-form-button\" type=\"button\" class=\"button\">", "</button>\n\t\t"])), main_core.Loc.getMessage('CANCEL'));
	      submitButton.addEventListener('click', function () {
	        _this5.sendForm(numberOfDay, numberOfCouple, 'add');
	      }, {
	        once: true
	      });
	      cancelButton.addEventListener('click', function () {
	        _this5.closeCoupleModal();
	      }, {
	        once: true
	      });
	      coupleAddButtonsContainer.appendChild(submitButton);
	      coupleAddButtonsContainer.appendChild(cancelButton);
	    }
	  }, {
	    key: "sendForm",
	    value: function sendForm(numberOfDay, numberOfCouple, typeOfRequest) {
	      var _this6 = this;
	      var subjectInput = document.getElementById('subject-select');
	      var teacherInput = document.getElementById('teacher-select');
	      var audienceInput = document.getElementById('audience-select');
	      var groupInput = document.getElementById('group-select');
	      var submitButton = document.getElementById('submit-form-button');
	      if (subjectInput && teacherInput && audienceInput && groupInput) {
	        var coupleInfo = {
	          'GROUP_ID': groupInput.value,
	          'SUBJECT_ID': subjectInput.value,
	          'TEACHER_ID': teacherInput.value,
	          'AUDIENCE_ID': audienceInput.value,
	          'DAY_OF_WEEK': numberOfDay,
	          'NUMBER_IN_DAY': numberOfCouple
	        };
	        BX.ajax.runAction('up:schedule.api.couplesList.' + typeOfRequest + 'Couple', {
	          data: {
	            coupleInfo: coupleInfo
	          }
	        }).then(function () {
	          _this6.sendMessage('', 'Пара успешно добавлена');
	          _this6.closeCoupleModal();
	          _this6.reload();
	        })["catch"](function (error) {
	          _this6.sendMessage(error.data.errors);
	          submitButton.addEventListener('click', function () {
	            _this6.sendForm(numberOfDay, numberOfCouple, 'add');
	          }, {
	            once: true
	          });
	          console.error(error);
	        });
	      } else {
	        submitButton.addEventListener('click', function () {
	          _this6.sendForm(numberOfDay, numberOfCouple, 'add');
	        }, {
	          once: true
	        });
	      }
	    }
	  }, {
	    key: "removeCouple",
	    value: function removeCouple(numberOfDay, numberOfCouple) {
	      var _this7 = this;
	      var subject = document.getElementById("subjectId-".concat(numberOfDay, "-").concat(numberOfCouple)).innerText;
	      var teacher = document.getElementById("teacherId-".concat(numberOfDay, "-").concat(numberOfCouple)).innerText;
	      var audience = document.getElementById("audienceId-".concat(numberOfDay, "-").concat(numberOfCouple)).innerText;
	      var group = document.getElementById("groupId-".concat(numberOfDay, "-").concat(numberOfCouple)).innerText;
	      if (subject && teacher && audience && group) {
	        var coupleInfo = {
	          'GROUP_ID': group,
	          'SUBJECT_ID': subject,
	          'TEACHER_ID': teacher,
	          'AUDIENCE_ID': audience,
	          'DAY_OF_WEEK': numberOfDay,
	          'NUMBER_IN_DAY': numberOfCouple
	        };
	        BX.ajax.runAction('up:schedule.api.couplesList.deleteCouple', {
	          data: {
	            coupleInfo: coupleInfo
	          }
	        }).then(function () {
	          _this7.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      }
	    }
	  }, {
	    key: "insertSubjectsDataForAddForm",
	    value: function insertSubjectsDataForAddForm(subjectsList) {
	      var _this8 = this;
	      var form;
	      var modalBody = document.getElementById('modal-body');
	      if (document.getElementById('add-edit-form')) {
	        form = document.getElementById('add-edit-form');
	        form.innerHTML = '';
	      } else {
	        form = main_core.Tag.render(_templateObject13 || (_templateObject13 = babelHelpers.taggedTemplateLiteral(["<form id=\"add-edit-form\"></form>"])));
	      }
	      modalBody.innerHTML = '';
	      this.formData = subjectsList;
	      if (subjectsList.length === 0) {
	        this.isValidInput = false;
	        this.fillEmptyForm('SUBJECTS');
	        return;
	      } else {
	        this.isValidInput = true;
	        this.deleteEmptyForm();
	      }
	      var selectContainer = main_core.Tag.render(_templateObject14 || (_templateObject14 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"subject-select\" name=\"subject\"></select>\n\t\t"])));
	      var option = main_core.Tag.render(_templateObject15 || (_templateObject15 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<option selected disabled hidden></option>\n\t\t\t"])));
	      selectContainer.appendChild(option);
	      subjectsList.forEach(function (subject) {
	        var option = main_core.Tag.render(_templateObject16 || (_templateObject16 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t", "\n\t\t\t\t</option>\n\t\t\t"])), subject.subject.ID, Validator.escapeHTML(subject.subject.TITLE));
	        selectContainer.appendChild(option);
	      });
	      var container = main_core.Tag.render(_templateObject17 || (_templateObject17 = babelHelpers.taggedTemplateLiteral(["<div class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject18 || (_templateObject18 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">", "</label>"])), main_core.Loc.getMessage('SUBJECT'));
	      var divControl = main_core.Tag.render(_templateObject19 || (_templateObject19 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject20 || (_templateObject20 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject21 || (_templateObject21 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
	      underLabel.appendChild(selectContainer);
	      divSelect.appendChild(underLabel);
	      divControl.appendChild(divSelect);
	      container.appendChild(label);
	      container.appendChild(divControl);
	      form.appendChild(container);
	      modalBody.appendChild(form);
	      selectContainer.addEventListener('change', function () {
	        _this8.isValidInput = true;
	        _this8.insertAudiencesDataForForm(selectContainer.value);
	        _this8.insertGroupsDataForForm(selectContainer.value);
	        _this8.insertTeachersDataForForm(selectContainer.value);
	      });
	    }
	  }, {
	    key: "insertAudiencesDataForForm",
	    value: function insertAudiencesDataForForm(subjectId) {
	      var _this9 = this;
	      if (!this.isValidInput) {
	        return;
	      }
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('audience-container')) {
	        document.getElementById('audience-container').remove();
	      }
	      var selectContainer = main_core.Tag.render(_templateObject22 || (_templateObject22 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"audience-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          if (subject.audiences.length === 0) {
	            _this9.isValidInput = false;
	            _this9.fillEmptyForm('AUDIENCES');
	            if (document.getElementById('group-container')) {
	              document.getElementById('group-container').remove();
	            }
	            if (document.getElementById('teacher-container')) {
	              document.getElementById('teacher-container').remove();
	            }
	            return;
	          } else {
	            _this9.deleteEmptyForm();
	          }
	          subject.audiences.forEach(function (audience) {
	            var option = main_core.Tag.render(_templateObject23 || (_templateObject23 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), audience.ID, Validator.escapeHTML(audience.NUMBER));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
	      if (!this.isValidInput) {
	        return;
	      }
	      var container = main_core.Tag.render(_templateObject24 || (_templateObject24 = babelHelpers.taggedTemplateLiteral(["<div id=\"audience-container\" class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject25 || (_templateObject25 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">", "</label>"])), main_core.Loc.getMessage('AUDIENCE'));
	      var divControl = main_core.Tag.render(_templateObject26 || (_templateObject26 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject27 || (_templateObject27 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject28 || (_templateObject28 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
	      underLabel.appendChild(selectContainer);
	      divSelect.appendChild(underLabel);
	      divControl.appendChild(divSelect);
	      container.appendChild(label);
	      container.appendChild(divControl);
	      form.appendChild(container);
	    }
	  }, {
	    key: "insertGroupsDataForForm",
	    value: function insertGroupsDataForForm(subjectId) {
	      var _this10 = this;
	      if (!this.isValidInput) {
	        return;
	      }
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('group-container')) {
	        document.getElementById('group-container').remove();
	      }
	      var selectContainer = main_core.Tag.render(_templateObject29 || (_templateObject29 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"group-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          if (subject.groups.length === 0) {
	            _this10.isValidInput = false;
	            _this10.fillEmptyForm('GROUPS');
	            if (document.getElementById('teacher-container')) {
	              document.getElementById('teacher-container').remove();
	            }
	            return;
	          } else {
	            _this10.deleteEmptyForm();
	          }
	          subject.groups.forEach(function (group) {
	            var option = main_core.Tag.render(_templateObject30 || (_templateObject30 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), group.ID, Validator.escapeHTML(group.TITLE));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
	      if (!this.isValidInput) {
	        return;
	      }
	      var container = main_core.Tag.render(_templateObject31 || (_templateObject31 = babelHelpers.taggedTemplateLiteral(["<div id=\"group-container\" class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject32 || (_templateObject32 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">", "</label>"])), main_core.Loc.getMessage('GROUP'));
	      var divControl = main_core.Tag.render(_templateObject33 || (_templateObject33 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject34 || (_templateObject34 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject35 || (_templateObject35 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
	      underLabel.appendChild(selectContainer);
	      divSelect.appendChild(underLabel);
	      divControl.appendChild(divSelect);
	      container.appendChild(label);
	      container.appendChild(divControl);
	      form.appendChild(container);
	    }
	  }, {
	    key: "insertTeachersDataForForm",
	    value: function insertTeachersDataForForm(subjectId) {
	      var _this11 = this;
	      if (!this.isValidInput) {
	        return;
	      }
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('teacher-container')) {
	        document.getElementById('teacher-container').remove();
	      }
	      var selectContainer = main_core.Tag.render(_templateObject36 || (_templateObject36 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"teacher-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          if (subject.teachers.length === 0) {
	            _this11.isValidInput = false;
	            _this11.fillEmptyForm('TEACHERS');
	            return;
	          } else {
	            _this11.deleteEmptyForm();
	          }
	          subject.teachers.forEach(function (teacher) {
	            var option = main_core.Tag.render(_templateObject37 || (_templateObject37 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", " ", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), teacher.ID, Validator.escapeHTML(teacher.NAME), Validator.escapeHTML(teacher.LAST_NAME));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
	      if (!this.isValidInput) {
	        return;
	      }
	      var container = main_core.Tag.render(_templateObject38 || (_templateObject38 = babelHelpers.taggedTemplateLiteral(["<div id=\"teacher-container\" class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject39 || (_templateObject39 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">", "</label>"])), main_core.Loc.getMessage('TEACHERS'));
	      var divControl = main_core.Tag.render(_templateObject40 || (_templateObject40 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject41 || (_templateObject41 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject42 || (_templateObject42 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
	      underLabel.appendChild(selectContainer);
	      divSelect.appendChild(underLabel);
	      divControl.appendChild(divSelect);
	      container.appendChild(label);
	      container.appendChild(divControl);
	      form.appendChild(container);
	    }
	  }, {
	    key: "fetchSubjectsForAddForm",
	    value: function fetchSubjectsForAddForm() {
	      var _this12 = this;
	      this.extractEntityFromUrl();
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.couplesList.fetchAddCoupleData', {
	          data: {
	            entity: _this12.entity,
	            id: _this12.entityId
	          }
	        }).then(function (response) {
	          var subjectList = response.data;
	          resolve(subjectList);
	        })["catch"](function (error) {
	          reject(error);
	        });
	      });
	    }
	  }, {
	    key: "closeCoupleModal",
	    value: function closeCoupleModal() {
	      var submitButton = document.getElementById('submit-form-button');
	      var cancelButton = document.getElementById('cancel-form-button');
	      if (submitButton && cancelButton) {
	        submitButton.remove();
	        cancelButton.remove();
	      }
	      this.deleteEmptyForm();
	      var modal = document.getElementById('coupleModal');
	      modal.classList.remove('is-active');
	    }
	  }, {
	    key: "fillEmptyForm",
	    value: function fillEmptyForm(entity) {
	      var modalBody = document.getElementById('modal-body');
	      this.deleteEmptyForm();
	      var emptyForm = main_core.Tag.render(_templateObject43 || (_templateObject43 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<div id=\"empty-form\" class=\"has-text-danger\">", "</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('EMPTY_' + entity + '_MESSAGE'));
	      modalBody.appendChild(emptyForm);
	    }
	  }, {
	    key: "deleteEmptyForm",
	    value: function deleteEmptyForm() {
	      if (document.getElementById('empty-form')) {
	        document.getElementById('empty-form').remove();
	      }
	    }
	  }, {
	    key: "sendMessage",
	    value: function sendMessage() {
	      var errorMessage = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	      var successMessage = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      BX.ready(function () {
	        new PopupMessage({
	          rootNodeId: 'messages',
	          errorsMessage: errorMessage,
	          successMessage: successMessage
	        });
	      });
	    }
	  }]);
	  return CouplesList;
	}();

	exports.CouplesList = CouplesList;

}((this.BX.Up = this.BX.Up || {}),BX));
