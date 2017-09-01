define(function(require){
	var MarriageGraph=require('common/MarriageGraph');

	/* 合婚分析图 */
    var o={};

	o.init=function(domid){
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
		var data = marriageGraph;
		var graph = new MarriageGraph({render:domid});
		graph.show(data);
	};

	return o;
});
