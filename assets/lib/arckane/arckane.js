var log = function(l) {
	console.log(l)
},
proto = {};

(function (name, selector) {
	'use strict';
	
	var DOM = Object.create(proto);
	
	Object.defineProperties(proto, {
		parent: {
			set: function(obj) {
				Object.defineProperty(this, 'obj', {value: obj})
			},
			get: function() {
				return Object.getOwnPropertyDescriptor(this, 'obj').value;
			},
			configurable: true
		},
		set: {
			value: function(key, value, rewrite) {
				Object.defineProperties(value, {
					_parent: {
						value: this,
						configurable: true
					},
					_name: {
						value: key,
						writable: true,
						configurable: true,
						enumerable: rewrite
					},
					_index: {
						value: 0,
						writable: true,
						configurable: true,
						enumerable: rewrite ? false : true
					},
					_subsets: {
						value: [],
						writable: true,
						configurable: true,
						enumerable: rewrite ? false : true
					},
					_root: {
						get: function() {
							return this._parent[this._name];
						},
						configurable: true,
						enumerable: false
					},
					_equal: {
						get: function() {
							return this._subsets.filter(function(v) {
								return v;
							});
						},
						set: function(value) {
							return this._subsets.push(value);
						},
						configurable: true
					},
					_get: {
						value: function(k) {
							return typeof k == 'number' ? this._equal.filter(function(v) {
								return v._index == k;
							})[0] : this._equal;
						},
						configurable: true
					},
					_properties: {
						get: function(full) {
							var f = {}, v;
							
							Object.getOwnPropertyNames(this).forEach(function(k) {
								v = Object.getOwnPropertyDescriptor(this, k);
								f[k] = full ? v : v.value;
							}, this);
							
							return f;
						},
						configurable: true
					},
					_unset: {
						value: function() {
							this._root._subsets.splice(this._root._get().indexOf(this), 1);

							return this;
						},
						configurable: true
					}
				});
				
				if (!this.hasOwnProperty(key) || rewrite) {
					this[key] = value;
				} else {
					value._index = this[key]._equal.length;
				}
				
				this[key]._equal = value;
				
				return this[key];
			}
		},
		append: {
			value: function(key, value) {
				this[key] = value;
				
				return this;
			}
		},
		unset: {
			value: function(key) {
				if (Array.isArray(key)) {
					this.each(key, function(i) {
						delete this.unset(i);
					});
				} else {
					delete this[key];
				}
				
				return this;
			}
		},
		find: {
			value: function(key) {
				return this.hasOwnProperty(key) || this[key] ? this[key] : false;
			}
		},
		filter: {
			value: function(fn) {
				var key, find = {};
			
				for (key in this) {
					fn(key, this[key]) ? find[key] = this.find(key) : false;
				}
				
				return find;
			}
		},
		extend: {
			value: function(object) {
				Object.assign(this, object);
				
				return this;
			}
		},
		empty: {
			value: function() {
				for (var i in this) {
					delete this[i];
				}
				
				return this;
			}
		},
		triggerAll: {
			value: function() {
				return this;
			}
		},
		trigger: {
			value: function(ev, data, key) {
				if (!Object.keys(this).length) return this;

				if (!this.hasOwnProperty(key)) {
					this.each(function(obj, name) {
						if (obj && obj._get) {
							this.each(obj._get(), function(o) {
								o.hasOwnProperty('trigger') ? o.trigger(ev, data) : false;
							});
						}
					});
					
					return this;
				}
				
				return this;
			}
		},
		each: {
			value: function(obj, fn) {
				var o, f, a;
				
				typeof obj != 'function' ? (o = obj, f = fn) : (o = this, f = obj);
				a = typeof o == 'object' ? Object.keys(o) : o;
				
				a.forEach(function(k, i) {
					f.call(this, o[k], k, i, a);
				}, this);
			}
		}
	});
	
	Object.defineProperties(DOM, {
		setEvents: {
			value: function() {var DOM = this;
				if (!Object.keys(this).length) return this;

				this.each(function(e, k) {
					this.each(this.parent.events, function(f, ev) {
						if (typeof f[k] != 'function') return false;
						
						this.each(e._root._get(), function(el) {
							el.on(ev, f[k]);
						});
					});
				});
				
				return this;
			}
		},
		init: {
			value: function(doc) {
				var element = this.parent.models.find('element');
				
				if (!element) return this;
				
				this.each([].slice.call((doc || document).querySelectorAll(selector)), function(e, k, i, es) {
					e = element.wrap(e, selector);
					e.core = this.parent.models.find(e.info.core) || this.parent;
					e.core.DOM.set(e.info.name, e);
					
					i == es.length - 1 && Object.keys(this).length ? this.setEvents() : false;
				});
				
				return this;
			}
		},
		render: {
			value: function() {
				var _that = this;
				document.addEventListener('DOMContentLoaded', function() {
					_that.init(this).trigger('load');
				});
				
				return this;
			}
		},
		create: {
			value: function(tag, attributes) {
				return this.parent.models.find('element').create(tag, attributes, this.parent, selector);
			},
			enumerable: true
		},
		fromString: {
			value: function(str) {
				var parser = new DOMParser();
				return parser.parseFromString(str, 'text/html').body.childNodes[0];
			}
		}
	});
	
	window[name] = {
		name: name,
		version: '1.4',
		DOM: Object.create(DOM),
		events: Object.create(proto),
		models: Object.create(proto),
		ajax: function (options) {
			var i, xhr = new XMLHttpRequest(),
			fd = new FormData(),
			o = Object.assign({
					url: '',
					method: 'get',
					data: {},
					contentType: 'text/html',
					dataType: false,
					formData: true,
					sync: true,
					cache: true,
					done: function(){},
					error: function(){}
				}, options);

			if (o.formData) {
				o.data = Arckane.convert.toFormData(o.data);
			}

			xhr.open(o.method, encodeURI(o.url), o.sync);
			!o.cache ? xhr.setRequestHeader('Cache-Control', 'no-cache') : false;
			o.dataType ? xhr.setRequestHeader('Content-Type', 'application/' + o.dataType) : false;
			xhr.send(o.data);
			xhr.context = this;
			xhr.onload = function() {
				if (xhr.status === 200) {
					xhr.data = o.contentType == 'json' ? JSON.parse(xhr.response) : xhr.response;
					o.done.call(xhr.context, xhr.data);
				} else {
					o.error(xhr);
				}
			};

			return xhr;
		},
		model: function (name, model) {
			model = this.models.set(name, Object.create(proto).extend(model || {}), true);			
			
			model.extend({
				models: this.models,
				DOM: Object.create(DOM),
				events: Object.create(proto),
				ajax: this.ajax
			});
			
			model.parent = this;
			model.DOM.parent = model;
			model.DOM.render();
			
			return model;
		},
		ie: function() {
			return navigator.userAgent.match(/msie/i) || navigator.userAgent.match(/trident/i) || navigator.userAgent.match(/edge/i);
		},
		convert: {
			toFormData: function(obj) {
				var i, fd = new FormData();
				
				for (i in obj) {
					fd.append(decodeURIComponent(i), typeof obj[i] == 'object' ? JSON.stringify(obj[i]) : decodeURIComponent(obj[i]));
				}
				
				return fd;
			}
		},
		init: function() {
			this.DOM.parent = this;
			this.DOM.render();
			return this;
		}
	}.init();
}('Arckane', '[data-name]'));