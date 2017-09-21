define(function(require){
    /* 流年婚恋 */


    var o={};

    o.getLiuNianJue = function(im)
    {
        var badges = [];
        var kmp = {
            gan_he: ['干合','流年天干与日元相合'],
            zhi_he: ['支合','流年地支与配偶宫相合'],
            gan_taohua: ['干桃花','日干桃花'],
            zhi_taohua: ['支桃花','日支桃花'],
            zhi_chong: ['支刑冲','流年地支与配偶宫刑冲']
        };
        for (var k in kmp) {
            if (!im[k]) continue;
            var code = kmp[k][0];
            if (im[k]>1) code += 'x'+im[k];
            var cls = k=='zhi_chong' ? 'danger' : 'success';
            badges.push('<span class="'+cls+'" pop-title="'+kmp[k][1]+'" pop-cls="mwt-popover-'+cls+'">'+code+'</span>');
        }
        return badges.join('');
    };


	o.show=function(domid,bazi) {
        var trs = [];
        var nowyear = date('Y');
        for (var year in bazi.hunLian.liunian) {
            var im = bazi.hunLian.liunian[year];
            var liuNian = bazi.liunian[year];
            var cls = nowyear==year ? ' class="nowyear"' : '';
            var code = '<tr'+cls+'>'+
                    '<td style="text-align:center">'+year+'('+liuNian.gan+liuNian.zhi+')年</td>'+
                    '<td style="text-align:center">'+liuNian.age+'岁</td>'+
                    '<td>'+o.getLiuNianJue(im)+'</td>'+
                '</tr>';
            trs.push(code);
        }

        var code = '<h2 style="margin-top:10px;">【流年婚恋】</h2>'+
            '<table class="hunlian-liunian-tab">'+
              '<tr>'+
                '<th width="120" style="text-align:center">流年</th>'+
                '<th width="80" style="text-align:center">年龄</th>'+
                '<th>姻缘</th>'+
              '</tr>'+
              trs.join('')+
            '</table>';
        jQuery('#'+domid).html(code);
        mwt.popinit();
	};
	return o;
});
