define(function(require){
	var maleCase,femaleCase;

	function get_case_card(gender) 
	{
		var icon = gender=='x' ? 'fa fa-venus' : 'fa fa-mars';
		var str = gender=='x' ? '女' : '男';
		var code = '<div id="case-card-'+gender+'" class="mwt-btn marriage-card marriage-card-'+gender+'" data-gender="'+gender+'">'+
			'<i class="'+icon+'"></i>'+
			'<p style="margin-top:10px;fonr-size:13px;">点击选择'+str+'命</p>'+
		'</div>';
		return code;
	}

    var o = {};
	o.execute = function(domid) {
		var code = '<p align="center" class="marriage-tip mwt-alert mwt-alert-warning">'+
			'<i class="fa fa-info-circle"></i> 从命例库中选择一男一女，然后点击合婚按钮，进行合婚分析'+
		'</p>'+
		'<table class="marriage-tab"><tr>'+
			'<td width="50%" align="right">'+get_case_card('y')+'</td>'+
			'<td style="padding:50px; "align="center">'+
			    '<button id="mariage-merge-btn" class="mwt-btn mwt-btn-danger mwt-btn-lg radius"><i class="fa fa-venus-mars"></i></button>'+
			'</td>'+
			'<td width="50%" align="left">'+get_case_card('x')+'</td>'+
		'</tr></table>';

		jQuery('#'+domid).html(code);
		//2. 男女命例选择
		jQuery('.marriage-card').unbind('click').click(function(){
			var gender = jQuery(this).data('gender');
			select_case(gender);
		});
		//3. 点击合婚按钮
		jQuery('#mariage-merge-btn').unbind('click').click(mariage_merge);
	};

	// 男女命例选择
	function select_case(gender) {
		require('./case_sel_dialog').open(gender,function(res){
			console.log(res);
			var tm = strtotime(res.solar_calendar);
			var code = '<div class="name-'+res.gender+'" style="font-size:18px;">'+res.name+'</div>'+
				'<table class="formtab">'+
				  '<tr><th>年龄</th><td>'+res.age+'岁</td>'+
				  '<tr><th>生肖</th><td>'+mwtcalendar.zhi[res.nian_zhi].animal+'</td>'+
				  '<tr><th>公历生日</th><td>'+date('Y年m月d日',tm)+'</td>'+
				  '<tr><th>农历生日</th><td>'+mwtcalendar.parse_lunar_calendar(res.lunar_calendar)+'</td>'+
				'</table>'+
				'<div class="bazihr">生辰八字</div>'+
				'<table class="bazitab">'+
				  '<tr><th>年柱</th><th>月柱</th><th>日柱</th><th>时柱</th></tr>'+
				  '<tr><td>'+res.nian_gan+'</td><td>'+res.yue_gan+'</td><td>'+res.ri_gan+'</td><td>'+res.hour_gan+'</td></tr>'+
				  '<tr><td>'+res.nian_zhi+'</td><td>'+res.yue_zhi+'</td><td>'+res.ri_zhi+'</td><td>'+res.hour_zhi+'</td></tr>'+
				'</table>';
			jQuery('#case-card-'+res.gender).html(code);
			if (res.gender=='x') {
				femaleCase = res;
			} else {
				maleCase = res;
			}
		});
	}

	// 合婚
	function mariage_merge() {
		if (!maleCase) {
			mwt.notify('请选择男命',500,'danger');
			return;
		}
		if (!femaleCase) {
			mwt.notify('请选择女命',500,'danger');
			return;
		}
		var data = {
			'male_case_id': maleCase.caseid,
			'female_case_id': femaleCase.caseid
		};
		ajax.post('marriage&action=merge',data,function(res){
			if (res.retcode!=0) {
				mwt.notify(res.retmsg,1500,'danger');
			} else {
				var url = dz.siteurl+'plugin.php?id=bazi:marriage&m='+res.data;
				window.location = url;
			}
		});
	}

    return o;
});

