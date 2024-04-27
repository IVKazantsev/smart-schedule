/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject, _templateObject2;
	var EntityList = /*#__PURE__*/function () {
	  function EntityList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, EntityList);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('EntityList: options.rootNodeId required');
	    }
	    if (main_core.Type.isStringFilled(options.entity)) {
	      this.entity = options.entity;
	    } else {
	      throw new Error('EntityList: options.entity required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("EntityList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.entityList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(EntityList, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadList().then(function (entityList) {
	        _this.entityList = entityList;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      var _this2 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.adminPanel.get' + _this2.entity + 'List', {
	          data: {}
	        }).then(function (response) {
	          var entityList = response.data.entityList;
	          resolve(entityList);
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
	      var containerContent;
	      switch (this.entity) {
	        case 'subject':
	          containerContent = "\n\t\t\t\t\t<div class=\"column is-11 is-60-height\">\n\t\t\t\t\t\t\u041D\u0430\u0437\u0432\u0430\u043D\u0438\u0435\n\t\t\t\t\t</div>\n\t\t\t\t";
	          break;
	        case 'user':
	          containerContent = "\n\t\t\t\t\t<div class=\"column is-4 is-60-height\">\n\t\t\t\t\t\t\u0418\u043C\u044F\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-4 is-60-height\">\n\t\t\t\t\t\t\u041F\u043E\u0447\u0442\u0430\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-3 is-60-height\">\n\t\t\t\t\t\t\u0420\u043E\u043B\u044C\n\t\t\t\t\t</div>\n\t\t\t\t";
	          break;
	        case 'group':
	          containerContent = "\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t\u041D\u0430\u0437\u0432\u0430\u043D\u0438\u0435\n\t\t\t\t\t</div>\n\t\t\t\t";
	          break;
	        case 'audience':
	          containerContent = "\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t\u041D\u043E\u043C\u0435\u0440\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t\u0422\u0438\u043F\n\t\t\t\t\t</div>\n\t\t\t\t";
	          break;
	        case 'audienceType':
	          containerContent = "\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t\u041D\u0430\u0437\u0432\u0430\u043D\u0438\u0435\n\t\t\t\t\t</div>\n\t\t\t\t";
	      }
	      var entitiesContainerNode = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<div class=\"box is-flex is-align-items-center is-flex-direction-column\">\n\t\t\t\t<div class=\"columns is-60-height is-fullwidth title-of-table\">\n\t\t\t\t\t<div class=\"column is-60-height is-1\">\n\t\t\t\t\t\tID\n\t\t\t\t\t</div>\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t"])), containerContent);
	      this.entityList.forEach(function (entityData) {
	        var entityNode = main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a class=\"columns is-fullwidth is-60-height button has-text-left\" href=\"/admin/edit/", "/", "/\">\n\t\t\t\t\t", "\n\t\t\t\t</a>\n\t\t\t"])), _this3.entity, entityData.ID, _this3.getEntityNodeContent(entityData));
	        entitiesContainerNode.appendChild(entityNode);
	      });
	      this.rootNode.appendChild(entitiesContainerNode);
	    }
	  }, {
	    key: "getEntityNodeContent",
	    value: function getEntityNodeContent(entityData) {
	      switch (this.entity) {
	        case 'subject':
	          return "\n\t\t\t\t\t<div class=\"column is-1\">\n\t\t\t\t\t\t".concat(entityData.ID, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-11\">\n\t\t\t\t\t\t").concat(entityData.TITLE, "\n\t\t\t\t\t</div>\n\t\t\t\t");
	        case 'user':
	          return "\n\t\t\t\t\t<div class=\"column is-1\">\n\t\t\t\t\t\t".concat(entityData.ID, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-4\">\n\t\t\t\t\t\t").concat(entityData.NAME, " ").concat(entityData.LAST_NAME, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-4\">\n\t\t\t\t\t\t").concat(entityData.EMAIL ? entityData.EMAIL : 'Отсутствует', "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-3\">\n\t\t\t\t\t\t").concat(entityData.ROLE, "\n\t\t\t\t\t</div>\n\t\t\t\t");
	        case 'group':
	          return "\n\t\t\t\t\t<div class=\"column is-1\">\n\t\t\t\t\t\t".concat(entityData.ID, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column\">\n\t\t\t\t\t\t").concat(entityData.TITLE, "\n\t\t\t\t\t</div>\n\t\t\t\t");
	        case 'audience':
	          return "\n\t\t\t\t\t<div class=\"column is-1\">\n\t\t\t\t\t\t".concat(entityData.ID, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column\">\n\t\t\t\t\t\t").concat(entityData.NUMBER, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column\">\n\t\t\t\t\t\t").concat(entityData.UP_SCHEDULE_MODEL_AUDIENCE_AUDIENCE_TYPE_TITLE, "\n\t\t\t\t\t</div>\n\t\t\t\t");
	        case 'audienceType':
	          return "\n\t\t\t\t\t<div class=\"column is-1\">\n\t\t\t\t\t\t".concat(entityData.ID, "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column\">\n\t\t\t\t\t\t").concat(entityData.TITLE, "\n\t\t\t\t\t</div>\n\t\t\t\t");
	      }
	    }
	  }]);
	  return EntityList;
	}();

	exports.EntityList = EntityList;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
