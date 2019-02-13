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
/******/ 	return __webpack_require__(__webpack_require__.s = 133);
/******/ })
/************************************************************************/
/******/ ({

/***/ 133:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(134);


/***/ }),

/***/ 134:
/***/ (function(module, exports) {

$(function () {
	var $body = $('body');
	var tableCommissionRole = $('#table_commission_role_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('commission_roles.table'),
			data: function data(q) {
				q.filters = JSON.stringify($('#commission_detail_role_search_form').serializeArray());
			}
		}),
		conditionalPaging: true,
		info: false,
		lengthChange: false,
		iDisplayLength: 20
	});
	$('#commission_detail_role_search_form').on('submit', function () {
		tableCommissionRole.reload();
		return false;
	});
	$('#btn_reset_filter').on('click', function () {
		$('#commission_detail_role_search_form').resetForm();
		tableCommissionRole.reload();
	});

	//Export tools
	$('#btn_export_excel').on('click', function () {
		tableCommissionRole.exportExcel();
	});
	$('#btn_export_pdf').on('click', function () {
		tableCommissionRole.exportPdf();
	});

	$body.on('click', '.btn-save-commission-role', function () {
		window.blockPage();

		var url = $(this).data('url');
		var roleId = $(this).data('role-id');
		var spec = $(this).data('spec');
		var commissionRoleId = $(this).data('commission-role-id');
		var tr = $(this).parents('tr');

		var percentCommission = tr.find('.txt-percent-commission').val();
		var level = tr.find('.txt-level').val();
		var bonusCommission = tr.find('.txt-bonus-commission').val();
		var dealCompleted = tr.find('.txt-deal-completed').val();

		axios.post(url, {
			percentCommission: percentCommission,
			level: level,
			bonusCommission: bonusCommission,
			dealCompleted: dealCompleted,
			roleId: roleId,
			commissionRoleId: commissionRoleId,
			spec: spec
		}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			window.unblock();
		});
	});
});

/***/ })

/******/ });