define(function(require){
    /* 八字命盘 */

    function getYYCls(yy) { return yy=='阳' ? 'fyang' : 'fyin';}

    function getZ(bazicase,gz,i) 
    {/*{{{*/
        var z    = bazicase[gz][i].z;
        var key = gz=='gan' ? 'tiangan' : 'dizhi';
        var info = bazicase.dict[key][z];
        var cls  = getYYCls(info.yy);
        if (gz=='gan' && i==2) cls += ' riyuan';
        return '<span class="ganzhi '+cls+'">'+z+'</span>'+
               '<span class="wuxing">'+info.wuxing+'</span>';
    }/*}}}*/

    // 获取藏干或支神
    function getCangGan(bazicase,idx,isZhiShen) 
    {/*{{{*/
        //1. 获取地支藏干
        var zhi = bazicase.zhi[idx].z;
        var zhiInfo = bazicase.dict.dizhi[zhi];
        var canggans = zhiInfo.canggan;
        //2. 藏干排序
        var arr = ['','',''];
        var n = canggans.length;
        switch (n) {
            case 1: arr[1] = canggans[0]; break;
            case 2: 
                arr[1] = canggans[0]; 
                arr[2] = canggans[1];
                break;
            case 3:
                arr[0] = canggans[1]; 
                arr[1] = canggans[0]; 
                arr[2] = canggans[2];
                break;
        }
        //3. 藏干或十神
        var spans = [];
        for (var i=0;i<3;++i) {
            var im = arr[i];
            if (im=='') {
                spans.push('<span class="yuqi"></span>');
                continue;
            }
            var yy = bazicase.dict.tiangan[im.gan].yy;
            var cls = i==1 ? 'zhuqi' : 'yuqi';
            if (cls=='zhuqi' && !isZhiShen) {
                cls += getYYCls(yy);
            }
            var v = im.gan;
            if (isZhiShen) {
                v = getShiShenShortName(bazicase,im.shishen);
            }
            var code = '<span class="'+cls+'">'+v+'</span>';
            spans.push(code);
        }
        return spans.join('');
    }/*}}}*/

    // 获取五行时令
    function getShiLing(bazicase) 
    {/*{{{*/
        var wuxingmap = bazicase.dict.wuxing;
        var arr=['木','火','土','金','水'];
        var rs = [];
        for (var i=0;i<arr.length;++i) {
            var wx = arr[i];
            var state = wuxingmap[wx].state;
            var cls = wx==bazicase.gan[2].wuxing ? '' : 'flm';
            rs.push('<span class="'+cls+'">'+wx+':'+state+'</span>');
        }
        return rs.join(" ");
    }/*}}}*/

    // 大运流年的cell
    function yunNianGanZhi(bazicase,im)
    {/*{{{*/
        var shiShenMap = bazicase.dict.shishen;
        var rs = [];
        //1. 天干
        var ganInfo = bazicase.dict.tiangan[im.gan];
        var code = '<span class="'+getYYCls(ganInfo.yy)+'">'+im.gan+'<span>'+
                   '<span class="sub">'+shiShenMap[ganInfo.shishen].short_name+'</span>';
        rs.push(code);
        //2. 地支
        var zhiInfo = bazicase.dict.dizhi[im.zhi];
        var code = '<span class="'+getYYCls(zhiInfo.yy)+'">'+im.zhi+'<span>'+
                   '<span class="sub">'+shiShenMap[zhiInfo.canggan[0].shishen].short_name+'</span>';
        rs.push(code);
        return rs.join('<br>');
    }/*}}}*/

    // 大运
    function getDaYun(bazicase) 
    {/*{{{*/
        var list = bazicase.dayun;
        var n = list.length;
        var width = 100/n+'%';
        var rs = [];
        for (var i=0;i<n;++i) {
            var im = list[i];
            var code = '<td width="'+width+'">'+yunNianGanZhi(bazicase,im)+'</td>';
            rs.push(code);
        }
        return rs.join('');
    }/*}}}*/

    // 岁数
    function getAgeList(bazicase)
    {/*{{{*/
        var list = bazicase.dayun;
        var n = list.length;
        var rs = [];
        for (var i=0;i<n;++i) {
            var im = list[i];
            var code = '<td class="age">'+im.age+'岁</td>';
            rs.push(code);
        }
        return rs.join('');
    }/*}}}*/

    // 始于
    function getNianList(bazicase)
    {/*{{{*/
        var list = bazicase.dayun;
        var n = list.length;
        var rs = [];
        for (var i=0;i<n;++i) {
            var im = list[i];
            var code = '<td style="font-size:12px;">'+im.nian+'年</td>';
            rs.push(code);
        }
        return rs.join('');
    }/*}}}*/

    // 流年
    function getLiuNian(bazicase)
    {/*{{{*/
        var list = bazicase.dayun;
        var n = list.length;
        var code = '';
        var jinnian = date('Y');
        for (var r=0;r<10;++r) {
            code += '<tr><th>'+(r==0 ? '流年' : '')+'</th>';
            for (var c=0;c<n;++c) {
                var yunnian = list[c].nian;
                var nian = yunnian + r;
                var info = bazicase.liunian[nian];
                var cls = 'liunian';
                if (nian==jinnian) cls += ' jinnian';
                var pop = nian+'年 '+info.age+'岁';
                code += '<td><a href="javascript:;" name="a-liunian" class="'+cls+'" '+
                               'data-year="'+nian+'" pop-title="'+pop+'" pop-cls="mwt-popover-danger">'+
                               yunNianGanZhi(bazicase,info)+'</a></td>';
            }
            code += '</tr>';
        }
        return code;
    }/*}}}*/

    // 获取十神简称
    function getShiShenShortName(bazicase,shishen)
    {/*{{{*/
        return bazicase.dict.shishen[shishen].short_name;
    }/*}}}*/

    // 获取地支藏干
    function getZhiCangGan(bazicase,zhi)
    {
        return bazicase.dict.shishen[shishen].short_name;
    }


    var o={};
    o.show=function(domid,bazicase,simple) {
        var bcolor = '#88814A';
        var code = '<table class="mingpan-tab" border="1">'+
            '<tr>'+
              '<th width="40"></th>'+
              '<th colspan="2">年柱</th>'+
              '<th colspan="2">月柱</th>'+
              '<th colspan="2">日柱</th>'+
              '<th colspan="2">时柱</th>'+
              '<th></th>'+
            '</tr>'+
            '<tr>'+
              '<th>十神</th>'+
              '<td colspan="2">'+getShiShenShortName(bazicase,bazicase.gan[0].shishen)+'</td>'+
              '<td colspan="2">'+getShiShenShortName(bazicase,bazicase.gan[1].shishen)+'</td>'+
              '<td colspan="2">日元</td>'+
              '<td colspan="2">'+getShiShenShortName(bazicase,bazicase.gan[3].shishen)+'</td>'+
              '<td></td>'+
            '</tr>'+
            '<tr>'+
              '<th rowspan="2">'+(bazicase.gender=='男'?'乾造':'坤造')+'</th>'+
              '<td colspan="2" style="border-top:solid 1px '+bcolor+';border-left:solid 1px '+bcolor+';">'+getZ(bazicase,'gan',0)+'</td>'+
              '<td colspan="2" style="border-top:solid 1px '+bcolor+';">'+getZ(bazicase,'gan',1)+'</td>'+
              '<td colspan="2" style="border-top:solid 1px '+bcolor+';">'+getZ(bazicase,'gan',2)+'</td>'+
              '<td colspan="2" style="border-top:solid 1px '+bcolor+';border-right:solid 1px '+bcolor+';">'+getZ(bazicase,'gan',3)+'</td>'+
              '<th rowspan="2"><span class="flm kongwang">'+bazicase.kongWang.join('')+'<br>(空)</span></th>'+
            '</tr>'+
            '<tr>'+
              '<td colspan="2" style="border-bottom:solid 1px '+bcolor+';border-left:solid 1px '+bcolor+';">'+getZ(bazicase,'zhi',0)+'</td>'+
              '<td colspan="2" style="border-bottom:solid 1px '+bcolor+';">'+getZ(bazicase,'zhi',1)+'</td>'+
              '<td colspan="2" style="border-bottom:solid 1px '+bcolor+';">'+getZ(bazicase,'zhi',2)+'</td>'+
              '<td colspan="2" style="border-bottom:solid 1px '+bcolor+';border-right:solid 1px '+bcolor+';">'+getZ(bazicase,'zhi',3)+'</td>'+
            '</tr>'+
            '<tr>'+
              '<th>藏干</th>'+
              '<td colspan="2">'+getCangGan(bazicase,0)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,1)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,2)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,3)+'</td>'+
              '<td></td>'+
            '</tr>'+
            '<tr>'+
              '<th>支神</th>'+
              '<td colspan="2">'+getCangGan(bazicase,0,true)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,1,true)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,2,true)+'</td>'+
              '<td colspan="2">'+getCangGan(bazicase,3,true)+'</td>'+
              '<td></td>'+
            '</tr>'+
            '<tr>'+
              '<th>地势</th>'+
              '<td colspan="2" class="dishi">'+bazicase.zhi[0].dishi+'</td>'+
              '<td colspan="2" class="dishi">'+bazicase.zhi[1].dishi+'</td>'+
              '<td colspan="2" class="dishi">'+bazicase.zhi[2].dishi+'</td>'+
              '<td colspan="2" class="dishi">'+bazicase.zhi[3].dishi+'</td>'+
              '<td></td>'+
            '</tr>'+
            '<tr>'+
              '<th>纳音</th>'+
              '<td colspan="2">'+bazicase.nayin[0]+'</td>'+
              '<td colspan="2">'+bazicase.nayin[1]+'</td>'+
              '<td colspan="2">'+bazicase.nayin[2]+'</td>'+
              '<td colspan="2">'+bazicase.nayin[3]+'</td>'+
              '<td></td>'+
            '</tr>'+
            '<tr>'+
              '<th>时令</th>'+
              '<td colspan="6">'+getShiLing(bazicase)+'</td>'+
              '<td colspan="3">日元身<span class="fem">'+bazicase.riyuanPower.powerLevel+'</span></td>'+
            '</tr>'+
            '<tr><th>大运</th>'+getDaYun(bazicase)+'</tr>'+
            '<tr><th>岁数</th>'+getAgeList(bazicase)+'</tr>'+
            '<tr><th>始于</th>'+getNianList(bazicase)+'</tr>'+
            ( simple ? '' : getLiuNian(bazicase)) +
        '</table>';
        jQuery('#'+domid).html(code);
                    
        mwt.popinit();
    };

    return o;
});
