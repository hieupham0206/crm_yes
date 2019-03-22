$(function() {
	let isConfirm = $('#payment_details_form').data('confirm')

	$('#payment_details_form').validate({
		submitHandler: isConfirm ? function(form, e) {
			window.blockPage()
			e.preventDefault()

			$(form).confirmation(result => {
				if (result && (typeof result === 'object' && result.value)) {
					$(form).submitForm().then(() => {
						location.href = route('payment_details.index')
					})
				} else {
					window.unblock()
				}
			})
		} : false,
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

		if (newCost !== '' && newCost !== null) {
			if (currentCost !== '') {
				newCost = parseFloat(newCost) + parseFloat(currentCost)
			}
			$('#txt_cost').val(numeral(newCost).format('0,00'))
		}
		// else {
		// 	$('#txt_cost').val('')
		// }
	})
})
