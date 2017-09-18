define(function(require){
    /* 顶部区域 */
    var bazicase;

    // 顶部导航
    function getNavHtml()
    {
        var navs = [
            {id:'summary',   title:'总论', icon:'fa fa-connectdevelop'},
            {id:'marriage',  title:'婚恋', icon:'fa fa-venus-mars'},
            {id:'character', title:'心性', icon:'fa fa-male'},
            {id:'fortune',   title:'事业', icon:'fa fa-cny'},
            {id:'health',    title:'健康', icon:'fa fa-heartbeat'}
        ];
        var ls = [];
        for (var i=0;i<navs.length;++i) {
            var im = navs[i];
            var code = '<li><a name="navbtn" href="javascript:;" data-id="'+im.id+'">'+
                '<i class="'+im.icon+'"></i> '+im.title+
            '</a></li>';
            ls.push(code);
        }
        return '<ul class="navul">'+ls.join('')+'</ul>';
    }

    var o={};

    o.init=function(domid,_bazicase) {
        bazicase = _bazicase;
        var bazi = bazicase.getData();
        var color = bazi.gender=='男' ? '#065679' : '#730800';
        var cls = bazi.gender=='男' ? 'fyang' : 'fyin';
        new mwt.ToolBar({
            render: domid,
            style : 'background:none;border:none;',
            items: [
                '<label class="xingming" style="background:'+color+'">'+bazi.name+'</label>',
                '<span class="'+cls+'" style="font-size:16px;">'+bazi.gender+'</span>',
                '<span class="fem" style="font-size:16px;white-space:nowrap;">'+bazi.age+'岁</span>',
                '<label style="font-size:13px;color:#aaa;">'+
                    '公历生日：'+bazi.solarCalendar+'<br>'+
                    '农历生日：'+bazi.lunarCalendar+' '+bazi.zhi[3]['z']+'时'+
                '</label>',
                '<label style="font-size:13px;color:#aaa;">生肖：<b style="font-size:18px;color:#fff;">'+bazi.birthAnimal+'</b></label>',
                '->',
                '<div id="nav-div">'+getNavHtml()+'</div>',
                '<a class="logo" href="'+dz.siteurl+'/plugin.php?id=bazi:console">'+
                    '<img src="'+dz.pluginpath+'/template/static/logo2.png"></a>'
            ]
        }).create();
        jQuery('[name=navbtn]').unbind('click').click(function(){
            var id = jQuery(this).data('id');
            o.active(id);
        });
        //o.active('summary');
        o.active('marriage');
    };

    o.active=function(id) {
        jQuery('[name=navbtn]').removeClass('active');
        jQuery('[name=navbtn][data-id='+id+']').addClass('active');
        var domid = 'frame-center';
        switch (id) {
            case 'marriage' : require('./panel_marriage/index').show(domid,bazicase); break;   // 婚恋
            case 'character': require('./panel_character/index').show(domid,bazicase); break;  // 心性
            case 'fortune'  : require('./panel_fortune/index').show(domid,bazicase); break;    // 事业
            case 'health'   : require('./panel_health/index').show(domid,bazicase); break;     // 健康
            case 'summary'  :
            default: require('./panel_summary/index').show(domid,bazicase); break;  // 总论
        }
    };

    return o;
});
