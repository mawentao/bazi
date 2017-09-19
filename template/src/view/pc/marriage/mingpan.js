define(function(require){

    // Base Info
    function show_base(domid,bazi)
    {/*{{{*/
        var cls = 'mz-tab ';
        cls += bazi.gender=='男' ? 'mz-tab-male' : 'mz-tab-female';
        var foreseeUrl = dz.siteurl+'plugin.php?id=bazi:foresee&caseid='+bazi.caseid;
        var code = '<table class="'+cls+'"'+
            '<tr>'+
              '<td>'+
                '<span class="mingzi">'+bazi.name+'</span>'+
                '<span class="xingbie">'+bazi.gender+'</span>'+
                '<span class="age">'+bazi.age+'岁</span>'+
              '</td>'+
              '<td rowspan="3" class="shengxiao" style="width:30px;">'+
                '<p>生肖</p><span style="font-size:18px;">'+bazi.birthAnimal+'</span>'+
              '</td>'+
              '<td rowspan="3" align="right" valign="top" width="55">'+
                '<a style="font-size:12px;" href="'+foreseeUrl+'" target="_blank">命例详批</a>'+
              '</td>'+
            '</tr>'+
            '<tr><td style="font-size:12px;">公历生日：'+bazi.solarCalendar+'</td></tr>'+
            '<tr><td style="font-size:12px;">农历生日：'+bazi.lunarCalendar+' '+bazi.zhi[3].z+'时</td></tr>'+
        '</table>';
        jQuery('#'+domid).html(code);
    }/*}}}*/

    var o={};
    o.show=function(domid,bazicase) {
        var code = '<div id="base-'+domid+'"></div>'+
            '<hr class="split-line"/>'+
            '<div id="mingpan-'+domid+'"></div>'+
            '<hr class="split-line"/>'+
            '<div id="summary-'+domid+'"></div>'+
            '<div id="hunlian-'+domid+'"></div>';
        jQuery('#'+domid).html(code);

        var bazi = bazicase.getData();
        show_base('base-'+domid,bazi);
        bazicase.show_mingpan('mingpan-'+domid,1);
        bazicase.show_summary('summary-'+domid);
        bazicase.show_marriage_summary('hunlian-'+domid);
    };
    return o;
});
