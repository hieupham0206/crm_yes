$(function() {
    const $app = $('#app')
    const tableHistoryCall = $('#table_history_calls').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('history_calls.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#history_calls_search_form').serializeArray())
            },
        }),
        conditionalPaging: true,
        info: true,
        lengthChange: true,
		'columnDefs': [
			{
				'targets': [0],
				'visible': false,
			},
		],
    })
    $app.on('click', '.btn-delete', function () {
        tableHistoryCall.actionDelete({btnDelete: $(this)})
    })
    $('#history_calls_search_form').on('submit', function() {
        tableHistoryCall.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#history_calls_search_form').resetForm()
        tableHistoryCall.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tableHistoryCall.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tableHistoryCall.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tableHistoryCall.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })
})