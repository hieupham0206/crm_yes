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
/******/ 	return __webpack_require__(__webpack_require__.s = 93);
/******/ })
/************************************************************************/
/******/ ({

/***/ 93:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(94);


/***/ }),

/***/ 94:
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

$(function () {
	var isConfirm = $('#payment_details_form').data('confirm');

	$('#payment_details_form').validate({
		submitHandler: isConfirm ? function (form, e) {
			window.blockPage();
			e.preventDefault();

			$(form).confirmation(function (result) {
				if (result && (typeof result === 'undefined' ? 'undefined' : _typeof(result)) === 'object' && result.value) {
					$(form).submitForm().then(function () {
						location.href = route('payment_details.index');
					});
				} else {
					window.unblock();
				}
			});
		} : false
	});

	function loadBankBasedOnPaymentMethod(paymentMethod, selectSelector, textSelector) {
		if (paymentMethod !== '') {
			selectSelector.prop('disabled', false).empty().trigger('change');
			axios.get(route('payment_costs.get_bank'), {
				params: {
					method: paymentMethod
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
						selectSelector.append(option).trigger('change');

						textSelector.val(selectSelector.select2('data')[0]['text']);
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
			selectSelector.prop('disabled', true);
		}
	}

	$('#select_payment_method').on('change', function () {
		loadBankBasedOnPaymentMethod($(this).val(), $('#select_bank'), $('#txt_bank_name'));
	});

	$('#select_payment_installment_id').on('change', function () {
		loadBankBasedOnPaymentMethod($(this).val(), $('#select_bank_installment'), $('#txt_bank_name_installment'));
	});

	$('#select_bank, #select_bank_installment').on('change', function () {
		var currentCost = $('#txt_cost').val();
		var newCost = $(this).val();

		if (newCost !== '' && newCost !== null) {
			if (currentCost !== '') {
				newCost = parseFloat(newCost) + parseFloat(currentCost);
			}
			$('#txt_cost').val(numeral(newCost).format('0,00'));
		}
		// else {
		// 	$('#txt_cost').val('')
		// }
	});
});

/***/ })

/******/ });