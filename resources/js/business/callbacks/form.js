$(function() {
	let isConfirm = $('#callbacks_form').data('confirm')

    $('#callbacks_form').validate({
        submitHandler: isConfirm ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm().then(() => {
                        location.href = route('callbacks.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })
})