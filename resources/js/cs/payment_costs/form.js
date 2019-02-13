$(function() {
	let isConfirm = $('#payment_costs_form').data('confirm')

    $('#payment_costs_form').validate({
        submitHandler: isConfirm ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm().then(() => {
                        location.href = route('payment_costs.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })

	$('#txt_cost').numeric()
})