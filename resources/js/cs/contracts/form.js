$(function() {
	let isConfirm = $('#contracts_form').data('confirm')

	$('#contracts_form').validate({
		rules: {
			email: {
				email(element) {
					let val = $(element).data('value')
					return val === ''
				},
			},
			spouse_email: {
				email(element) {
					let val = $(element).data('value')
					return val === ''
				},
			},
		},
		submitHandler: isConfirm ? function(form, e) {
			window.blockPage()
			e.preventDefault()

			$(form).confirmation(result => {
				if (result && (typeof result === 'object' && result.value)) {
					let fd = new FormData(form)
					fd.append('bank_name', $('#select_bank').select2('data')[0]['text'])
					$(form).submitForm().then(() => {
						location.href = route('contracts.index')
					})
				} else {
					window.unblock()
				}
			})
		} : false,
	})

	$('#select_province').select2Ajax({
		data(q) {
			q.provinceIds = [24, 28, 30]
		},
	})

	$('#select_payment_method').on('change', function() {
		if ($(this).val() !== '') {
			$('#select_bank').prop('disabled', false).empty().trigger('change')
			axios.get(route('payment_costs.get_bank'), {
				params: {
					method: $(this).val(),
				},
			}).then(result => {
				let items = result['data']['items']

				for (const item of items) {
					let option = new Option(item.bank_name, item.cost, false, false)
					$('#select_bank').append(option).trigger('change')
					$('#txt_bank_name').val($('#select_bank').select2('data')[0]['text'])
					console.log($('#select_bank').select2('data')[0]['text'])

				}
			}).catch(e => console.log(e)).finally(() => {
				window.unblock()
			})
		} else {
			$('#select_bank').prop('disabled', true)
		}
	})

	$('#select_bank').on('change', function() {
		if ($(this).val() !== '') {
			$('#txt_cost').val($(this).val())
		} else {
			$('#txt_cost').val('')
		}
	})

	let tablePaymentDetail = $('#table_payment_detail').DataTable({
		paging: false,
		'columnDefs': [
			{'targets': [-1], 'orderable': false, 'width': '5%'},
		],
	})

	$('#btn_add_payment_detail').on('click', function() {
		let paymentTime = parseInt($('#txt_payment_time').val())
		if (paymentTime > 1000) {
			return
		}
		let rows = [], $leftAmount = 0
		let totalAmount = $('#txt_amount').val()
		let firstPaid = $('#txt_total_paid_deal').val()

		if (totalAmount !== '') {
			$leftAmount = numeral((numeral(totalAmount).value() - numeral(firstPaid).value()) / numeral(paymentTime).value()).format('0,00')
		}

		for (let i = 0; i < paymentTime; i++) {
			rows.push([
				`<input class="form-control txt-payment-date" name="PaymentDetail[pay_date][${i}][]" type="text" autocomplete="off">`,
				`<input class="form-control txt-total-paid-deal" name="PaymentDetail[total_paid_deal][${i}][]" value="${$leftAmount}" type="text" autocomplete="off">`,
			])
		}
		tablePaymentDetail.rows().remove()
		tablePaymentDetail.rows.add(rows).draw(false)
		$('.txt-payment-date').datepicker({
			startDate: new Date(),
		})
		$('.txt-total-paid-deal').numeric()
	})

	$('.identity-number').numeric({
		allowDecimal: false,
		allowMinus: false,  // Allow the - sign
		allowThouSep: false,  // Allow the thousands separator, default is the comma eg 12,000
	})
})