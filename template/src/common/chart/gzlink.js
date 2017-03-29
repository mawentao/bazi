/**
 * 干支关系（图的有向边）
 **/
define(function(require){
    var o={};
	// 天干相合边
	o.get_gan_he_link=function(g1,g2,wuxing)
	{
		var seq = {'年干':0,'月干':1,'日干':2,'时干':3,'运干':4,'岁干':5};
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:wuxing}},
			lineStyle: {curveness:0.5},
			edgeSymbol: ['circle','circle']
		};
		if (seq[g1]>seq[g2]) {
			link.source = g2;
			link.target = g1;
		}
		return link;
	}

	// 天干相冲边
	o.get_gan_chong_link=function(g1,g2,label) 
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
	o.get_sheng_ke_link=function(g1,g2,label) 
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
	o.get_zhi_hui_link=function(g1,g2)
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
	o.get_zhi_he_link=function(g1,g2,label)
	{
		var curveness = 0; //is_adjacent(g1,g2) ? 0 : -0.5;

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
	o.get_zhi_chong_link=function(g1,g2,label)
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
	o.get_zhi_xing_link=function(g1,g2)
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


    return o;
});
