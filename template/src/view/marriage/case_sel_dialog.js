/* 命例选择器对话框 */
define(function(require){
	var gender,store,grid,dialog;
	var domid = 'marriage-case-sel-dialog';
	var callfun;

	function query() {
		store.baseParams = {
			gender: gender,
            key: get_value("so-key-"+domid)
        };
        grid.load();
	};

	function create_grid(divid) {
		store = new MWT.Store({
			url: ajax.getAjaxUrl('case&action=query_all&formarry=1')
        });
		grid = new MWT.Grid({
			render: divid,
            store: store,
			noheader: true,
            pagebar: true,
            pageSize: 10,
			pagebarSimple: true,
			notoolbox: true,
            multiSelect:false, 
			striped: true,
            bordered: false,
			position: 'fixed',
			tbarStyle: 'background:#eee;',
            cm: new MWT.Grid.ColumnModel([
              {head:"姓名", dataIndex:"name",width:130,sort:true,render:function(v,item){
				  return '<span class="name-'+item.gender+'">'+v+'</div>';
			  }},
              {head:"年龄", dataIndex:"age",width:40,sort:true,render:function(v,item){
				  return v+'岁';
			  }},
              {head:"备注", dataIndex:"desc",render:function(v,item){
				  return '<span style="font-size:12px;color:gray">'+v+'</div>';
			  }},
              {head:"公历生日", dataIndex:"solar_calendar",align:'right',width:130,sort:true,render:function(v,item){
				  var tm = strtotime(v);
				  return date('Y年m月d日',tm)+' 生';
			  }}
            ]),
            tbar: [
              {type:"search",label:"查询",id:"so-key-"+domid,width:300,placeholder:'查询姓名',handler:query}
            ],
			rowclick: function(im) {
				if(callfun) callfun(im);
				dialog.close();
			}
        });
		grid.create();
	}


	function init() {
		// dialog
		dialog = new MWT.Dialog({
			render : domid,
            title  : '选择命例',
            width  : 600,
            height : "auto",
            top    : 50,
			height : 389,
            bodyStyle: 'position:relative;',
            body   : '<div id="grid-'+domid+'"></div>'
        });
		dialog.create();
		create_grid('grid-'+domid);

		// 打开窗口事件
		dialog.on('open',function(){
			query();
			jQuery('#grid-marriage-case-sel-dialog-body').css({'top':'42px','bottom':'36px'});
		});
	}

	var o={};
	o.open=function(_gender,_callfun) {
		gender=_gender;
		callfun=_callfun;
		if (!dialog) init();
		dialog.open();
	};
	return o;
});

