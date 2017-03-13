/* 导航设置 */
define(function(require){
    var store;

    function showlist()
	{
		var rows=[];
		for (var i=0; i<store.size(); ++i) {
			var item = store.get(i);
            var dataidx = "data-idx='"+i+"'";
			var checked = item.newtab==1 ? 'checked' : '';
			var enable_checked = item.enable==1 ? 'checked' : '';
			var code = '<tr>'+
			  '<td><input type="text" name="displayorder[]" '+dataidx+
                   ' value="'+item.displayorder+'" class="txt" style="width:40px;"></td>'+
              '<td><input name="icon[]" id="icon-'+i+'" type="hidden" style="width:40px;" value="'+item.icon+'">'+
                '<i name="iconbtn" class="'+item.icon+'" data-idx="'+i+'" '+
                   'style="font-size:16px;padding:5px;cursor:pointer;border:solid 1px #ddd;color:#333;'+
                          'width:16px;height:16px;text-align:center"></i>'+
              '</td>'+
              "<td><input type='text' name='text[]' "+dataidx+" value='"+item.text+"' class='txt'></td>"+
              "<td><input type='text' name='href[]' "+dataidx+"' value='"+item.href+"' class='txt' style='width:300px;'></td>"+
              "<td>"+
				'<input type="hidden" name="newtab[]" id="newtab-'+i+'" value="'+item.newtab+'">'+
				"<input type='checkbox' name='newtab-cbx' "+dataidx+"' "+checked+"></td>"+
              "<td>"+
				'<input type="hidden" name="enable[]" id="enable-'+i+'" value="'+item.enable+'">'+
				"<input type='checkbox' name='enable-cbx' "+dataidx+"' "+enable_checked+"></td>"+
              "<td><a href='javascript:void(0);' name='delbtn' "+dataidx+" style='cursor:pointer;'>删除</a></td>"+
            "</tr>";
			rows.push(code);
		}
		jQuery('#listbody').html(rows.join(''));

		// 图标点击事件
		jQuery('[name=iconbtn]').click(function(){
			var jthis = jQuery(this);
			var idx = jthis.data("idx");
			require('common/icon_select').open(function(ic){
				store.root[idx].icon = ic;
				jQuery('#icon-'+idx).val(ic);
				jthis.attr('class',ic);
			})
		});
		// 删除事件
		jQuery('[name=delbtn]').unbind('click').click(function(){
			var idx = jQuery(this).data("idx");
			store.remove(idx);
		});
		// 新窗口打开勾选事件
		jQuery('[name=newtab-cbx]').change(function(){
			var idx = jQuery(this).data('idx');
			var ckd = jQuery(this).is(':checked');
			var v = ckd ? 1 : 0;
			set_value('newtab-'+idx,v);
		});
		// 可用勾选事件
		jQuery('[name=enable-cbx]').change(function(){
			var idx = jQuery(this).data('idx');
			var ckd = jQuery(this).is(':checked');
			var v = ckd ? 1 : 0;
			set_value('enable-'+idx,v);
		});
	}

    var o={};
    o.execute = function(){
		store = new MWT.Store();
        store.on('load',showlist);
		store.load(v.navlist);
		jQuery('#addrowbtn').click(function(){
            var item = { 
                'displayorder' : store.size(),
                'icon' : 'fa fa-list',
                'text' : '',
                'href' : '',
				'newtab': 0,
				'eanble': 1,
            };  
            store.push(item);
        });
    };
    return o;
});
