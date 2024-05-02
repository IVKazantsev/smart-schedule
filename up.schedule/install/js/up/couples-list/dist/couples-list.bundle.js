/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var Validator = /*#__PURE__*/function () {
	  function Validator() {
	    babelHelpers.classCallCheck(this, Validator);
	  }
	  babelHelpers.createClass(Validator, null, [{
	    key: "escapeHTML",
	    value: function escapeHTML(text) {
	      return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
	    }
	  }]);
	  return Validator;
	}();

	var _templateObject, _templateObject2, _templateObject3, _templateObject4, _templateObject5, _templateObject6, _templateObject7, _templateObject8, _templateObject9, _templateObject10, _templateObject11, _templateObject12, _templateObject13, _templateObject14, _templateObject15, _templateObject16, _templateObject17, _templateObject18, _templateObject19, _templateObject20, _templateObject21, _templateObject22, _templateObject23, _templateObject24, _templateObject25, _templateObject26, _templateObject27, _templateObject28, _templateObject29, _templateObject30, _templateObject31, _templateObject32, _templateObject33, _templateObject34, _templateObject35, _templateObject36, _templateObject37, _templateObject38, _templateObject39, _templateObject40, _templateObject41, _templateObject42;
	var CouplesList = /*#__PURE__*/function () {
	  function CouplesList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
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
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("CouplesList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.extractEntityFromUrl();
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
	      var _this3 = this;
	      return new Promise(function (resolve, reject) {
	        var _this3$entity;
	        BX.ajax.runAction('up:schedule.api.couplesList.getCouplesList', {
	          data: {
	            entity: (_this3$entity = _this3.entity) !== null && _this3$entity !== void 0 ? _this3$entity : _this3.defaultEntity,
	            id: Number(_this3.entityId)
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
	      var _this4 = this;
	      this.rootNode.innerHTML = '';
	      var _loop = function _loop(day) {
	        var dayTitleContainer = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center\">\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t"])), _this4.daysOfWeek[day]);
	        var dayColumnContainer = document.createElement('div');
	        dayColumnContainer.className = 'column is-2';
	        var dayContainer = document.createElement('div');
	        dayContainer.className = 'box has-text-centered couples';
	        dayContainer.appendChild(dayTitleContainer);
	        var _loop2 = function _loop2(i) {
	          var coupleTextContainer = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["<br>"])));
	          var dropdownContent = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-content\"></div>"])));
	          if (typeof _this4.coupleList[day] !== 'undefined' && typeof _this4.coupleList[day][i] !== 'undefined') {
	            coupleTextContainer = main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<div class=\"couple-text\">\n\t\t\t\t\t\t\t<p>", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"subjectId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"audienceId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"groupId-", "-", "\">", "</p>\n\t\t\t\t\t\t\t<p>", "\n\t\t\t\t\t\t\t   ", "</p>\n\t\t\t\t\t\t\t<p hidden id=\"teacherId-", "-", "\">", "</p>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t"])), Validator.escapeHTML(_this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE), day, i, _this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID, Validator.escapeHTML(_this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER), day, i, _this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_ID, Validator.escapeHTML(_this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_TITLE), day, i, _this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_GROUP_ID, Validator.escapeHTML(_this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME), Validator.escapeHTML(_this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME), day, i, _this4.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_ID);
	            if (_this4.isAdmin === true) {
	              var removeCoupleButton = main_core.Tag.render(_templateObject5 || (_templateObject5 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-remove-", "-", "\" class=\"js-modal-trigger dropdown-item btn-remove-couple button is-clickable is-small is-primary is-light\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i, main_core.Loc.getMessage('DELETE'));
	              removeCoupleButton.addEventListener('click', function () {
	                _this4.handleRemoveCoupleButtonClick(day, i);
	              });
	              var editCoupleButton = main_core.Tag.render(_templateObject6 || (_templateObject6 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-edit-", "-", "\" class=\"js-modal-trigger dropdown-item btn-edit-couple button is-clickable is-small is-primary is-light mb-1\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i, main_core.Loc.getMessage('EDIT'));
	              editCoupleButton.addEventListener('click', function () {
	                _this4.handleEditCoupleButtonClick();
	              });
	              dropdownContent.appendChild(editCoupleButton);
	              dropdownContent.appendChild(removeCoupleButton);
	            }
	          } else {
	            var addCoupleButton = main_core.Tag.render(_templateObject7 || (_templateObject7 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-add-", "-", "\" class=\"js-modal-trigger dropdown-item btn-add-couple button is-clickable is-small is-primary is-light\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i, main_core.Loc.getMessage('ADD'));
	            if (_this4.isAdmin === true) {
	              addCoupleButton.addEventListener('click', function () {
	                _this4.handleAddCoupleButtonClick(day, i);
	              });
	              dropdownContent.appendChild(addCoupleButton);
	            }
	          }
	          var coupleContainer = document.createElement('div');
	          coupleContainer.className = 'box is-clickable couple m-0';
	          if (_this4.isAdmin) {
	            var dropdownTrigger = main_core.Tag.render(_templateObject8 || (_templateObject8 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-trigger\"></div>"])));
	            var button = main_core.Tag.render(_templateObject9 || (_templateObject9 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<button type=\"button\" aria-haspopup=\"true\" aria-controls=\"dropdown-menu\" id=\"button-", "-", "\" class=\"btn-dropdown-couple button is-clickable is-small is-ghost\">\n\t\t\t\t\t\t...\n\t\t\t\t\t</button>\n\t\t\t\t"])), day, i);
	            button.addEventListener('click', function () {
	              _this4.handleOpenDropdownCoupleButtonClick(day, i);
	            }, {
	              once: true
	            });
	            dropdownTrigger.appendChild(button);
	            var btnContainer = main_core.Tag.render(_templateObject10 || (_templateObject10 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div id=\"dropdown-", "-", "\" class=\"btn-edit-couple-container dropdown\"></div>"])), day, i);
	            var dropdownMenu = main_core.Tag.render(_templateObject11 || (_templateObject11 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-menu\" id=\"dropdown-menu\" role=\"menu\"></div>"])));
	            dropdownMenu.appendChild(dropdownContent);
	            btnContainer.appendChild(dropdownTrigger);
	            btnContainer.appendChild(dropdownMenu);
	            coupleContainer.appendChild(btnContainer);
	          }
	          coupleContainer.appendChild(coupleTextContainer);
	          dayContainer.appendChild(coupleContainer);
	        };
	        for (var i = 1; i < 7; i++) {
	          _loop2(i);
	        }
	        dayColumnContainer.appendChild(dayContainer);
	        _this4.rootNode.appendChild(dayColumnContainer);
	      };
	      for (var day in this.daysOfWeek) {
	        _loop(day);
	      }
	    }
	  }, {
	    key: "handleOpenDropdownCoupleButtonClick",
	    value: function handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple) {
	      var _this5 = this;
	      var modals = document.querySelectorAll('.dropdown');
	      modals.forEach(function (modalWindow) {
	        modalWindow.classList.remove('is-active');
	      });
	      var dropdown = document.getElementById("dropdown-".concat(numberOfDay, "-").concat(numberOfCouple));
	      dropdown.className = 'btn-edit-couple-container dropdown is-active';
	      var button = document.getElementById("button-".concat(numberOfDay, "-").concat(numberOfCouple));
	      button.addEventListener('click', function () {
	        _this5.handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
	      }, {
	        once: true
	      });
	    }
	  }, {
	    key: "handleCloseDropdownCoupleButtonClick",
	    value: function handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple) {
	      var _this6 = this;
	      var dropdown = document.getElementById("dropdown-".concat(numberOfDay, "-").concat(numberOfCouple));
	      dropdown.className = 'btn-edit-couple-container dropdown';
	      var button = document.getElementById("button-".concat(numberOfDay, "-").concat(numberOfCouple));
	      button.addEventListener('click', function () {
	        _this6.handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
	      }, {
	        once: true
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
	    key: "handleEditCoupleButtonClick",
	    value: function handleEditCoupleButtonClick(numberOfDay, numberOfCouple) {
	      this.openCoupleModal();
	    }
	  }, {
	    key: "openCoupleModal",
	    value: function openCoupleModal() {
	      var _this7 = this;
	      var modal = document.getElementById('coupleModal');
	      modal.classList.add('is-active');
	      document.addEventListener('keydown', function (event) {
	        if (event.key === 'Escape') {
	          _this7.closeCoupleModal();
	        }
	      });
	      var closeButton = document.getElementById('button-close-modal');
	      closeButton.addEventListener('click', function () {
	        _this7.closeCoupleModal();
	      });
	    }
	  }, {
	    key: "createAddForm",
	    value: function createAddForm(numberOfDay, numberOfCouple) {
	      var _this8 = this;
	      this.fetchSubjectsForAddForm().then(function (subjectsList) {
	        _this8.insertSubjectsDataForAddForm(subjectsList);
	      });
	      var submitButton = document.getElementById('submit-form-button');
	      var cancelButton = document.getElementById('cancel-form-button');
	      submitButton.addEventListener('click', function () {
	        _this8.sendForm(numberOfDay, numberOfCouple, 'add');
	      }, {
	        once: true
	      });
	      cancelButton.addEventListener('click', function () {
	        _this8.closeCoupleModal();
	      }, {
	        once: true
	      });
	    }
	  }, {
	    key: "sendForm",
	    value: function sendForm(numberOfDay, numberOfCouple, typeOfRequest) {
	      var _this9 = this;
	      var subjectInput = document.getElementById('subject-select');
	      var teacherInput = document.getElementById('teacher-select');
	      var audienceInput = document.getElementById('audience-select');
	      var groupInput = document.getElementById('group-select');
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
	        }).then(function (response) {
	          _this9.closeCoupleModal();
	          _this9.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      }
	    }
	  }, {
	    key: "removeCouple",
	    value: function removeCouple(numberOfDay, numberOfCouple) {
	      var _this10 = this;
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
	        }).then(function (response) {
	          _this10.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      }
	    }
	  }, {
	    key: "insertSubjectsDataForAddForm",
	    value: function insertSubjectsDataForAddForm(subjectsList) {
	      var _this11 = this;
	      var form;
	      var modalBody = document.getElementById('modal-body');
	      if (document.getElementById('add-edit-form')) {
	        form = document.getElementById('add-edit-form');
	        form = main_core.Tag.render(_templateObject12 || (_templateObject12 = babelHelpers.taggedTemplateLiteral(["<form id=\"add-edit-form\"></form>"])));
	        modalBody.innerHTML = '';
	      }
	      this.formData = subjectsList;
	      if (subjectsList.length === 0) {
	        if (document.getElementById('empty-form')) {
	          form.removeChild(document.getElementById('empty-form'));
	        }
	        var emptyForm = main_core.Tag.render(_templateObject13 || (_templateObject13 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div id=\"empty-form\">", "</div>\n\t\t\t"])), main_core.Loc.getMessage('EMPTY_ADD_MESSAGE'));
	        modalBody.appendChild(emptyForm);
	        return;
	      }
	      var selectContainer = main_core.Tag.render(_templateObject14 || (_templateObject14 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"subject-select\" name=\"subject\"> </select>\n\t\t"])));
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
	      var select = document.getElementById('subject-select');
	      select.addEventListener('change', function () {
	        _this11.insertAudiencesDataForForm(select.value);
	        _this11.insertGroupsDataForForm(select.value);
	        _this11.insertTeachersDataForForm(select.value);
	      });
	    }
	  }, {
	    key: "insertAudiencesDataForForm",
	    value: function insertAudiencesDataForForm(subjectId) {
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('audience-container')) {
	        form.removeChild(document.getElementById('audience-container'));
	      }
	      var selectContainer = main_core.Tag.render(_templateObject22 || (_templateObject22 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"audience-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          subject.audiences.forEach(function (audience) {
	            var option = main_core.Tag.render(_templateObject23 || (_templateObject23 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), audience.ID, Validator.escapeHTML(audience.NUMBER));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
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
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('group-container')) {
	        form.removeChild(document.getElementById('group-container'));
	      }
	      var selectContainer = main_core.Tag.render(_templateObject29 || (_templateObject29 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"group-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          subject.groups.forEach(function (group) {
	            var option = main_core.Tag.render(_templateObject30 || (_templateObject30 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), group.ID, Validator.escapeHTML(group.TITLE));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
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
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('teacher-container')) {
	        form.removeChild(document.getElementById('teacher-container'));
	      }
	      var selectContainer = main_core.Tag.render(_templateObject36 || (_templateObject36 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"teacher-select\" name=\"subject\"> </select>\n\t\t"])));
	      this.formData.forEach(function (subject) {
	        if (subject.subject.ID === subjectId) {
	          subject.teachers.forEach(function (teacher) {
	            var option = main_core.Tag.render(_templateObject37 || (_templateObject37 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", " ", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), teacher.ID, Validator.escapeHTML(teacher.NAME), Validator.escapeHTML(teacher.LAST_NAME));
	            selectContainer.appendChild(option);
	          });
	        }
	      });
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
	    value: function fetchSubjectsForAddForm(numberOfDay, numberOfCouple) {
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
	      var modal = document.getElementById('coupleModal');
	      modal.classList.remove('is-active');
	    }
	  }]);
	  return CouplesList;
	}();

	exports.CouplesList = CouplesList;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
