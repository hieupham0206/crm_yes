$(function() {
	const $app = $('#app')
	const tableAppointment = $('#table_appointments').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('appointments.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#appointments_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: true,
		lengthChange: true,
		columnDefs: [],
	})
	$app.on('click', '.btn-delete', function() {
		tableAppointment.actionDelete({btnDelete: $(this)})
	})

	$('#appointments_search_form').on('submit', function() {
		tableAppointment.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#appointments_search_form').resetForm()
		tableAppointment.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableAppointment.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableAppointment.exportPdf()
	})
	//Quick actions
	$('#link_delete_selected_rows').on('click', function() {
		let ids = $('.m-checkbox--single > input[type=\'checkbox\']:checked').getValues()

		if (ids.length > 0) {
			tableAppointment.actionDelete({btnDelete: $(this), params: {ids: ids}})
		}
	})
})