var txt2img = function(str) {

	function hex2rgb(hex){
		hex = '0x' + hex;
		return { 'r' : hex >> 16, 'g' : hex >> 8 & 0xFF, 'b' : hex & 0xFF }
	}

	var str_hash = md5(str);
	var str_hash_blocks = str_hash.match(/.{1,6}/g);

	var canvas_size = 1200;
	var canvas = document.getElementById("canvas");

	var ctx = canvas.getContext("2d");
		ctx.canvas.width = ctx.canvas.height = canvas_size;

		// Background Solid
		ctx.fillStyle = '#000';
		ctx.fillRect(0,0,canvas_size,canvas_size);

		// Background Alpha
		var bg_alpha = Math.round(parseInt(str_hash_blocks[5], 16) * 100 / 255) / 100;
		var bg_color = hex2rgb( str_hash_blocks[4] );
		var bg_str = 'rgba(' + bg_color.r + ', ' + bg_color.g + ', ' + bg_color.b + ', ' + bg_alpha + ')';
		ctx.fillStyle = bg_str;
		ctx.fillRect(0,0,canvas_size,canvas_size);

		// Radial Gradients
		for ( var i=0; i<4; i++ ) {
			var grad = null;
			var size = canvas_size;
			switch (i) {
				case 3: grad = ctx.createRadialGradient(0,canvas_size, 0, 0,canvas_size, size); break;
				case 2: grad = ctx.createRadialGradient(canvas_size,canvas_size, 0, canvas_size,canvas_size, size); break;
				case 1: grad = ctx.createRadialGradient(canvas_size,0, 0, canvas_size,0, size); break;
				case 0: grad = ctx.createRadialGradient(0,0, 0, 0,0, size); break;
			}
			var grad_color = hex2rgb(str_hash_blocks[i]);
			grad.addColorStop(0, 'rgba(' + grad_color.r + ', ' + grad_color.g + ', ' + grad_color.b + ', 1)');
			grad.addColorStop(1, 'rgba(' + grad_color.r + ', ' + grad_color.g + ', ' + grad_color.b + ', 0)');
			ctx.fillStyle = grad;
			ctx.fillRect(0,0,canvas_size,canvas_size);
		}

		var image_data = ctx.canvas.toDataURL('image/png');
		var filename = 'text2image.moro.es-' + str_hash.substr(0, 8) + '.png';

		var img = document.getElementById('i'); // image output
			img.setAttribute('width', 400);
			img.setAttribute('height', 400);
			img.setAttribute('src', image_data);
			img.setAttribute('alt', str_hash);

		var lnk = document.getElementById('l'); // image output link
			lnk.innerHTML = 'Download Generated Image';
			lnk.setAttribute('href', image_data);
			lnk.setAttribute('title', str_hash);
			lnk.setAttribute('download', filename );

	return {
		'string' : str,
		'hash' : str_hash,
		'image' : image_data
	}
}

function convert(str) {
	var generated = txt2img(str);
	document.querySelector('#dl').style.display = 'block';
	document.querySelector('#ds').textContent = generated.string;
	document.querySelector('#dh').textContent = generated.hash;

	// Go to #output (#o) on mobile layout
	var pos = window.getComputedStyle(document.querySelector('#o'), null).getPropertyValue('position');
	if (pos == 'static') {
		window.location.href = "#o";
	}

	var url_sh = window.location.href;
		url_sh = url_sh.split('?')
	for (var i=0; i< social_networks.length; i++) {
		document.querySelector('#' + social_networks[i]['id']).href = url_sh[0] + '?s=' + generated.string;
	}
}

var social_networks = [
	{ 'id' : 'fb', 'name' : 'Facebook', 'url' : 'http://www.facebook.com/sharer.php?u={url}&t={title}' },
	{ 'id' : 'tw', 'name' : 'Twitter', 'url' : 'http://twitter.com/share?text={title}&url={url}&via=alterebro&hashtags=txt2img,MD5' },
	{ 'id' : 'gp', 'name' : 'G+', 'url' : 'https://plusone.google.com/_/+1/confirm?hl=en&url={url}' }
];

window.onload = function() {

	// Global Variables
	window.data_input = document.querySelector('#s');

	// Create the Generator Canvas
	var c = document.createElement('canvas');
		c.setAttribute('id', 'canvas');
	document.getElementById('o').appendChild(c);
	c.style.display = 'none';


	// Data list HTML content
	var list_output = '';
	list_output += '<li>[ <a id="l"></a> ]</li>';
	list_output += '<li><strong>string</strong> : <em id="ds"></em></li>';
	list_output += '<li><strong>hash</strong> : <em id="dh"></em></li>';
		share_output = '<li id="sh"><strong>Share</strong> : <em>';
		for ( var i=0; i<social_networks.length; i++ ) {
			share_output += '<a href="'+window.location.href+'" id="'+social_networks[i]['id']+'" onclick="open_sharer(this.href, this.id); return false;">' + social_networks[i]['name'] + '</a>';
			share_output += ( i < social_networks.length-1) ? ' / ' : '';
		}
		share_output += '</em></li>';
	list_output += share_output;

	// Create the data list if does not exist
	var dl = document.getElementById('dl');
	if( dl === null ) {
		var dl = document.createElement('ul');
			dl.setAttribute('id', 'dl');
			dl.innerHTML = list_output;

		document.querySelector('main').appendChild(dl);
		dl.style.display = 'none';
	} else {
		dl.innerHTML += share_output;
	}

	data_input.focus();

	// Init form
	document.getElementById('f').onsubmit = function() {
		convert( data_input.value );
		return false;
	}
}

function open_sharer(url, network) {
	var networks = {};
	for (var i = 0; i < social_networks.length; i++) {
		networks[social_networks[i]['id']] = social_networks[i]['url'];
	}
	var u = networks[network].replace(/\{url\}/, encodeURIComponent(url)).replace(/\{title\}/, encodeURIComponent(document.title));
	var network_window = window.open(u, network+'-share', 'height=350,width=600');
		network_window.focus();
}
