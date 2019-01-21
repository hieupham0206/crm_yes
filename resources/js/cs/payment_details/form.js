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
})
