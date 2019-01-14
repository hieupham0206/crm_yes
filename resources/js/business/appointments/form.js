$(function() {
	let isConfirm = $('#appointments_form').data('confirm')

    $('#appointments_form').validate({
        submitHandler: isConfirm ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm().then(() => {
                        location.href = route('appointments.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })
})