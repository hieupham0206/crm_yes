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
/******/ 	return __webpack_require__(__webpack_require__.s = 63);
/******/ })
/************************************************************************/
/******/ ({

/***/ 63:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(64);


/***/ }),

/***/ 64:
/***/ (function(module, exports) {

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

$(function () {
	var userId = $('#txt_user_id').val();

	var loginHours = 0,
	    loginMinutes = 0,
	    loginSeconds = 0;
	var callHours = 0,
	    callMinutes = 0,
	    callSeconds = 0,
	    callTimeInSecond = 0,
	    callTimeLimit = 15 * 60;
	var totalCustomer = 0;
	var wantToBreak = false,
	    wantToCallOut = false,
	    wantToReCall = false,
	    wantToCheckout = false,
	    btnIdOfRecall = void 0;

	var callInterval = void 0,
	    loginInterval = void 0;
	var $body = $('body');

	var waitTimer = new Timer();
	waitTimer.addEventListener('started', function () {
		updateCallTypeText('Waiting');
		clearLeadInfo();
		$('#btn_form_change_state').prop('disabled', true);
	});
	waitTimer.addEventListener('stopped', function () {
		updateCallTypeText('Auto');
		if (!wantToBreak) {
			autoCall();
		}
	});
	waitTimer.addEventListener('secondsUpdated', function () {
		$('#span_call_time').html(waitTimer.getTimeValues().toString());
	});
	waitTimer.addEventListener('targetAchieved', function () {
		$('#span_call_time').html('00:00:00');
	});

	var breakTimer = new Timer();
	breakTimer.addEventListener('secondsUpdated', function () {
		$('#span_pause_time').html(breakTimer.getTimeValues().toString());
	});
	breakTimer.addEventListener('targetAchieved', function () {
		resume().then(function () {
			toggleAudioAlert('play');
			flash('Đã quá thời gian nghỉ, vui lòng trở lại làm việc.', 'danger', false);
			setTimeout(function () {
				toggleAudioAlert('stop');
			}, 10000);
		});
	});

	function toggleAudioAlert(task) {
		if (task === 'play') {
			$('#audio_alert').trigger('play');
		}
		if (task === 'stop') {
			$('#audio_alert').trigger('pause');
			$('#audio_alert').prop('currentTime', 0);
		}
	}

	function pause() {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToBreak) {
			wantToBreak = true;
			submitLeadForm();
		} else {
			var url = route('users.form_break');
			$('#modal_sm').showModal({ url: url, params: {}, method: 'get' });
		}
	}

	function callOut(phone) {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToCallOut) {
			wantToCallOut = true;
			submitLeadForm();
		} else {
			if (phone !== '') {
				axios.get(route('leads.list'), {
					params: {
						phone: phone
					}
				}).then(function (result) {
					var totalCount = result.data.total_count;
					var items = result.data.items;
					var lead = items[0];

					console.log(lead);
					if (totalCount === 1 && (lead.state === 2 || lead.state === 3 || lead.state === 4 || lead.state === 7 || lead.state === 8 || lead.state === 10)) {
						swal('', 'Khách hàng đã tồn tại trong hệ thống', 'warning').then(function () {
							$('#txt_phone_out_call').val('');
							wantToCallOut = false;
							// autoCall()
							fetchLead(lead.id, 0).then(function () {
								callInterval = setInterval(callClock, 1000);
								$('#btn_form_change_state').prop('disabled', false);

								var leadId = $('#txt_lead_id').val();
								if (leadId !== '') {
									axios.post(route('leads.put_call_cache', leadId), {
										typeCall: 1
									}).then(function (result) {}).catch(function (e) {
										return console.log(e);
									}).finally(function () {
										unblock();
									});
								}
							});
						});
						return false;
					} else {
						// phone = ''
						callInterval = setInterval(callClock, 1000);
						$('#span_lead_birthday').text('');
						$('#span_lead_phone').text(phone);
						$('#span_lead_title').text('');
						var url = route('leads.form_change_state');
						if (lead) {
							$('#txt_lead_id').val(lead.id);
							$('#span_lead_name').text(lead.name);
							url = route('leads.form_change_state', lead.id);
						} else {
							$('#txt_lead_id').val('');
							$('#span_lead_name').text('No Name');
						}
						wantToCallOut = false;
						showFormChangeState({ url: url, phone: phone, modalId: '#modal_outcall' });
					}
				});
			}
		}
	}

	function resume() {
		var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

		blockPage();
		var url = $('#btn_resume').data('url');
		return axios.post(url, params).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			$('#btn_resume').hide();
			$('#btn_pause').show();
			resetPauseClock();
			$('#break_section').removeClass('break-state');
			$('.work-section').show();
			if (wantToBreak) {
				wantToBreak = false;
				autoCall();
			}
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	}

	function fetchLead() {
		var leadId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
		var isNew = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;

		return axios.get(route('leads.list'), {
			params: {
				isNew: isNew,
				leadId: leadId,
				getLeadFromUser: true
			}
		}).then(function (result) {
			var items = result.data.items;
			var count = result.data.total_count;
			if (count > 0) {
				var lead = items[0];

				var birthday = lead.birthday !== '' ? moment(birthday).format('DD-MM-YYYY') : '';
				$('#span_lead_visibility').text(lead.visibility);
				$('#span_lead_name').text(lead.name);
				$('#span_lead_birthday').text(birthday);
				$('#span_lead_phone').text(lead.phone);
				$('#span_lead_title').text(lead.title);
				$('#txt_lead_id').val(lead.id);

				reloadTable();
			}
		});
	}

	//kiểm tra còn lead nào trạng thái la New k
	function checkAvailableLead() {
		return axios.get(route('leads.check_available_new'), {
			params: {}
		}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message, 'warning', false);
			}
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			window.unblock();
		});
	}

	function clearLeadInfo() {
		$('#span_lead_name').text('');
		$('#span_lead_birthday').text('');
		$('#span_lead_phone').text('');
		$('#span_lead_title').text('');
	}

	function autoCall() {
		checkAvailableLead().then(function () {
			fetchLead('', 1).then(function () {
				callInterval = setInterval(callClock, 1000);
				$('#btn_form_change_state').prop('disabled', false);

				var leadId = $('#txt_lead_id').val();
				if (leadId !== '') {
					axios.post(route('leads.put_call_cache', leadId), {
						typeCall: 1
					}).then(function (result) {}).catch(function (e) {
						return console.log(e);
					}).finally(function () {
						unblock();
					});
				}
			});
		});
	}

	function harold(standIn) {
		if (standIn < 10) {
			standIn = '0' + standIn;
		}
		return standIn;
	}

	function loginClock() {
		loginSeconds++;
		if (loginSeconds === 60) {
			loginMinutes++;
			loginSeconds = 0;

			if (loginMinutes === 60) {
				loginMinutes = 0;
				loginHours++;
			}
		}
		$('#span_login_time').text(harold(loginHours) + ':' + harold(loginMinutes) + ':' + harold(loginSeconds));
	}

	function callClock() {
		callTimeInSecond++;
		callSeconds++;
		if (callSeconds === 60) {
			callMinutes++;
			callSeconds = 0;

			if (callMinutes === 60) {
				callMinutes = 0;
				callHours++;
			}
		}
		$('#span_call_time').text(harold(callHours) + ':' + harold(callMinutes) + ':' + harold(callSeconds));

		//note: nếu quá 15phút thì hiện thông báo
		if (callTimeInSecond > callTimeLimit) {
			flash('Thời gian cuộc đã quá 15 phút', 'warning', false);
		}
	}

	function initLoginClock() {
		var diffTime = $('#span_login_time').data('diff-login-time');
		var times = _.split(diffTime, ':');

		loginHours = times[0];
		loginMinutes = times[1];
		loginSeconds = times[2];
	}

	function waitClock() {
		waitTimer.start({ countdown: true, startValues: { seconds: 1 } });
		$('#span_call_time').html(waitTimer.getTimeValues().toString());
	}

	function initCallClock() {
		var diffTime = $('#span_call_time').text();
		var times = _.split(diffTime, ':');

		callHours = numeral(times[0]).value();
		callMinutes = numeral(times[1]).value();
		callSeconds = numeral(times[2]).value();
	}

	function initBreakClock() {
		var diffTime = $('#span_pause_time').data('diff-break-time');
		var startValues = $('#span_pause_time').data('start-break-value');
		var maxBreakTime = $('#span_pause_time').data('max-break-time');
		if (diffTime !== '') {
			wantToBreak = true;
			breakTimer.start({ precision: 'seconds', startValues: { seconds: startValues }, target: { seconds: maxBreakTime + startValues } });
			$('#btn_pause').hide();
			$('#btn_resume').show();
			$('#break_section').addClass('break-state');
			$('.work-section').hide();
		}
	}

	function resetPauseClock() {
		breakTimer.stop();
	}

	function resetCallClock() {
		clearInterval(callInterval);
		callHours = callTimeInSecond = callMinutes = callSeconds = 0;
		$('#span_call_time').text('00:00:00');
	}

	function updateCallTypeText(type) {
		$('#span_call_type').text(type);
	}

	function reloadTable() {
		tableAppointment.reload();
		tableCallback.reload();
		tableCustomerHistory.reload();
		tableLead.reload();
		// tableHistoryCall.reload()
	}

	function showFormChangeState(_ref) {
		var _ref$typeCall = _ref.typeCall,
		    typeCall = _ref$typeCall === undefined ? 1 : _ref$typeCall,
		    url = _ref.url,
		    _ref$callId = _ref.callId,
		    callId = _ref$callId === undefined ? '' : _ref$callId,
		    _ref$table = _ref.table,
		    table = _ref$table === undefined ? '' : _ref$table,
		    _ref$modalId = _ref.modalId,
		    modalId = _ref$modalId === undefined ? '#modal_md' : _ref$modalId,
		    _ref$phone = _ref.phone,
		    phone = _ref$phone === undefined ? '' : _ref$phone;

		setTimeout(function () {
			unblock();

			if (modalId === '#modal_recall') {
				wantToReCall = false;
			}
			$('' + modalId).showModal({
				url: url, params: {
					typeCall: typeCall,
					callId: callId,
					table: table,
					phone: phone
				}, method: 'get'
			});
		}, 0);
	}

	function submitLeadForm() {
		var leadId = $('#txt_lead_id').val();
		var phone = '';

		if (leadId === '') {
			phone = $('#span_lead_phone').text();
		}
		showFormChangeState({ url: route('leads.form_change_state', leadId), phone: phone });
		if ($('#span_call_time').text() === '00:00:00') {
			callInterval = setInterval(callClock, 1000);
		}
	}

	function recall(self, table, callTypeText) {
		var leadId = self.data('lead-id');
		var typeCall = self.data('type-call');
		var callId = self.data('id');

		if ($('#span_call_time').text() !== '00:00:00' && !wantToReCall) {
			wantToReCall = true;
			btnIdOfRecall = self.attr('id');
			submitLeadForm();
		} else {
			if (callTypeText === 'Appointment Call') {
				$('#section_appointment_feature').show();
				$('#btn_form_change_state').prop('disabled', true);
			}
			updateCallTypeText(callTypeText);
			fetchLead(leadId, 0).then(function () {
				showFormChangeState({ typeCall: typeCall, url: route('leads.form_change_state', leadId), callId: callId, table: table, modalId: '#modal_recall' });

				axios.post(route('leads.put_call_cache', leadId), {
					typeCall: typeCall
				}).then(function (result) {}).catch(function (e) {
					return console.log(e);
				}).finally(function () {
					unblock();
				});
			});
		}
	}

	function checkout() {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToCheckout) {
			wantToCheckout = true;
			submitLeadForm();
		} else {
			blockPage();

			axios.post(route('users.end_audit'), {}).then(function (result) {
				$('#txt_is_checked_in').val(false).trigger('change');
				$('#btn_check_out').hide();
				swal('Check out thành công', '', 'success').then(function () {
					$('.work-section').hide();
					$('#btn_check_in').show();
					$('#btn_pause').hide();
					$('.label-span-time').hide();
				});
			}).catch(function (e) {
				return console.log(e);
			}).finally(function () {
				unblock();
			});
		}
	}

	if ($('#txt_lead_id').val() !== '') {
		callInterval = setInterval(callClock, 1000);
	} else {
		$('.work-section').hide();
	}

	// const tableHistoryCall = $('#table_history_calls').DataTable({
	// 	'serverSide': true,
	// 	'paging': false,
	// 	'ajax': $.fn.dataTable.pipeline({
	// 		url: route('history_calls.console.table'),
	// 		data: function(q) {
	// 			q.filters = JSON.stringify([{'name': 'user_id', 'value': userId}])
	// 			q.table = 'history_call'
	// 		},
	// 	}),
	// 	conditionalPaging: true,
	// 	'columnDefs': [],
	// 	sort: false,
	// 	'iDisplayLength': 20,
	// })
	var tableLead = $('#table_leads').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('leads.console.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'user_id', 'value': userId }]);
			}
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
		'iDisplayLength': 1000
	});
	var tableCustomerHistory = $('#table_customer_history').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('history_calls.console.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'lead_id', 'value': $('#txt_lead_id').val() }]);
				q.table = 'customer_history';
			}
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
		iDisplayLength: 2
	});
	var tableCallback = $('#table_callback').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('callbacks.console.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'user_id', 'value': userId }]);
			}
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false
	});
	var tableAppointment = $('#table_appointment').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('appointments.console.table'),
			data: function data(q) {
				q.filters = JSON.stringify([{ 'name': 'user_id', 'value': userId }]);
			}
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false
	});

	$('#leads_form').on('submit', function (e) {
		e.preventDefault();
		submitLeadForm();
	});

	$('#btn_callout').on('click', function () {
		var phone = $('#txt_phone_out_call').val();
		callOut(phone);
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

	$body.on('click', '.btn-close-modal-change-state', function () {
		wantToBreak = false;
		wantToCallOut = false;
		wantToReCall = false;
	});

	$('#btn_check_in').on('click', function () {
		var _this = this;

		var url = route('users.start_audit');
		blockPage();

		axios.post(url, {}).then(function (result) {
			$('#txt_is_checked_in').val(true).trigger('change');
			$(_this).hide();
			swal('Check in thành công', '', 'success').then(function () {
				$('.work-section').show();
				$('#btn_check_out').show();
				$('#btn_pause').show();
				$('#btn_load_private').show();
				$('.label-span-time').show();
				initCallClock();
				loginInterval = setInterval(loginClock, 1000);
				autoCall();
			});
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$('#btn_check_out').on('click', function () {
		checkout();
	});

	$('#btn_load_private').on('click', function () {
		var _this2 = this;

		var url = route('users.toggle_private_only');
		blockPage();

		axios.post(url, { loadPrivate: true }).then(function (result) {
			$(_this2).hide();
			swal('Cập nhật thành công', '', 'success').then(function () {
				$('#btn_not_load_private').show();
				location.reload();
			});
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$('#btn_not_load_private').on('click', function () {
		var _this3 = this;

		var url = route('users.toggle_private_only');
		blockPage();

		axios.post(url, { loadPrivate: false }).then(function (result) {
			$(_this3).hide();
			swal('Cập nhật thành công', '', 'success').then(function () {
				$('#btn_load_private').show();
				location.reload();
			});
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$body.on('submit', '#change_state_leads_form', function (e) {
		var _this4 = this;

		e.preventDefault();

		var isFormValid = validateFormChangeLeadState();
		if (!isFormValid) {
			return;
		}

		if ($('#modal_recall').is(':visible')) {
			mApp.block('#modal_recall');
		} else if ($('#modal_md').is(':visible')) {
			mApp.block('#modal_md');
		}
		var formData = new FormData($(this)[0]);
		var url = $(this).prop('action');

		axios({
			method: 'post',
			url: url,
			data: formData,
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(function () {
			$(_this4).resetForm();
			resetCallClock();
			$('#span_customer_no').text(++totalCustomer);
			$('#btn_form_change_state').prop('disabled', false);

			$('#section_appointment_feature').hide();
			$('#txt_appointment_id').val('');
			if ($('#modal_recall').is(':visible')) {
				$('#modal_recall').modal('hide');
			} else if ($('#modal_md').is(':visible')) {
				$('#modal_md').modal('hide');
			} else if ($('#modal_outcall').is(':visible')) {
				$('#modal_outcall').modal('hide');
			}

			if (wantToBreak) {
				pause();
			} else if (wantToReCall) {
				if (btnIdOfRecall.includes('appointment')) {
					$('#section_appointment_feature').show();
				}
				$('#' + btnIdOfRecall).trigger('click');
				callInterval = setInterval(callClock, 1000);
			} else if (wantToCallOut) {
				var phone = $('#txt_phone_out_call').val();
				callOut(phone);
			} else if (wantToCheckout) {
				checkout();
			} else {
				waitClock();
			}
		}).finally(function () {
			mApp.unblock('#modal_md');
			mApp.unblock('#modal_recall');
			mApp.unblock('#modal_outcall');
		}).catch(function (result) {
			var response = result.response;
			var errors = response.data.errors;
			var message = response.data.message;

			if (errors !== undefined) {
				var html = '';
				Object.entries(errors).forEach(function (_ref2) {
					var _ref3 = _slicedToArray(_ref2, 2),
					    key = _ref3[0],
					    values = _ref3[1];

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
				$(_this4).find('.alert').show().find('strong').html(html);
			} else {
				$(_this4).find('.alert').show().find('strong').html(message);
			}
		});
	});

	$body.on('submit', '#break_form', function (e) {
		var _this5 = this;

		e.preventDefault();
		mApp.block('#modal_sm');

		$(this).submitForm({ returnEarly: true }).then(function (result) {
			$(_this5).resetForm();
			$('#btn_pause').hide();
			$('#btn_resume').show();
			var target = result.data.maxTimeBreak;

			breakTimer.start({ precision: 'seconds', startValues: { seconds: 0 }, target: { seconds: parseInt(target) } });
			$('#break_section').addClass('break-state');
			$('#modal_sm').modal('hide');
			mApp.unblock('#modal_sm');

			//hide tat ca chuc nang
			$('.work-section').hide();
		}).finally(function () {
			window.unblock();
		});
	});

	$body.on('click', '.btn-delete', function () {
		var route = $(this).data('route');
		if (route === 'callbacks') {
			tableCallback.actionDelete({
				btnDelete: $(this)
			});
		} else if (route === 'appointments') {
			tableAppointment.actionDelete({
				btnDelete: $(this)
			});
		} else if (route === 'history_calls') {
			// tableHistoryCall.actionDelete({
			// 	btnDelete: $(this),
			// })
		}
	});

	$body.on('click', '.btn-callback-call', function () {
		$('#section_appointment_feature').hide();
		recall($(this), 'callbacks', 'Callback Call');
	});

	$body.on('click', '.btn-appointment-call', function () {
		var callId = $(this).data('id');

		$('#txt_appointment_id').val(callId);
		recall($(this), 'appointments', 'Appointment Call');
	});

	$body.on('click', '.btn-history-call', function () {
		$('#section_appointment_feature').hide();
		recall($(this), 'history_calls', 'History Call');
	});

	$body.on('click', '.btn-edit-datetime', function () {
		var appointmentId = $(this).data('id');
		var $tr = $(this).parents('tr');
		var spanAppointmentDatetimeText = $tr.find('.span-datetime');
		var appointmentDatetime = spanAppointmentDatetimeText.text();
		var urlEdit = $(this).data('url');

		var html = '<div class="input-group">\n\t\t\t\t\t\t\t<input type="text" class="form-control text-inline-datepicker" value="' + appointmentDatetime + '" data-appointment-id="' + appointmentId + '">\n\t\t\t\t\t\t\t<div class="input-group-append">\n\t\t\t\t\t\t\t\t<button class="btn btn-success btn-change-datetime btn-sm" type="button" data-url="' + urlEdit + '"><i class="fa fa-check"></i></button>\n\t\t\t\t\t\t\t\t<button class="btn btn-danger btn-cancel-datetime btn-sm" type="button"><i class="fa fa-trash"></i></button>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>';

		spanAppointmentDatetimeText.html(html);
		$tr.find('.text-inline-datepicker').datetimepicker({
			startDate: new Date()
		});
		$(this).prop('disabled', true);
	});

	$body.on('click', '.btn-cancel-datetime', function () {
		var parents = $(this).parents('tr');
		var appointmentDatetime = parents.find('.text-inline-datepicker').val();
		parents.find('.span-datetime').text(appointmentDatetime);

		parents.find('.btn-edit-datetime').prop('disabled', false);
	});

	$body.on('click', '.btn-change-datetime', function () {
		var _this6 = this;

		var $textInlineDatepicker = $(this).parents('.input-group').find('.text-inline-datepicker');
		var dateTime = $textInlineDatepicker.val();
		var urlEdit = $(this).data('url');

		axios.post(urlEdit, {
			dateTime: dateTime
		}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			var parents = $(_this6).parents('tr');
			parents.find('.span-datetime').text(dateTime);
			parents.find('.btn-edit-datetime').prop('disabled', false);
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

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

	$body.on('change', '#select_reason_break', function () {
		if ($(this).val() === '5') {
			$('#another_reason_section').show();
		} else {
			$('#textarea_reason').val('');
			$('#another_reason_section').hide();
		}
	});

	// $('.m-menu__submenu .m-menu__subnav .m-menu__link').on('click', function(e) {
	// 	e.preventDefault()
	//
	// 	let url = $(this).attr('href')
	// 	if (url.includes('business')) {
	// 		let isCheckedIn = $('#txt_is_checked_in').val()
	// 		if (isCheckedIn === '1') {
	// 			flash('Vui lòng checkout để chuyển trang', 'warning')
	// 		} else {
	// 			location.href = url
	// 		}
	// 	} else {
	// 		location.href = url
	// 	}
	// })

	$('#btn_cancel_appointment').on('click', function () {
		var appointmentId = $('#txt_appointment_id').val();
		if (appointmentId !== '') {
			var url = route('appointments.cancel', appointmentId);

			blockPage();
			axios.post(url, {}).then(function (result) {
				var obj = result['data'];
				if (obj.message) {
					flash(obj.message);
				}
				$('#section_appointment_feature').hide();
				tableAppointment.reload();
				waitClock();
			}).catch(function (e) {
				return console.log(e);
			}).finally(function () {
				unblock();
			});
		}
	});
	$('#btn_resend_appointment_email').on('click', function () {
		var appointmentId = $('#txt_appointment_id').val();
		var leadId = $('#txt_lead_id').val();
		if (appointmentId !== '') {
			var url = route('leads.resend_email', { lead: leadId, appointment: appointmentId });

			blockPage();
			axios.post(url, {}).then(function (result) {
				var obj = result['data'];
				if (obj.message) {
					flash(obj.message);
				}
			}).catch(function (e) {
				return console.log(e);
			}).finally(function () {
				unblock();
			});
		}
	});

	$('#btn_reappointment').on('click', function () {
		var leadId = $('#txt_lead_id').val();
		var url = route('appointments.cancel', $('#txt_appointment_id').val());

		blockPage();
		axios.post(url, {}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			var callId = $('#txt_appointment_id').val();
			showFormChangeState({ url: route('leads.form_change_state', leadId), typeCall: 4, table: 're_app', callId: callId });
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$('#btn_appointment_confirm').on('click', function () {
		var leadId = $('#txt_lead_id').val();

		var url = route('leads.save_history_call', leadId);

		blockPage();
		axios.post(url, {}).then(function (result) {
			var obj = result['data'];
			if (obj.message) {
				flash(obj.message);
			}
			$('#section_appointment_feature').hide();
			resetCallClock();
			waitClock();
		}).catch(function (e) {
			return console.log(e);
		}).finally(function () {
			unblock();
		});
	});

	$('.modal').on('show.bs.modal', function () {
		$('#select_state_modal').select2();
		$('#select_time').select2();
		$('#txt_date').datepicker({
			startDate: new Date()
		});
	});

	$('#modal_sm').on('show.bs.modal', function () {
		$('#select_reason_break').select2();
	});

	$('#btn_pause').on('click', function () {
		pause();
	});

	$('#btn_resume').on('click', function () {
		resume();
	});

	$('#txt_is_checked_in').on('change', function () {
		var isCheckedIn = $(this).val();

		if (isCheckedIn === true) {
			loginInterval = setInterval(loginClock, 1000);
		} else {
			clearInterval(loginInterval);
		}
	});

	if ($('#txt_is_checked_in').val()) {
		initCallClock();
		loginInterval = setInterval(loginClock, 1000);
	}

	initLoginClock();
	initBreakClock();
});

/***/ })

/******/ });