$(function() {
    const $app = $('#app')
    const tablePaymentCost = $('#table_payment_costs').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('payment_costs.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#payment_costs_search_form').serializeArray())
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
        tablePaymentCost.actionDelete({btnDelete: $(this)})
    })
    $('#payment_costs_search_form').on('submit', function() {
        tablePaymentCost.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#payment_costs_search_form').resetForm()
        tablePaymentCost.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tablePaymentCost.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tablePaymentCost.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tablePaymentCost.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })
})