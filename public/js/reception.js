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
/******/ 	return __webpack_require__(__webpack_require__.s = 61);
/******/ })
/************************************************************************/
/******/ ({

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(62);


/***/ }),

/***/ 62:
/***/ (function(module, exports) {

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

$(function () {
	var userId = $('#txt_user_id').val();
	var $body = $('body'),
	    $btnShowUp = $('#btn_show_up'),
	    $btnNotShowUp = $('#btn_not_show_up'),
	    $btnQueue = $('#btn_queue'),
	    $btnNotQueue = $('#btn_not_queue'),
	    $btnUpdateLeadInfo = $('#btn_update_lead_info'),
	    $btnReappointment = $('#btn_re_appointment'),
	    $btnSearch = $('#btn_search'),
	    $btnNewLead = $('#btn_new_lead'),
	    $selectTo = $('#select_to'),
	    $selectCs = $('#select_cs'),
	    $selectRep = $('#select_rep');

	var tableAppointment = $('#table_appointment').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('appointments.console.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'code', 'value': $('#txt_voucher_code').val() }, { 'name': 'phone', 'value': $('#txt_phone').val() }]); //, {'name': 'is_queue', 'value': -1}
				q.form = 'reception_console';
			}
		}),
		conditionalPaging: true,
		'columnDefs': []
		// sort: false,
	});
	var tableEventData = $('#table_event_data').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('event_datas.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'code', 'value': $('#txt_voucher_code').val() }, { 'name': 'phone', 'value': $('#txt_phone').val() }]);
			}
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false
	});

	function fetchLead() {
		var leadId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
		var isNew = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
		var appointmentId = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';

		blockPage();
		var fetchRoute = route('appointments.list');
		if (appointmentId === '') {
			fetchRoute = route('leads.list');
		}
		return axios.get(fetchRoute, {
			params: {
				appointmentId: appointmentId,
				leadId: leadId
			}
		}).then(function (result) {
			var items = result.data.items;
			var appointment = items[0];
			var lead = void 0,
			    user = void 0,
			    appointmentDatetime = void 0;

			if (appointmentId !== '') {
				appointment = items[0];
				if (appointment) {
					lead = appointment.lead;
					user = appointment.user;
					appointmentDatetime = appointment.appointment_datetime;
				}
			} else {
				lead = items[0];
				user = lead.user;
			}

			// $('#span_lead_name').text(lead.name)
			// $('#span_lead_email').text(lead.email)
			// $('#span_spouse_name').text(appointment.spouse_name)
			// $('#span_spouse_phone').text(appointment.spouse_phone)
			// $('#span_appointment_datetime').text(appointmentDatetime)
			// $('#span_lead_title').text(lead.title)

			$('#txt_lead_name').val(lead.name);
			$('#txt_lead_email').val(lead.email);
			if (appointment !== undefined) {
				$('#txt_spouse_name').val(appointment.spouse_name);
				$('#txt_spouse_phone').val(appointment.spouse_phone);
				$('#txt_appointment_datetime').val(appointmentDatetime);
			}

			$('#txt_lead_title').val(lead.title);
			if (user) {
				$('#span_tele_marketer').text(user.username);
			}
			$('#span_lead_phone').text(lead.phone);

			$('#txt_lead_id').val(lead.id);
			$('#txt_appointment_id').val(appointmentId);
		}).finally(function () {
			unblock();
		});
	}

	function clearCustomerInfo() {
		$('#span_lead_name').text('');
		$('#span_lead_email').text('');
		$('#span_lead_phone').text('');
		$('#span_lead_title').text('');
		$('#span_tele_marketer').text('');
		$('#span_appointment_datetime').text('');

		$('#txt_lead_id').val('');
		$('#txt_appointment_id').val('');
	}

	function toggleFormEventData() {
		var disabled = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

		if (disabled) {
			$('#event_data_section').hide();
			$('#event_data_form').find('input, textarea').prop('disabled', disabled);
		} else {
			$('#event_data_section').show();
			$('#event_data_form').find('input, textarea').prop('disabled', disabled);
		}
	}

	function clearFormEventData() {
		$('#event_data_form').resetForm();
	}

	function toggleShowUpSection() {
		var isShow = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

		if (isShow) {
			$btnShowUp.prop('disabled', false);
			$btnNotShowUp.prop('disabled', false);
		} else {
			$btnShowUp.prop('disabled', true);
			$btnNotShowUp.prop('disabled', true);
		}
	}

	$selectTo.select2Ajax({
		url: route('users.list'),
		data: function data(q) {
			q.roleId = 8;
		},
		column: 'username'
	});
	$selectRep.select2Ajax({
		url: route('users.list'),
		data: function data(q) {
			q.roleId = 9;
		},
		column: 'username'
	});
	$selectCs.select2Ajax({
		url: route('users.list'),
		data: function data(q) {
			q.roleId = 12;
		},
		column: 'username'
	});

	$('#modal_lg').on('show.bs.modal', function () {
		$('.select').select2();

		$('#select_province').select2Ajax();
		$(this).find('#txt_phone').alphanum({
			allowMinus: false,
			allowLatin: false,
			allowOtherCharSets: false,
			maxLength: 11
		});
	});

	$body.on('click', '.link-lead-name', function () {
		var leadId = $(this).data('lead-id');
		var appointmentId = $(this).data('appointment-id');
		fetchLead(leadId, 0, appointmentId).then(function () {
			// toggleShowUpSection(true)
		});
		$('#txt_lead_id').val(leadId);
	});

	$body.on('click', '.btn-change-event-status', function () {
		var message = $(this).data('message');
		var url = $(this).data('url');
		if (url !== '') {
			tableEventData.actionEdit({
				btnEdit: $(this),
				params: {
					state: $(this).data('state')
				},
				message: message
			});
		}
	});

	$body.on('submit', '#new_leads_form', function (e) {
		e.preventDefault();

		var formData = new FormData($(this)[0]);
		formData.append('form', 'reception');

		$(this).submitForm({ url: route('leads.store'), formData: formData }).then(function () {
			$('#modal_lg').modal('hide');
			tableAppointment.reload();
			tableEventData.reload();
		});
	});

	$('#modal_lg').on('shown.bs.modal', function () {
		$('#select_user').select2Ajax();
		$('#select_by_customer').select2Ajax({
			data: function data(q) {
				q.state = 10;
			}
		});
	});

	$body.on('click', '#btn_reappointment', function () {
		var leadId = $('#txt_lead_id').val();
		var url = route('appointments.cancel', $('#txt_appointment_id').val());

		blockPage();
		axios.post(url, {}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			$('#modal_md').showModal({
				url: route('leads.form_change_state', leadId), params: {
					typeCall: 4,
					callId: ''
				}, method: 'get'
			});
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$body.on('click', '.link-event-data', function () {
		var $tr = $(this).parents('tr');
		var eventDataId = $(this).data('event-id');
		var toId = $(this).data('to-id');
		var csId = $(this).data('cs-id');
		var repId = $(this).data('rep-id');
		var appointmentId = $(this).data('appointment-id');

		var toUserName = $tr.find('.txt-to-username').val();
		var csUserName = $tr.find('.txt-cs-username').val();
		var repUserName = $tr.find('.txt-rep-username').val();

		var hasBonus = $(this).data('has-bonus');
		var leadName = $tr.find('.txt-lead-name').val();
		var leadId = $tr.find('.txt-lead-id').val();
		var eventNote = $tr.find('.txt-event-data-note').val();
		var eventCode = $tr.find('.txt-event-data-code').val();
		var eventStateName = $tr.find('.txt-event-data-state').val();
		var queueText = $tr.find('.txt-event-queue-text').val();

		fetchLead('', 0, appointmentId).then(function () {
			// toggleShowUpSection(true)
		});

		if (toId !== '') {
			var $newOption = $("<option selected='selected'></option>").val(toId).text(toUserName);
			$('#select_to').append($newOption).trigger('change');
		}
		if (csId !== '') {
			$('#select_cs').select2('data', { id: csId, username: csUserName });
		}
		if (repId !== '') {
			$('#select_rep').select2('data', { id: repId, username: repUserName });
		}

		$('#span_event_data_status').text(eventStateName);
		$('#span_appointment_queue').text(queueText);
		$('#txt_lead_id').val(leadId);
		$('#txt_event_data_code').val(eventCode);
		$('#txt_note').val(eventNote);
		if (hasBonus === 1) {
			$('input[name="hot_bonus"]').prop('checked', true);
		} else {
			$('input[name="hot_bonus"]').prop('checked', false);
		}

		$('#txt_event_data_id').val(eventDataId);
		toggleFormEventData();

		//4 button
		$('.btn-change-event-status').data('lead-name', leadName);

		var title = $('#btn_busy').data('org-title');
		$('#btn_busy').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName);

		title = $('#btn_overflow').data('org-title');
		$('#btn_overflow').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName);

		title = $('#btn_deal').data('org-title');
		$('#btn_deal').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName);

		title = $('#btn_not_deal').data('org-title');
		$('#btn_not_deal').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName);
	});

	$body.on('click', '#btn_cancel_appointment', function () {
		var url = route('appointments.cancel', $('#txt_appointment_id').val());

		blockPage();
		axios.post(url, {}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			$('#modal_md').modal('hide');
			tableAppointment.reload();
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$btnShowUp.on('click', function () {
		// toggleFormEventData(false)
		$('#queue_section').show();
	});

	$btnNotShowUp.on('click', function () {
		$('#queue_section').hide();
		var url = route('appointments.not_show_up', $('#txt_appointment_id').val());

		axios.post(url, {
			notQueue: true
		}).then(function (result) {
			var obj = result['data'];
			flash(obj.message);
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	function doQueue() {
		var url = route('appointments.do_queue', $('#txt_appointment_id').val());

		axios.post(url, {}).then(function (result) {
			var obj = result['data'];
			flash(obj.message);
			tableEventData.reload();
			tableAppointment.reload();
			clearCustomerInfo();
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	}

	$btnQueue.on('click', function () {
		$(this).confirmation(function (confirm) {
			if (confirm && (typeof confirm === 'undefined' ? 'undefined' : _typeof(confirm)) === 'object' && confirm.value) {
				doQueue();
			}
		});
	});

	$btnNotQueue.on('click', function () {
		$(this).confirmation(function (confirm) {
			if (confirm && (typeof confirm === 'undefined' ? 'undefined' : _typeof(confirm)) === 'object' && confirm.value) {
				var url = route('appointments.do_queue', $('#txt_appointment_id').val());

				axios.post(url, {
					notQueue: true
				}).then(function (result) {
					url = route('appointments.form_change_appointment');
					$('#modal_md').showModal({
						url: url, method: 'get'
					});
				}).catch(function (e) {
					return console.log(e);
				}).finally(function () {
					unblock();
				});
			}
		});
	});

	$body.on('click', '#btn_queue_on_modal', function () {
		doQueue();
	});

	$('#event_data_form').on('submit', function (e) {
		e.preventDefault();

		$(this).confirmation(function (confirm) {
			if (confirm && (typeof confirm === 'undefined' ? 'undefined' : _typeof(confirm)) === 'object' && confirm.value) {
				var eventDataFormData = new FormData($('#event_data_form')[0]);

				$('#event_data_form').submitForm({ url: route('event_datas.update', $('#txt_event_data_id').val()), formData: eventDataFormData }).then(function () {
					toggleFormEventData(true);
					tableAppointment.reload();
					tableEventData.reload();
					clearFormEventData();
				});
			}
		});
	});

	$btnSearch.on('click', function () {
		tableAppointment.reload();
		tableEventData.reload();
	});

	$btnNewLead.on('click', function () {
		//todo: form tạo new customer
		$('#modal_lg').showModal({ url: route('leads.form_new_lead'), method: 'get' });
	});

	$btnUpdateLeadInfo.on('click', function () {
		var leadId = $('#txt_lead_id').val();
		var url = route('leads.update', { lead: leadId });
		$('#leads_form').submitForm({ url: url }).then(function () {
			tableAppointment.reload();
			tableEventData.reload();
		});
	});

	$btnReappointment.on('click', function () {
		var leadId = $('#txt_lead_id').val();
		var appointmentId = $('#txt_appointment_id').val();
		if (appointmentId !== '' && leadId !== '') {
			var url = route('appointments.cancel', appointmentId);

			blockPage();
			axios.post(url, {}).then(function (result) {
				var obj = result['data'];
				if (obj.message) {
					flash(obj.message);
				}
				$('#modal_md').showModal({
					url: route('leads.form_change_state', leadId), params: {
						typeCall: 4,
						callId: '',
						table: '',
						phone: ''
					}, method: 'get'
				});
			}).catch(function (e) {
				return console.log(e);
			}).finally(function () {
				unblock();
			});
		}
	});

	$('.modal').on('show.bs.modal', function () {
		$('#select_state_modal').select2();
		$('#select_time').select2();
		$('#txt_date').datepicker({
			startDate: new Date()
		});
	});

	function validateFormChangeLeadState() {
		var errorText = '';
		if ($('#section_datetime').is(':visible') && $('#txt_date').val() === '') {
			errorText = 'Vui lòng chọn ngày.';
		}
		if ($('#select_state_modal').val() === '') {
			errorText = 'Vui lòng chọn trạng thái lead.';
		}

		if (errorText !== '') {
			$('#change_state_leads_form .alert').show().find('strong').html(errorText);
			return false;
		}

		return true;
	}
	$body.on('change', '#select_state_modal', function () {
		if ($(this).val() === '8') {
			$('#appointment_lead_section').show();
			$('#section_datetime').show();
			$('#comment_section').show();
		} else {
			$('#appointment_lead_section').hide();
			if ($(this).val() === '7') {
				$('#section_datetime').show();
				$('#comment_section').show();
			} else {
				$('#section_datetime').hide();
				$('#comment_section').show();
			}

			if ($(this).val() === '4') {
				$('#province_section').show();
				$('#select_province').select2Ajax();
			} else {
				$('#province_section').hide();
			}
		}
	});
	$body.on('submit', '#change_state_leads_form', function (e) {
		var _this = this;

		e.preventDefault();

		var isFormValid = validateFormChangeLeadState();
		if (!isFormValid) {
			return;
		}

		var formData = new FormData($(this)[0]);
		var url = $(this).prop('action');

		axios({
			method: 'post',
			url: url,
			data: formData,
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(function () {
			tableAppointment.reload();
			$('#modal_md').modal('hide');
		}).finally(function () {
			mApp.unblock('#modal_md');
		}).catch(function (result) {
			var response = result.response;
			var errors = response.data.errors;
			var message = response.data.message;

			if (errors !== undefined) {
				var html = '';
				Object.entries(errors).forEach(function (_ref) {
					var _ref2 = _slicedToArray(_ref, 2),
					    key = _ref2[0],
					    values = _ref2[1];

					var _iteratorNormalCompletion = true;
					var _didIteratorError = false;
					var _iteratorError = undefined;

					try {
						for (var _iterator = values[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
							var value = _step.value;

							html += '<li>' + value + '</li>';
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
				});
				$(_this).find('.alert').show().find('strong').html(html);
			} else {
				$(_this).find('.alert').show().find('strong').html(message);
			}
		});
	});
});

/***/ })

/******/ });