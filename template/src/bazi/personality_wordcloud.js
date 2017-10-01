define(function(require){
    /* 性格优劣词云图 */
    var wordcloud = require('common/chart/wordcloud');

    var o={};

	o.show=function(domid,bazi) {
        var code = '<table width="100%">'+
            '<tr>'+
                '<td width="50%"><div id="positive-'+domid+'" style="height:200px;"></div></td>'+
                '<td width="50%" style="border-left:solid 1px #065679">'+
                    '<div id="negative-'+domid+'" style="height:200px;"></div></td>'+
            '</tr>'+
        '</table>';
        jQuery('#'+domid).html(code);

        for (var k in {'positive':1,'negative':1}) {
            var data = [];
            if (bazi.personality && bazi.personality[k] && bazi.personality[k].length) {
                for (var i=0;i<bazi.personality[k].length;++i) {
                    var im = bazi.personality[k][i];
                    data.push({name:im.word,value:im.v});
                }
            }
            var title = k=='positive' ? '性格优势' : '性格劣势';
            var color = k=='positive' ? '#24A6AE' : '#E6886B';
            wordcloud.show(k+'-'+domid,title,data,color);
        }
	};
	return o;
});
