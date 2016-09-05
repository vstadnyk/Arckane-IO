Arckane.model('router', {
	scripts: Object.create(proto),
	start: function() {
		this.DOM.set('root', this.models.find('element').wrap(window, false, this), true);
		this.DOM.setEvents().find('root').once('load', this.run).on('hashchange', this.run);
		
		return this;
	},
	run: function() {
		var hash = location.hash.replace(/#/, '').split('='),
			search = hash[0],
			param = hash[1] ? JSON.parse(decodeURIComponent(hash[1])) : {},
			menu = this.core.DOM.find('menu')._root._get().filter(function(el) {
				return el.hash && el.hash == '#'+search;
			});
		
		this.core.set('history', {
			search: search,
			param: param,
			menu: menu.length ? menu.shift()._index : 0,
			last: function() {
				return this._get().pop();
			},
			current: function(k) {
				return k ? this.last().param[k] : this.last().param;
			}
		});

		this.core.DOM.trigger('render');
		this.core.DOM.find(search) ? this.core.DOM.find(search).trigger('hashchange', param) : false;
	},
	loadModel: function(scripts) {
		this.each(scripts, function(s, k) {
			s = this.DOM.create('script', {src: s});
			
			s.on('load', function() {
				k == scripts.length - 1 ? this.core.render() : false;
			})._name = s.src.replace(/^.*[\\\/]/, '');

			if (!this.scripts.find(s._name)) {
				this.scripts.set(s._name, s, true);
				document.body.appendChild(s);
			} else {
				this.render();
			}
		});
	},
	render: function() {
		this.each(this.models, function(m) {
			m.DOM.empty().init();
			m.DOM.trigger('load');
		});
	}
}).start().events.extend({
	load: {
		modal: function(e) {
			var modal = this.core.models.find('modal');
			if (!modal || location.hash != this.hash || !this._root.content) return this;

			modal.build({
				core: this,
				open: function() {
					this.core.models.find('elements') ? this.core.models.find('elements').DOM.init() : false;
				},
				close: function() {
					history.go(-1);
					jQuery.magnificPopup.close();
				}
			}).DOM.find('modal').append(this.core.DOM.fromString(this._root.content)).trigger('build');
		}
	},
	get: {
		modal: function(e) {
			var _this = this._root;
			
			this.core.models.find('modal') ? this.core.models.find('modal').close() : false;
			
			this.core.ajax({
				url: 'ajax.php?type=modal&todo=get',
				method: 'post',
				contentType: 'json',
				data: this.core.history.last().param,
				done: function(r) {
					log(r.message);
					if (r.type != 'success') return;
					_this.content = r.content;
					this.loadModel(r.scripts);
				}
			});
		}
	},
	resize: {
		root: function() {
			this.each(this.core.models, function(m) {
				m.DOM.trigger('resize');
			});
		}
	},
	hashchange: {
		modal: function(e) {
			this.trigger('get');
		}
	}
});