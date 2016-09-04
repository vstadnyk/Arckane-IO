Arckane.model('modal', {
	_default: {
		core: Arckane,
		open: function() {},
		close: function() {}
	},
	options: Object.create(proto),
	build: function(options) {
		this.options.extend(this._default).extend(options || {});
		this.DOM.set('modal', this.DOM.create('div', {'class':'modal'}));
		this.DOM.init();
		return this;
	}
}).events.extend({
	build: {
		modal: function(e) {
			var _that = this.core;
			
			jQuery.magnificPopup.open({
				items: {
					src: _that.DOM.find('modal'),
					type: 'inline',
				},
				callbacks: {
					open: function() {
						_that.options.open.call(_that.options.core, _that);
					},
					close: function() {
						_that.options.close.call(_that.options.core, _that);
					},
				}
			});
		}
	},
	click: {
		close: function(e) {
			jQuery.magnificPopup.close();
		}
	}
});