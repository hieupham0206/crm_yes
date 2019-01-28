$(function() {
	const tableCommissionDetail = $('#table_commission_detail_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('commission_details.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#commission_detail_report_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
	})
	$('#commission_detail_report_search_form').on('submit', function() {
		tableCommissionDetail.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#commission_detail_report_search_form').resetForm()
		tableCommissionDetail.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableCommissionDetail.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableCommissionDetail.exportPdf()
	})
})