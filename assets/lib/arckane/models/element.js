Arckane.model('element', {
	wrap: function(e, selector, core) {
		var name = selector ? e.getAttribute(selector.replace(/[[\]]/g, '')) : false;
		
		e = Object.defineProperties(e, this.defines);
		e._proto = this;

		core ? e.core = core : false;
		
		if (!name) return e;
		
		e._name = /\:/.test(name) ? name.split(':')[1] : name;
		e._core = /\:/.test(name) ? name.split(':')[0] : false;
		
		return e;
	},
	create: function(tag, attributes, core, selector) {
		return this.wrap(document.createElement(tag), selector, core).attr(attributes);
	},
	defines: {
		_name: {
			writable: true,
			configurable: true
		},
		_core: {
			writable: true
		},
		info: {
			get: function() {
				return {name: this._name, core: this._core};
			},
			configurable: true,
		},
		core: {
			writable: true
		},
		_proto: {
			writable: true,
			enumerable: false
		},
		each: {
			get: function() {
				return this.core.each;
			},
			configurable: true,
			enumerable: false
		},
		index: {
			value: function() {
				return Array.prototype.slice.call(this.parentNode.children).indexOf(this);
			},
			configurable: true
		},
		eq: {
			value: function(k) {
				return !Array.isArray(this) && this._root ? this._root._get(k) : this[k];
			},
			configurable: true
		},
		subsets: {
			value: function(index) {
				index = index ? -1 : this._index;
				
				return this._proto.wrap(this._root._get().filter(function(e) {
					return e._index != index;
				}, this), false, this.core);
			}
		},
		q: {
			value: function(q) {
				var f = [], _this = this;
				
				f = f.map.call(this.querySelectorAll(q), function(e) {
					return _this._proto.wrap(e, false, _this.core);
				});

				return f.length - 1 ? f : f[0];
			},
			configurable: true
		},
		$: {
			value: function(e) {
				return this._proto.wrap(e, false, this.core);
			},
			configurable: true
		},
		remove: {
			value: function() {
				return this._unset().parentNode.removeChild(this);
			},
			configurable: true
		},
		on: {
			value: function(ev, fn, data) {
				if (!this.addEventListener) return this;
				
				if (/, /.test(ev)) {
					this.each(ev.split(', '), function(ev) {
						this.on(ev, fn, data);
					});
					
					return this;
				}
				
				this.addEventListener(Object.assign(ev, {data: data || false}), fn, false);
				
				return this;
			},
			configurable: true
		},
		_one: {
			value: false,
			writable: true,
			enumerable: false
		},
 		one: {
			get: function() {
				return this._one;
			},
			set: function(v) {
				this._one = v;
			},
			configurable: true
		},
		once: {
			value: function(ev, fn, data) {
				this.one = true;
				return this.on(ev, fn, data);
			},
			configurable: true
		},
 		off: {
			value: function(ev) {
				this.removeEventListener(ev, this.core.events[ev][this._name], false);

				return this;
			},
			configurable: true
		},
		run: {
			value: function(ev, data) {
				this.dispatchEvent(Object.assign(ev, {data: data || false}));
				
				return this;
			},
			configurable: true
		},
		trigger: {
			value: function(ev, data) {
				if (Array.isArray(this)) {
					this.each(function(e) {
						e.trigger(ev, data);
					});
				}

				!this.one ? this.run(new CustomEvent(ev), data) : false;
				
				return this;
			},
			configurable: true
		},
		removeClass: {
			value: function(c) {
				if (Array.isArray(this)) {
					this.each(function(e) {
						e ? e.removeClass(c) : false;
					});
				}
				
				this.tagName ? this.classList.remove(c) : false;
				
				return this;
			},
			configurable: true
		},
		addClass: {
			value: function(c) {
				this.classList.add(c);

				return this;
			},
			configurable: true
		},
		toggleClass: {
			value: function(c) {
				this.classList.toggle(c);
				
				return this;
			},
			configurable: true
		},
		empty: {
			value: function() {
				while (this.firstChild) {
					this.removeChild(this.firstChild);
				}
				
				return this;
			},
			configurable: true
		},
		append: {
			value: function(child) {
				this.appendChild(child);
				
				return this;
			},
			configurable: true
		},
		css: {
			value: function(css) {
				if (Array.isArray(this)) {
					this.each(function(e) {
						e ? e.css(css) : false;
					});
				} else {
					this.each(css, function(value, key) {
						this.style[key] = value;
					});
				}				
				
				return this;
			},
			configurable: true
		},
		position: {
			get: function() {
				return this.getBoundingClientRect();
			}
		},
		text: {
			value: function(txt) {
				this.textContent = txt;
				
				return this;
			},
			configurable: true
		},
		attr: {
			value: function(attributes) {
				if (attributes) {
					this.each(attributes, function(v, k) {
						this.setAttribute(k, v);
					});
				}
				
				return this;
			}
		}
	}
});