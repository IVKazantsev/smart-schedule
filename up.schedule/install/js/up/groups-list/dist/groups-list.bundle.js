/* eslint-disable */
this.BX = this.BX || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject;
	var GroupsList = /*#__PURE__*/function () {
	  function GroupsList() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, GroupsList);
	    babelHelpers.defineProperty(this, "currentGroup", {});
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('GroupsList: options.rootNodeId required');
	    }
	    if (main_core.Type.isStringFilled(options.groups)) {
	      this.groups = options.groups;
	    } else {
	      throw new Error('GroupsList: options.entity required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("GroupsList: element with id = \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.groupsList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(GroupsList, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadList().then(function (data) {
	        _this.groupsList = data.groups;
	        //this.currentGroup = data.currentGroup;

	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:schedule.api.groupList.getGroupList', {
	          data: {}
	        }).then(function (response) {
	          var groupsList = response.data.groups;
	          resolve(groupsList);
	        })["catch"](function (error) {
	          reject(error);
	        });
	      });
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      this.rootNode.innerHTML = '';
	      var currentGroupTitle = babelHelpers["typeof"](this.currentGroup) === undefined ? '' : this.currentGroup.title;
	      var groupsContainerNode = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<div class=\"dropdown-trigger group-selection-trigger is-60-height-child\">\n\t\t\t\t\t\t<button id=\"group-selection-button\" class=\"button is-fullwidth is-60-height-child\" aria-haspopup=\"true\" aria-controls=\"dropdown-menu\">\n\t\t\t\t\t\t\t<span> ", " </span>\n\t\t\t\t\t\t</button>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"dropdown-menu\" id=\"dropdown-menu\" role=\"menu\">\n\t\t\t\t\t\t<div class=\"dropdown-content\">\n\t\t\t\t\t\t\t<?php foreach ($arResult['GROUPS'] as $group): ?>\n\t\t\t\t\t\t\t\t<a href=\"/group/<?= $group->getId() ?>/\" class=\"dropdown-item <?= ($group->getId() === $arResult['CURRENT_GROUP_ID']) ? 'is-active' : '' ?>\"><?= htmlspecialcharsbx($group->getTitle()) ?></a>\n\t\t\t\t\t\t\t<?php endforeach; ?>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t"])), currentGroupTitle);
	    }
	  }]);
	  return GroupsList;
	}();

	exports.GroupsList = GroupsList;

}((this.BX[''] = this.BX[''] || {}),BX));
