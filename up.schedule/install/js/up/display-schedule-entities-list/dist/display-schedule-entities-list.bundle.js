/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject, _templateObject2, _templateObject3;
	var DisplayScheduleEntitiesList = /*#__PURE__*/function () {
	  function DisplayScheduleEntitiesList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, DisplayScheduleEntitiesList);
	    babelHelpers.defineProperty(this, "entityList", []);
	    babelHelpers.defineProperty(this, "entity", undefined);
	    babelHelpers.defineProperty(this, "suitableEntityList", undefined);
	    babelHelpers.defineProperty(this, "entityId", undefined);
	    babelHelpers.defineProperty(this, "currentEntity", undefined);
	    babelHelpers.defineProperty(this, "defaultEntity", 'group');
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('CouplesList: options.rootNodeId required');
	    }
	    if (main_core.Type.isObject(options.entityInfo)) {
	      this.entity = options.entityInfo.entity;
	      this.entityId = options.entityInfo.entityId;
	    } else {
	      throw new Error('CouplesList: options.entityInfo required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("CouplesList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.entityList = [];
	    this.suitableEntityList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(DisplayScheduleEntitiesList, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      var entityInfo = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
	      var searchInput = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      if (entityInfo.length !== 0) {
	        this.entity = entityInfo.entity;
	        this.entityId = entityInfo.entityId;
	      }
	      if (searchInput.length !== 0) {
	        this.searchInList(searchInput);
	        this.render(false);
	        return;
	      }
	      this.loadList().then(function (data) {
	        _this.entityList = data.entities;
	        _this.suitableEntityList = data.entities;
	        _this.currentEntity = data.currentEntity;
	        _this.locEntity = data.locEntity;
	        _this.render();
	      });
	    }
	  }, {
	    key: "searchInList",
	    value: function searchInList(searchInput) {
	      var suitableEntityList = [];
	      this.entityList.forEach(function (entity) {
	        if (entity['NAMING'].toLowerCase().includes(searchInput.toLowerCase())) {
	          suitableEntityList.push(entity);
	        }
	      });
	      this.suitableEntityList = suitableEntityList;
	      console.log(this.entityList);
	      console.log(this.suitableEntityList);
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      var _this$entity,
	        _this2 = this;
	      this.entity = (_this$entity = this.entity) !== null && _this$entity !== void 0 ? _this$entity : this.defaultEntity;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.displayEntitiesList.getDisplayEntitiesList', {
	          data: {
	            entity: _this2.entity,
	            id: Number(_this2.entityId)
	          }
	        }).then(function (response) {
	          var data = response.data;
	          resolve(data);
	        })["catch"](function (error) {
	          reject(error);
	        });
	      });
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      var _this3 = this;
	      var needToChangeInputValue = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
	      this.rootNode.innerHTML = '';
	      if (this.suitableEntityList.length === 0) {
	        var message = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"dropdown-item\">\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t"])), main_core.Loc.getMessage('EMPTY_ENTITY_LIST'));
	        this.rootNode.appendChild(message);
	        return;
	      }
	      this.suitableEntityList.forEach(function (entity) {
	        var entityLink;
	        if (_this3.currentEntity) {
	          entityLink = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a href=\"/", "/", "/\"\n\t\t\t\tclass=\"dropdown-item ", "\">\n\t\t\t\t", "\n\t\t\t\t</a>\n\t\t\t"])), _this3.entity, entity['ID'], entity['ID'] === _this3.currentEntity['ID'] ? 'is-active' : '', entity['NAMING']);
	        } else {
	          if (needToChangeInputValue) {
	            document.getElementById('entity-selection-button').placeholder = main_core.Loc.getMessage('SELECT_' + _this3.locEntity);
	            document.getElementById('entity-selection-button').value = '';
	          }
	          entityLink = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a href=\"/", "/", "/\"\n\t\t\t\tclass=\"dropdown-item\">", "\n\t\t\t\t</a>\n\t\t\t"])), _this3.entity, entity['ID'], entity['NAMING']);
	        }
	        _this3.rootNode.appendChild(entityLink);
	        _this3.dropdownsListeners();
	        entityLink.addEventListener('click', function (event) {
	          event.preventDefault();
	          var dropdowns = document.querySelectorAll('.dropdown-item');
	          dropdowns.forEach(function (dropdown) {
	            dropdown.classList.remove('is-active');
	          });
	          entityLink.classList.add('is-active');
	          if (needToChangeInputValue) {
	            document.getElementById('entity-selection-button').placeholder = main_core.Loc.getMessage(_this3.locEntity) + ' ' + entityLink.textContent;
	            document.getElementById('entity-selection-button').value = '';
	          }
	          if (history.pushState) {
	            var newUrl = entityLink.href;
	            window.history.pushState({
	              path: newUrl
	            }, '', newUrl);
	          }
	          window.ScheduleCouplesList.extractEntityFromUrl();
	          window.ScheduleCouplesList.reload();
	        });
	      });
	    }
	  }, {
	    key: "dropdownsListeners",
	    value: function dropdownsListeners() {
	      var _this4 = this;
	      var dropdowns = document.querySelectorAll('.dropdown-item');
	      dropdowns.forEach(function (dropdown) {
	        dropdown.addEventListener('click', function (event) {
	          event.preventDefault();
	          dropdowns.forEach(function (dropdown) {
	            dropdown.classList.remove('is-active');
	          });
	          dropdown.classList.add('is-active');
	          document.getElementById('entity-selection-button').placeholder = main_core.Loc.getMessage(_this4.locEntity) + ' ' + dropdown.textContent;
	          document.getElementById('entity-selection-button').value = '';
	          if (history.pushState) {
	            var newUrl = dropdown.href;
	            window.history.pushState({
	              path: newUrl
	            }, '', newUrl);
	          }
	          window.ScheduleCouplesList.extractEntityFromUrl();
	          window.ScheduleCouplesList.reload();
	        });
	      });
	    }
	  }]);
	  return DisplayScheduleEntitiesList;
	}();

	exports.DisplayScheduleEntitiesList = DisplayScheduleEntitiesList;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
