Arckane.model('image', {
	resize: function(img, options) {
		var w, h, r, ctx, canvas = this.DOM.create('canvas');
		
		options = Object.create(proto).extend({
			width: 0,
			height: 0,
			max: 200,
			mime: 'image/jpeg',
			quality: 0.8,
			background: 'white'
		}).extend(options || {});
	
		w = img.width < options.max ? img.width : options.max;
		h = img.height < options.max ? img.height : options.max;

		if (img.width > img.height) {
			r = img.width / img.height;
			h = w / r;
		} else {
			r = img.height / img.width;
			w = h / r;
		}

		canvas.width = w;
		canvas.height = h;
		
		ctx = canvas.getContext('2d');
		options.background ? ctx.fillStyle = options.background : false;
		options.background ? ctx.fillRect(0, 0, w, h) : ctx.fillRect(40, 60, w, h); 
		ctx.drawImage(img, 0, 0, w, h);

		img.src = canvas.toDataURL(options.mime, options.quality);
		
		return img;
	}
});