Arckane.model('contents').events.extend({
	load: {
		form: function() {
			this.trigger('resize');
		}
	},
	resize: {
		form: function(e) {
			var h = this.core.models.find('router').DOM.find('section').position.height - this.core.models.find('tabs').DOM.find('nav').position.y;
			
			this.core.models.find('tabs').DOM.find('tab').subsets(true).css({height: h - 6+'px'});
		}
	}
});