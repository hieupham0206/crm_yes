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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 65);
/******/ })
/************************************************************************/
/******/ ({

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(66);


/***/ }),

/***/ 66:
/***/ (function(module, exports) {

$(function () {
	function initLoginTime() {
		var _this = this;

		var loginTimer = new Timer();
		loginTimer.addEventListener('secondsUpdated', function () {
			_this.html(loginTimer.getTimeValues().toString());
		});

		var timeInSecond = this.data('time-in-second');
		console.log(timeInSecond);
		if (timeInSecond > 0) {
			loginTimer.start({ precision: 'seconds', startValues: { seconds: timeInSecond } });
		}
	}

	$('.span-login-time').each(function () {
		if ($(this).data('is-online')) {
			initLoginTime.call($(this));
		}
	});

	function initCallTime() {
		var _this2 = this;

		var callTimer = new Timer();
		callTimer.addEventListener('secondsUpdated', function () {
			_this2.html(callTimer.getTimeValues().toString());
		});

		var timeInSecond = this.data('call-time-in-second');
		if (timeInSecond !== '0') {
			callTimer.start({ precision: 'seconds', startValues: { seconds: timeInSecond } });
		}
	}

	// $('.span-call-time').each(function() {
	// 	initCallTime.call($(this))
	// })

	function loadSectionMonitor() {
		var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

		blockPage();

		axios.get(route('monitor_sale.section_monitor'), {
			params: params
		}).then(function (result) {
			$('#section_monitor_sale').html(result.data);

			$('.span-login-time').each(function () {
				if ($(this).data('is-online')) {
					initLoginTime.call($(this));
				}
			});
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	}

	$('.btn-filter-call').on('click', function () {
		var filter = $(this).data('filter');

		loadSectionMonitor({
			filter: filter
		});
	});

	$('body').on('click', '.link-form-detail', function () {
		var url = $(this).data('url');

		$('#modal_md').showModal({ url: url, method: 'get' });
	});

	$('#modal_md').on('shown.bs.modal', function () {
		var className = $('#txt_form_modal_bg').val();

		$(this).addClass(className);

		$('.span-call-time').each(function () {
			initCallTime.call($(this));
		});
	});

	setInterval(function () {
		loadSectionMonitor();
	}, 5000);
});

/***/ })

/******/ });