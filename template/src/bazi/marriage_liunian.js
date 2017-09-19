define(function(require){
    /* 流年婚恋 */

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

    var o={};
	o.show=function(domid,bazi) {
        var trs = [];
        for (var year in bazi.hunLian.liunian) {
            var im = bazi.hunLian.liunian[year];
            var liuNian = bazi.liunian[year];
            var code = '<tr><td>'+year+'('+liuNian.gan+liuNian.zhi+')年</td>'+
                    '<td>'+liuNian.age+'岁</td>'+
                    '<td>'+getLiuNianJue(im)+'</td>'+
                '</tr>';
            trs.push(code);
        }

        var code = '<h2 style="margin-top:10px;">【流年婚恋】</h2>'+
            '<table>'+trs.join('')+'</table>';
        jQuery('#'+domid).html(code);
	};
	return o;
});
