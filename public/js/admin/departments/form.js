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
/******/ 	return __webpack_require__(__webpack_require__.s = 81);
/******/ })
/************************************************************************/
/******/ ({

/***/ 81:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(82);


/***/ }),

/***/ 82:
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

$(function () {
	var $btnAddUser = $('#btn_add_user'),
	    $selectUser = $('#select_user'),
	    $selectLeader = $('#select_leader'),
	    $selectManager = $('#select_mananger');

	$('#departments_form').validate({
		submitHandler: $(this).data('confirm') ? function (form, e) {
			window.blockPage();
			e.preventDefault();

			var isConfirm = $(form).data('confirm');
			$(form).confirmation(function (result) {
				if (result && (typeof result === 'undefined' ? 'undefined' : _typeof(result)) === 'object' && result.value) {
					$(form).submitForm({
						data: {
							'isConfirm': isConfirm
						}
					}).then(function () {
						location.href = route('departments.index');
					});
				} else {
					window.unblock();
				}
			});
		} : false
	});

	var tableUserDepartment = $('#table_user_department').DataTable({
		paging: false,
		'columnDefs': [{ 'targets': [-1], 'orderable': false, 'width': '5%' }]
	});

	if ($selectLeader.length > 0) {
		$selectLeader.select2Ajax({
			url: route('users.list'),
			data: function data(q) {
				q.roleId = 5;
				q.restrict = 'leader';
			},
			column: 'username',
			allowClear: false
		});
	}

	if ($selectManager.length > 0) {
		$selectManager.select2Ajax({
			url: route('users.list'),
			data: function data(q) {
				q.roleId = 4;
				q.restrict = 'manager';
			},
			column: 'username',
			allowClear: false
		});
	}

	$selectUser.select2Ajax({
		url: route('users.list'),
		data: function data(q) {
			q.excludeIds = [1].concat(_toConsumableArray($('.txt-user-id').getValues({ parse: 'int' })));
			q.roleId = 6;
		},
		column: 'username',
		allowClear: true
	});

	$btnAddUser.on('click', function () {
		if ($selectUser.val() === '') {
			flash('Vui lòng chọn user', 'danger');
			return;
		}

		var username = $selectUser.select2('data')[0]['username'];
		var userId = $selectUser.val();
		var idx = tableUserDepartment.data().count();

		tableUserDepartment.row.add([username + ('<input type="hidden" name="UserDepartment[user_id][' + idx + '][]" value="' + userId + '" class="txt-user-id">'), 'Tele Marketer <input type="hidden" name="UserDepartment[position][' + idx + '][]" value="1" class="txt-position">', '<button type="button" class="btn-delete-user btn btn-sm btn-danger m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill" title="Delete"><i class="la la-trash"></i></button>']).draw(false);

		$selectUser.val('').trigger('change');
	});

	$('body').on('click', '.btn-delete-user', function () {
		tableUserDepartment.row($(this).parents('tr')).remove().draw(false);
	});
});

/***/ })

/******/ });