$(function() {
	const $body = $('body')
	const tableCommissionRole = $('#table_commission_role_report').DataTable({
		'serverSide': true,
		'paging': true,
		'ajax': $.fn.dataTable.pipeline({
			url: route('commission_roles.table'),
			data: function(q) {
				q.filters = JSON.stringify($('#commission_detail_role_search_form').serializeArray())
			},
		}),
		conditionalPaging: true,
		info: false,
		lengthChange: false,
		iDisplayLength: 20,
	})
	$('#commission_detail_role_search_form').on('submit', function() {
		tableCommissionRole.reload()
		return false
	})
	$('#btn_reset_filter').on('click', function() {
		$('#commission_detail_role_search_form').resetForm()
		tableCommissionRole.reload()
	})

	//Export tools
	$('#btn_export_excel').on('click', function() {
		tableCommissionRole.exportExcel()
	})
	$('#btn_export_pdf').on('click', function() {
		tableCommissionRole.exportPdf()
	})

	$body.on('click', '.btn-save-commission-role', function() {
		let url = $(this).data('url')
		let roleId = $(this).data('role-id')
		let commissionRoleId = $(this).data('commission-role-id')
		let tr = $(this).parents('tr')

		let percentCommission = tr.find('.txt-percent-commission').val()
		let level = tr.find('.txt-level').val()
		let bonusCommission = tr.find('.txt-bonus-commission').val()
		let dealCompleted = tr.find('.txt-deal-completed').val()

		axios.post(url, {
			percentCommission: percentCommission,
			level: level,
			bonusCommission: bonusCommission,
			dealCompleted: dealCompleted,
			roleId: roleId,
			commissionRoleId: commissionRoleId,
		}).then(result => {
			let obj = result['data']
			if (obj.message) {
				flash(obj.message)
			}
		}).catch(e => console.log(e)).finally(() => {
			window.unblock()
		})
	})
})