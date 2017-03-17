/**
 * 八字生克图
 * 使用: require("common/bazi_graph").show(domid,data,title);
 * 其中data的数据结构如下:
{ 
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
}
 **/
define(function(require){
    var o={};
    //require("echarts");
    var echarts = require("echarts");
    var echarts_theme = require("echarts/theme/dark");
    var chart,echarts_theme;

	// 节点分类
	var categories = [{name:'木'},{name:'火'},{name:'土'},{name:'金'},{name:'水'},{name:''}];
	var wuxingmap = {'木':0,'火':1,'土':2,'金':3,'水':4};

	// 宫位坐标
	var x1=70,x2=140,x3=210,x4=280,x5=350,x6=420;
	var y1=50,y2=100,y3=150;
	var gongwei = {
		'nian_zhu':[x1,y1], 'yue_zhu':[x2,y1], 'ri_zhu':[x3,y1], 'shi_zhu':[x4,y1], 'yun_zhu':[x5,y1], 'liunian_zhu':[x6,y1],
		'年干':[x1,y2], '月干':[x2,y2], '日干':[x3,y2], '时干':[x4,y2], '大运天干':[x5,y2], '流年天干':[x6,y2],
		'年支':[x1,y3], '月支':[x2,y3], '日支':[x3,y3], '时支':[x4,y3], '大运地支':[x5,y3], '流年地支':[x6,y3]
	};

    o.show = function(domid,data,title,clickfun,color) {
		var dom = document.getElementById(domid);
        chart = echarts.init(dom, 'dark');
		//chart._theme.graph.color = ['#005d00','#900','orange','lightslategray','#444','#999'];
		chart._theme.graph.color = ['#73a373','#ea7e53','#aa9967','#7289ab','#333','#999'];

		// 节点
		var nodes = [];
		var symbolStyle = {normal:{color:'rgba(128, 128, 128, 0)'}};
		var textStyle = {normal:{textStyle:{fontSize:18}}}
		var textStyleR = {normal:{textStyle:{fontSize:18,color:'#FF5722'}}}
		for (var i=0;i<data.nodes.length;++i) {
			var im = data.nodes[i];
			if (!gongwei[im.role]) continue;
			var gw = gongwei[im.role];
			nodes.push({name:im.role,value:im.name,x: gw[0], y: gw[1], category:wuxingmap[im.wuxing], 
				label: {normal:{formatter:'{c}'}}
			});
			if (im.role=='年支') {
				var gw = gongwei['nian_zhu'];
				nodes.push({ name:'年柱',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyle});
			}
			if (im.role=='月支') {
				var gw = gongwei['yue_zhu'];
				nodes.push({ name:'月柱',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyle});
			}
			if (im.role=='日支') {
				var gw = gongwei['ri_zhu'];
				nodes.push({ name:'日柱',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyleR});
			}
			if (im.role=='时支') {
				var gw = gongwei['shi_zhu'];
				nodes.push({ name:'时柱',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyle});
			}
			if (im.role=='大运地支') {
				var gw = gongwei['yun_zhu'];
				nodes.push({ name:'大运',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyle});
			}
			if (im.role=='流年地支') {
				var gw = gongwei['liunian_zhu'];
				nodes.push({ name:'流年',value:'',category:5,symbol:'roundRect',itemStyle:symbolStyle,x:gw[0],y:gw[1],label:textStyle});
			}
		}
		// 边
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
			// 同柱五行相生
			if (data.links.sheng && data.links.sheng.length>0) {
				for (var i=0;i<data.links.sheng.length;++i) {
					var im = data.links.sheng[i];
					links.push(get_sheng_ke_link(im[0],im[1],im[2]));
				}
			}
			// 同柱五行相克
			if (data.links.ke && data.links.ke.length>0) {
				for (var i=0;i<data.links.ke.length;++i) {
					var im = data.links.ke[i];
					links.push(get_sheng_ke_link(im[0],im[1],im[2]));
				}
			}
		}
		// 绘图
		var option = {
			tooltip: {},
			legend: [{
				//orient: 'vertical',
				//left: 5,
				itemWidth:20, itemHeight:13,
				textStyle : {fontSize:12},
                data: categories.map(function (a) {
                    return a.name;
                })  
            }],
			textStyle: {
				fontFamily: "KaiTi,SimSun,'microsoft yahei'",
				fontSize: 16
			},
			animationDurationUpdate: 1500,
			animationEasingUpdate: 'quinticInOut',
			series : [{
				type: 'graph',
				categories: categories,
				layout: 'none',
				symbolSize: 40,
				roam: true,
				label: {
					normal: { show:true }
            	},
				edgeSymbol: ['circle', 'arrow'],
				edgeSymbolSize: [6, 6],
				edgeLabel: {
                	normal: {
                    	textStyle: {fontSize: 14}
                	}
            	},
            	data: nodes, 
				links: links,
				lineStyle: {
                	normal: {
                    	opacity: 0.9,
                    	width: 1,
                    	curveness: 0.2
                	}
            	}
        	}]
		};
		
        chart.setOption(option);

/*
		// 节点点击事件
		if (clickfun) {
			chart.on('click', function (param) {  
				clickfun(param);
			});
		}
*/
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
			symbol: ['circle','arrow']
		};
		if (seq[g1]>seq[g2]) {
			link.source = g2;
			link.target = g1;
			link.symbol = ['arrow','circle'];
		}
		return link;
	}
	// 五行生克边
	function get_sheng_ke_link(g1,g2,label) 
	{
		var color = label=='生' ? '#0f0' : '#f00';
		var link = {
			source: g1,
			target: g2,
			label: {normal:{show:true,formatter:label,textStyle:{color:color}}},
			lineStyle: {normal:{curveness:0,color:color}},
			symbol: ['circle','arrow']
		};
		return link;
	}

    return o;
});
