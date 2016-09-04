Arckane.model('form', {
	options: {
		done: function() {}
	},
	getData: function(form) {
		var es, fd = new FormData();
			
		if (form.tagName != 'FORM') {
			es = form.q('input').filter(function(e) {
				return e.name && e.tagName;
			});
			fd = Object.create(proto);
		} else {
			es = [].map.call(form.elements, function(e) {
				return e.name ? e : false;
			});
		}
		
		this.each(es, function(e) {
			if (e.files && e.files.length) {
				this.each(e.files, function(f) {
					fd.append(e.name+'[]', f);
				});
			}
			
			if ((e || {}).type == 'checkbox' && (!parseInt(e.value) || !parseInt(e.value) - 1)) {
				e.checked ? e.value = 1 : false;
			}
			
			e.name ? fd.append(e.name, e.getAttribute && e.getAttribute('encode') ? md5(e.value) : e.value) : false;
		});
		
		return fd;
	}
}).events.extend({
	render: {
		status: function(e) {
			this.trigger('reset').addClass(e.data.type).text(e.data.message);
			if (e.data.type == 'error') return this;
			
			setTimeout(function(e) {
				e.trigger('reset');
			}, 5000, this);
		}
	},
	reset: {
		status: function(e) {
			this.removeClass('error').removeClass('success').text('');
		}
	},
	submit: {
		form: function(e) {
			e.preventDefault();

			this.core.DOM.set('status', this.q('.confirm'), true);
			this.core.DOM.setEvents().find('status').trigger('reset');

			this.core.ajax.call(this.core, {
				url: this.action,
				method: this.method,
				contentType: 'json',
				formData: false,
				data: this.core.getData(this),
				done: function(r) {
					this.options.done ? this.options.done.call(this, r) : false;
					this.DOM.find('status').trigger('render', r);
				}
			});
		}
	}
});