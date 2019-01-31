$(function() {
	const tableCommissionUser = $('#table_commission_user_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('commission_users.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#commission_user_report_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
	})
	$('#commission_user_report_search_form').on('submit', function() {
		tableCommissionUser.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#commission_user_report_search_form').resetForm()
		tableCommissionUser.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableCommissionUser.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableCommissionUser.exportPdf()
	})
})