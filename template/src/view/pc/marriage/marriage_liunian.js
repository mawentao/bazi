define(function(require){
    // 适婚流年合看

    function getLiuNianJue(im)
    {
        var badges = [];
        if (im.gan_he) {
            badges.push('<span>干合</span>');
        }
        if (im.zhi_he) {
            badges.push('<span>支合</span>');
        }
        if (im.gan_taohua) {
            badges.push('<span>干桃花</span>');
        }
        if (im.zhi_taohua) {
            badges.push('<span>支桃花</span>');
        }
        if (im.zhi_chong) {
            badges.push('<span>支冲</span>');
        }
        return badges.join('');
    }

    function getLiunianInfo(bazi,year) {
        var hunlianLiunian = bazi.hunLian.liunian[year];
        var liunianInfo = bazi.liunian[year];
        return liunianInfo.age+'岁'+getLiuNianJue(hunlianLiunian);
    }

    var o={};
    o.show = function(domid,maleCase,femaleCase) 
    {
        var maleBazi = maleCase.getData();
        var femaleBazi = femaleCase.getData();
        var maleHunlianLiunian = maleBazi.hunLian.liunian;
        var femaleHunlianLiunian = femaleBazi.hunLian.liunian;

        var trs = [];
        for (var year in maleHunlianLiunian) {
            if (!isset(femaleHunlianLiunian[year])) {
                continue;
            }
            var liunianInfo = maleBazi.liunian[year];
            var code = '<tr><td>'+year+'('+liunianInfo.gan+liunianInfo.zhi+')年</td>'+
                '<td>'+getLiunianInfo(maleBazi,year)+'</td>'+
                '<td>'+getLiunianInfo(femaleBazi,year)+'</td>';
            trs.push(code);
        }
        var trscode = trs.join('');
        if (trs.length==0) {
            trscode = '二者年龄悬殊，无适婚流年重合';
        }

        var code = '<table>'+
            '<tr><th>流年</th>'+
                '<th>'+maleBazi.name+'</th>'+
                '<th>'+femaleBazi.name+'</th>'+
            '</tr>'+trscode+
        '</table>';
        jQuery('#'+domid).html(code);
    };
    return o;
});
