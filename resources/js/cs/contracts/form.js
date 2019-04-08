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

			let addVal = $('#txt_address').val()
			let city = $('#select_address_city').select2('data')[0]['text']
			let county = $('#select_address_county').select2('data')[0]['county_name']
			let ward = $('#select_address_ward').select2('data')[0]['ward_name']
			$('#txt_hidden_address').val(`${addVal} ${ward}, ${county}, ${city}`)

			let addTmpVal = $('#txt_temp_address').val()
			let cityTmp = $('#select_tmp_address_city').select2('data')[0]['text']
			let countyTmp = $('#select_tmp_address_county').select2('data')[0]['county_name']
			let wardTmp = $('#select_tmp_address_ward').select2('data')[0]['ward_name']
			$('#txt_hidden_temp_address').val(`${addTmpVal} ${wardTmp}, ${countyTmp}, ${cityTmp}`)

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

	$('#select_address_county').select2Ajax({
		data(q) {
			q.cityCode = $('#select_address_city').val()
		},
		column: 'county_name',
	})

	$('#select_address_ward').select2Ajax({
		data(q) {
			q.cityCode = $('#select_address_city').val()
			q.countyCode = $('#select_address_county').val() ? $('#select_address_county').select2('data')[0]['county_code'] : 0
		},
		column: 'ward_name',
	})

	$('#select_tmp_address_county').select2Ajax({
		data(q) {
			q.cityCode = $('#select_tmp_address_city').val()
		},
		column: 'county_name',
	})

	$('#select_tmp_address_ward').select2Ajax({
		data(q) {
			q.cityCode = $('#select_tmp_address_city').val()
			q.countyCode = $('#select_tmp_address_county').val() ? $('#select_tmp_address_county').select2('data')[0]['county_code'] : 0
		},
		column: 'ward_name',
	})

	function loadBankBasedOnPaymentMethod(paymentMethod, selectSelector, textSelector) {
		if (paymentMethod !== '') {
			selectSelector.prop('disabled', false).empty().trigger('change')
			axios.get(route('payment_costs.get_bank'), {
				params: {
					method: paymentMethod,
				},
			}).then(result => {
				let items = result['data']['items']

				for (const item of items) {
					let option = new Option(item.bank_name, item.cost, false, false)
					selectSelector.append(option).trigger('change')

					textSelector.val(selectSelector.select2('data')[0]['text'])
				}
			}).catch(e => console.log(e)).finally(() => {
				window.unblock()
			})
		} else {
			selectSelector.prop('disabled', true)
		}
	}

	$('#select_payment_method').on('change', function() {
		loadBankBasedOnPaymentMethod($(this).val(), $('#select_bank'), $('#txt_bank_name'))
	})

	$('#select_payment_installment_id').on('change', function() {
		loadBankBasedOnPaymentMethod($(this).val(), $('#select_bank_installment'), $('#txt_bank_name_installment'))
	})

	$('#select_bank, #select_bank_installment').on('change', function() {
		let currentCost = $('#txt_cost').val()
		let newCost = $(this).val()

		if (currentCost === '') {
			$('#txt_cost').val(numeral(newCost).format('0,00'))
		} else {
			let bankCost = $('#select_bank').val()
			let bankInstallmentCost = $('#select_bank_installment').val()

			newCost = numeral(bankCost).value() + numeral(bankInstallmentCost).value()
			$('#txt_cost').val(numeral(newCost).format('0,00'))
		}

	})

	let tablePaymentDetail = $('#table_payment_detail').DataTable({
		paging: false,
		'columnDefs': [
			{'targets': [0, 1], 'orderable': false, 'width': '5%'},
			{'targets': [-1, -2], 'orderable': false},
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
				`<input class="form-control txt-payment-date" name="PaymentDetail[pay_date][${i}][]" type="text" autocomplete="off" required>`,
				`<input class="form-control txt-total-paid-deal" name="PaymentDetail[total_paid_deal][${i}][]" value="${$leftAmount}" type="text" autocomplete="off">`,
				`<select name="PaymentDetail[payment_method][${i}][]" class="select-payment-method" required>
<option></option>
<option value="1">Tiền mặt</option>
<option value="2">Trả góp ngân hàng</option>
</select>`,
				`
<select class="select-bank" disabled><option></option></select>
<input name="PaymentDetail[bank_name][${i}][]" class="txt-bank-name" type="hidden" />
				`,
			])
		}
		tablePaymentDetail.rows().remove()
		tablePaymentDetail.rows.add(rows).draw(false)
		$('.txt-payment-date').datepicker({
			startDate: new Date(),
		})
		$('.txt-total-paid-deal').numeric()
		$('.select-payment-method, .select-bank').select2()
	})

	$('body').on('change', '.select-payment-method', function() {
		let tr = $(this).parents('tr')

		loadBankBasedOnPaymentMethod($(this).val(), tr.find('.select-bank'), tr.find('.txt-bank-name'))
	})

	$('.identity-number').numeric({
		allowDecimal: false,
		allowMinus: false,  // Allow the - sign
		allowThouSep: false,  // Allow the thousands separator, default is the comma eg 12,000
	})

	$('#select_limit').on('change', function() {

		let limit = $(this).val()
		let amount = 4400000

		if (limit === '2') {
			amount = 5400000
		} else if (limit === '3') {
			amount = 6400000
		}
		$('#txt_year_cost').val(numeral(amount).format('0,00'))
	})

	$('#select_room_type').on('change', function() {
		let roomType = $(this).val()

		if (roomType !== '4') {
			$('#select_limit').select2('open').val(roomType).trigger('change').select2('close')
		}

	})
})