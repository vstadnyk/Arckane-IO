Arckane.model('modal', {
	_default: {
		core: Arckane,
		open: function() {},
		close: function() {}
	},
	options: Object.create(proto),
	build: function(options) {
		var _that = this;
		
		this.options.extend(this._default).extend(options || {});
		
		this.DOM.set('modal', this.DOM.create('div', {'class':'modal'}));
		
		jQuery.magnificPopup.open({
			items: {
				src: this.DOM.find('modal'),
				type: 'inline',
			},
			callbacks: {
				open: function() {
					_that.options.open.call(_that.options.core, this);
				},
				close: function() {
					_that.options.close.call(_that.options.core, this);
				},
			}
		});
		
		this.DOM.init();
		
		return this;
	}
}).events.extend({
	click: {
		close: function(e) {
			jQuery.magnificPopup.close();
		}
	}
});