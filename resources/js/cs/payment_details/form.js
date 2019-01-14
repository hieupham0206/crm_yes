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
})