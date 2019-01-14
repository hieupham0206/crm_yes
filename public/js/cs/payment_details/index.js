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
/******/ 	return __webpack_require__(__webpack_require__.s = 95);
/******/ })
/************************************************************************/
/******/ ({

/***/ 95:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(96);


/***/ }),

/***/ 96:
/***/ (function(module, exports) {

$(function () {
    var $app = $('#app');
    var tablePaymentDetail = $('#table_payment_details').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('payment_details.table'),
            data: function data(q) {
                q.filters = JSON.stringify($('#payment_details_search_form').serializeArray());
            }
        }),
        'columnDefs': [{
            'targets': [-1],
            'searchable': false,
            'orderable': false,
            'visible': true,
            'width': '10%'
        }],
        "ordering": false,
        conditionalPaging: true,
        info: true,
        lengthChange: true
    });
    $app.on('click', '.btn-delete', function () {
        tablePaymentDetail.actionDelete({ btnDelete: $(this) });
    });
    $('#payment_details_search_form').on('submit', function () {
        tablePaymentDetail.reload();
        return false;
    });
    $('#btn_reset_filter').on('click', function () {
        $('#payment_details_search_form').resetForm();
        tablePaymentDetail.reload();
    });

    //Export tools
    $('#btn_export_excel').on('click', function () {
        tablePaymentDetail.exportExcel();
    });
    $('#btn_export_pdf').on('click', function () {
        tablePaymentDetail.exportPdf();
    });
    //Quick actions
    $('#link_delete_selected_rows').on('click', function () {
        var ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues();

        if (ids.length > 0) {
            tablePaymentDetail.actionDelete({ btnDelete: $(this), params: { ids: ids } });
        }
    });
});

/***/ })

/******/ });