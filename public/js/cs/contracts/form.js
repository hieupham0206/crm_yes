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
/******/ 	return __webpack_require__(__webpack_require__.s = 89);
/******/ })
/************************************************************************/
/******/ ({

/***/ 89:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(90);


/***/ }),

/***/ 90:
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

$(function () {
	var isConfirm = $('#contracts_form').data('confirm');

	$('#contracts_form').validate({
		submitHandler: isConfirm ? function (form, e) {
			window.blockPage();
			e.preventDefault();

			$(form).confirmation(function (result) {
				if (result && (typeof result === 'undefined' ? 'undefined' : _typeof(result)) === 'object' && result.value) {
					$(form).submitForm().then(function () {
						location.href = route('contracts.index');
					});
				} else {
					window.unblock();
				}
			});
		} : false
	});

	$('#select_province').select2Ajax({
		data: function data(q) {
			q.provinceIds = [24, 28, 30];
		}
	});

	$('#select_payment_method').on('change', function () {
		if ($(this).val() !== '') {
			$('#select_bank').prop('disabled', false).empty().trigger('change');
			axios.get(route('payment_costs.get_bank'), {
				params: {
					method: $(this).val()
				}
			}).then(function (result) {
				var items = result['data']['items'];

				var _iteratorNormalCompletion = true;
				var _didIteratorError = false;
				var _iteratorError = undefined;

				try {
					for (var _iterator = items[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
						var item = _step.value;

						var option = new Option(item.bank_name, item.cost, false, false);
						$('#select_bank').append(option).trigger('change');
					}
				} catch (err) {
					_didIteratorError = true;
					_iteratorError = err;
				} finally {
					try {
						if (!_iteratorNormalCompletion && _iterator.return) {
							_iterator.return();
						}
					} finally {
						if (_didIteratorError) {
							throw _iteratorError;
						}
					}
				}
			}).catch(function (e) {
				return console.log(e);
			}).finally(function () {
				window.unblock();
			});
		} else {
			$('#select_bank').prop('disabled', true);
		}
	});

	$('#select_bank').on('change', function () {
		if ($(this).val() !== '') {
			$('#txt_cost').val($(this).val());
		} else {
			$('#txt_cost').val('');
		}
	});

	var tablePaymentDetail = $('#table_payment_detail').DataTable({
		paging: false,
		'columnDefs': [{ 'targets': [-1], 'orderable': false, 'width': '5%' }]
	});

	$('#btn_add_payment_detail').on('click', function () {
		var paymentTime = parseInt($('#txt_payment_time').val());
		var rows = [],
		    $leftAmount = 0;
		var totalAmount = $('#txt_amount').val();
		var firstPaid = $('#txt_total_paid_deal').val();

		if (totalAmount !== '') {
			$leftAmount = numeral((numeral(totalAmount).value() - numeral(firstPaid).value()) / numeral(paymentTime).value()).format('0,00');
		}

		for (var i = 0; i < paymentTime; i++) {
			rows.push(['<input class="form-control txt-payment-date" name="PaymentDetail[payment_date][' + i + '][]" type="text" autocomplete="off">', '<input class="form-control txt-total-paid-deal" name="PaymentDetail[total_paid_deal][' + i + '][]" value="' + $leftAmount + '" type="text" autocomplete="off">']);
		}
		tablePaymentDetail.rows().remove();
		tablePaymentDetail.rows.add(rows).draw(false);
		$('.txt-payment-date').datepicker();
		$('.txt-total-paid-deal').numeric();
	});

	$('.identity-number').numeric({
		allowDecimal: false,
		allowMinus: false, // Allow the - sign
		allowThouSep: false // Allow the thousands separator, default is the comma eg 12,000
	});
});

/***/ })

/******/ });