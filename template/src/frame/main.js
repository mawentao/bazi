/**
 * 页面框架, 负责页面整体布局和导航菜单管理
 * 框架接口:
 *     init()                : 框架初始化
 *     addcontroller(conf)   : 添加控制器配置
 *     active()              : 选中菜单/导航
 *     showpage()            : 显示主页区域
 **/
define(function(require){
    var controller_confs={};  //!< 控制器配置列表
	var controller_active;    //!< 当前激活的控制器
	var leftnavwidth=50;     //!< 左部导航宽度设置
    var o={};

    // 框架初始化函数
    o.init = function() {
		init_header();
    };


    // 添加控制器配置
    o.addcontroller = function(conf) {
		controller_confs[conf.controller] = conf;
    };

    // 选中菜单
    o.active = function(controller,action) {
		//1. 未切换controller只需选中action
		if (controller_active==controller) {
			jQuery('[name="navitem"]').removeClass('active');
			jQuery('#navitem-'+controller+'-'+action).addClass('active');
			return;
		}

		//2. 清理布局
        jQuery(".menu-item").removeClass('active');
		jQuery('#frame-body').html('');
		if (!controller_confs[controller]) return;  //!< 未添加过此controller的配置
		var conf = controller_confs[controller];

        //3. 选中顶部导航菜单
        jQuery("#menu-"+controller).addClass('active');

		//4. 显示controller布局
		// 左导航布局
		if (conf.menu && conf.menu.length>0) {
			var code = '<div id="frame-west" style="width:'+leftnavwidth+'px;">'+
//					'<div id="leftnavexpbtn" data-fold="0">|||</div>'+
					'<div id="leftuldiv"></div>'+
				'</div>'+
				'<div id="frame-center" style="left:'+leftnavwidth+'px;"></div>';
			jQuery('#frame-body').html(code);
			init_expbtn();
			init_nav(conf);
			// 选中action
			jQuery('[name="navitem"]').removeClass('active');
			jQuery('#navitem-'+controller+'-'+action).addClass('active');
		} else {
			var code = '<div id="frame-center" style="left:0;"></div>';
			jQuery('#frame-body').html(code);
		}
    };

    // 显示页面
    o.showpage = function(code) {
        jQuery("#frame-center").html(code);
    };

    ///////////////////////////////////////////////////////
    
	// 从链接地址中提取controller
	function parse_controller_from_href(href)
	{
		var idx = href.lastIndexOf('#/');
		if (idx<0) return '';
		var tmp = href.substr(idx+2);
		var arr = tmp.split('/');
		return arr[0];
	}

	// 初始化头部
    function init_header() {
		//1. 导航菜单
		var navs = [];
		for (var i=0;i<navlist.length;++i) {
			var im=navlist[i];
			var icon = '<i class="'+im.icon+'" style="padding-left:0;font-size:15px;"></i>';
			var target = im.newtab==1 ? 'target="_blank"' : '';
			var controller = parse_controller_from_href(im.href);
			var id = controller!='' ? 'menu-'+controller : 'menu-'+i;
			var licode = '<li><a class="menu-item" id="'+id+'" href="'+im.href+'" '+target+'>'+icon+im.text+'</a></li>';	
			navs.push(licode);
		}
		var code = '<ul class="menuul">'+navs.join('')+'</ul>';
		//2. 帐号菜单
		var usermenu = [
			{icon:'icon icon-log',text:'Profile',href:'#/uc/profile'},
			{icon:'icon icon-lock',text:'Password',href:'#/uc/changepass'}
		];
		if (dz.role!=0) {
			usermenu.push({icon:'icon icon-log',text:'Control Center',href:'#/admin'});
		}
		usermenu.push({icon:'icon icon-logout',text:'Exit',href:dz.logouturl});

		var sims = [];
		for (var i=0;i<usermenu.length;++i) {
			var im=usermenu[i];
			sims.push('<a href="'+im.href+'" style="padding:0px 10px;white-space:nowrap;">'+
					  '<i class="'+im.icon+'" style="padding:0;"></i> '+im.text+'</a>');
		}

        code += '<div class="comdiv">'+
          '<div class="userdiv">'+
            '<a href="javascript:;"><i class="icon icon-contact"></i> '+dz.username+'</a>'+
            '<div>'+sims.join('<br>')+'</div>'+
          '</div>'+
        '</div>';
        jQuery('#frame-menu').html(code);
    };

	// 左部导航折叠按钮
    function init_expbtn() {
        jQuery('#leftnavexpbtn').unbind('click').click(function(){
            var fold = jQuery(this).data('fold');
            if (fold==0) {
                jQuery('#frame-west').css('width','20px');
                jQuery('#frame-center').css('left','20px');
                jQuery('#leftuldiv').css('display','none');
                jQuery(this).data('fold',1);
            } else {
                jQuery('#frame-west').css('width',leftnavwidth+'px');
                jQuery('#frame-center').css('left',leftnavwidth+'px');
                jQuery('#leftuldiv').css('display','block');
                jQuery(this).data('fold',0);
            }   
        }); 
    }

    // 初始化左部导航
    function init_nav(controlconf) {
		var controller=controlconf.controller;
		var navitems=controlconf.menu;
        var code = '<ul class="leftmenu" id="nav-'+controller+'">';
		for (var j=0; j<navitems.length; ++j) {
			var item = navitems[j];
			var href = item.action ? '#/'+controller+'/'+item.action : "javascript:;";
			var hassubmenu = (item.submenu && item.submenu.length>0);
			var cls = hassubmenu ? 'class="menu-open"' : '';
			var icon = item.icon ? item.icon : 'fa fa-th-large';
			var style = item.style ? item.style : '';
			var liid = item.action ? 'id="navitem-'+controller+'-'+item.action+'"' : '';
			code += "<li "+cls+" style='"+style+"'>"+
					"<a name='navitem' class='lm-menu' href='"+href+"' "+liid+">"+
						'<i class="'+icon+'" style="padding-left:0;"></i><br>'+item.name+"</a>";
			// 子菜单
			if (hassubmenu) {
				code += "<ul class='submenu'>";
				for (var k=0; k<item.submenu.length; ++k) {
					var im = item.submenu[k];
					var href = im.action ? '#/'+controller+'/'+im.action : "javascript:;";
					var style = im.style ? im.style : '';
					liid = im.action ? 'id="navitem-'+controller+'-'+im.action+'"' : '';
					icon = im.icon ? im.icon : 'fa fa-caret-right';
					code += "<li style='"+style+"'><a name='navitem' class='lm-item' href='"+href+"' "+liid+">"+
							'<i class="'+icon+'" style="padding-left:0;"></i>&nbsp;'+im.name+"</a></li>";
				}   
				code += "</ul>";
			}
			code += "</li>";
		}
		code += '</ul>';
        jQuery("#leftuldiv").html(code);
		//2. bunddle event
        jQuery(".lm-menu").unbind('click').click(function(){
            var child = jQuery(this).parent().children(".submenu");
            if (child) {
                var dsp = child.css("display");
                if (!dsp) {
                    //alert(dsp);
                } else if ("none" == dsp) {
                    jQuery(this).parent().removeClass("menu-close");
                    jQuery(this).parent().addClass("menu-open");
                } else {
                    jQuery(this).parent().removeClass("menu-open");
                    jQuery(this).parent().addClass("menu-close");
                }   
            }
        });
    }

    return o;
});

