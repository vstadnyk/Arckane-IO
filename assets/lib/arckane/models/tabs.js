Arckane.model('tabs').events.extend({
	load: {
		tab: function(e) {
			!this._index ? this.addClass('active') : false;
		},
		nav: function(e) {
			!this._index ? this.addClass('active') : false;
		}
	},
	click: {
		nav: function(e) {
			e.preventDefault();
			this.addClass('active').subsets().removeClass('active');
			this.core.DOM.find('tab').subsets(true).removeClass('active').eq(this._index).addClass('active');
		}
	}
});