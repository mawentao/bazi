define(function(require){
	var dict=require('common/dict');
//	var help=require('common/help');
//	var dialog=require('./dialog');
	var store,grid,gridid;

    var o = {};
	o.init = function(_gridid) {
		gridid = _gridid;
		// create grid
		var bar = [
			{type:"select",id:"gender-"+gridid,value:0,label:'性别',options:dict.get_gender_options({text:'全部',value:'0'}),handler:o.query},
            {type:'search',id:'so-key-'+gridid,width:300,placeholder:"查询姓名和备注",handler:o.query},
			'->',
			{label:'<i class="fa fa-plus"></i> 添加命例',handler:function(){
				window.location = "#/index/index";
			}}
        ];
		store = new MWT.Store({
			url: ajax.getAjaxUrl('case&action=query_all'),
			beforeLoad: store_before_load,
			afterLoad: store_after_load
  		});
		grid = new MWT.Grid({
			render: gridid,
			store: store,
			pagebar: true,
			pageSize: 50,
			bordered: false,
			position: 'fixed',
			multiSelect:false, 
			striped: true,
			tbar: bar,
			tbarStyle: 'margin-bottom:-1px;border:none;background:#fff;',
			bodyStyle: 'top:78px;bottom:37px;background:#fff;',
			cm: new MWT.Grid.ColumnModel([
                {head:"性别", dataIndex:"gender", width:40, align:'center', render:function(v,item){
				    var s = v=='x' ? '女' : '男';
					var color = item.gender=='x' ? 'red' : 'darkgreen';
					return '<span style="color:'+color+'">'+s+'</span>';
			    }},
                {head:"姓名", dataIndex:"name", width:100, align:'left', sort:true, render:function(v,item){
				    var color = item.gender=='x' ? 'red' : 'darkgreen';
				    return '<a style="color:'+color+'" class="grida" '+
						'href="'+item.foresee_url+'" target="_blank">'+v+'</a>';
			    }},
                {head:"备注", dataIndex:"desc", align:'left', render:function(v,item){
				    return '<span style="color:gray;font-size:12px;">'+v+'</span>';
			    }},
              {head:"年龄", dataIndex:"age", width:40, align:'center',render:function(v){return v+'岁';}},
              {head:"生肖", dataIndex:"nian_zhi", width:40, align:'center', render:function(v){
                  return mwtcalendar.zhi[v].animal;
              }}, 
			  {head:"公历生日", dataIndex:"solar_calendar", width:150, align:'left', sort:true, render:function(v,item){
				  var week = mwtcalendar.week[item.week];
				  var tm = strtotime(v);
				  return date('Y年m月d日',tm)+"(周"+week+")";
			  }},
			  {head:"农历生日", dataIndex:"lunar_calendar", width:120, sort:true, align:'left', render:function(v){
				  return mwtcalendar.parse_lunar_calendar(v);
			  }},
              {head:"年柱", dataIndex:"bid", width:40, align:'center', render:function(v,item){
				  return item.nian_gan+item.nian_zhi;
              }}, 
              {head:"月柱", dataIndex:"bid", width:40, align:'center', render:function(v,item){
				  return item.yue_gan+item.yue_zhi;
              }}, 
              {head:"日柱", dataIndex:"bid", width:40, align:'center', render:function(v,item){
				  return item.ri_gan+item.ri_zhi;
              }}, 
              {head:"时柱", dataIndex:"bid", width:40, align:'center', render:function(v,item){
				  return item.hour_gan+item.hour_zhi;
              }}, 
              {head:"节气", dataIndex:"term", width:40, align:'center', render:function(v){
                  return v;
              }},
              {head:"节日", dataIndex:"festival", width:100, align:'center', render:function(v){
                  return v;
              }},
				{head:'创建时间',dataIndex:'ctime',align:'center',width:130,sort:true,render:function(v,item){
					var tm = strtotime(v);
					return '<span style="color:gray;font-size:12px;">'+date('Y-m-d H:i',tm)+'</span>';
				}},
				{head:"操作", dataIndex:"caseid",width:80,align:'center',render:function(v,item){
					var btncls = 'mwt-btn mwt-btn-xs mwt-btn-default';
					var popcls = 'mwt-popover-primary';

				  var foresee = '<a href="'+item.foresee_url+'" target="_blank" '+
						'class="'+btncls+'" pop-title="查看批命" pop-cls="'+popcls+'">'+
						'<i class="fa fa-eye"></i></a>';
				  var editbtn = '<a href="javascript:;" name="editbtn" data-id="'+v+'">编辑</a>';
				  var delbtn = '<a href="javascript:;" name="delbtn" data-id="'+v+'"'+
						'class="'+btncls+'" pop-title="删除" pop-cls="'+popcls+'">'+
						'<i class="fa fa-trash-o"></i></a>';
                  var btns = [foresee,delbtn];
                  return btns.join("&nbsp;&nbsp;");
              }}
      		])
        });
		grid.create();
		store.on('load',function(res){
			// 帮助信息
			mwt.popinit();
			// 编辑按钮
			jQuery('[name=editbtn]').unbind('click').click(function(){
				var id = jQuery(this).data('id');
				var im = grid.getRecord('caseid',id);
				//form.open(im);
			});
			// 删除按钮
			jQuery('[name=delbtn]').unbind('click').click(function(){
				var id = jQuery(this).data('id');
				del(id);
			});

		});
		o.query();
	};

	o.query=function() {
		store.baseParams = {
			gender: get_select_value('gender-'+gridid),
            key: get_value("so-key-"+gridid)
        };
        grid.load();
	};
	o.refresh=function() {store.load(); }

	// 删除一条记录
	function del(id) {
		mwt.confirm("确定要删除吗？",function(res){
			if (res) {
				ajax.post('case&action=del',{caseid:id},function(res){
					if(res.retcode!=0) mwt.notify(res.retmsg,1500,'danger');
					else {
						o.refresh();
					}
				});
			}
		});
	}

    return o;
});

