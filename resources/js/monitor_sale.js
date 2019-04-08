$(function() {
	function initLoginTime() {
		let loginTimer = new Timer()
		loginTimer.addEventListener('secondsUpdated', () => {
			this.html(loginTimer.getTimeValues().toString())
		})

		let timeInSecond = this.data('time-in-second')
		if (timeInSecond > 0) {
			loginTimer.start({precision: 'seconds', startValues: {seconds: timeInSecond}})
		}
	}

	$('.span-login-time').each(function() {
		if ($(this).data('is-online')) {
			initLoginTime.call($(this))
		}
	})

	function initCallTime() {
		let callTimer = new Timer()
		callTimer.addEventListener('secondsUpdated', () => {
			this.html(callTimer.getTimeValues().toString())
		})

		let timeInSecond = this.data('call-time-in-second')
		if (timeInSecond !== '0') {
			callTimer.start({precision: 'seconds', startValues: {seconds: timeInSecond}})
		}
	}

	// $('.span-call-time').each(function() {
	// 	initCallTime.call($(this))
	// })

	function loadSectionMonitor (params = {}) {
		blockPage()

		axios.get(route('monitor_sale.section_monitor'),
			{
				params: params
			}
		).then(result => {
		    $('#section_monitor_sale').html(result.data)

			$('.span-login-time').each(function() {
				if ($(this).data('is-online')) {
					initLoginTime.call($(this))
				}
			})
		}).catch(e => console.log(e)).finally(() => {
		    unblock()
		})
	}

	$('.btn-filter-call').on('click', function() {
		let filter = $(this).data('filter')

		loadSectionMonitor({
			filter: filter
		})
	})

	$('body').on('click', '.link-form-detail', function() {
		let url = $(this).data('url')

		$('#modal_md').showModal({url: url, method: 'get'})
	})

	$('#modal_md').on('shown.bs.modal', function() {
		let className = $('#txt_form_modal_bg').val()

		$(this).addClass(className)

		$('.span-call-time').each(function() {
			initCallTime.call($(this))
		})
	})

	setInterval(() => {
		loadSectionMonitor()
	}, 1000 * 60 * 5)

	//5p load trang 1 lần

	setTimeout(() => {
		location.reload()
	}, 1000 * 60 * 5)
})