$(function() {
    const $app = $('#app')
	let $selectTo = $('#select_to'),
		$selectRep = $('#select_rep')

    const tableEventData = $('#table_event_datas').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('event_data_receps.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#event_datas_search_form').serializeArray())
            },
        }),
        conditionalPaging: true,
        info: true,
        lengthChange: true,
    })

    $app.on('click', '.btn-delete', function () {
        tableEventData.actionDelete({btnDelete: $(this)})
    })
    $('#event_datas_search_form').on('submit', function() {
        tableEventData.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#event_datas_search_form').resetForm()
        tableEventData.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tableEventData.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tableEventData.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tableEventData.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })

	$selectTo.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 8
		},
		column: 'username',
	})
	$selectRep.select2Ajax({
		url: route('users.list'),
		data: function(q) {
			q.roleId = 9
		},
		column: 'username',
	})
})