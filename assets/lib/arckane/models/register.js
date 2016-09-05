Arckane.model('register').events.extend({
	load: {
		form: function(e) {
			var form = this.core.models.find('form');
			
			form.options.done = function(r) {
				setTimeout(function() {
					if (r.type == 'error') return this;
					history.go(-1);
					location.reload(true);
				}, 100);
			}
			
			this.core = form;

			form.DOM.set('form', this, true);
			form.DOM.setEvents();
			form.DOM.trigger('load');
		}
	}
});