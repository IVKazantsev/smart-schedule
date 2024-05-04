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
	      if (!text || text === '') {
	        return '';
	      }
	      return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
	    }
	  }]);
	  return Validator;
	}();

	var _templateObject, _templateObject2, _templateObject3;
	var DisplayScheduleEntitiesList = /*#__PURE__*/function () {
	  function DisplayScheduleEntitiesList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    var dataSourceIsDb = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
	    babelHelpers.classCallCheck(this, DisplayScheduleEntitiesList);
	    babelHelpers.defineProperty(this, "entityList", []);
	    babelHelpers.defineProperty(this, "suitableEntityList", []);
	    babelHelpers.defineProperty(this, "entity", undefined);
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
	    if (!options.scheduleCouplesList) {
	      throw new Error("CouplesList: schedule couples list in not included");
	    }
	    this.scheduleCouplesList = options.scheduleCouplesList;
	    this.dataSourceIsDb = dataSourceIsDb;
	    this.entityList = [];
	    this.suitableEntityList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(DisplayScheduleEntitiesList, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      var entityInfo = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
	      var searchInput = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
	      if (typeof searchInput === 'string' || searchInput instanceof String) {
	        this.searchInList(searchInput);
	        this.render(false);
	        return;
	      }
	      if (entityInfo.length !== 0) {
	        this.entity = entityInfo.entity;
	        this.entityId = entityInfo.entityId;
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
	      if (String.length === 0) {
	        this.suitableEntityList = this.entityList;
	        return;
	      }
	      this.entityList.forEach(function (entity) {
	        if (entity['NAMING'].toLowerCase().includes(searchInput.toLowerCase())) {
	          suitableEntityList.push(entity);
	        }
	      });
	      this.suitableEntityList = suitableEntityList;
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
	      var isStateChanged = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
	      this.rootNode.innerHTML = '';
	      if (this.suitableEntityList.length === 0) {
	        var message = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<div class=\"dropdown-item\">\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t"])), main_core.Loc.getMessage('EMPTY_ENTITY_LIST'));
	        this.rootNode.appendChild(message);
	        return;
	      }
	      this.suitableEntityList.forEach(function (entity) {
	        var entityLink;
	        var linkPrefix = '';
	        if (!_this3.dataSourceIsDb) {
	          linkPrefix = '/scheduling/preview';
	        }
	        if (_this3.currentEntity) {
	          entityLink = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a href=\"", "/", "/", "/\"\n\t\t\t\tclass=\"dropdown-item ", "\">\n\t\t\t\t", "\n\t\t\t\t</a>\n\t\t\t"])), linkPrefix, Validator.escapeHTML(_this3.entity), entity['ID'], entity['ID'] === _this3.currentEntity['ID'] ? 'is-active' : '', Validator.escapeHTML(entity['NAMING']));
	        } else {
	          if (isStateChanged) {
	            document.getElementById('entity-selection-button').placeholder = main_core.Loc.getMessage('SELECT_' + _this3.locEntity);
	            document.getElementById('entity-selection-button').value = '';
	          }
	          entityLink = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a href=\"", "/", "/", "/\"\n\t\t\t\tclass=\"dropdown-item\">", "\n\t\t\t\t</a>\n\t\t\t"])), linkPrefix, Validator.escapeHTML(_this3.entity), entity['ID'], Validator.escapeHTML(entity['NAMING']));
	        }
	        _this3.rootNode.appendChild(entityLink);
	        entityLink.addEventListener('click', function (event) {
	          event.preventDefault();
	          _this3.entityList.forEach(function (entity) {
	            if (entity['NAMING'] === entityLink.textContent) {
	              _this3.currentEntity = entity;
	              _this3.entityId = entity['ID'];
	            }
	          });
	          var dropdowns = document.querySelectorAll('.dropdown-item');
	          dropdowns.forEach(function (dropdown) {
	            dropdown.classList.remove('is-active');
	          });
	          entityLink.classList.add('is-active');
	          document.getElementById('entity-selection-button').placeholder = main_core.Loc.getMessage(_this3.locEntity) + ' ' + Validator.escapeHTML(entityLink.textContent);
	          document.getElementById('entity-selection-button').value = '';
	          if (history.pushState) {
	            var newUrl = entityLink.href;
	            window.history.pushState({
	              path: newUrl
	            }, '', newUrl);
	          }
	          _this3.scheduleCouplesList.extractEntityFromUrl();
	          _this3.scheduleCouplesList.reload();
	          _this3.reload();
	        });
	      });
	    }
	  }]);
	  return DisplayScheduleEntitiesList;
	}();

	exports.DisplayScheduleEntitiesList = DisplayScheduleEntitiesList;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
