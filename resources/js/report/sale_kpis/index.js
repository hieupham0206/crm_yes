$(function() {
	const tableSaleKpi = $('#table_sale_kpi_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('sale_kpis.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#sale_kpi_report_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
		iDisplayLength: 50
	})
	$('#sale_kpi_report_search_form').on('submit', function() {
		tableSaleKpi.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#sale_kpi_report_search_form').resetForm()
		tableSaleKpi.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableSaleKpi.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableSaleKpi.exportPdf()
	})
})