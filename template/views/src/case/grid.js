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
              {head:"姓名", dataIndex:"name", width:50, sort:true, render:function(v,item){
				  var color = item.gender=='x' ? 'red' : 'darkgreen';
				  return '<span style="color:'+color+'">'+v+'</span>';
			  }},
              {head:"性别", dataIndex:"gender", width:40, sort:true, render:function(v){
				  return v=='x' ? '女' : '男';
			  }},
			  {head:"公历生日", dataIndex:"solar_calendar", width:150, sort:true, render:function(v,item){
				  var week = mwtcalendar.week[item.week];
				  var tm = strtotime(v);
				  return date('Y年m月d日',tm)+" (周"+week+")";
			  }},
			  {head:"农历生日", dataIndex:"lunar_calendar", width:120, sort:true, render:function(v){
				  return mwtcalendar.parse_lunar_calendar(v);
			  }},
              {head:"生肖", dataIndex:"nian_zhi", sort:true, width:50, render:function(v){
                  return mwtcalendar.zhi[v].animal;
              }}, 
              {head:"年柱", dataIndex:"bid", width:40, render:function(v,item){
				  return item.nian_gan+item.nian_zhi;
              }}, 
              {head:"月柱", dataIndex:"bid", width:40, render:function(v,item){
				  return item.yue_gan+item.yue_zhi;
              }}, 
              {head:"日柱", dataIndex:"bid", width:40, render:function(v,item){
				  return item.ri_gan+item.ri_zhi;
              }}, 
              {head:"时柱", dataIndex:"bid", width:40, render:function(v,item){
				  return item.hour_gan+item.hour_zhi;
              }}, 
              {head:"节气", dataIndex:"term", width:40, render:function(v){
                  return v;
              }},
              {head:"节日", dataIndex:"festival", width:100, render:function(v){
                  return v;
              }},
              {head:"操作", dataIndex:"caseid",render:function(v,item){
				  var foresee = '<a href="plugin.php?id=bazi:foresee&caseid='+v+'" target="_blank">命理预测</a>';
                  var btns = [foresee];
                  return btns.join("&nbsp;|&nbsp;");
              }}
            ]),
			tbar: [
                {label:"性别",id:"gender-sel",type:'select',value:'0',options:dict.get_gender_options({text:'全部',value:0}),handler:o.query},
                {type:'search',id:'so-key',width:300,placeholder:'查询姓名，公历生日（如19001231）',handler:o.query},
				'->',
				{label:'<i class="fa fa-plus"></i> 添加命例',class:'mwt-btn mwt-btn-primary',handler:function(){
					var data = {
						caseid: 0,
						name: ''
					};
					form.open(data);
				}}
			]
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

    return o;
});
