$(function() {
    const $app = $('#app')
    const tablePaymentDetail = $('#table_payment_details').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('payment_details.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#payment_details_search_form').serializeArray())
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
        tablePaymentDetail.actionDelete({btnDelete: $(this)})
    })
    $('#payment_details_search_form').on('submit', function() {
        tablePaymentDetail.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#payment_details_search_form').resetForm()
        tablePaymentDetail.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tablePaymentDetail.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tablePaymentDetail.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tablePaymentDetail.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })
})