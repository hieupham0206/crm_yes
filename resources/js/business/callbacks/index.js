$(function() {
    const $app = $('#app')
    const tableCallback = $('#table_callbacks').DataTable({
        'serverSide': true,
        'paging': true,
        'ajax': $.fn.dataTable.pipeline({
            url: route('callbacks.table'),
            data: function(q) {
                q.filters = JSON.stringify($('#callbacks_search_form').serializeArray())
            },
        }),
        conditionalPaging: true,
        info: true,
        lengthChange: true,
		columnDefs: []
    })
    $app.on('click', '.btn-delete', function () {
        tableCallback.actionDelete({btnDelete: $(this)})
    })
    $('#callbacks_search_form').on('submit', function() {
        tableCallback.reload()
        return false
    })
    $('#btn_reset_filter').on('click', function() {
        $('#callbacks_search_form').resetForm()
        tableCallback.reload()
    })

    //Export tools
    $('#btn_export_excel').on('click', function() {
        tableCallback.exportExcel()
    })
    $('#btn_export_pdf').on('click', function() {
        tableCallback.exportPdf()
    })
    //Quick actions
    $('#link_delete_selected_rows').on('click', function() {
        let ids = $(".m-checkbox--single > input[type='checkbox']:checked").getValues()

        if (ids.length > 0) {
            tableCallback.actionDelete({btnDelete: $(this), params: {ids: ids}})
        }
    })
})