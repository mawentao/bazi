define(function(require){
    /* 婚恋总论 */

    // 断语列表
    function getJueList(list) 
    {/*{{{*/
        var res = [];
        for (var i=0;i<list.length;++i) {
            var v = list[i];
            var code = '<li class="nature_'+v.nature+'">'+v.desc+'</li>';
            res.push(code);
        }
        return res;
    }/*}}}*/

    function getPart(title,jueList)
    {
        var code = '<h2 style="margin-top:10px;">【'+title+'】</h2>';
        if (!jueList.length) {
            code += '<span style="color:#aaa;margin-left:20px;font-size:14px;">无显著特征</span>';
        } else {
            code += '<ul class="textul">'+getJueList(jueList).join('')+'</ul>';
        }
        return code;
    }


    // 配偶星
    function get_spouse_summary(bazi) {
        var spouse = bazi.spouse;
        var rs = [
            '<li>配偶星：'+spouse['正']+'('+spouse.zhengStat+')，'+spouse['偏']+'('+spouse.pianStat+')</li>'
        ];
        return '<ul class="textul">'+
            rs.join('')+
            getJueList(spouse.jue).join('')+
        '</ul>';
    }

    var o={};
    o.show=function(domid,bazi) {
        var code = '<h2>【配偶星】</h2>'+get_spouse_summary(bazi)+
            getPart('配偶宫',bazi.hunLian.gong.jue)+
            getPart('合',bazi.hunLian.he.jue)+
            getPart('桃花',bazi.hunLian.taohua.jue)+
            getPart('伤官星',bazi.hunLian.shangguan.jue)+
            getPart('神煞',bazi.hunLian.shensha.jue);
        jQuery('#'+domid).html(code);
    };

    return o;
});
