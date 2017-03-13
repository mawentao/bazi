if (typeof Array.prototype.indexOf != "function") {
  Array.prototype.indexOf = function (searchElement, fromIndex) {
    var index = -1;
    fromIndex = fromIndex * 1 || 0;
    for (var k = 0, length = this.length; k < length; k++) {
      if (k >= fromIndex && this[k] === searchElement) {
          index = k;
          break;
      }
    }
    return index;
  };
}

if(!String.prototype.trim) {
  String.prototype.trim = function () {
    return this.replace(/^\s+|\s+$/g,'');
  };
}

//firefox兼容innerText
(function (bool) { 
    function setInnerText(o, s) { 
        while (o.childNodes.length != 0) { 
            o.removeChild(o.childNodes[0]); 
        } 
        o.appendChild(document.createTextNode(s)); 
    } 
    function getInnerText(o) { 
        var sRet = ""; 
        for (var i = 0; i < o.childNodes.length; i ++) { 
            if (o.childNodes[i].childNodes.length != 0) { 
                sRet += getInnerText(o.childNodes[i]); 
            } 
            if (o.childNodes[i].nodeValue) { 
                if (o.currentStyle.display == "block") { 
                    sRet += o.childNodes[i].nodeValue + "\n"; 
                } else { 
                    sRet += o.childNodes[i].nodeValue; 
                } 
            } 
        } 
        return sRet; 
    } 
    if (bool) { 
        HTMLElement.prototype.__defineGetter__("currentStyle", function () { 
            return this.ownerDocument.defaultView.getComputedStyle(this, null); 
        }); 
        HTMLElement.prototype.__defineGetter__("innerText", function () { 
            return getInnerText(this); 
        });
        HTMLElement.prototype.__defineSetter__("innerText", function(s) { 
            setInnerText(this, s); 
        });
    } 
})(/Firefox/.test(window.navigator.userAgent)); 

//跨浏览器DOM对象
var DOMUtil = {
    getStyle:function(node,attr){
        return node.currentStyle ? node.currentStyle[attr] : getComputedStyle(node,0)[attr];
    },
    getScroll:function(){           //获取滚动条的滚动距离
        var scrollPos={};
        if (window.pageYOffset||window.pageXOffset) {
            scrollPos['top'] = window.pageYOffset;
            scrollPos['left'] = window.pageXOffset;
        }else if (document.compatMode && document.compatMode != 'BackCompat'){
            scrollPos['top'] = document.documentElement.scrollTop;
            scrollPos['left'] = document.documentElement.scrollLeft;
        }else if(document.body){
            scrollPos['top'] = document.body.scrollTop;
            scrollPos['left'] = document.body.scrollLeft;
        }
        return scrollPos;
    },
    getClient:function(){           //获取浏览器的可视区域位置
        var l,t,w,h;
        l  =  document.documentElement.scrollLeft || document.body.scrollLeft;
        t  =  document.documentElement.scrollTop || document.body.scrollTop;
        w =   document.documentElement.clientWidth;
        h =   document.documentElement.clientHeight;
        return {'left':l,'top':t,'width':w,'height':h} ;
    },
    getNextElement:function(node){  //获取下一个节点
        if(node.nextElementSibling){
            return node.nextElementSibling;
        }else{
            var NextElementNode = node.nextSibling;
            while(NextElementNode.nodeValue != null){
                NextElementNode = NextElementNode.nextSibling
            }
            return NextElementNode;         
        }
    },
    getElementById:function(idName){
        return document.getElementById(idName);
    },
    getElementsByClassName:function(className,context,tagName){ //根据class获取节点
        if(typeof context == 'string'){
            tagName = context;
            context = document;
        }else{
            context = context || document;
            tagName = tagName || '*';
        }
        if(context.getElementsByClassName){
            return context.getElementsByClassName(className);
        }
        var nodes = context.getElementsByTagName(tagName);
        var results= [];
        for (var i = 0; i < nodes.length; i++) {
            var node = nodes[i];
            var classNames = node.className.split(' ');
            for (var j = 0; j < classNames.length; j++) {
                if (classNames[j] == className) {
                    results.push(node);
                    break;
                }
            }
        }
        return results;
    },
    addClass:function(node,classname){          //对节点增加class
        if(!new RegExp("(^|\s+)"+classname).test(node.className)){
            node.className = (node.className+" "+classname).replace(/^\s+|\s+$/g,'');
        }
    },
    removeClass:function(node,classname){       //对节点删除class
        node.className = (node.className.replace(classname,"")).replace(/^\s+|\s+$/g,'');
    }
};

//响应
(function(){
    var isIE= navigator.userAgent.indexOf("MSIE")>-1;
    if(isIE){
        var $body =  document.body;
        var resize = function(){
            if(document.documentElement.clientWidth<1200){
                $body.className="dev-small";
            }else if(document.documentElement.clientWidth<1400){
                $body.className="dev-middle";
            }else if(document.documentElement.clientWidth<1600){
                $body.className="dev-large";
            }else{
                $body.className="";
            }
        };
        window.attachEvent('onresize',resize);
        resize();   
    }
})();

//分享
(function(){
    var $share = document.getElementById('J_share');
    if($share){
        $share.innerHTML='<div class="bdsharebuttonbox">\
            <a href="#" class="bds_more" data-cmd="more"></a>\
            <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>\
            <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>\
            <a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>\
            <a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>\
            <a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a>\
        </div>';
        window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
    }
})();

//获取日期
(function() {
    var $mod_head = DOMUtil.getElementsByClassName('mod-head')[0];
    var $date = DOMUtil.getElementsByClassName('icon-date', $mod_head)[0];
    var today = new Date();
    //$date.innerHTML = '' + today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
})();

//菜单
(function(){
    var $mod_head = DOMUtil.getElementsByClassName('mod-head')[0];
    var $mod_mask = DOMUtil.getElementsByClassName('mod-mask')[0];
    var $menu = DOMUtil.getElementsByClassName('menu',$mod_head)[0];
    var $wrapper = DOMUtil.getElementsByClassName('wrapper')[0];
    var toggle = {
        isOpen:false,
        open:function(){
            this.isOpen = true,
            DOMUtil.addClass($wrapper,'status-show');
            document.body.style.overflow = 'hidden';
        },
        close:function(){
            this.isOpen = false,
            DOMUtil.removeClass($wrapper,'status-show');
            document.body.style.overflow = '';
        }
    };
    if($mod_mask){
        $mod_mask.onclick = function(){
            toggle.close();
        };
    }
    if($menu){
        $menu.onclick = function(){
            if(toggle.isOpen){
                toggle.close();
            }else{
                toggle.open();
            }
        };
    }
})();

//回到顶部
(function(){
    var $mod_goback = DOMUtil.getElementsByClassName('mod-goback')[0];
    if($mod_goback){
        var scroll = function(){
            var top = document.documentElement.scrollTop||document.body.scrollTop;
            $mod_goback.style.display = top>420?'block':'none';
        }  
        window.onscroll = scroll;
    }
})();
