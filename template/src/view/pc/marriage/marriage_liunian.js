define(function(require){
    // 适婚流年合看
    var bazi_marriage_liunian = require('bazi/marriage_liunian');


    function getLiunianInfo(bazi,year) {
        var hunlianLiunian = bazi.hunLian.liunian[year];
        var liunianInfo = bazi.liunian[year];
        return liunianInfo.age+'岁'+bazi_marriage_liunian.getLiuNianJue(hunlianLiunian);
    }

    var o={};
    o.show = function(domid,maleCase,femaleCase) 
    {
        var maleBazi = maleCase.getData();
        var femaleBazi = femaleCase.getData();
        var maleHunlianLiunian = maleBazi.hunLian.liunian;
        var femaleHunlianLiunian = femaleBazi.hunLian.liunian;

        var trs = [];
        var nowyear = date('Y');
        for (var year in maleHunlianLiunian) {
            if (!isset(femaleHunlianLiunian[year])) {
                continue;
            }
            var liunianInfo = maleBazi.liunian[year];
            var cls = nowyear==year ? ' class="nowyear"' : '';

            var code = '<tr'+cls+'>'+
                '<td style="text-align:center">'+year+'('+liunianInfo.gan+liunianInfo.zhi+')年</td>'+
                '<td>'+getLiunianInfo(maleBazi,year)+'</td>'+
                '<td>'+getLiunianInfo(femaleBazi,year)+'</td>';
            trs.push(code);
        }
        var trscode = trs.join('');
        if (trs.length==0) {
            trscode = '二者年龄悬殊，无适婚流年重合';
        }

        var code = '<table class="hunlian-liunian-tab">'+
            '<tr><th width="150" style="text-align:center">流年</th>'+
                '<th><label class="fyang">'+maleBazi.name+'</label></th>'+
                '<th><label class="fyin">'+femaleBazi.name+'</label></th>'+
            '</tr>'+trscode+
        '</table>';
        jQuery('#'+domid).html(code);
        mwt.popinit();
    };
    return o;
});
