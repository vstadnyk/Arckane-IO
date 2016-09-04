Arckane.model('upload', {
	file: function(file) {
		var reader = new FileReader(), core = this.core;

		reader.readAsBinaryString(file);
		reader.onload = function() {
			file.src = 'data:'+file.type+';base64,'+btoa(this.result);
			core.DOM.find('select').trigger('render', file);
		};
	},
	thumb: function() {
		this.data.height = this.height;
		this.data.width = this.width;

		this.src = this.core.models.find('image').resize(this, {
			mime: this.data.type,
			background: false
		}).src;

		this.onload = false;
		
		this.core.DOM.set('thumbs', this);
		this.core.DOM.setEvents();
	},
	clear: function() {
		this.DOM.unset(['thumbs', 'item', 'itemSize', 'itemRemove']);
		return this;
	}
}).events.extend({
	load: {
		thumbs: function(e) {
			var item = this.core.DOM.create('div', {
				'class': 'row-33',
				'data-name': 'upload:item'
			});
			
			item.append(
				this.core.DOM.create('div', {
					'class': 'padding-5'
				}).append(
					this.core.DOM.create('div', {
						'class': 'bg-center no-repeat radius-3 white1 relative hidden',
						'data-name': 'elements:listItem'
					}).css({
						backgroundImage: 'url('+this.src+')',
						height: '220px'
					}).append(
						this.core.DOM.create('div', {
							'class': 'absolute row-100 black-5'
						}).css({
							top: '0px'
						}).append(
							this.core.DOM.create('div', {
								'class': 'padding-5 align-right'
							}).append(
								this.core.DOM.create('span', {
									'class': 'icon-24 color-white',
									'data-name': 'upload:itemRemove'
								}).append(
									this.core.DOM.create('i', {
										'class': 'fa fa-trash'
									})
								)
							)
						)
					).append(
						this.core.DOM.create('div', {
							'class': 'absolute row-100 black-5'
						}).css({
							bottom: '0px'
						}).append(
							this.core.DOM.create('div', {
								'class': 'hidden color-white padding-5-0 align-center'
							}).css({margin: '5px'})
							.append(
								this.core.DOM.create('span', {
									'data-name': 'upload:itemName'
								})
							)
							.append(
								this.core.DOM.create('div', {
									'class': 'align-right size-11'
								}).css({paddingTop: '7px'})
								.append(
									this.core.DOM.create('span', {
										'data-name': 'upload:itemSize'
									})
								)
								.append(
									this.core.DOM.create('span', {
										'class': 'left',
										'data-name': 'upload:itemResolution'
									})
								)
							)
						)
					)
				)
			);
			
			item.data = this.data;
			this.core.clear().DOM.find('select').append(item);
			this.core.DOM.init().find('item').subsets(true).trigger('render').trigger('load');
			this.core.models.find('elements').DOM.empty().init().trigger('load');
			this.core.DOM.find('editImage').trigger('show');
		},
		item: function(e) {
			return !this._index ? this.trigger('click') : this;
		}
	},
	render: {
		select: function(e) {
			var img = this.core.DOM.create('img', {
				src: e.data.src,
				'data-name': 'upload:thumbs'
			});
			
			img.data = e.data;
			img.onload = this.core.thumb;
		},
		item: function(e) {
			this.data.itemName = this.data.name;
			this.data.itemSize = (Math.round(this.data.size / 1024))+' kb';
			this.data.itemResolution = this.data.width + 'x' + this.data.height;
			
			this.each(this.q('*').filter(function(el) {
				return el._name;
			}), function(el) {
				if (el._name in this.data) el.text(this.data[el._name]);
			});
			
			return this;
		}
	},
	show: {
		editImage: function(e) {
			this.core.DOM.find('reset').removeClass('hide');
			this.$(this.parentNode).removeClass('hide').addClass('row-30');
			this.$(this.core.DOM.find('select').parentNode).removeClass('row-100').addClass('row-70');
		}
	},
	hide: {
		editImage: function(e) {
			this.core.DOM.find('reset').addClass('hide');
			this.$(this.parentNode).removeClass('row-30').addClass('hide');
			this.$(this.core.DOM.find('select').parentNode).removeClass('row-70').addClass('row-100');
		}
	},
	remove: {
		item: function(e) {
			this.remove();
			!this._root._get().length ? this.core.DOM.find('editImage').trigger('hide') : false;
		}
	},
	click: {
		select: function(e) {
			if (e.target != this) return false;
			this.core.DOM.find('file').click();
			
			return this;
		},
		item: function(e) {
			var d = this.core.DOM;
			
			d.find('fileName').value = this.data.name;
			d.find('fileWidth').value = this.data.width;
			d.find('fileHeight').value = this.data.height;
			
			this.core.models.find('elements').DOM.find('numRatio').trigger('render');
			d.active = this._index;
		},
		itemRemove: function(e) {
			this.core.DOM.find('item').eq(this._index).trigger('remove');
		},
		save: function(e) {
			this.core.DOM.find('editImage').trigger('submit');			
		}
	},
	submit: {
		editImage: function(e) {
			this.core.DOM.find('active').trigger('render', this.core.models.find('form').getData(this));
		}
	},
	reset: {
		form: function(e) {
			this.core.DOM.find('item').subsets(true).trigger('remove');			
			this.core.DOM.find('editImage').trigger('hide');
			this.core.clear();
		}
	},
	change: {
		file: function(e) {
			this.each(this.files, this.core.file);
		}
	}
});