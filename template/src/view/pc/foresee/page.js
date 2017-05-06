/* page.js, (c) 2017 mawentao */
define(function(require){
	var graph_outline = require('./graph_outline');  //!< 先天命盘
	var graph_hunlian = require('./graph_hunlian');  //!< 婚恋命盘

	function select_nav(idx) {
		var idx = parseInt(idx);
		jQuery('.panel-div').hide();
		jQuery('#panel-'+idx).show();
		jQuery('[name=navim]').removeClass("active");
		jQuery('[name=navim]:eq('+idx+')').addClass("active");
		switch (idx) {
			case 2: // 婚恋命盘
				//require('common/bazi_graph').show('hunlian-mingpan-div',bazi_graph);
				graph_hunlian.show();
				break;
			default: // 先天命盘
				graph_outline.show();
				//require('common/bazi_graph').show('outline-char-div',bazi_graph);
				break;
		}
	}

    var o={};

	o.execute=function(){
		// 初始化图
		graph_outline.init();
		graph_hunlian.init();

		// 导航菜单点击事件
		jQuery('[name=navim]').unbind('click').click(function(){
			var idx = jQuery(this).index("[name=navim]");
			select_nav(idx);
		});
		select_nav(nav_idx);

		// 婚恋流年按钮事件
		jQuery('[name=a-hunlian-liunian]')
			.mouseover(function(){
				var year = jQuery(this).data('year');
				jQuery('[name=a-liunian][data-year='+year+']').css({'border-color':'#94936A'});
			})
			.mouseout(function(){
				var year = jQuery(this).data('year');
				var jd = jQuery('[name=a-liunian][data-year='+year+']');
				if (!jd.hasClass('jinnian')) {
					jd.css({'border-color':'rgba(255,255,255,0)'});
				}
			})
			.click(function(){
				var year = jQuery(this).data('year');
				jQuery('[name=a-hunlian-liunian]').removeClass('jinnian');
				jQuery(this).addClass('jinnian');
				//alert(year);
				//require("./hunlian_liunian_graph").show();
				graph_hunlian.show();
			});
	};

	return o;
});
