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
			s = this.DOM.create('script', {src: s+'?_='+new Date().getTime()});
			
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
		this.render();
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
			this.core.models.find('modal') && location.hash == this.hash ? log(this.models.find('modal')) : false;
		}
	},
	get: {
		modal: function(e) {
			this.core.ajax({
				url: 'ajax.php?type=modal&todo=get',
				method: 'post',
				contentType: 'json',
				data: this.core.history.last().param,
				done: function(r) {
					log(r.message);
					if (r.type != 'success') return;log(this)
					this.loadModel(r.scripts);

					/* modal.build({
						core: this,
						close: function() {
							log(history.go(-1))
						}
					}).DOM.find('modal').append(this.DOM.fromString(r.content)); */
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