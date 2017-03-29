/* 先天命盘图 */
define(function(require){
	var BaziGraph = require("common/BaziGraph");
	var graph;

    var o={};

	o.init=function() {
		graph = new BaziGraph({render:'outline-char-div'});
	};

	o.show=function() {
		graph.show(bazi_graph);
	};

	return o;
});
