$(function() {
    const $app = $('#app')
    const tableContract = $('#table_contracts').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('contracts.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#contracts_search_form').serializeArray())
            },
        }),
		'columnDefs': [
			{
				'targets': [-1],
				'searchable': false,
				'orderable': false,
				'visible': true,
				'width': '10%',
			},
		],
		"ordering": false,
        conditionalPaging: true,
        info: true,
        lengthChange: true,
    })
    $app.on('click', '.btn-delete', function () {
        tableContract.actionDelete({btnDelete: $(this)})
    })
    $('#contracts_search_form').on('submit', function() {
        tableContract.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#contracts_search_form').resetForm()
        tableContract.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tableContract.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tableContract.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tableContract.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })
})