/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject, _templateObject2, _templateObject3, _templateObject4, _templateObject5, _templateObject6, _templateObject7, _templateObject8, _templateObject9, _templateObject10, _templateObject11, _templateObject12, _templateObject13, _templateObject14, _templateObject15, _templateObject16, _templateObject17, _templateObject18, _templateObject19, _templateObject20, _templateObject21, _templateObject22, _templateObject23, _templateObject24, _templateObject25, _templateObject26, _templateObject27, _templateObject28, _templateObject29, _templateObject30, _templateObject31, _templateObject32, _templateObject33, _templateObject34;
	var CouplesList = /*#__PURE__*/function () {
	  function CouplesList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, CouplesList);
	    babelHelpers.defineProperty(this, "formData", {});
	    babelHelpers.defineProperty(this, "daysOfWeek", {
	      1: 'Понедельник',
	      2: 'Вторник',
	      3: 'Среда',
	      4: 'Четверг',
	      5: 'Пятница',
	      6: 'Суббота'
	    });
	    babelHelpers.defineProperty(this, "groupId", undefined);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('CouplesList: options.rootNodeId required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("CouplesList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.groupId = this.getGroupId();
	    this.coupleList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(CouplesList, [{
	    key: "getGroupId",
	    value: function getGroupId() {
	      var url = window.location.pathname;
	      if (url.length === 0) ;
	      var groupId = url.slice('group/'.length + 1, 'group/'.length + 2);
	      return typeof Number(groupId) === "number" ? groupId : undefined;
	    }
	  }, {
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadList().then(function (coupleList) {
	        _this.coupleList = coupleList;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      var _this2 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.couplesList.getCouplesList', {
	          data: {
	            id: _this2.groupId
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
	      var _loop = function _loop(day) {
	        var dayTitleContainer = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center\">\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t"])), _this3.daysOfWeek[day]);
	        var dayColumnContainer = document.createElement('div');
	        dayColumnContainer.className = 'column is-2';
	        var dayContainer = document.createElement('div');
	        dayContainer.className = 'box has-text-centered couples';
	        dayContainer.appendChild(dayTitleContainer);
	        var _loop2 = function _loop2(i) {
	          var coupleTextContainer = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["<br>"])));
	          var dropdownContent = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-content\"></div>"])));
	          if (typeof _this3.coupleList[day] !== "undefined" && typeof _this3.coupleList[day][i] !== "undefined") {
	            //console.log(this.coupleList[day][i]);
	            coupleTextContainer = main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<div class=\"couple-text\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t<br>\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t<br>\n\t\t\t\t\t\t\t", " ", "\n\t\t\t\t\t\t</div>\n\t\t\t\t\t"])), _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME, _this3.coupleList[day][i].UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME);
	            var removeCoupleButton = main_core.Tag.render(_templateObject5 || (_templateObject5 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-remove-", "-", "\" class=\"js-modal-trigger dropdown-item btn-remove-couple button is-clickable is-small is-primary is-light\">\n\t\t\t\t\t\t\t\u0423\u0434\u0430\u043B\u0438\u0442\u044C\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i);
	            removeCoupleButton.addEventListener('click', function () {
	              _this3.handleRemoveCoupleButtonClick();
	            });
	            var editCoupleButton = main_core.Tag.render(_templateObject6 || (_templateObject6 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-edit-", "-", "\" class=\"js-modal-trigger dropdown-item btn-edit-couple button is-clickable is-small is-primary is-light mb-1\">\n\t\t\t\t\t\t\t\u0418\u0437\u043C\u0435\u043D\u0438\u0442\u044C\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i);
	            editCoupleButton.addEventListener('click', function () {
	              _this3.handleEditCoupleButtonClick();
	            });
	            dropdownContent.appendChild(editCoupleButton);
	            dropdownContent.appendChild(removeCoupleButton);
	          } else {
	            var addCoupleButton = main_core.Tag.render(_templateObject7 || (_templateObject7 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<button \n\t\t\t\t\t\tdata-target=\"modal-js-example\" type=\"button\" id=\"button-add-", "-", "\" class=\"js-modal-trigger dropdown-item btn-add-couple button is-clickable is-small is-primary is-light\">\n\t\t\t\t\t\t\t\u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C\n\t\t\t\t\t\t</button>\n\t\t\t\t\t"])), day, i);
	            addCoupleButton.addEventListener('click', function () {
	              _this3.handleAddCoupleButtonClick(day, i);
	            });
	            dropdownContent.appendChild(addCoupleButton);
	          }
	          var coupleContainer = document.createElement('div');
	          coupleContainer.className = 'box is-clickable couple m-0';

	          //КНОПКА
	          var dropdownTrigger = main_core.Tag.render(_templateObject8 || (_templateObject8 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-trigger\"></div>"])));
	          var button = main_core.Tag.render(_templateObject9 || (_templateObject9 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<button type=\"button\" aria-haspopup=\"true\" aria-controls=\"dropdown-menu\" id=\"button-", "-", "\" class=\"btn-dropdown-couple button is-clickable is-small is-ghost\">\n\t\t\t\t\t\t...\n\t\t\t\t\t</button>\n\t\t\t\t"])), day, i);
	          button.addEventListener('click', function () {
	            _this3.handleOpenDropdownCoupleButtonClick(day, i);
	          }, {
	            once: true
	          });
	          dropdownTrigger.appendChild(button);
	          var btnContainer = main_core.Tag.render(_templateObject10 || (_templateObject10 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div id=\"dropdown-", "-", "\" class=\"btn-edit-couple-container dropdown\"></div>"])), day, i);
	          var dropdownMenu = main_core.Tag.render(_templateObject11 || (_templateObject11 = babelHelpers.taggedTemplateLiteral(["<div class=\"dropdown-menu\" id=\"dropdown-menu\" role=\"menu\"></div>"])));
	          dropdownMenu.appendChild(dropdownContent);
	          btnContainer.appendChild(dropdownTrigger);
	          btnContainer.appendChild(dropdownMenu);

	          //coupleContainer.appendChild(some);

	          coupleContainer.appendChild(btnContainer);
	          coupleContainer.appendChild(coupleTextContainer);
	          dayContainer.appendChild(coupleContainer);
	        };
	        for (var i = 1; i < 7; i++) {
	          _loop2(i);
	        }
	        dayColumnContainer.appendChild(dayContainer);
	        _this3.rootNode.appendChild(dayColumnContainer);
	      };
	      for (var day in this.daysOfWeek) {
	        _loop(day);
	      }
	    }
	  }, {
	    key: "handleOpenDropdownCoupleButtonClick",
	    value: function handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple) {
	      var _this4 = this;
	      console.log('open');
	      var dropdown = document.getElementById("dropdown-".concat(numberOfDay, "-").concat(numberOfCouple));
	      dropdown.className = 'btn-edit-couple-container dropdown is-active';
	      var button = document.getElementById("button-".concat(numberOfDay, "-").concat(numberOfCouple));
	      button.addEventListener('click', function () {
	        _this4.handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
	      }, {
	        once: true
	      });
	    }
	  }, {
	    key: "handleCloseDropdownCoupleButtonClick",
	    value: function handleCloseDropdownCoupleButtonClick(numberOfDay, numberOfCouple) {
	      var _this5 = this;
	      console.log('close');
	      var dropdown = document.getElementById("dropdown-".concat(numberOfDay, "-").concat(numberOfCouple));
	      dropdown.className = 'btn-edit-couple-container dropdown';
	      var button = document.getElementById("button-".concat(numberOfDay, "-").concat(numberOfCouple));
	      button.addEventListener('click', function () {
	        _this5.handleOpenDropdownCoupleButtonClick(numberOfDay, numberOfCouple);
	      }, {
	        once: true
	      });
	    }
	  }, {
	    key: "handleAddCoupleButtonClick",
	    value: function handleAddCoupleButtonClick(numberOfDay, numberOfCouple) {
	      //console.log(numberOfDay);

	      this.openCoupleModal();
	      this.createAddForm(numberOfDay, numberOfCouple);
	      console.log('add');
	    }
	  }, {
	    key: "handleRemoveCoupleButtonClick",
	    value: function handleRemoveCoupleButtonClick(numberOfDay, numberOfCouple) {
	      this.openCoupleModal();
	      console.log('remove');
	    }
	  }, {
	    key: "handleEditCoupleButtonClick",
	    value: function handleEditCoupleButtonClick(numberOfDay, numberOfCouple) {
	      this.openCoupleModal();
	      console.log('edit');
	    }
	  }, {
	    key: "openCoupleModal",
	    value: function openCoupleModal() {
	      var _this6 = this;
	      var modal = document.getElementById('coupleModal');
	      modal.classList.add('is-active');
	      document.addEventListener('keydown', function (event) {
	        if (event.key === "Escape") {
	          _this6.closeCoupleModal();
	        }
	      });
	      var closeButton = document.getElementById('button-close-modal');
	      closeButton.addEventListener('click', function () {
	        _this6.closeCoupleModal();
	      });
	    }
	  }, {
	    key: "createAddForm",
	    value: function createAddForm(numberOfDay, numberOfCouple) {
	      var _this7 = this;
	      this.fetchSubjectsForAddForm().then(function (subjectsList) {
	        _this7.insertDataForAddForm(subjectsList);
	      });
	      console.log(numberOfDay + ' ' + numberOfCouple);
	      var submitButton = document.getElementById('submit-form-button');
	      var cancelButton = document.getElementById('cancel-form-button');
	      submitButton.addEventListener('click', function () {
	        //console.log(numberOfDay);
	        _this7.sendForm(numberOfDay, numberOfCouple);
	      }, {
	        once: true
	      });
	      cancelButton.addEventListener('click', function () {
	        _this7.closeCoupleModal();
	      }, {
	        once: true
	      });

	      /*const form = document.getElementById('add-edit-form');*/

	      // 		`<div class="is-60-height box edit-fields">
	      // \t\t\t<?php if (is_array($field)): ?>
	      // \t\t\t\t<label class="label"><?= GetMessage($key) ?></label>
	      // \t\t\t\t\t<div class="control">
	      // \t\t\t\t\t\t<div class="select">
	      // \t\t\t\t\t\t\t<label>
	      // \t\t\t\t\t\t\t\t<select name="<?= $key ?>">
	      // \t\t\t\t\t\t\t\t\t<?php foreach ($field as $keyOfField => $subfield): ?>
	      // \t\t\t\t\t\t\t\t\t\t<option value="<?=$subfield['ID']?>">
	      // \t\t\t\t\t\t\t\t\t\t\t<?=$subfield['TITLE']?>
	      // \t\t\t\t\t\t\t\t\t\t</option>
	      // \t\t\t\t\t\t\t\t\t<?php
	      // \t\t\t\t\t\t\t\t\tendforeach; ?>
	      // \t\t\t\t\t\t\t\t</select>
	      // \t\t\t\t\t\t\t</label>
	      // \t\t\t\t\t\t</div>
	      // \t\t\t\t\t</div>
	      // \t\t\t<?php endif; ?>
	      // \t\t</div>`
	    }
	  }, {
	    key: "sendForm",
	    value: function sendForm(numberOfDay, numberOfCouple) {
	      var _this8 = this;
	      var subjectInput = document.getElementById('subject-select');
	      var teacherInput = document.getElementById('teacher-select');
	      var audienceInput = document.getElementById('audience-select');
	      if (subjectInput && teacherInput && audienceInput) {
	        console.log(subjectInput.value);
	        BX.ajax.runAction('up:schedule.api.couplesList.addCouple', {
	          data: {
	            GROUP_ID: this.groupId,
	            SUBJECT_ID: subjectInput.value,
	            TEACHER_ID: teacherInput.value,
	            AUDIENCE_ID: audienceInput.value,
	            DAY_OF_WEEK: numberOfDay,
	            NUMBER_IN_DAY: numberOfCouple
	          }
	        }).then(function (response) {
	          console.log(response);
	          _this8.closeCoupleModal();
	          _this8.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      }
	    }
	  }, {
	    key: "insertDataForAddForm",
	    value: function insertDataForAddForm(subjectsList) {
	      var _this9 = this;
	      var form;
	      var modalBody = document.getElementById('modal-body');
	      if (document.getElementById('add-edit-form')) {
	        form = document.getElementById('add-edit-form');
	        form = main_core.Tag.render(_templateObject12 || (_templateObject12 = babelHelpers.taggedTemplateLiteral(["<form id=\"add-edit-form\"></form>"])));
	        modalBody.innerHTML = '';
	      }
	      this.formData = subjectsList;
	      var selectContainer = main_core.Tag.render(_templateObject13 || (_templateObject13 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"subject-select\" name=\"subject\"> </select>\n\t\t"])));
	      var option = main_core.Tag.render(_templateObject14 || (_templateObject14 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<option selected disabled hidden></option>\n\t\t\t"])));
	      selectContainer.appendChild(option);
	      subjectsList.forEach(function (subject) {
	        var option = main_core.Tag.render(_templateObject15 || (_templateObject15 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t", "\n\t\t\t\t</option>\n\t\t\t"])), subject.subject.SUBJECTSID, subject.subject.SUBJECTSTITLE);
	        selectContainer.appendChild(option);
	        //console.log(subject.subject);
	      });

	      var container = main_core.Tag.render(_templateObject16 || (_templateObject16 = babelHelpers.taggedTemplateLiteral(["<div class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject17 || (_templateObject17 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">\u041F\u0440\u0435\u0434\u043C\u0435\u0442</label>"])));
	      var divControl = main_core.Tag.render(_templateObject18 || (_templateObject18 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject19 || (_templateObject19 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject20 || (_templateObject20 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
	      underLabel.appendChild(selectContainer);
	      divSelect.appendChild(underLabel);
	      divControl.appendChild(divSelect);
	      container.appendChild(label);
	      container.appendChild(divControl);
	      form.appendChild(container);
	      modalBody.appendChild(form);
	      var select = document.getElementById('subject-select');
	      select.addEventListener('change', function () {
	        _this9.insertAudiencesDataForForm(select.value);
	        _this9.insertTeachersDataForForm(select.value);
	      });
	      //console.log(subjectsList);
	    }
	  }, {
	    key: "insertAudiencesDataForForm",
	    value: function insertAudiencesDataForForm(subjectId) {
	      var form = document.getElementById('add-edit-form');
	      if (document.getElementById('audience-container')) {
	        form.removeChild(document.getElementById('audience-container'));
	      }
	      var selectContainer = main_core.Tag.render(_templateObject21 || (_templateObject21 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"audience-select\" name=\"subject\"> </select>\n\t\t"])));
	      //console.log(this.formData);
	      this.formData.forEach(function (subject) {
	        if (subject.subject.SUBJECTSID === subjectId) {
	          subject.audiences.forEach(function (audience) {
	            var option = main_core.Tag.render(_templateObject22 || (_templateObject22 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), audience.ID, audience.NUMBER);
	            selectContainer.appendChild(option);
	          });
	        }
	        //console.log(subject.subject);
	      });

	      var container = main_core.Tag.render(_templateObject23 || (_templateObject23 = babelHelpers.taggedTemplateLiteral(["<div id=\"audience-container\" class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject24 || (_templateObject24 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">\u0410\u0443\u0434\u0438\u0442\u043E\u0440\u0438\u044F</label>"])));
	      var divControl = main_core.Tag.render(_templateObject25 || (_templateObject25 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject26 || (_templateObject26 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject27 || (_templateObject27 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
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
	      var selectContainer = main_core.Tag.render(_templateObject28 || (_templateObject28 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<select id=\"teacher-select\" name=\"subject\"> </select>\n\t\t"])));
	      //console.log(this.formData);
	      this.formData.forEach(function (subject) {
	        if (subject.subject.SUBJECTSID === subjectId) {
	          subject.teachers.forEach(function (teacher) {
	            var option = main_core.Tag.render(_templateObject29 || (_templateObject29 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<option value=\"", "\">\n\t\t\t\t\t\t\t", " ", "\n\t\t\t\t\t\t</option>\n\t\t\t\t\t"])), teacher.ID, teacher.NAME, teacher.LAST_NAME);
	            selectContainer.appendChild(option);
	          });
	        }
	        //console.log(subject.subject);
	      });

	      var container = main_core.Tag.render(_templateObject30 || (_templateObject30 = babelHelpers.taggedTemplateLiteral(["<div id=\"teacher-container\" class=\"is-60-height box edit-fields\"></div>"])));
	      var label = main_core.Tag.render(_templateObject31 || (_templateObject31 = babelHelpers.taggedTemplateLiteral(["<label class=\"label\">\u041F\u0440\u0435\u043F\u043E\u0434\u0430\u0432\u0430\u0442\u0435\u043B\u0438</label>"])));
	      var divControl = main_core.Tag.render(_templateObject32 || (_templateObject32 = babelHelpers.taggedTemplateLiteral(["<div class=\"control\"></div>"])));
	      var divSelect = main_core.Tag.render(_templateObject33 || (_templateObject33 = babelHelpers.taggedTemplateLiteral(["<div class=\"select\"></div>"])));
	      var underLabel = main_core.Tag.render(_templateObject34 || (_templateObject34 = babelHelpers.taggedTemplateLiteral(["<label></label>"])));
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
	      var _this10 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.couplesList.fetchAddCoupleData', {
	          data: {
	            id: _this10.groupId,
	            numberOfDay: numberOfDay,
	            numberOfCouple: numberOfCouple
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
