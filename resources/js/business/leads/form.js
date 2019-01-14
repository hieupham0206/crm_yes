$(function() {
    $('#leads_form').validate({
		rules: {
			email: {
				email() {
					return $('#txt_email').val() !== ''
				}
			}
		},
        submitHandler: $(this).data('confirm') ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            let isConfirm = $(form).data('confirm')
            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm({
                        data: {
                            'isConfirm': isConfirm,
                        },
                    }).then(() => {
                        location.href = route('leads.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })
})