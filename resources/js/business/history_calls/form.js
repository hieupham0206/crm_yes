$(function() {
	let isConfirm = $('#history_calls_form').data('confirm')

    $('#history_calls_form').validate({
        submitHandler: isConfirm ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm().then(() => {
                        location.href = route('history_calls.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })
})