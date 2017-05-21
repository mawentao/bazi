/* calendar.js, (c) 2017 mawentao */
define(function(require){
	// 当前设置的年月日时数据
	var year_sel_id,month_sel_id,hour_sel_id;
	var data = {year:0,month:0,day:0};
	
	// 年份选择器
	function get_year_sel() 
	{/*{{{*/
		var code = '<select id="'+year_sel_id+'" class="form-control" style="width:auto;">';
		var ny = date("Y");
		for (var i=1901;i<=2050;++i) {
			var selected = i==ny ? ' selected' : '';
			code += '<option value="'+i+'"'+selected+'>'+i+'年</option>';
		}	
		return code+'</select>';
	}/*}}}*/

	// 月份选择器
    function get_month_sel() 
	{/*{{{*/
        var code = '<select id="'+month_sel_id+'" class="form-control" style="width:auto;">';
        var ny = date("m");
        for (var i=1;i<=12;++i) {
            var selected = i==ny ? ' selected' : ''; 
            code += '<option value="'+i+'"'+selected+'>'+i+'月</option>';
        }   
        return code+'</select>';
    }/*}}}*/

	// 时辰选择器ID
    function get_hour_sel() 
	{/*{{{*/
        var code = '<select id="'+hour_sel_id+'" class="form-control" style="width:auto;">';
        var ny = date("H");
		var zhiarr = ['子','丑','丑','寅','寅','卯','卯','辰','辰','巳','巳','午','午',
			'未','未','申','申','酉','酉','戌','戌','亥','亥','子'];
        for (var i=0;i<=23;++i) {
            var selected = i==ny ? ' selected' : ''; 
            var ei = i+1;
			var im = i<10 ? '0' : '';
			var em = ei<10 ? '0' : '';
            code += '<option value="'+i+'"'+selected+'>['+zhiarr[i]+'时] '+im+i+/*'~'+em+ei+*/'点</option>';
        }
        return code+'</select>';
    }/*}}}*/

	// 获取本月第一天时间戳
	function get_month_first_date_time(day) 
	{/*{{{*/
		var tm = strtotime(day);
		var y = parseInt(date('Y',tm));
		var m = parseInt(date('m',tm));
		var m1 = y+'-'+(m<10?'0':'')+m+'-01';
		return strtotime(m1);
	}/*}}}*/

	// 获取日历日期列表
	function get_date_list() 
	{/*{{{*/
		var m = data.month<10 ? '0'+data.month : data.month;
		var d = data.day<10 ? '0'+data.day : data.day;
		var active_date = data.year+'-'+m+'-'+d;
		var m1tm = get_month_first_date_time(active_date);
		var m1 = date('Y-m-d',m1tm);
		var w = parseInt(date('w',m1tm));
		if(w==0)w=7;
		var tm1=m1tm-(w-1)*86400;
		var curm = parseInt(date('m',m1tm));	//!< 当月
		var days = [];
		while (true) {
			var m = parseInt(date('m',tm1));
			if (days.length>7 && m!=curm) break;
			for (var i=0;i<7;++i) {
				days.push(tm1);
				tm1 += 86400;
			}
		}
		return days;
	}/*}}}*/

    // 前后月份切换
	function switch_month(dlt)
	{/*{{{*/
		var y = data.year;
		var m = data.month;
		if (dlt>0) {
			++m;
			if (m>12) {++y;m=1;}
		} else {
			--m;
			if (m<0) {--y;m=12;}
		}
		// 如果切换之后的月份最大天数小于日
		var days = mwtcalendar.get_days_in_year_month(y,m);
		if (data.day>days) data.day=days;
		o.setDate(y+'-'+m+'-'+data.day);
	}/*}}}*/

	// 加载日历
	function load_calendar()
	{/*{{{*/
		var days = get_date_list();
		var code = '';
		for (var i=0;i<days.length;++i) {
			if (i==0) code += '<tr>';
			else if (i%7==0) code +='</tr><tr>';
			var tm = days[i];
			var y = parseInt(date('Y',tm));
			var m = parseInt(date('m',tm));
			var d = parseInt(date('d',tm));
			var active = '';
			if (data.year==y && data.month==m && data.day==d) active='active';
			var solar_date = date('Y-m-d',tm);
			var curmonth = data.month==m ? 'curmonth ' : '';
			code += '<td name="day-'+year_sel_id+'" class="'+curmonth+active+'" data-date="'+solar_date+'">'+
						'<span class="date">'+d+'</span>'+
						'<span class="date-yinli">'+get_lunar_date(solar_date)+'</span></td>';
		}
		code += '</tr>';
		jQuery('#cleanday-body').html(code);
		// 日期点击事件
		jQuery('[name=day-'+year_sel_id+']').unbind('click').click(function(){
			jQuery('[name=day-'+year_sel_id+']').removeClass('active');
			jQuery(this).addClass('active');
			var dt = jQuery(this).data('date');
			o.setDate(dt);
		});
	}/*}}}*/

	// 年份月份改变时,切换日历
    function syncdate() 
	{/*{{{*/
        var year = get_select_value(year_sel_id);
        var month = get_select_value(month_sel_id);
		var d = year+'-'+month+'-'+data.day;
		o.setDate(d);		
    }/*}}}*/

	// 同步右上角信息
	function syncmsg() 
	{/*{{{*/
		var tm = strtotime(data.year+'-'+data.month+'-'+data.day);
		var msg = date("Y年m月d日",tm);
		var h = o.getHour();
		msg+=' '+h+'点';

		msg+='（'+get_lunar_date(date("Y-m-d",tm));
		var zhiarr = ['子','丑','丑','寅','寅','卯','卯','辰','辰','巳','巳','午','午',
			'未','未','申','申','酉','酉','戌','戌','亥','亥','子'];
		msg+= ' '+zhiarr[h]+'时）';
		jQuery('#calendarmsg').html(msg);
	};/*}}}*/
	

    var o={};

	// 设置日期
	o.setDate=function(dstr) 
	{/*{{{*/
		var tm = strtotime(dstr);
		var y = parseInt(date('Y',tm));
		var m = parseInt(date('m',tm));
		data.day = parseInt(date('d',tm));
		if (y!=data.year || m!=data.month) {
			data.year  = y;
			data.month = m;
			// 设置sel
			set_select_value(year_sel_id,data.year);
			set_select_value(month_sel_id,data.month);
			// 加载日历
			load_calendar();
		}
		// 右上角信息
		syncmsg();
	};/*}}}*/

	// 获取日期
	o.getDate=function() 
	{/*{{{*/
		var tm = strtotime(data.year+'-'+data.month+'-'+data.day);
		return date("Y-m-d",tm);
	}/*}}}*/

	// 获取时辰
	o.getHour=function() { return get_select_value(hour_sel_id); }

	o.init=function(domid){
		data = {year:0,month:0,day:0};
		year_sel_id = domid+'year';
		month_sel_id = domid+'month';
		hour_sel_id = domid+'hour';
		
		var code = '<table class="caltb">'+
			'<tr><th colspan="7" style="text-align:left;padding:5px 10px !important;">生辰：'+
			  '<button id="prebtn-'+domid+'" class="form-control">'+
				'<i class="fa fa-angle-left"></i></button>'+
			  get_year_sel()+
			  get_month_sel()+
			  '<button id="nextbtn-'+domid+'" class="form-control">'+
				'<i class="fa fa-angle-right"></i></button>'+
			  get_hour_sel()+
			  '<div id="calendarmsg" class="labtxt"></div>'+
			'</th></tr>'+
		  '<tr>';
		var weeks = ['一','二','三','四','五','六','日'];
		for (var i=0;i<weeks.length;++i) {
			var w = weeks[i];
			var cls = i>=5 ? 'weekend' : '';
			code += '<th class="'+cls+'">'+w+'</th>';
		}
		code+='</tr><tbody id="cleanday-body"></tbody>';
		/**/
		code+='</table>';
		jQuery('#'+domid).html(code);
		jQuery('#'+year_sel_id).unbind('change').change(syncdate);
		jQuery('#'+month_sel_id).unbind('change').change(syncdate);
		jQuery('#'+hour_sel_id).unbind('change').change(syncmsg);
		jQuery('#prebtn-'+domid).unbind('click').click(function(){switch_month(-1);});
		jQuery('#nextbtn-'+domid).unbind('click').click(function(){switch_month(1);});
		o.setDate(date('Y-m-d'));
		//o.setDate("1987-06-28");
	};

	return o;
});
