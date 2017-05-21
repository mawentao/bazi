define(function(require){
	var ajax=require("ajax");
	var dict=require("common/dict");
	var form=require("./form");
	var store,grid;

    var o={};
    o.init = function(){
		store = new MWT.Store({
            url: ajax.getAjaxUrl("case&action=query_all")
        });
		grid = new MWT.Grid({
            render: "grid-div",
            store: store,
            pagebar: true, //!< false 表示不分页
            pageSize: 10,
            multiSelect:false, 
            bordered: false,
			tbarStyle: 'margin-bottom:10px;',
            emptyMsg: '查询为空',
            position: 'fixed',
            bodyStyle: 'top:92px;bottom:37px;',
            cm: new MWT.Grid.ColumnModel([
              {head:"姓名", dataIndex:"name", width:50, align:'left', sort:true, render:function(v,item){
				  var color = item.gender=='x' ? 'red' : 'darkgreen';
				  return '<span style="color:'+color+'">'+v+'</span>';
			  }},
              {head:"备注", dataIndex:"desc", align:'center', render:function(v,item){
				  return '<span style="color:gray;font-size:12px;">'+v+'</span>';
			  }},
              {head:"性别", dataIndex:"gender", width:30, align:'center', render:function(v){
				  return v=='x' ? '女' : '男';
			  }},
              {head:"年龄", dataIndex:"age", width:30, align:'center'},
              {head:"生肖", dataIndex:"nian_zhi", width:30, align:'center', render:function(v){
                  return mwtcalendar.zhi[v].animal;
              }}, 
			  {head:"公历生日", dataIndex:"solar_calendar", width:150, align:'left', sort:true, render:function(v,item){
				  var week = mwtcalendar.week[item.week];
				  var tm = strtotime(v);
				  return date('Y年m月d日',tm)+" (周"+week+")";
			  }},
			  {head:"农历生日", dataIndex:"lunar_calendar", width:120, sort:true, align:'left', render:function(v){
				  return mwtcalendar.parse_lunar_calendar(v);
			  }},
              {head:"年柱", dataIndex:"bid", width:30, align:'center', render:function(v,item){
				  return item.nian_gan+item.nian_zhi;
              }}, 
              {head:"月柱", dataIndex:"bid", width:30, align:'center', render:function(v,item){
				  return item.yue_gan+item.yue_zhi;
              }}, 
              {head:"日柱", dataIndex:"bid", width:30, align:'center', render:function(v,item){
				  return item.ri_gan+item.ri_zhi;
              }}, 
              {head:"时柱", dataIndex:"bid", width:30, align:'center', render:function(v,item){
				  return item.hour_gan+item.hour_zhi;
              }}, 
              {head:"节气", dataIndex:"term", width:40, align:'center', render:function(v){
                  return v;
              }},
              {head:"节日", dataIndex:"festival", width:100, align:'center', render:function(v){
                  return v;
              }},
              {head:"操作", dataIndex:"caseid",width:130,align:'center',render:function(v,item){
				  var foresee = '<a href="'+item.foresee_url+'" target="_blank">命理预测</a>';
				  var editbtn = '<a href="javascript:;" name="editbtn" data-id="'+v+'">编辑</a>';
				  var delbtn = '<a href="javascript:;" name="delbtn" data-id="'+v+'">删除</a>';
                  var btns = [foresee,editbtn,delbtn];
                  return btns.join("&nbsp;&nbsp;");
              }}
            ]),
			tbar: [
                {label:"性别",id:"gender-sel",type:'select',value:'0',options:dict.get_gender_options({text:'全部',value:0}),handler:o.query},
                {type:'search',id:'so-key',width:300,placeholder:'查询姓名，公历生日（如19001231）',handler:o.query},
				'->',
				{label:'<i class="fa fa-plus"></i> 添加命例',class:'mwt-btn mwt-btn-primary',handler:function(){
					var data = {
						caseid: 0,
						gender: 'y',
						name: '',
						desc: '',
					};
					form.open(data);
				}}
			]
        });
		store.on('load',function(){
			// 编辑按钮
			jQuery('[name=editbtn]').unbind('click').click(function(){
				var id = jQuery(this).data('id');
				var im = grid.getRecord('caseid',id);
				form.open(im);
			});
			// 删除按钮
			jQuery('[name=delbtn]').unbind('click').click(function(){
				var id = jQuery(this).data('id');
				del(id);
			});
		});
        grid.create();
		o.query();
    };

	o.query = function() {
		store.baseParams = {
            gender: get_select_value("gender-sel"),
            key: get_value("so-key")
        };
        grid.load();
	}
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
