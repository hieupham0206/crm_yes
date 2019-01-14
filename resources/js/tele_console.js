$(function() {
	let userId = $('#txt_user_id').val()

	let loginHours = 0, loginMinutes = 0, loginSeconds = 0
	let callHours = 0, callMinutes = 0, callSeconds = 0, callTimeInSecond = 0, callTimeLimit = 15 * 60
	let totalCustomer = 0
	let wantToBreak = false, wantToCallOut = false, wantToReCall = false, wantToCheckout = false, btnIdOfRecall

	let callInterval, loginInterval
	let $body = $('body')

	let waitTimer = new Timer()
	waitTimer.addEventListener('started', function() {
		updateCallTypeText('Waiting')
		clearLeadInfo()
		$('#btn_form_change_state').prop('disabled', true)
	})
	waitTimer.addEventListener('stopped', function() {
		updateCallTypeText('Auto')
		if (!wantToBreak) {
			autoCall()
		}
	})
	waitTimer.addEventListener('secondsUpdated', function() {
		$('#span_call_time').html(waitTimer.getTimeValues().toString())
	})
	waitTimer.addEventListener('targetAchieved', function() {
		$('#span_call_time').html('00:00:00')
	})

	let breakTimer = new Timer()
	breakTimer.addEventListener('secondsUpdated', function() {
		$('#span_pause_time').html(breakTimer.getTimeValues().toString())
	})
	breakTimer.addEventListener('targetAchieved', function() {
		resume().then(() => {
			toggleAudioAlert('play')
			flash('Đã quá thời gian nghỉ, vui lòng trở lại làm việc.', 'danger', false)
			setTimeout(() => {
				toggleAudioAlert('stop')
			}, 10000)
		})
	})

	function toggleAudioAlert(task) {
		if (task === 'play') {
			$('#audio_alert').trigger('play')
		}
		if (task === 'stop') {
			$('#audio_alert').trigger('pause')
			$('#audio_alert').prop('currentTime', 0)
		}
	}

	function pause() {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToBreak) {
			wantToBreak = true
			submitLeadForm()
		} else {
			let url = route('users.form_break')
			$('#modal_sm').showModal({url: url, params: {}, method: 'get'})
		}
	}

	function callOut(phone) {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToCallOut) {
			wantToCallOut = true
			submitLeadForm()
		} else {
			if (phone !== '') {
				axios.get(route('leads.list'), {
					params: {
						phone: phone,
					},
				}).then(result => {
					let totalCount = result.data.total_count

					if (totalCount === 1 && (lead.state === 2 || lead.state === 3 || lead.state === 4 || lead.state === 7 || lead.state === 8 || lead.state === 10)) {
						let items = result.data.items
						let lead = items[0]
						swal(
							'',
							'Khách hàng đã tồn tại trong hệ thống',
							'warning',
						).then(() => {
							$('#txt_phone_out_call').val('')
							wantToCallOut = false
							// autoCall()
							fetchLead(lead.id, 1).then(() => {
								callInterval = setInterval(callClock, 1000)
								$('#btn_form_change_state').prop('disabled', false)

								let leadId = $('#txt_lead_id').val()
								if (leadId !== '') {
									axios.post(route('leads.put_call_cache', leadId), {
										typeCall: 1,
									}).then(result => {

									}).catch(e => console.log(e)).finally(() => {
										unblock()
									})
								}
							})

						})
						return false

					} else {
						callInterval = setInterval(callClock, 1000)
						$('#span_lead_name').text('No Name')
						$('#span_lead_birthday').text('')
						$('#span_lead_phone').text(phone)
						$('#span_lead_title').text('')
						$('#txt_lead_id').val('')
						wantToCallOut = false
						showFormChangeState({url: route('leads.form_change_state'), phone: phone, modalId: '#modal_outcall'})
					}
				})

			}
		}
	}

	function resume(params = {}) {
		blockPage()
		let url = $('#btn_resume').data('url')
		return axios.post(url, params).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			$('#btn_resume').hide()
			$('#btn_pause').show()
			resetPauseClock()
			$('#break_section').removeClass('break-state')
			$('.work-section').show()
			if (wantToBreak) {
				wantToBreak = false
				autoCall()
			}
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	}

	function fetchLead(leadId = '', isNew = 1) {
		return axios.get(route('leads.list'), {
			params: {
				isNew: isNew,
				leadId: leadId,
				getLeadFromUser: true,
			},
		}).then(result => {
			let items = result.data.items
			let count = result.data.total_count
			if (count > 0) {
				let lead = items[0]

				let birthday = lead.birthday !== '' ? moment(birthday).format('DD-MM-YYYY') : ''
				$('#span_lead_visibility').text(lead.visibility)
				$('#span_lead_name').text(lead.name)
				$('#span_lead_birthday').text(birthday)
				$('#span_lead_phone').text(lead.phone)
				$('#span_lead_title').text(lead.title)
				$('#txt_lead_id').val(lead.id)

				reloadTable()
			}
		})
	}

	function clearLeadInfo() {
		$('#span_lead_name').text('')
		$('#span_lead_birthday').text('')
		$('#span_lead_phone').text('')
		$('#span_lead_title').text('')
	}

	function autoCall() {
		fetchLead('', 1).then(() => {
			callInterval = setInterval(callClock, 1000)
			$('#btn_form_change_state').prop('disabled', false)

			let leadId = $('#txt_lead_id').val()
			if (leadId !== '') {
				axios.post(route('leads.put_call_cache', leadId), {
					typeCall: 1,
				}).then(result => {

				}).catch(e => console.log(e)).finally(() => {
					unblock()
				})
			}
		})
	}

	function harold(standIn) {
		if (standIn < 10) {
			standIn = '0' + standIn
		}
		return standIn
	}

	function loginClock() {
		loginSeconds++
		if (loginSeconds === 60) {
			loginMinutes++
			loginSeconds = 0

			if (loginMinutes === 60) {
				loginMinutes = 0
				loginHours++
			}
		}
		$('#span_login_time').text(harold(loginHours) + ':' + harold(loginMinutes) + ':' + harold(loginSeconds))
	}

	function callClock() {
		callTimeInSecond++
		callSeconds++
		if (callSeconds === 60) {
			callMinutes++
			callSeconds = 0

			if (callMinutes === 60) {
				callMinutes = 0
				callHours++
			}
		}
		$('#span_call_time').text(harold(callHours) + ':' + harold(callMinutes) + ':' + harold(callSeconds))

		//note: nếu quá 15phút thì hiện thông báo
		if (callTimeInSecond > callTimeLimit) {
			flash('Thời gian cuộc đã quá 15 phút', 'warning', false)
		}
	}

	function initLoginClock() {
		let diffTime = $('#span_login_time').data('diff-login-time')
		let times = _.split(diffTime, ':')

		loginHours = times[0]
		loginMinutes = times[1]
		loginSeconds = times[2]
	}

	function waitClock() {
		waitTimer.start({countdown: true, startValues: {seconds: 1}})
		$('#span_call_time').html(waitTimer.getTimeValues().toString())
	}

	function initCallClock() {
		let diffTime = $('#span_call_time').text()
		let times = _.split(diffTime, ':')

		callHours = numeral(times[0]).value()
		callMinutes = numeral(times[1]).value()
		callSeconds = numeral(times[2]).value()
	}

	function initBreakClock() {
		let diffTime = $('#span_pause_time').data('diff-break-time')
		let startValues = $('#span_pause_time').data('start-break-value')
		let maxBreakTime = $('#span_pause_time').data('max-break-time')
		if (diffTime !== '') {
			wantToBreak = true
			breakTimer.start({precision: 'seconds', startValues: {seconds: startValues}, target: {seconds: maxBreakTime + startValues}})
			$('#btn_pause').hide()
			$('#btn_resume').show()
			$('#break_section').addClass('break-state')
			$('.work-section').hide()
		}
	}

	function resetPauseClock() {
		breakTimer.stop()
	}

	function resetCallClock() {
		clearInterval(callInterval)
		callHours = callTimeInSecond = callMinutes = callSeconds = 0
		$('#span_call_time').text('00:00:00')
	}

	function updateCallTypeText(type) {
		$('#span_call_type').text(type)
	}

	function reloadTable() {
		tableAppointment.reload()
		tableCallback.reload()
		tableCustomerHistory.reload()
		tableHistoryCall.reload()
	}

	function showFormChangeState({typeCall = 1, url, callId = '', table = '', modalId = '#modal_md', phone = ''}) {
		setTimeout(() => {
			unblock()

			if (modalId === '#modal_recall') {
				wantToReCall = false
			}
			$(`${modalId}`).showModal({
				url: url, params: {
					typeCall,
					callId,
					table,
					phone,
				}, method: 'get',
			})
		}, 0)
	}

	function submitLeadForm() {
		let leadId = $('#txt_lead_id').val()
		showFormChangeState({url: route('leads.form_change_state', leadId)})
		if ($('#span_call_time').text() === '00:00:00') {
			callInterval = setInterval(callClock, 1000)
		}
	}

	function recall(self, table, callTypeText) {
		let leadId = self.data('lead-id')
		let typeCall = self.data('type-call')
		let callId = self.data('id')

		if ($('#span_call_time').text() !== '00:00:00' && !wantToReCall) {
			wantToReCall = true
			btnIdOfRecall = self.attr('id')
			submitLeadForm()
		} else {
			if (callTypeText === 'Appointment Call') {
				$('#section_appointment_feature').show()
				$('#btn_form_change_state').prop('disabled', true)
			}
			updateCallTypeText(callTypeText)
			fetchLead(leadId, 0).then(() => {
				showFormChangeState({typeCall: typeCall, url: route('leads.form_change_state', leadId), callId: callId, table: table, modalId: '#modal_recall'})

				axios.post(route('leads.put_call_cache', leadId), {
					typeCall: typeCall,
				}).then(result => {

				}).catch(e => console.log(e)).finally(() => {
					unblock()
				})
			})
		}
	}

	function checkout() {
		if ($('#span_call_time').text() !== '00:00:00' && !wantToCheckout) {
			wantToCheckout = true
			submitLeadForm()
		} else {
			blockPage()

			axios.post(route('users.end_audit'), {}).then(result => {
				$('#txt_is_checked_in').val(false).trigger('change')
				$('#btn_check_out').hide()
				swal('Check out thành công', '', 'success').then(() => {
					$('.work-section').hide()
					$('#btn_check_in').show()
					$('#btn_pause').hide()
					$('.label-span-time').hide()
				})
			}).catch(e => console.log(e)).finally(() => {
				unblock()
			})
		}
	}

	if ($('#txt_lead_id').val() !== '') {
		callInterval = setInterval(callClock, 1000)
	} else {
		$('.work-section').hide()
	}

	const tableHistoryCall = $('#table_history_calls').DataTable({
		'serverSide': true,
		'paging': false,
		'ajax': $.fn.dataTable.pipeline({
			url: route('history_calls.console.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'user_id', 'value': userId}])
				q.table = 'history_call'
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
		'iDisplayLength': 20,
	})
	const tableCustomerHistory = $('#table_customer_history').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('history_calls.console.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'lead_id', 'value': $('#txt_lead_id').val()}])
				q.table = 'customer_history'
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
	})
	const tableCallback = $('#table_callback').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('callbacks.console.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'user_id', 'value': userId}])
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
	})
	const tableAppointment = $('#table_appointment').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('appointments.console.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'user_id', 'value': userId}])
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
	})

	$('#leads_form').on('submit', function(e) {
		e.preventDefault()
		submitLeadForm()
	})

	$('#btn_callout').on('click', function() {
		let phone = $('#txt_phone_out_call').val()
		callOut(phone)
	})

	function validateFormChangeLeadState() {
		let errorText = ''
		if ($('#section_datetime').is(':visible') && $('#txt_date').val() === '') {
			errorText = 'Vui lòng chọn ngày.'
		}
		if ($('#select_state_modal').val() === '') {
			errorText = 'Vui lòng chọn trạng thái lead.'
		}

		if (errorText !== '') {
			$('#change_state_leads_form .alert').show().find('strong').html(errorText)
			return false

		}

		return true
	}

	$body.on('click', '.btn-close-modal-change-state', function() {
		wantToBreak = false
		wantToCallOut = false
		wantToReCall = false
	})

	$('#btn_check_in').on('click', function() {
		let url = route('users.start_audit')
		blockPage()

		axios.post(url, {}).then(result => {
			$('#txt_is_checked_in').val(true).trigger('change')
			$(this).hide()
			swal('Check in thành công', '', 'success').then(() => {
				$('.work-section').show()
				$('#btn_check_out').show()
				$('#btn_pause').show()
				$('#btn_load_private').show()
				$('.label-span-time').show()
				initCallClock()
				loginInterval = setInterval(loginClock, 1000)
				autoCall()
			})
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$('#btn_check_out').on('click', function() {
		checkout()
	})

	$('#btn_load_private').on('click', function() {
		let url = route('users.toggle_private_only')
		blockPage()

		axios.post(url, {loadPrivate: true}).then(result => {
			$(this).hide()
			swal('Cập nhật thành công', '', 'success').then(() => {
				$('#btn_not_load_private').show()
			})
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$('#btn_not_load_private').on('click', function() {
		let url = route('users.toggle_private_only')
		blockPage()

		axios.post(url, {loadPrivate: false}).then(result => {
			$(this).hide()
			swal('Cập nhật thành công', '', 'success').then(() => {
				$('#btn_load_private').show()
			})
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$body.on('submit', '#change_state_leads_form', function(e) {
		e.preventDefault()

		let isFormValid = validateFormChangeLeadState()
		if (!isFormValid) {
			return
		}

		if ($('#modal_recall').is(':visible')) {
			mApp.block('#modal_recall')
		} else if ($('#modal_md').is(':visible')) {
			mApp.block('#modal_md')
		}
		let formData = new FormData($(this)[0])
		let url = $(this).prop('action')

		axios({
			method: 'post',
			url: url,
			data: formData,
			config: {headers: {'Content-Type': 'multipart/form-data'}},
		}).then(() => {
			$(this).resetForm()
			resetCallClock()
			$('#span_customer_no').text(++totalCustomer)
			$('#btn_form_change_state').prop('disabled', false)

			$('#section_appointment_feature').hide()
			$('#txt_appointment_id').val('')
			if ($('#modal_recall').is(':visible')) {
				$('#modal_recall').modal('hide')
			} else if ($('#modal_md').is(':visible')) {
				$('#modal_md').modal('hide')
			} else if ($('#modal_outcall').is(':visible')) {
				$('#modal_outcall').modal('hide')
			}

			if (wantToBreak) {
				pause()
			} else if (wantToReCall) {
				if (btnIdOfRecall.includes('appointment')) {
					$('#section_appointment_feature').show()
				}
				$(`#${btnIdOfRecall}`).trigger('click')
				callInterval = setInterval(callClock, 1000)
			} else if (wantToCallOut) {
				let phone = $('#txt_phone_out_call').val()
				callOut(phone)
			} else if (wantToCheckout) {
				checkout()
			} else {
				waitClock()
			}
		}).finally(() => {
			mApp.unblock('#modal_md')
			mApp.unblock('#modal_recall')
			mApp.unblock('#modal_outcall')
		}).catch(result => {
			let response = result.response
			let errors = response.data.errors
			let message = response.data.message

			if (errors !== undefined) {
				let html = ''
				Object.entries(errors).forEach(
					([key, values]) => {
						for (let value of values) {
							html += `<li>${value}</li>`
						}
					},
				)
				$(this).find('.alert').show().find('strong').html(html)
			} else {
				$(this).find('.alert').show().find('strong').html(message)
			}
		})
	})

	$body.on('submit', '#break_form', function(e) {
		e.preventDefault()
		mApp.block('#modal_sm')

		$(this).submitForm({returnEarly: true}).then(result => {
			$(this).resetForm()
			$('#btn_pause').hide()
			$('#btn_resume').show()
			let target = result.data.maxTimeBreak

			breakTimer.start({precision: 'seconds', startValues: {seconds: 0}, target: {seconds: parseInt(target)}})
			$('#break_section').addClass('break-state')
			$('#modal_sm').modal('hide')
			mApp.unblock('#modal_sm')

			//hide tat ca chuc nang
			$('.work-section').hide()
		}).finally(() => {
			window.unblock()
		})
	})

	$body.on('click', '.btn-delete', function() {
		let route = $(this).data('route')
		if (route === 'callbacks') {
			tableCallback.actionDelete({
				btnDelete: $(this),
			})
		} else if (route === 'appointments') {
			tableAppointment.actionDelete({
				btnDelete: $(this),
			})
		} else if (route === 'history_calls') {
			tableHistoryCall.actionDelete({
				btnDelete: $(this),
			})
		}
	})

	$body.on('click', '.btn-callback-call', function() {
		$('#section_appointment_feature').hide()
		recall($(this), 'callbacks', 'Callback Call')
	})

	$body.on('click', '.btn-appointment-call', function() {
		let callId = $(this).data('id')

		$('#txt_appointment_id').val(callId)
		recall($(this), 'appointments', 'Appointment Call')
	})

	$body.on('click', '.btn-history-call', function() {
		$('#section_appointment_feature').hide()
		recall($(this), 'history_calls', 'History Call')
	})

	$body.on('click', '.btn-edit-datetime', function() {
		let appointmentId = $(this).data('id')
		let $tr = $(this).parents('tr')
		let spanAppointmentDatetimeText = $tr.find('.span-datetime')
		let appointmentDatetime = spanAppointmentDatetimeText.text()
		let urlEdit = $(this).data('url')

		let html = `<div class="input-group">
							<input type="text" class="form-control text-inline-datepicker" value="${appointmentDatetime}" data-appointment-id="${appointmentId}">
							<div class="input-group-append">
								<button class="btn btn-success btn-change-datetime btn-sm" type="button" data-url="${urlEdit}"><i class="fa fa-check"></i></button>
								<button class="btn btn-danger btn-cancel-datetime btn-sm" type="button"><i class="fa fa-trash"></i></button>
							</div>
						</div>`

		spanAppointmentDatetimeText.html(html)
		$tr.find('.text-inline-datepicker').datetimepicker({
			startDate: new Date(),
		})
		$(this).prop('disabled', true)
	})

	$body.on('click', '.btn-cancel-datetime', function() {
		let parents = $(this).parents('tr')
		let appointmentDatetime = parents.find('.text-inline-datepicker').val()
		parents.find('.span-datetime').text(appointmentDatetime)

		parents.find('.btn-edit-datetime').prop('disabled', false)
	})

	$body.on('click', '.btn-change-datetime', function() {
		let $textInlineDatepicker = $(this).parents('.input-group').find('.text-inline-datepicker')
		let dateTime = $textInlineDatepicker.val()
		let urlEdit = $(this).data('url')

		axios.post(urlEdit, {
			dateTime: dateTime,
		}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			let parents = $(this).parents('tr')
			parents.find('.span-datetime').text(dateTime)
			parents.find('.btn-edit-datetime').prop('disabled', false)
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$body.on('change', '#select_state_modal', function() {
		if ($(this).val() === '8') {
			$('#appointment_lead_section').show()
			$('#section_datetime').show()
			$('#comment_section').show()
		} else {
			$('#appointment_lead_section').hide()
			if ($(this).val() === '7') {
				$('#section_datetime').show()
				$('#comment_section').show()
			} else {
				$('#section_datetime').hide()
				$('#comment_section').show()
			}

			if ($(this).val() === '4') {
				$('#province_section').show()
				$('#select_province').select2Ajax()
			} else {
				$('#province_section').hide()
			}
		}
	})

	$body.on('change', '#select_reason_break', function() {
		if ($(this).val() === '5') {
			$('#another_reason_section').show()
		} else {
			$('#textarea_reason').val('')
			$('#another_reason_section').hide()
		}
	})

	$('.m-menu__submenu .m-menu__subnav .m-menu__link').on('click', function(e) {
		e.preventDefault()

		let url = $(this).attr('href')
		if (url.includes('business')) {
			let isCheckedIn = $('#txt_is_checked_in').val()
			if (isCheckedIn === '1') {
				flash('Vui lòng checkout để chuyển trang', 'warning')
			} else {
				location.href = url
			}
		} else {
			location.href = url
		}
	})

	$('#btn_cancel_appointment').on('click', function() {
		let appointmentId = $('#txt_appointment_id').val()
		if (appointmentId !== '') {
			let url = route('appointments.cancel', appointmentId)

			blockPage()
			axios.post(url, {}).then(result => {
				let obj = result['data']
				if (obj.message) {
					flash(obj.message)
				}
				$('#section_appointment_feature').hide()
				tableAppointment.reload()
				waitClock()
			}).catch(e => console.log(e)).finally(() => {
				unblock()
			})
		}
	})
	$('#btn_resend_appointment_email').on('click', function() {
		let appointmentId = $('#txt_appointment_id').val()
		let leadId = $('#txt_lead_id').val()
		if (appointmentId !== '') {
			let url = route('leads.resend_email', {lead: leadId, appointment: appointmentId})

			blockPage()
			axios.post(url, {}).then(result => {
				let obj = result['data']
				if (obj.message) {
					flash(obj.message)
				}
			}).catch(e => console.log(e)).finally(() => {
				unblock()
			})
		}
	})

	$('#btn_reappointment').on('click', function() {
		let leadId = $('#txt_lead_id').val()
		let url = route('appointments.cancel', $('#txt_appointment_id').val())

		blockPage()
		axios.post(url, {}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			showFormChangeState({url: route('leads.form_change_state', leadId), typeCall: 4})
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$('#btn_appointment_confirm').on('click', function() {
		let leadId = $('#txt_lead_id').val()

		let url = route('leads.save_history_call', leadId)

		blockPage()
		axios.post(url, {}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			$('#section_appointment_feature').hide()
			resetCallClock()
			waitClock()
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$('.modal').on('show.bs.modal', function() {
		$('#select_state_modal').select2()
		$('#select_time').select2()
		$('#txt_date').datepicker({
			startDate: new Date(),
		})
	})

	$('#modal_sm').on('show.bs.modal', function() {
		$('#select_reason_break').select2()
	})

	$('#btn_pause').on('click', function() {
		pause()
	})

	$('#btn_resume').on('click', function() {
		resume()
	})

	$('#txt_is_checked_in').on('change', function() {
		let isCheckedIn = $(this).val()

		if (isCheckedIn === true) {
			loginInterval = setInterval(loginClock, 1000)
		} else {
			clearInterval(loginInterval)
		}
	})

	initLoginClock()
	initBreakClock()

	if ($('#txt_is_checked_in').val()) {
		initCallClock()
		loginInterval = setInterval(loginClock, 1000)
	}
})