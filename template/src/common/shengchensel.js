/* 生辰选择器 */
define(function(require){
	var calendar_type_id;  //!< 日历选择器ID
	var year_sel_id;   //!< 年份选择器ID
	var month_sel_id;  //!< 月份选择器ID
	var date_sel_id;   //!< 日期选择器ID
	var hour_sel_id;   //!< 时辰选择器ID

/*
	// 阴阳历选择器
	function get_calendar_type_sel() {
		var data = [
			[1,'公历（阳历）'],
			[2,'农历（阴历）']
		];
		var code = '<div style="margin-bottom:5px;">';
		for (var i=0;i<data.length;++i) {
			var im=data[i];
			var checked = i==0 ? ' checked' : '';
			code += '<label class="">'+
				'<input name="'+calendar_type_id+'" type="radio" value="'+im[0]+'"'+checked+'><span>'+im[1]+'</span>'+
			'</label>';
		}
		code += '</div>';
		return code;
	}
*/

	// 年份选择器
	function get_year_sel() {
		var code = '<select id="'+year_sel_id+'" class="shengchen">';
		var ny = date("Y");
		for (var i=1901;i<=2050;++i) {
			var selected = i==ny ? ' selected' : '';
			code += '<option value="'+i+'"'+selected+'>'+i+'年</option>';
		}	
		return code+'</select>';
	}

	// 月份选择器
	function get_month_sel() {
		var code = '<select id="'+month_sel_id+'" class="shengchen">';
		var ny = date("m");
		for (var i=1;i<=12;++i) {
			var selected = i==ny ? ' selected' : '';
			code += '<option value="'+i+'"'+selected+'>'+i+'月</option>';
		}	
		return code+'</select>';
	}

	// 日期选择器ID
	function get_date_sel() {
		var code = '<select id="'+date_sel_id+'" class="shengchen">';
		var ny = date("d");
		var days = mwtcalendar.get_days_in_year_month(date('Y'),date('m'));
		for (var i=1;i<=days;++i) {
			var selected = i==ny ? ' selected' : '';
			code += '<option value="'+i+'"'+selected+'>'+i+'日</option>';
		}	
		return code+'</select>';
	}

	// 时辰选择器ID
	function get_hour_sel() {
		var code = '<select id="'+hour_sel_id+'" class="shengchen">';
		var ny = date("H");
		for (var i=0;i<=23;++i) {
			var selected = i==ny ? ' selected' : '';
			var ei = i+1;
			code += '<option value="'+i+'"'+selected+'>'+i+'~'+ei+'点</option>';
		}	
		return code+'</select>';
	}

	// 年份月份改变时，日期选项可能会改变（28,29,30,31） 
	function syncdate() {
		var year = get_select_value(year_sel_id);
		var month = get_select_value(month_sel_id);
		var day = get_select_value(date_sel_id);
		var days = mwtcalendar.get_days_in_year_month(year,month);
		var code = '';
		for (var i=1;i<=days;++i) {
			var selected = i==day ? ' selected' : '';
			code += '<option value="'+i+'"'+selected+'>'+i+'日</option>';
		}
		jQuery('#'+date_sel_id).html(code);
	}

	var o={};

	o.init=function(domid){
		calendar_type_id = domid+'-calendar_type';
		year_sel_id = domid+'-year_sel';
		month_sel_id = domid+'-month-sel';
		date_sel_id = domid+'-date-sel';
		hour_sel_id = domid+'-hour-sel';
		var code = "（公历）"+
			get_year_sel()+
			get_month_sel()+
			get_date_sel()+
			get_hour_sel();
		jQuery('#'+domid).html(code);
		// 年份月份改变时，日期选项可能会改变（28,29,30,31）
		jQuery('#'+year_sel_id).unbind('change').change(syncdate);
		jQuery('#'+month_sel_id).unbind('change').change(syncdate);

	};

	o.get = function() {
		return {
			year: get_select_value(year_sel_id),
			month: get_select_value(month_sel_id),
			date: get_select_value(date_sel_id),
			hour: get_select_value(hour_sel_id)
		};
	};

	return o;
});
