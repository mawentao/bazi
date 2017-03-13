/* page.js, (c) 2017 mawentao */
define(function(require){

	function select_nav(idx) {
		jQuery('.panel-div').hide();
		jQuery('#panel-'+idx).show();
		jQuery('[name=navim]').removeClass("active");
		jQuery('[name=navim]:eq('+idx+')').addClass("active");
	}

    var o={};

	o.execute=function(){
		jQuery('[name=navim]').unbind('click').click(function(){
			var idx = jQuery(this).index("[name=navim]");
			select_nav(idx);
		});
		select_nav(0);
	};

	return o;
});
