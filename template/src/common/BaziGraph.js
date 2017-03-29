/**
 * 八字生克制化关系图
 **/
define(function(require){
var graph  = require("common/chart/graph");
var gzlink = require("common/chart/gzlink");
/**
 * data数据结构
{
	nodes: [
		{role:'年干',name:'丁',wuxing:'火',shishen:'印'},
		{role:'年支',name:'卯',wuxing:'木',shishen:'官'},
		{role:'月干',name:'丙',wuxing:'火',shishen:'枭'},
		{role:'月支',name:'午',wuxing:'火',shishen:'印'},
		{role:'日干',name:'戊',wuxing:'土',shishen:'日元'},
		{role:'日支',name:'申',wuxing:'金',shishen:'食'},
		{role:'时干',name:'戊',wuxing:'土',shishen:'比'},
		{role:'时支',name:'午',wuxing:'火',shishen:'印'},
		{role:'运干',name:'丁',wuxing:'火',shishen:'印'},
		{role:'运支',name:'午',wuxing:'火',shishen:'印'},
		{role:'岁干',name:'丁',wuxing:'火',shishen:'印'},
		{role:'岁支',name:'午',wuxing:'火',shishen:'印'}
	],
	links: {
		'gan_he': [
			['年干','日干','木'],
			['时干','日干','木']
		]
	}
}
 **/

// 类
function BaziGraph(conf)
{
	var thiso=this;
	var render;
	var chartid;
	var barid;
	var radioid;
	var mode=1;
	var data;

	if (conf) {
		if(conf.render)render=conf.render;
		chartid = render+'chart';
		barid = render+'bar';
		radioid = render+'radio';
		init();
	}

	function init() {
		var code = '<div id="'+chartid+'" style="position:absolute;left:0;right:0;top:0;bottom:0;z-index:1;"></div>'+
			'<div id="'+barid+'" class="mwt-btn-group" style="position:absolute;right:10px;top:10px;z-index:2;">'+
				'<button name="'+radioid+'" class="mwt-btn mwt-btn-primary mwt-btn-xs active" data-mode="1">干支</button>'+
				'<button name="'+radioid+'" class="mwt-btn mwt-btn-primary mwt-btn-xs" data-mode="2">十神</button>'+
			'</div>';
		jQuery('#'+render).html(code);
		jQuery('[name='+radioid+']').unbind('click').click(function(){
			jQuery('[name='+radioid+']').removeClass('active');
			jQuery(this).addClass('active');
			var v = jQuery(this).data('mode');
			mode=v;
			refresh();
		});
	}

	function refresh() {
		// 节点分类
		var categories = [{name:'木'},{name:'火'},{name:'土'},{name:'金'},{name:'水'},{name:''}];
		var wuxingmap = {'木':0,'火':1,'土':2,'金':3,'水':4};
		// 宫位坐标
		var xgap=70,ygap=50;
		var x1=70,x2=x1+xgap,x3=x2+xgap,x4=x3+xgap,x5=x4+xgap,x6=x5+xgap;
		var y1=50,y2=y1+ygap,y3=y2+ygap,y4=y3+ygap;
		var gongwei = {
			'年柱':[x1,y1],'月柱':[x2,y1],'日柱':[x3,y1],'时柱':[x4,y1],'大运':[x5,y1],'流年':[x6,y1],
			'年干':[x1,y2],'月干':[x2,y2],'日干':[x3,y2],'时干':[x4,y2],'运干':[x5,y2],'岁干':[x6,y2],
			'年支':[x1,y3],'月支':[x2,y3],'日支':[x3,y3],'时支':[x4,y3],'运支':[x5,y3],'岁支':[x6,y3],
			'年底':[x1,y4],'月底':[x2,y4],'日底':[x3,y4],'时底':[x4,y4],'运底':[x5,y4],'岁底':[x6,y4],
			'无恩之刑':[x1+35,y4], '恃势之刑':[x2+35,y4], '无礼之刑':[x3+35,y4], '自刑':[x4+35,y4]
		};
		// 封装graph节点
		var nodes=[];
		var symbolStyle = {normal:{color:'rgba(128, 128, 128, 0)'}};   			//!< 柱名节点样式
		var textStyle = {normal:{textStyle:{fontSize:18}}};						//!< 节点名称样式
		var textStyleR = {normal:{textStyle:{fontSize:18,color:'#FF5722'}}}
		var namekey = mode==1 ? 'name' : 'shishen';    //!< 显示干支或十神
		var zhumap = {'年支':'年柱','月支':'月柱','日支':'日柱','时支':'时柱','运支':'大运','岁支':'流年'};
		for (var i=0;i<data.nodes.length;++i) {
			var im = data.nodes[i];
			if (!gongwei[im.role]) continue;
			var gw = gongwei[im.role];   //!< 宫位
			var v = im[namekey];		 //!< 显示值
			/////////////////////////////////////////////////////////////////
			if (namekey=='shishen') v=(im.role=='日干') ? '日元' : v.name;   //!< 显示十神全名还是短名
			/////////////////////////////////////////////////////////////////
			nodes.push({name:im.role,value:v,x:gw[0],y:gw[1],category:wuxingmap[im.wuxing],label:{normal:{formatter:'{c}'}}});
			// 添加柱节点
			if (zhumap[im.role]) {
				var zhu = zhumap[im.role];
				var gw = gongwei[zhu];
				var st = im.role=='日支' ? textStyleR : textStyle;
				nodes.push({ name:zhu,value:'',category:5,itemStyle:symbolStyle,x:gw[0],y:gw[1],label:st});
			}
		}
		// 封装graph边
		var nodemap = {};
		var links = [];
		{
			// 天干五合
			if (data.links.gan_he && data.links.gan_he.length>0) {
				for (var i=0;i<data.links.gan_he.length;++i) {
					var im = data.links.gan_he[i];
					links.push(get_gan_he_link(im[0],im[1],im[2]));
					//links.push({source:im[0],target:im[1],label:{normal:{show:true,formatter:im[2]}}});
				}
			}
			// 天干相冲
			if (data.links.gan_chong && data.links.gan_chong.length>0) {
				for (var i=0;i<data.links.gan_chong.length;++i) {
					var im = data.links.gan_chong[i];
					links.push(get_gan_chong_link(im[0],im[1],im[2]));
				}
			}
			// 同柱干支关系（生、克、自合）
			var ks = ['sheng','ke','zihe'];
			for (var m=0; m<ks.length; ++m) {
				var k = ks[m];
				if (data.links[k] && data.links[k].length>0) {
					for (var i=0;i<data.links[k].length;++i) {
						var im = data.links[k][i];
						links.push(get_sheng_ke_link(im[0],im[1],im[2]));
					}
				}
			}
			// 地支三会三合
			var ks = ['zhi_hui','zhi_sanhe'];
			for (var m=0; m<ks.length; ++m) {
				var k = ks[m];
				if (data.links[k] && data.links[k].length>0) {
					for (var i=0;i<data.links[k].length;++i) {
						var im = data.links[k][i];
						var name = im[1].substr(0,1)+'底';
						if (!isset(nodemap[name])) {
							var gw = gongwei[name];
							var hh = (k=='zhi_hui') ? '三会' : '三合';
							nodes.push({ name:name,value:im[3],category:im[3],x:gw[0],y:gw[1],label:{normal:{formatter:hh+'{c}'}},
								symbolSize:[70,30]
							});
							nodemap[name] = 1;
						}
						links.push(get_zhi_hui_link(im[0],name));
						links.push(get_zhi_hui_link(im[1],name));
						links.push(get_zhi_hui_link(im[2],name));
					}
				}
			}
			// 地支六合、半合、暗合
			var ks = ['zhi_liuhe','zhi_banhe','zhi_anhe'];
			for (var m=0; m<ks.length; ++m) {
				var k = ks[m];
				if (data.links[k] && data.links[k].length>0) {
					for (var i=0;i<data.links[k].length;++i) {
						var im = data.links[k][i];
						var h = k=='zhi_liuhe' ? '合'+im[2] : '半合'+im[2];
						if (k=='zhi_anhe') h = '暗合';
						links.push(get_zhi_he_link(im[0],im[1],h));
					}
				}
			}
			// 地支相刑
			if (data.links.zhi_xing && data.links.zhi_xing.length>0) {
				for (var i=0;i<data.links.zhi_xing.length;++i) {
					var im = data.links.zhi_xing[i];
					var name = im[2];
					if (name=='自刑') continue;     //!< 自刑不显示
					if (!isset(nodemap[name])) {
						var gw = gongwei[name];
						nodes.push({ name:name,value:name,category:5,x:gw[0],y:gw[1],symbolSize:[70,30],symbol:'roundRect'});
						nodemap[name] = 1;
					}
					links.push(get_zhi_xing_link(im[0],name));
					links.push(get_zhi_xing_link(im[1],name));
				}
			}
			// 地支相冲、害、破
			var ks = ['zhi_chong','zhi_hai','zhi_po'];
			for (var m=0; m<ks.length; ++m) {
				var k = ks[m];
				if (data.links[k] && data.links[k].length>0) {
					for (var i=0;i<data.links[k].length;++i) {
						var im = data.links[k][i];
						links.push(get_zhi_chong_link(im[0],im[1],im[2]));
					}
				}
			}
		}
		// 渲染图形
		//print_r(nodes);
		graph.show(chartid,nodes,links,categories);
	}

	this.show=function(_data) {
		data = _data;
		refresh();
	};

	// 天干相合边
	var seq = {'年干':0,'月干':1,'日干':2,'时干':3,'大运天干':4,'流年天干':5};
	function get_gan_he_link(g1,g2,wuxing)
	{
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:wuxing,textStyle:{color:'#E6886B'}}},
			lineStyle: {normal:{curveness:0.5,color:'#E6886B'}},
			symbol: ['circle','circle']
		};
		if (seq[g1]>seq[g2]) {
			link.source = g2;
			link.target = g1;
		}
		return link;
	}
	// 天干相冲边
	function get_gan_chong_link(g1,g2,label) 
	{
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:label,textStyle:{color:'#f00'}}},
			lineStyle: {normal:{curveness:0.3,color:'#f00'}},
			symbol: ['arrow','arrow']
		};
		if (seq[g1]>seq[g2]) {
			link.source = g2;
			link.target = g1;
			link.symbol = ['arrow','arrow'];
		}
		return link;
	}
	// 同柱生、克、自合边
	function get_sheng_ke_link(g1,g2,label) 
	{
		var color = '#0f0';
		var symbol = ['circle','arrow'];
		if (label=='生') {
			color = '#0f0';
		} else if (label=='克') {
			color = '#f00';
		} else {
			color = '#E6886B';
			symbol = ['circle','circle'];
		}
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:label,textStyle:{color:color}}},
			lineStyle: {normal:{curveness:0,color:color}},
			symbol: symbol
		};
		return link;
	}
	// 地支三会三合边
	function get_zhi_hui_link(g1,g2)
	{
		var link = {
			source: g1,
			target: g2,
			//label: {normal:{show:true,formatter:label,textStyle:{color:'#0f0'}}},
			lineStyle: {normal:{curveness:0,color:'#0f0'}},
			symbol: ['circle','circle']
		};
		return link
	}
	// 地支相合边
	function get_zhi_he_link(g1,g2,label)
	{
		var curveness = is_adjacent(g1,g2) ? 0 : -0.5;

		var color = '#E6886B';
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:label,textStyle:{color:color}}},
			lineStyle: {normal:{curveness:curveness,color:color}},
			symbol: ['circle','circle']
		};
		return link;
	}
	// 地支相冲、破、害边
	function get_zhi_chong_link(g1,g2,label)
	{
		var color = label=='冲' ? '#f00' : '#999';
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:label,textStyle:{color:color}}},
			lineStyle: {normal:{curveness:0.5,color:color}},
			symbol: ['arrow','arrow']
		};
		var seq = {'年支':0,'月支':1,'日支':2,'时支':3,'大运地支':4,'流年地支':5};
		if (seq[g1]<seq[g2]) {
			link.source = g2;
			link.target = g1;
		}
		return link;
	}
	// 地支相刑边
	function get_zhi_xing_link(g1,g2)
	{
		var color = '#999';
		var link = {
			source: g1,
			target: g2,
			lineStyle: {normal:{curveness:0,color:color}},
			symbol: ['circle','arrow']
		};
		return link;
	}

	// 判断是否邻柱
	function is_adjacent(g1,g2)
	{
		var adjacent_distance = x2-x1;
		var dis = Math.abs(gongwei[g1][0]-gongwei[g2][0]);
		return dis<=adjacent_distance;
	}

}

return BaziGraph;
});
