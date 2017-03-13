/* 图标选择对话框 */
define(function(require){
    var callback;
	var dialog;

	var icons = [
		'fa fa-home','icon icon-home',
		'fa fa-question-circle','sicon-question',
		'fa fa-th-large','fa fa-th','fa fa-caret-right','fa fa-list','fa fa-list-ol','fa fa-list-ul',
		'fa fa-star','fa fa-star-o','fa fa-tag','fa fa-tags',
		'fa fa-car','fa fa-taxi','fa fa-bus','fa fa-user','fa fa-users','fa fa-cny','fa fa-dollar','fa fa-warning',
		'fa fa-sort-amount-asc','fa fa-sort-amount-desc','fa fa-signal','fa fa-line-chart','fa fa-area-chart','fa fa-bar-chart','fa fa-pie-chart',
		'fa fa-cube','fa fa-cubes','fa fa-database','fa fa-file-text-o','fa fa-list-alt','fa fa-table',
		'fa fa-globe',
		'icon icon-score','sicon-compass','fa fa-commenting','fa fa-info-circle','fa fa-wrench'
	];

	function getbody() {
		var code = "";
		for (var i=0;i<icons.length;++i) {
			code += '<i class="'+icons[i]+'" name="iconselitem" '+
                'style="font-size:16px;padding:8px;line-height:30px;cursor:pointer;border:solid 0px #ddd;color:#333;"></i>';
		}
		return code;
	}

	function init() {
		dialog = new MWT.Dialog({
            title  : '选择图标',
            width  : 390,
            top    : 50,
			bodyStyle: 'padding:10px 20px;',
            body   : getbody()/*,
            buttons  : [
                {label:"确定",handler:function(){alert("sss");}},
                {label:"关闭",type:'close',cls:'mwt-btn-danger'}
            ]*/
        });
		dialog.on('open', function(){
		    jQuery("[name=iconselitem]").click(function(){
				var v = jQuery(this).attr('class');
				dialog.close();
				if (callback) callback(v);
		    });
		});
	}

    var o={};
	o.open = function(callfun) {
		if (callfun) callback=callfun;
		if (!dialog) init();
		dialog.open();
	};
	return o;
});
