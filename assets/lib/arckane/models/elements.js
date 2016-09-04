Arckane.model('elements').events.extend({
	load: {
		editor: function() {
			return typeof tinymce == 'function' ? tinymce.init({
				selector: '[contenteditable]',
				language: 'ru',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'template paste textcolor colorpicker textpattern imagetools'
				],
				toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor',
				image_advtab: true
			}) : this;
		},
		listItem: function() {
			!this.index() ? this.addClass('active') : false;
		},
		numRatio: function() {
			if (!this.core.DOM.find('numRatioCheck')) {
				this.append(
					this.core.DOM.create('span', {
						'class': 'icon-24',
						'data-name': 'elements:numRatioCheck',
						'data-enable': 1
					}).append(
						this.core.DOM.create('i', {
							'class': 'fa fa-lock'
						})
					)
				);
			}
			
			this.trigger('render');
		},
		contextMenu: function() {
			this.core.DOM.set('doc', this.core.models.find('element').wrap(document, false, this), true);
		},
		expandList: function(e) {
			this.closest('li').querySelector('.active') ? this.closest('li').querySelector('ul').classList.add('show') : false;
		}
	},
	render: {
		numRatio: function(e) {
			this.core.DOM.unset(['numRatioInput']);

			this.each(this.q('[type="number"]'), function(el) {
				delete el['ratio'];
				el = this.$(el);
				this.core.DOM.find('numRatioCheck') ? el.prop = this.core.DOM.find('numRatioCheck') : 0;
				this.core.DOM.set('numRatioInput', el);
			});

			this.core.DOM.find('numRatioInput')._root._get().reduce(function(c, n) {
				c.ratio = parseInt(c.value) / parseInt(n.value);
			});
		
			this.core.DOM.setEvents();
		}
	},
	click: {
		listItem: function(e) {
			this.addClass('active').subsets().removeClass('active');
		},
		numRatioCheck: function(e) {
			var d = parseInt(this.dataset.enable);
			this.$(this.children[0]).removeClass(d ? 'fa-lock' : 'fa-unlock').addClass(d ? 'fa-unlock' : 'fa-lock');
			this.dataset.enable = d ? 0 : 1;
		},
		expandList: function(e) {
			e.preventDefault();
			this.closest('li').querySelector('ul').classList.toggle('show');
		}
	},
	contextmenu: {
		contextMenuTarget: function(e) {
			e.preventDefault();
			var x = e.pageX, y = e.pageY, menu = this.core.DOM.find('contextMenu').subsets(true).filter(function(el) {
				return el.dataset.id == this.dataset.id;
			}, this)[0], hide = function(e) {
				e.target.closest('menu') != menu ? menu.css({display: 'none'}) : false;
			};

			this.core.DOM.find('doc').on('click', hide);
			
			menu.css({display: 'block', left: x+'px', top: y+'px'});
		}
	},
	change: {
		numRatioInput: function(e) {
			var r = parseFloat(this._root.ratio), prop = parseInt(this.prop.dataset.enable);
			
			if (!prop) return this;
		
			this._root._get().reduce(function(c, n) {
				e.target == c ? n.value = Math.round(parseInt(c.value) / r) : c.value = Math.round(parseInt(n.value) * r);
			});
		},
		checkboxesItem: function(e) {
			var v, r = this.core.DOM.find('checkboxesResult');
			
			if (this.core.DOM.find('checkboxes').dataset.multyple) {
				v = JSON.parse(r.value) || {};
				v[this._index] = this.value;
				if (!this.checked) delete v[this._index];
				v = Object.keys(v).length ? JSON.stringify(v) : 0;
			} else {
				this.each(this._root._get(), function(el) {
					el.checked = false;
				});
				this.checked = true;
				v = this.value;
			}

			r.value = v;
		}
	}
});