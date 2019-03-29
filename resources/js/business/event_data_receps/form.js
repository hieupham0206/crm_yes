$(function() {
	let isConfirm = $('#contracts_form').data('confirm')
	let $selectTo = $('#select_to'),
		$selectRep = $('#select_rep'),
		$selectCs = $('#select_cs')

    $('#contracts_form').validate({
        submitHandler: isConfirm ? function(form, e) {
            window.blockPage()
            e.preventDefault()

            $(form).confirmation(result => {
                if (result && (typeof result === 'object' && result.value)) {
                    $(form).submitForm().then(() => {
                        location.href = route('contracts.index')
                    })
                } else {
                    window.unblock()
                }
            })
        } : false,
    })

	$selectTo.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 8
		},
		column: 'name',
	})
	$selectCs.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 12
		},
		column: 'name',
	})
	$selectRep.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 9
		},
		column: 'name',
	})
})