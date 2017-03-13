/* frame.js, (c) 2017 mawentao */
define(function(require){
    var o={};

	o.showpage=function(code) {
		jQuery('#frame-body').html(code);
	};

	return o;
});
