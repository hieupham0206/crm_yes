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
/******/ 	return __webpack_require__(__webpack_require__.s = 123);
/******/ })
/************************************************************************/
/******/ ({

/***/ 123:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(124);


/***/ }),

/***/ 124:
/***/ (function(module, exports) {

$(function () {
    var $app = $('#app');
    var tableHistoryCall = $('#table_history_calls').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('history_calls.table'),
            data: function data(q) {
                q.filters = JSON.stringify($('#history_calls_search_form').serializeArray());
            }
        }),
        conditionalPaging: true,
        info: true,
        lengthChange: true,
        'columnDefs': [{
            'targets': [0],
            'visible': false
        }]
    });
    $app.on('click', '.btn-delete', function () {
        tableHistoryCall.actionDelete({ btnDelete: $(this) });
    });
    $('#history_calls_search_form').on('submit', function () {
        tableHistoryCall.reload();
        return false;
    });
    $('#btn_reset_filter').on('click', function () {
        $('#history_calls_search_form').resetForm();
        tableHistoryCall.reload();
    });

    //Export tools
    $('#btn_export_excel').on('click', function () {
        tableHistoryCall.exportExcel();
    });
    $('#btn_export_pdf').on('click', function () {
        tableHistoryCall.exportPdf();
    });
    //Quick actions
    $('#link_delete_selected_rows').on('click', function () {
        var ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues();

        if (ids.length > 0) {
            tableHistoryCall.actionDelete({ btnDelete: $(this), params: { ids: ids } });
        }
    });
});

/***/ })

/******/ });