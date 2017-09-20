define(function(require){

/**
 * 八字合婚分析图
 * Data数据结构
    var data = {
			nodes: {
				'男年支': {name:'辰',wuxing:'土',shengxiao:'龙'},
				'男月支': {name:'卯',wuxing:'木',shengxiao:'兔'},
				'男日支': {name:'子',wuxing:'水',shengxiao:'鼠'},
				'男时支': {name:'酉',wuxing:'金',shengxiao:'鸡'},

				'女年支': {name:'巳',wuxing:'火',shengxiao:'蛇'},
				'女月支': {name:'午',wuxing:'火',shengxiao:'马'},
				'女日支': {name:'未',wuxing:'土',shengxiao:'羊'},
				'女时支': {name:'申',wuxing:'金',shengxiao:'猴'},
			},
			relations: {
				'三合': [['男年支','男日支','女月支']],
				'合': [['男日支','女日支']],
				'冲': [['男时支','女日支']]
			}
    };
 **/

var graph  = require("common/chart/graph");
var gzlink = require("common/chart/gzlink");

// 类
function MarriageGraph(conf)
{
	var thiso=this;
	var render;
	var chartid;
	var radioid;
	var mode=2;
	var data;

	if (conf) {
		if(conf.render)render=conf.render;
		chartid = render+'chart';
		radioid = render+'radio';
		init();
	}

	function init() 
	{/*{{{*/
		var code = '<div id="'+chartid+'" style="position:absolute;left:0;right:0;top:0;bottom:0;z-index:1;"></div>'+
			'<div class="mwt-btn-group" style="position:absolute;right:10px;top:10px;z-index:2;">'+
				'<button name="'+radioid+'" class="mwt-btn mwt-btn-primary mwt-btn-xs" data-mode="1">地支</button>'+
				'<button name="'+radioid+'" class="mwt-btn mwt-btn-primary mwt-btn-xs active" data-mode="2">生肖</button>'+
			'</div>';
		jQuery('#'+render).html(code);
		jQuery('[name='+radioid+']').unbind('click').click(function(){
			jQuery('[name='+radioid+']').removeClass('active');
			jQuery(this).addClass('active');
			var v = jQuery(this).data('mode');
			mode=v;
			refresh();
		});
	}/*}}}*/

	function refresh() 
    {/*{{{*/
		// 节点分类
		var categories = [{name:'木'},{name:'火'},{name:'土'},{name:'金'},{name:'水'},{name:''},
			{name:'三合局'},{name:'合'},{name:'冲'},{name:'刑'},{name:'破'},{name:'害'}
		];
		var wuxingmap = {'木':0,'火':1,'土':2,'金':3,'水':4};
		// 宫格
		var w=5,h=3;
		log.debug("MarriageGraph size: "+w+'x'+h);
		var symbolStyle = {normal:{color:'rgba(128, 128, 128, 0)'}};   			//!< 柱名节点样式
		var gongGrid = [
			['',{name:'年'},{name:'月'},{name:'日'},{name:'时'}],
			[{name:'男'},'男年支','男月支','男日支','男时支'],
			[{name:'女'},'女年支','女月支','女日支','女时支']
		];
		// 节点
		var xgap = 70;  //!< 水平间隔
		var ygap = 70;	 //!< 垂直间隔
		var nodes = [];
		var namekey = mode==1 ? 'name' : 'shengxiao';    //!< 显示地支或生肖
		for (var yi=0;yi<gongGrid.length;++yi) {
			var row = gongGrid[yi];
			for (var xi=0;xi<row.length;++xi) {
				var cell=row[xi];				
				var nodeItem = {
					x: xi==0 ? 0 : 30+(xi-1)*xgap,
					y: yi==0 ? 0 : 30+(yi-1)*ygap,
					name: '',
					value: '',
					category:5,
					itemStyle: symbolStyle
				};
				if (cell.name) {nodeItem.name=cell.name;}
				else if (cell!='') {
					var dn = data.nodes[cell] ? data.nodes[cell] : false;
					if (dn) {
						nodeItem.name = cell;
						nodeItem.value = dn[namekey];
						nodeItem.category = wuxingmap[dn.wuxing];
						nodeItem.label = {normal:{formatter:'{c}'}};
						delete nodeItem.itemStyle;
					}
				}
				nodes.push(nodeItem);
			}
		}
		// 关系节点
		var relationNodes = ['三合','合','冲','刑','破','害'];
		var yr = 30+ygap/2;
		var xr = 30;
		for (var i=0;i<relationNodes.length;++i) {
			var nodeItem = {
				x: xr+i*42,
				y: yr,
				name: relationNodes[i],
				value: '',
				category: 6+i,
				symbol: 'rect',
				symbolSize: [40,20],
			};
			nodes.push(nodeItem);
		}

		// 边
		var links = [];
		var symbol = ['circle','circle'];
		for (var relation in data.relations) {
			if (relation=='remove') break;
			var color = (relation=='合'||relation=='三合') ? '#0f0' : '#f00';
			for (var k=0; k<data.relations[relation].length; ++k) {
				var ns = data.relations[relation][k];
				for (var n=0;n<ns.length;++n) {
					var edge = {
                        target:ns[n],source:relation,symbol:symbol,
                        lineStyle:{normal:{curveness:0,color:color}}
                    };
					links.push(edge);
				}
			}
		}
		// 绘图
		graph.show(chartid,nodes,links,categories);
	}/*}}}*/

	// 显示图
	this.show=function(_data) {
		data = _data;
		refresh();
	};
}

return MarriageGraph;
});
