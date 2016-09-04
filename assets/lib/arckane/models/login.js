Arckane.model('login').events.extend({
	click: {
		button: function(e) {
			this.core.DOM.find('form').trigger('submit');
		}
	},
	submit: {
		form: function(e) {
			e.preventDefault();
			
			this.core.ajax({
				url: 'ajax.php',
				method: 'post',
				contentType: 'json',
				formData: true,
				data: this.core.models.find('form').getData(this).append('controller', 'login').append('todo', 'submit'),
				done: function(r) {
					if (e.data.type == 'error') return this;
			
					setTimeout(function() {
						!location.hash ? location.hash = '#contents' : false;
						location.reload(true);
					}, 100);
				}
			});
		}
	}
});