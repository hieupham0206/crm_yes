$(function() {
	const tableDailySale = $('#table_daily_sale_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('daily_sales.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#daily_sale_report_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
		iDisplayLength: 50
	})
	$('#daily_sale_report_search_form').on('submit', function() {
		tableDailySale.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#daily_sale_report_search_form').resetForm()
		tableDailySale.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableDailySale.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableDailySale.exportPdf()
	})
})