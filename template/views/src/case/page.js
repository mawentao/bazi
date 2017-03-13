define(function(require){
    var o={};
    o.execute = function(){
		require('./grid').init();
    };
    return o;
});
