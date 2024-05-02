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

	var _templateObject, _templateObject2, _templateObject3, _templateObject4, _templateObject5, _templateObject6, _templateObject7, _templateObject8, _templateObject9, _templateObject10, _templateObject11, _templateObject12, _templateObject13, _templateObject14, _templateObject15, _templateObject16, _templateObject17, _templateObject18, _templateObject19, _templateObject20, _templateObject21, _templateObject22, _templateObject23, _templateObject24, _templateObject25;
	var EntityList = /*#__PURE__*/function () {
	  function EntityList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, EntityList);
	    babelHelpers.defineProperty(this, "entityNode", {
	      'subject': {
	        'header': main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div class=\"column is-11 is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('TITLE')),
	        'content': function content(entityData) {
	          return main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<div class=\"column is-1 admin-entity-list-item\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column is-11 admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t"])), entityData.ID, Validator.escapeHTML(entityData.TITLE));
	        }
	      },
	      'user': {
	        'header': main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div class=\"column is-4 is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-4 is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-3 is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('NAME'), main_core.Loc.getMessage('EMAIL'), main_core.Loc.getMessage('ROLE')),
	        'content': function content(entityData) {
	          return main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<div class=\"column is-1 admin-entity-list-item\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column is-4 admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", " ", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column is-4 admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column is-3 admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t"])), entityData.ID, Validator.escapeHTML(entityData.NAME), Validator.escapeHTML(entityData.LAST_NAME), Validator.escapeHTML(entityData.EMAIL) ? Validator.escapeHTML(entityData.EMAIL) : 'Отсутствует', entityData.ROLE);
	        }
	      },
	      'group': {
	        'header': main_core.Tag.render(_templateObject5 || (_templateObject5 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('TITLE')),
	        'content': function content(entityData) {
	          return main_core.Tag.render(_templateObject6 || (_templateObject6 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<div class=\"column is-1 admin-entity-list-item\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t"])), entityData.ID, Validator.escapeHTML(entityData.TITLE));
	        }
	      },
	      'audience': {
	        'header': main_core.Tag.render(_templateObject7 || (_templateObject7 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('NUMBER'), main_core.Loc.getMessage('TYPE')),
	        'content': function content(entityData) {
	          return main_core.Tag.render(_templateObject8 || (_templateObject8 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<div class=\"column is-1 admin-entity-list-item\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t"])), entityData.ID, Validator.escapeHTML(entityData.NUMBER), Validator.escapeHTML(entityData.UP_SCHEDULE_MODEL_AUDIENCE_AUDIENCE_TYPE_TITLE));
	        }
	      },
	      'audienceType': {
	        'header': main_core.Tag.render(_templateObject9 || (_templateObject9 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<div class=\"column is-60-height\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t</div>\n\t\t\t\t\t"])), main_core.Loc.getMessage('TITLE')),
	        'content': function content(entityData) {
	          return main_core.Tag.render(_templateObject10 || (_templateObject10 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t\t<div class=\"column is-1 admin-entity-list-item\">\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"column admin-entity-list-item\">\n\t\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t"])), entityData.ID, Validator.escapeHTML(entityData.TITLE));
	        }
	      }
	    });
	    babelHelpers.defineProperty(this, "entity", undefined);
	    babelHelpers.defineProperty(this, "rootNodeId", undefined);
	    babelHelpers.defineProperty(this, "rootNode", undefined);
	    babelHelpers.defineProperty(this, "entityList", undefined);
	    babelHelpers.defineProperty(this, "pageNumber", undefined);
	    babelHelpers.defineProperty(this, "doesNextPageExist", undefined);
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
	      var pageNumber = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
	      var searchInput = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      this.loadList(pageNumber, searchInput).then(function (data) {
	        _this.entityList = data.entityList;
	        _this.pageNumber = data.pageNumber;
	        _this.doesNextPageExist = data.doesNextPageExist;
	        _this.countOfEntities = data.countOfEntities;
	        _this.entityPerPage = data.entityPerPage;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      var _this2 = this;
	      var pageNumber = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
	      var searchInput = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.adminPanel.getEntityList', {
	          data: {
	            entityName: _this2.entity,
	            pageNumber: pageNumber,
	            searchInput: searchInput
	          }
	        }).then(function (response) {
	          var data = response.data;
	          console.log(data);
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
	      this.rootNode.innerHTML = '';
	      var containerContent = this.entityNode[this.entity]['header'];
	      var entitiesContainerNode = main_core.Tag.render(_templateObject11 || (_templateObject11 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<div class=\"box is-flex is-align-items-center is-flex-direction-column\">\n\t\t\t\t<div class=\"columns is-60-height is-fullwidth title-of-table\">\n\t\t\t\t\t<div class=\"column is-60-height is-1\">\n\t\t\t\t\t\tID\n\t\t\t\t\t</div>\n\t\t\t\t\t", "\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t"])), containerContent);
	      this.entityList.forEach(function (entityData) {
	        var entityNode = main_core.Tag.render(_templateObject12 || (_templateObject12 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t<a class=\"columns is-fullwidth is-60-height button has-text-left\" href=\"/admin/edit/", "/", "/\">\n\t\t\t\t\t", "\n\t\t\t\t</a>\n\t\t\t"])), _this3.entity, entityData.ID, _this3.entityNode[_this3.entity]['content'](entityData));
	        entitiesContainerNode.appendChild(entityNode);
	      });
	      this.rootNode.appendChild(entitiesContainerNode);

	      // Пагинация

	      var previousPageButton = main_core.Tag.render(_templateObject13 || (_templateObject13 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<button class=\"pagination-previous ", "\">&#60;</button>\n\t\t"])), this.pageNumber > 1 ? '' : 'is-disabled');
	      var nextPageButton = main_core.Tag.render(_templateObject14 || (_templateObject14 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<button class=\"pagination-next ", "\">&#62;</button>\n\t\t"])), this.doesNextPageExist === true ? '' : 'is-disabled');
	      var firstPageButton = '';
	      if (this.pageNumber > 2) {
	        firstPageButton = main_core.Tag.render(_templateObject15 || (_templateObject15 = babelHelpers.taggedTemplateLiteral(["<button class=\"pagination-link\">1</button>"])));
	        firstPageButton.addEventListener('click', function () {
	          _this3.reload(1);
	        });
	      }
	      var previousPageWithNumber = '';
	      if (this.pageNumber > 1) {
	        previousPageWithNumber = main_core.Tag.render(_templateObject16 || (_templateObject16 = babelHelpers.taggedTemplateLiteral(["<button class=\"pagination-link\">", "</button>"])), this.pageNumber - 1);
	        previousPageButton.addEventListener('click', function () {
	          _this3.reload(_this3.pageNumber - 1);
	        });
	        previousPageWithNumber.addEventListener('click', function () {
	          _this3.reload(_this3.pageNumber - 1);
	        });
	      }
	      var nextPageWithNumber = '';
	      if (this.doesNextPageExist === true) {
	        nextPageWithNumber = main_core.Tag.render(_templateObject17 || (_templateObject17 = babelHelpers.taggedTemplateLiteral(["<button class=\"pagination-link\">", "</button>"])), this.pageNumber + 1);
	        nextPageButton.addEventListener('click', function () {
	          _this3.reload(_this3.pageNumber + 1);
	        });
	        nextPageWithNumber.addEventListener('click', function () {
	          _this3.reload(_this3.pageNumber + 1);
	        });
	      }
	      var countOfPages = Math.ceil(this.countOfEntities / this.entityPerPage);
	      var lastPageButton = '';
	      if (this.pageNumber + 1 < countOfPages) {
	        lastPageButton = main_core.Tag.render(_templateObject18 || (_templateObject18 = babelHelpers.taggedTemplateLiteral(["<button class=\"pagination-link\">", "</button>"])), countOfPages);
	        lastPageButton.addEventListener('click', function () {
	          _this3.reload(countOfPages);
	        });
	      }
	      var paginationContainer = main_core.Tag.render(_templateObject19 || (_templateObject19 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<nav class=\"pagination\" role=\"navigation\" aria-label=\"pagination\">\n\t\t\t\t", "\n\t\t\t\t", "\n\t\t\t\t<ul class=\"pagination-list\">\n\t\t\t\t\t", "\n\t\t\t\t\t\n\t\t\t\t\t", "\n\t\t\t\t\t\n\t\t\t\t\t", "\n\t\t\t\t\t\n\t\t\t\t\t<li>\n\t\t\t\t\t\t<div class=\"pagination-link is-current\" aria-current=\"page\">", "</div>\n\t\t\t\t\t</li>\n\t\t\t\t\t\n\t\t\t\t\t", "\n\t\t\t\t\t\n\t\t\t\t\t", "\n\t\t\t\t\t\n\t\t\t\t\t", "\n\t\t\t\t</ul>\n\t\t\t</nav>\n\t\t"])), previousPageButton, nextPageButton, firstPageButton !== '' ? main_core.Tag.render(_templateObject20 || (_templateObject20 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</li>"])), firstPageButton) : '', this.pageNumber > 3 ? main_core.Tag.render(_templateObject21 || (_templateObject21 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t<span class=\"pagination-ellipsis\">&hellip;</span>\n\t\t\t\t\t\t</li>"]))) : '', previousPageWithNumber !== '' ? main_core.Tag.render(_templateObject22 || (_templateObject22 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</li>"])), previousPageWithNumber) : '', this.pageNumber, nextPageWithNumber !== '' ? main_core.Tag.render(_templateObject23 || (_templateObject23 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t", "\n\t\t\t\t\t\t</li>"])), nextPageWithNumber) : '', this.pageNumber + 2 < countOfPages ? main_core.Tag.render(_templateObject24 || (_templateObject24 = babelHelpers.taggedTemplateLiteral(["<li>\n\t\t\t\t\t\t<span class=\"pagination-ellipsis\">&hellip;</span>\n\t\t\t\t\t</li>"]))) : '', lastPageButton !== '' ? main_core.Tag.render(_templateObject25 || (_templateObject25 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t", "\n\t\t\t\t\t\t</li>"])), lastPageButton) : '');
	      this.rootNode.appendChild(paginationContainer);
	    }
	  }]);
	  return EntityList;
	}();

	exports.EntityList = EntityList;

}((this.BX.Up.Schedule = this.BX.Up.Schedule || {}),BX));
