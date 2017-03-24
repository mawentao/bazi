/* page.js, (c) 2017 mawentao */
define(function(require){

	function select_nav(idx) {
		var idx = parseInt(idx);
		jQuery('.panel-div').hide();
		jQuery('#panel-'+idx).show();
		jQuery('[name=navim]').removeClass("active");
		jQuery('[name=navim]:eq('+idx+')').addClass("active");
		switch (idx) {
			case 2: // 婚恋命盘
				require('common/bazi_graph').show('hunlian-mingpan-div',bazi_graph);
				break;
			default: // 先天命盘
				require('common/bazi_graph').show('outline-char-div',bazi_graph);
				break;
		}
	}

	/*
	function show_bazi_graph(domid)
	{
		var data = { 
			nodes: [
				{name:'丁',wuxing:'火',role:'年干'},
				{name:'卯',wuxing:'木',role:'年支'},
				{name:'丙',wuxing:'火',role:'月干'},
				{name:'午',wuxing:'火',role:'月支'},
				{name:'戊',wuxing:'土',role:'日干'},
				{name:'申',wuxing:'金',role:'日支'},
				{name:'戊',wuxing:'土',role:'时干'},
				{name:'午',wuxing:'火',role:'时支'},
			],
			links: {
				'gan_he': [
					['年干','日干','木'],
					['时干','日干','木']
				]
			}
		};
		//require('common/bazi_graph').show('outline-char-div',data);
		require('common/bazi_graph').show('outline-char-div',bazi_graph);
	}*/

    var o={};

	o.execute=function(){
		jQuery('[name=navim]').unbind('click').click(function(){
			var idx = jQuery(this).index("[name=navim]");
			select_nav(idx);
		});
		select_nav(nav_idx);
	};

	return o;
});
