/* page.js, (c) 2017 mawentao */
define(function(require){
	//var shensha_matrix = require('./shensha_matrix'); //!< 神煞矩阵
	//var graph_outline = require('./graph_outline');   //!< 先天命盘
	//var graph_hunlian = require('./graph_hunlian');   //!< 婚恋命盘

    var o={};

	o.execute=function(){
		require('./merge-graph').init('merge-graph-div');
	};

	return o;
});
