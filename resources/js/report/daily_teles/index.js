$(function() {
	const tableDailyTele = $('#table_daily_tele_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('daily_teles.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#daily_tele_report_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
		iDisplayLength: 50
	})
	$('#daily_tele_report_search_form').on('submit', function() {
		tableDailyTele.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#daily_tele_report_search_form').resetForm()
		tableDailyTele.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableDailyTele.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableDailyTele.exportPdf()
	})
})