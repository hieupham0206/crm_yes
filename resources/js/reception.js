$(function() {
	let userId = $('#txt_user_id').val()
	let $body = $('body'),
		$btnShowUp = $('#btn_show_up'),
		$btnNotShowUp = $('#btn_not_show_up'),
		$btnQueue = $('#btn_queue'),
		$btnNotQueue = $('#btn_not_queue'),
		$btnUpdateLeadInfo = $('#btn_update_lead_info'),
		$btnReappointment = $('#btn_re_appointment'),
		$btnSearch = $('#btn_search'),
		$btnNewLead = $('#btn_new_lead'),
		$selectTo = $('#select_to'),
		$selectRep = $('#select_rep')

	const tableAppointment = $('#table_appointment').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('appointments.console.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'code', 'value': $('#txt_voucher_code').val()}, {'name': 'phone', 'value': $('#txt_phone').val()}])//, {'name': 'is_queue', 'value': -1}
				q.form = 'reception_console'
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		// sort: false,
	})
	const tableEventData = $('#table_event_data').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('event_datas.table'),
			data: function(q) {
				q.filters = JSON.stringify([{'name': 'code', 'value': $('#txt_voucher_code').val()}, {'name': 'phone', 'value': $('#txt_phone').val()}])
			},
		}),
		conditionalPaging: true,
		'columnDefs': [],
		sort: false,
	})

	function fetchLead(leadId = '', isNew = 1, appointmentId = '') {
		blockPage()
		let fetchRoute = route('appointments.list')
		if (appointmentId === '') {
			fetchRoute = route('leads.list')
		}
		return axios.get(fetchRoute, {
			params: {
				appointmentId: appointmentId,
				leadId: leadId,
			},
		}).then(result => {
			let items = result.data.items
			let appointment = items[0]
			let lead ,user, appointmentDatetime

			if (appointmentId !== '') {
				appointment = items[0]
				if (appointment) {
					lead = appointment.lead
					user = appointment.user
					appointmentDatetime = appointment.appointment_datetime
				}
			} else {
				lead = items[0]
				user = lead.user
			}

			// $('#span_lead_name').text(lead.name)
			// $('#span_lead_email').text(lead.email)
			// $('#span_spouse_name').text(appointment.spouse_name)
			// $('#span_spouse_phone').text(appointment.spouse_phone)
			// $('#span_appointment_datetime').text(appointmentDatetime)
			// $('#span_lead_title').text(lead.title)

			$('#txt_lead_name').val(lead.name)
			$('#txt_lead_email').val(lead.email)
			if (appointment !== undefined) {
				$('#txt_spouse_name').val(appointment.spouse_name)
				$('#txt_spouse_phone').val(appointment.spouse_phone)
				$('#txt_appointment_datetime').val(appointmentDatetime)
			}

			$('#txt_lead_title').val(lead.title)
			if (user) {
				$('#span_tele_marketer').text(user.username)
			}
			$('#span_lead_phone').text(lead.phone)

			$('#txt_lead_id').val(lead.id)
			$('#txt_appointment_id').val(appointmentId)

		}).finally(() => {
			unblock()
		})
	}

	function clearCustomerInfo() {
		$('#span_lead_name').text('')
		$('#span_lead_email').text('')
		$('#span_lead_phone').text('')
		$('#span_lead_title').text('')
		$('#span_tele_marketer').text('')
		$('#span_appointment_datetime').text('')

		$('#txt_lead_id').val('')
		$('#txt_appointment_id').val('')
	}

	function toggleFormEventData(disabled = false) {
		if (disabled) {
			$('#event_data_section').hide()
			$('#event_data_form').find('input, textarea').prop('disabled', disabled)
		} else {
			$('#event_data_section').show()
			$('#event_data_form').find('input, textarea').prop('disabled', disabled)
		}
	}

	function clearFormEventData() {
		$('#event_data_form').resetForm()
	}

	function toggleShowUpSection(isShow = false) {
		if (isShow) {
			$btnShowUp.prop('disabled', false)
			$btnNotShowUp.prop('disabled', false)
		} else {
			$btnShowUp.prop('disabled', true)
			$btnNotShowUp.prop('disabled', true)
		}
	}

	$selectTo.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 8
		},
		column: 'username',
	})
	$selectRep.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 9
		},
		column: 'username',
	})

	$('#modal_lg').on('show.bs.modal', function() {
		$('.select').select2()

		$('#select_province').select2Ajax()
		$(this).find('#txt_phone').alphanum({
			allowMinus: false,
			allowLatin: false,
			allowOtherCharSets: false,
			maxLength: 11,
		})
	})

	$body.on('click', '.link-lead-name', function() {
		let leadId = $(this).data('lead-id')
		let appointmentId = $(this).data('appointment-id')
		fetchLead(leadId, 0, appointmentId).then(() => {
			// toggleShowUpSection(true)
		})
		$('#txt_lead_id').val(leadId)
	})

	$body.on('click', '.btn-change-event-status', function() {
		let message = $(this).data('message')
		let url = $(this).data('url')
		if (url !== '') {
			tableEventData.actionEdit({
				btnEdit: $(this),
				params: {
					state: $(this).data('state'),
				},
				message: message,
			})
		}
	})

	$body.on('submit', '#new_leads_form', function(e) {
		e.preventDefault()

		let formData = new FormData($(this)[0])
		formData.append('form', 'reception')

		$(this).submitForm({url: route('leads.store'), formData: formData}).then(() => {
			$('#modal_lg').modal('hide')
			tableAppointment.reload()
			tableEventData.reload()
		})
	})

	$body.on('click', '#btn_reappointment', function() {
		let leadId = $('#txt_lead_id').val()
		let url = route('appointments.cancel', $('#txt_appointment_id').val())

		blockPage()
		axios.post(url, {}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			$('#modal_md').showModal({
				url: route('leads.form_change_state', leadId), params: {
					typeCall: 4,
					callId: '',
				}, method: 'get',
			})
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$body.on('click', '.link-event-data', function() {
		let eventDataId = $(this).data('event-id')
		let appointmentId = $(this).data('appointment-id')
		let hasBonus = $(this).data('has-bonus')
		let $tr = $(this).parents('tr')
		let leadName = $tr.find('.txt-lead-name').val()
		let leadId = $tr.find('.txt-lead-id').val()
		let eventNote = $tr.find('.txt-event-data-note').val()
		let eventCode = $tr.find('.txt-event-data-code').val()
		let eventStateName = $tr.find('.txt-event-data-state').val()
		let queueText = $tr.find('.txt-event-queue-text').val()

		console.log(leadName, leadId)

		fetchLead('', 0, appointmentId).then(() => {
			// toggleShowUpSection(true)
		})
		$('#span_event_data_status').text(eventStateName)
		$('#span_appointment_queue').text(queueText)
		$('#txt_lead_id').val(leadId)
		$('#txt_event_data_code').val(eventCode)
		$('#txt_note').val(eventNote)
		if (hasBonus === 1) {
			$('input[name="hot_bonus"]').prop('checked', true)
		} else {
			$('input[name="hot_bonus"]').prop('checked', false)
		}

		$('#txt_event_data_id').val(eventDataId)
		toggleFormEventData()

		//4 button
		$('.btn-change-event-status').data('lead-name', leadName)

		let title = $('#btn_busy').data('org-title')
		$('#btn_busy').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName)

		title = $('#btn_overflow').data('org-title')
		$('#btn_overflow').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName)

		title = $('#btn_deal').data('org-title')
		$('#btn_deal').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName)

		title = $('#btn_not_deal').data('org-title')
		$('#btn_not_deal').data('url', route('event_datas.change_state', eventDataId)).data('title', title + leadName)
	})

	$body.on('click', '#btn_cancel_appointment', function() {
		let url = route('appointments.cancel', $('#txt_appointment_id').val())

		blockPage()
		axios.post(url, {}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
			$('#modal_md').modal('hide')
			tableAppointment.reload()
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	$btnShowUp.on('click', function() {
		// toggleFormEventData(false)
		$('#queue_section').show()
	})

	$btnNotShowUp.on('click', function() {
		$('#queue_section').hide()
		let url = route('appointments.not_show_up', $('#txt_appointment_id').val())

		axios.post(url, {
			notQueue: true,
		}).then(result => {
			let obj = result['data']
			flash(obj.message)
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	})

	function doQueue() {
		let url = route('appointments.do_queue', $('#txt_appointment_id').val())

		axios.post(url, {}).then(result => {
			let obj = result['data']
			flash(obj.message)
			tableEventData.reload()
			tableAppointment.reload()
			clearCustomerInfo()
		}).catch(e => console.log(e)).finally(() => {
			unblock()
		})
	}

	$btnQueue.on('click', function() {
		$(this).confirmation(function(confirm) {
			if (confirm && (typeof confirm === 'object' && confirm.value)) {
				doQueue()
			}
		})
	})

	$btnNotQueue.on('click', function() {
		$(this).confirmation(function(confirm) {
			if (confirm && (typeof confirm === 'object' && confirm.value)) {
				let url = route('appointments.do_queue', $('#txt_appointment_id').val())

				axios.post(url, {
					notQueue: true,
				}).then(result => {
					url = route('appointments.form_change_appointment')
					$('#modal_md').showModal({
						url: url, method: 'get',
					})
				}).catch(e => console.log(e)).finally(() => {
					unblock()
				})
			}

		})
	})

	$body.on('click', '#btn_queue_on_modal', function() {
		doQueue()
	})

	$('#event_data_form').on('submit', function(e) {
		e.preventDefault()

		$(this).confirmation(function(confirm) {
			if (confirm && (typeof confirm === 'object' && confirm.value)) {
				let eventDataFormData = new FormData($('#event_data_form')[0])

				$('#event_data_form').submitForm({url: route('event_datas.update', $('#txt_event_data_id').val()), formData: eventDataFormData}).then(() => {
					toggleFormEventData(true)
					tableAppointment.reload()
					tableEventData.reload()
					clearFormEventData()
				})
			}
		})
	})

	$btnSearch.on('click', function() {
		tableAppointment.reload()
		tableEventData.reload()
	})

	$btnNewLead.on('click', function() {
		//todo: form tạo new customer
		$('#modal_lg').showModal({url: route('leads.form_new_lead'), method: 'get'})
	})

	$btnUpdateLeadInfo.on('click', function() {
		let leadId = $('#txt_lead_id').val()
		let url = route('leads.update', {lead: leadId})
		$('#leads_form').submitForm({url: url}).then(() => {
			tableAppointment.reload()
			tableEventData.reload()
		})
	})

	$btnReappointment.on('click', function() {
		let leadId = $('#txt_lead_id').val()
		let appointmentId = $('#txt_appointment_id').val()
		if (appointmentId !== '' && leadId !== '') {
			let url = route('appointments.cancel', appointmentId)

			blockPage()
			axios.post(url, {}).then(result => {
				let obj = result['data']
				if (obj.message) {
					flash(obj.message)
				}
				$('#modal_md').showModal({
					url: route('leads.form_change_state', leadId), params: {
						typeCall: 4,
						callId: '',
						table: '',
						phone: '',
					}, method: 'get',
				})
			}).catch(e => console.log(e)).finally(() => {
				unblock()
			})
		}
	})

	$('.modal').on('show.bs.modal', function() {
		$('#select_state_modal').select2()
		$('#select_time').select2()
		$('#txt_date').datepicker({
			startDate: new Date(),
		})
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
	$body.on('submit', '#change_state_leads_form', function(e) {
		e.preventDefault()

		let isFormValid = validateFormChangeLeadState()
		if (!isFormValid) {
			return
		}

		let formData = new FormData($(this)[0])
		let url = $(this).prop('action')

		axios({
			method: 'post',
			url: url,
			data: formData,
			config: {headers: {'Content-Type': 'multipart/form-data'}},
		}).then(() => {
			tableAppointment.reload()
			$('#modal_md').modal('hide')
		}).finally(() => {
			mApp.unblock('#modal_md')
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
})