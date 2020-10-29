/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/editor-app.js":
/*!************************************!*\
  !*** ./resources/js/editor-app.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery;
elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
  /*if ( 'section' !== model.attributes.elType && 'column' !== model.attributes.elType ) {
      return;
  }*/
  var $element = view.$el.find('.elementor-selector');

  if ($element.length) {
    $element.click(function () {
      alert('Some Message');
    });
  }
});
var fael_editor_opts = {
  'fael_form': {
    'form': {}
  },
  'fael_user_list': {},
  'fael_post_author': {
    'value': {}
  },
  'fael_post_list': {
    'post_type': {},
    'post_status': {}
  },
  'fael_post_status': {
    'value': {}
  },
  'fael_submit': {
    'post_type': {}
  }
};
var fael_editor_render = {
  fael_user_list: {},
  fael_form: {
    form: function form(value) {
      var options = '';

      for (var k in fael_editor_opts.fael_form.form) {
        options = options + '<option value="' + k + '" ' + (value.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts.fael_form.form[k] + '</option>';
      }

      $('select[data-setting="form"]').html(options);
      options = '';
    }
  },
  fael_post_author: {
    value: function value(_value) {
      var widget_name = 'fael_post_author';
      var field = 'value';
      var options = '';

      for (var k in fael_editor_opts[widget_name][field]) {
        options = options + '<option value="' + k + '" ' + (_value.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts[widget_name][field][k] + '</option>';
      }

      $('select[data-setting="' + field + '"]').html(options);
      options = '';
    }
  },
  fael_post_list: {
    post_type: function post_type(value) {
      var widget_name = 'fael_post_list';
      var field = 'post_type';
      var options = '';

      for (var k in fael_editor_opts[widget_name][field]) {
        options = options + '<option value="' + k + '" ' + (value.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts[widget_name][field][k] + '</option>';
      }

      $('select[data-setting="' + field + '"]').html(options);
      options = '';
    },
    post_status: function post_status(value) {
      var widget_name = 'fael_post_list';
      var field = 'post_status';
      var options = '';

      for (var k in fael_editor_opts[widget_name][field]) {
        options = options + '<option value="' + k + '" ' + (value.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts[widget_name][field][k] + '</option>';
      }

      $('select[data-setting="' + field + '"]').html(options);
      options = '';
    }
  },
  fael_post_status: {
    value: function value(_value2) {
      var widget_name = 'fael_post_status';
      var field = 'value';
      var options = '';

      for (var k in fael_editor_opts[widget_name][field]) {
        options = options + '<option value="' + k + '" ' + (_value2.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts[widget_name][field][k] + '</option>';
      }

      $('select[data-setting="' + field + '"]').html(options);
      options = '';
    }
  },
  fael_submit: {
    post_type: function post_type(value) {
      var widget_name = 'fael_submit';
      var field = 'post_type';
      var options = '';

      for (var k in fael_editor_opts[widget_name][field]) {
        options = options + '<option value="' + k + '" ' + (value.indexOf(k) != -1 ? 'selected' : '') + '>' + fael_editor_opts[widget_name][field][k] + '</option>';
      }

      console.log(options);
      console.log($('select[data-setting="' + field + '"]').length);
      $('select[data-setting="' + field + '"]').html(options);
    }
  }
};
elementor.hooks.addAction('panel/open_editor/widget/fael_user_list', function (panel, model, view) {
  var $elName = 'fael_user_list';
  fael_populate_field_data($elName, model);
});
elementor.hooks.addAction('panel/open_editor/widget/fael_post_list', function (panel, model, view) {
  var $elName = 'fael_post_list';
  fael_populate_field_data($elName, model);
});
elementor.hooks.addAction('panel/open_editor/widget/fael_form', function (panel, model, view) {
  var $elName = 'fael_form';
  fael_populate_field_data($elName, model);
});
elementor.hooks.addAction('panel/open_editor/widget/fael_post_author', function (panel, model, view) {
  var $elName = 'fael_post_author';
  fael_populate_field_data($elName, model);
});
elementor.hooks.addAction('panel/open_editor/widget/fael_post_status', function (panel, model, view) {
  var $elName = 'fael_post_status';
  fael_populate_field_data($elName, model);
});
elementor.hooks.addAction('panel/open_editor/widget/fael_submit', function (panel, model, view) {
  var $elName = 'fael_submit';
  fael_populate_field_data($elName, model);
});

function fael_populate_field_data($elName, model) {
  var fetch_data = [];

  for (var f in fael_editor_opts[$elName]) {
    if (!Object.keys(fael_editor_opts[$elName][f]).length) {
      fetch_data.push(f);
    }
  }

  if (Object.keys(fetch_data).length) {
    console.log(Object.keys(fetch_data));
    $.post(ajaxurl, {
      action: 'fael_fetch_data',
      widget: $elName,
      fetch_data: fetch_data
    }, function (res) {
      var data = res.data.data;

      for (var field in data) {
        if (typeof fael_editor_opts[$elName][field] != 'undefined') {
          fael_editor_opts[$elName][field] = data[field];
        }
      }

      for (var field in fael_editor_render[$elName]) {
        fael_editor_render[$elName][field](model.attributes.settings.attributes[field]);
      }
    });
  } else {
    for (var field in fael_editor_render[$elName]) {
      fael_editor_render[$elName][field](model.attributes.settings.attributes[field]);
    }
  }
}

/***/ }),

/***/ 1:
/*!******************************************!*\
  !*** multi ./resources/js/editor-app.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\xampp-new\htdocs\ufel\wp-content\plugins\user-frontend-for-elementor\resources\js\editor-app.js */"./resources/js/editor-app.js");


/***/ })

/******/ });