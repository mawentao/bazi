//时区计算
(function(){
	var $zone = document.getElementById('zone');
	var $time1 = document.getElementById('time1');
	var $time2 = document.getElementById('time2');
	var $date1 = document.getElementById('date1');
	var $date2 = document.getElementById('date2');
	var $left = document.getElementById('left');
	var $top = document.getElementById('top');
	var $place = document.getElementById('place');
	var map = [
		[149,-1],//格林威治
		[319,-1],//国际日期变更线
		[319,116],//萨摩亚
		[10,84],//夏威夷
		[12,32],//阿拉斯加
		[41,64],//美国太平洋沿岸
		[56,64],//美国山地
		[56,42],//加拿大中部
		[61,82],//墨西哥
		[72,62],//美国中部-芝加哥
		[82,99],//南美洲太平洋-波哥大
		[81,66],//美国东部-纽约
		[82,58],//加拿大东部-渥太华
		[82,82],//南美洲西部-圣地亚哥
		[88,57],//大西洋
		[94,49],//加拿大纽芬兰-圣约翰斯
		[106,116],//东南美洲-巴西利亚
		[99,96],//南美洲东部-乔治城
		[110,78],//大西洋中部
		[119,63],//亚速岛
		[149,50],//英国
		[137,91],//利比里亚
		[162,61],//罗马
		[163,53],//中欧-布拉格
		[162,50],//西欧-柏林
		[180,72],//以色列
		[170,54],//东欧
		[174,74],//开罗
		[170,65],//雅典
		[170,130],//南非
		[189,70],//巴格达
		[180,45],//莫斯科
		[195,69],//伊朗
		[196,80],//阿布扎比
		[209,69],//阿富汗
		[216,69],//巴基斯坦
		[215,86],//印度
		[215,61],//中亚
		[234,84],//缅甸-仰光
		[239,89],//曼谷
		[251,62],//中国大陆-北京
		[256,80],//台湾-台北
		[252,130],//澳大利亚西部-珀斯
		[273,68],//东京
		[269,114],//澳大利亚西部
		[282,135],//澳大利亚东部
		[277,90],//关岛
		[319,81],//太平洋中部
		[306,140],//新西兰
		[307,118]//斐济
	];
	var position={
		left:-1,
		top:-1,
		set:function(left,top){
			var _seft = this;
			var time = 20;
			var left_steps = (left - _seft.left)/time,
					top_steps = (top - _seft.top)/time;
			var i = 0;
			var hander = setInterval(function(){
				i++;
				if(i<time){
					_seft.left += left_steps;
					_seft.top += top_steps;
					$left.style.left = _seft.left+'px';
					$top.style.top = _seft.top+'px';
				}else{
					$left.style.left = left+'px';
					$top.style.top = top+'px';
					hander&&clearInterval(hander);
				}
			},25);
		}
	}
	var setPosition = function(left,top){
		var _left=-1,_top=-1;
		var time = 20;
		var left_steps = (left - _left)/time,
				top_steps = (top - _top)/time;
		var i = 0;
		var hander = setInterval(function(){
			i++;
			if(i<time){
				_left += left_steps;
				_top += top_steps;
				$left.style.left = _left+'px';
				$top.style.top = _top+'px';
			}else{
				$left.style.left = left+'px';
				$top.style.top = top+'px';
				hander&&clearInterval(hander);
			}
		},25);
	};
	//添加cookie
	var setCookie = function(objName,objValue,objHours,objDomain,objPath){
	    var str = objName + "=" + escape(objValue);
	    if(objHours > 0){ //为时不设定过期时间，浏览器关闭时cookie自动消失
	        var date = new Date();
	        var ms = objHours*3600*1000;
	        date.setTime(date.getTime() + ms);
	        str += "; expires=" + date.toGMTString();
	        if(objDomain){
	            str += ";domain="+objDomain;
	        }
	        if(objPath){
	            str += ";path="+objPath;
	        }
	    }
	    document.cookie = str;
	};
	//获取指定名称的cookie的值
	var getCookie = function(objName){
		var arrStr = document.cookie.split("; ");
		for(var i = 0;i < arrStr.length;i ++){
			var temp = arrStr[i].split("=");
			if(temp[0] == objName) return unescape(temp[1]);
		}
	};
	//获取日期数据
	var getTime = function(date){
		return {
			'year':date.getFullYear(),
			'month':date.getMonth()+1,
			'day':date.getDate(),
			'hour':date.getHours(),
			'minute':date.getMinutes(),
			'second':date.getSeconds(),
			'offset':date.getTimezoneOffset()
		};
	};
	//时间格式化
	var timeFormat = function(time){
		return (time['hour']>9?time['hour']:'0'+time['hour'])+':'+(time['minute']>9?time['minute']:'0'+time['minute'])+':'+(time['second']>9?time['second']:'0'+time['second']);
	};
	var dateFormat = function(time){
		return time['year']+'-'+(time['month']>9?time['month']:'0'+time['month'])+'-'+(time['day']>9?time['day']:'0'+time['day']);
	};
	//时间换算
	var countTime = function(){
		var date = new Date();
		var time = getTime(date);
		$time1.innerHTML = timeFormat(time);
		$date1.innerHTML = dateFormat(time);
		var hour = +_value.slice(0,3);
		var minute = +_value.slice(3,5);
		date.setMinutes(time['minute']+time['offset']+hour*60+minute);
		//美国日光节约时间法案
		var lightSavingBegin,lightSavingEnd,isLigthSaving=false;
		lightSavingBegin = new Date(time['year'],3,0);
		lightSavingBegin.setDate(lightSavingBegin.getDate()+(7-lightSavingBegin.getDay())%7+7);
		lightSavingEnd = new Date(time['year'],10,0);
		lightSavingEnd.setDate(lightSavingEnd.getDate()+(7-lightSavingEnd.getDay())%7);
		if(_value.indexOf('*')>0&&date>=lightSavingBegin&&date<=lightSavingEnd){
			isLigthSaving=true;
			date.setHours(time['hour']+1);
		}
		time = getTime(date);
		$time2.innerHTML = timeFormat(time);
		$date2.innerHTML = dateFormat(time)+(isLigthSaving?'(R)':'');
	}
	/*$zone.onchange = function(){
		_value = $zone.value;
		var index = $zone.selectedIndex;
		position.set(map[index][0],map[index][1]);
		$place.innerHTML = _value.substr(6);
		setCookie("TZ",$zone.selectedIndex,24);
	}
	$zone.selectedIndex=getCookie("TZ")||0;
	var _value = $zone.value;
	setInterval(countTime,1000);
	countTime();
	position.set(map[$zone.selectedIndex][0],map[$zone.selectedIndex][1]);
	$place.innerHTML = _value.substr(6);*/
})()

var getData = (function(){
	//公历农历转换
	var calendar = {
		lunarInfo:[0x04bd8,0x04ae0,0x0a570,0x054d5,0x0d260,0x0d950,0x16554,0x056a0,0x09ad0,0x055d2,
		0x04ae0,0x0a5b6,0x0a4d0,0x0d250,0x1d255,0x0b540,0x0d6a0,0x0ada2,0x095b0,0x14977,
		0x04970,0x0a4b0,0x0b4b5,0x06a50,0x06d40,0x1ab54,0x02b60,0x09570,0x052f2,0x04970,
		0x06566,0x0d4a0,0x0ea50,0x06e95,0x05ad0,0x02b60,0x186e3,0x092e0,0x1c8d7,0x0c950,
		0x0d4a0,0x1d8a6,0x0b550,0x056a0,0x1a5b4,0x025d0,0x092d0,0x0d2b2,0x0a950,0x0b557,
		0x06ca0,0x0b550,0x15355,0x04da0,0x0a5b0,0x14573,0x052b0,0x0a9a8,0x0e950,0x06aa0,
		0x0aea6,0x0ab50,0x04b60,0x0aae4,0x0a570,0x05260,0x0f263,0x0d950,0x05b57,0x056a0,
		0x096d0,0x04dd5,0x04ad0,0x0a4d0,0x0d4d4,0x0d250,0x0d558,0x0b540,0x0b6a0,0x195a6,
		0x095b0,0x049b0,0x0a974,0x0a4b0,0x0b27a,0x06a50,0x06d40,0x0af46,0x0ab60,0x09570,
		0x04af5,0x04970,0x064b0,0x074a3,0x0ea50,0x06b58,0x055c0,0x0ab60,0x096d5,0x092e0,
		0x0c960,0x0d954,0x0d4a0,0x0da50,0x07552,0x056a0,0x0abb7,0x025d0,0x092d0,0x0cab5,
		0x0a950,0x0b4a0,0x0baa4,0x0ad50,0x055d9,0x04ba0,0x0a5b0,0x15176,0x052b0,0x0a930,
		0x07954,0x06aa0,0x0ad50,0x05b52,0x04b60,0x0a6e6,0x0a4e0,0x0d260,0x0ea65,0x0d530,
		0x05aa0,0x076a3,0x096d0,0x04bd7,0x04ad0,0x0a4d0,0x1d0b6,0x0d250,0x0d520,0x0dd45,
		0x0b5a0,0x056d0,0x055b2,0x049b0,0x0a577,0x0a4b0,0x0aa50,0x1b255,0x06d20,0x0ada0,
		0x14b63,0x09370,0x049f8,0x04970,0x064b0,0x168a6,0x0ea50, 0x06b20,0x1a6c4,0x0aae0,
		0x0a2e0,0x0d2e3,0x0c960,0x0d557,0x0d4a0,0x0da50,0x05d55,0x056a0,0x0a6d0,0x055d4,
		0x052d0,0x0a9b8,0x0a950,0x0b4a0,0x0b6a6,0x0ad50,0x055a0,0x0aba4,0x0a5b0,0x052b0,
		0x0b273,0x06930,0x07337,0x06aa0,0x0ad50,0x14b55,0x04b60,0x0a570,0x054e4,0x0d160,
		0x0e968,0x0d520,0x0daa0,0x16aa6,0x056d0,0x04ae0,0x0a9d4,0x0a2d0,0x0d150,0x0f252,
		0x0d520],
		solarMonth:[31,28,31,30,31,30,31,31,30,31,30,31],
		Gan:["\u7532","\u4e59","\u4e19","\u4e01","\u620a","\u5df1","\u5e9a","\u8f9b","\u58ec","\u7678"],
		Zhi:["\u5b50","\u4e11","\u5bc5","\u536f","\u8fb0","\u5df3","\u5348","\u672a","\u7533","\u9149","\u620c","\u4ea5"],
		Animals:["\u9f20","\u725b","\u864e","\u5154","\u9f99","\u86c7","\u9a6c","\u7f8a","\u7334","\u9e21","\u72d7","\u732a"],
		solarTerm:["\u5c0f\u5bd2","\u5927\u5bd2","\u7acb\u6625","\u96e8\u6c34","\u60ca\u86f0","\u6625\u5206","\u6e05\u660e","\u8c37\u96e8","\u7acb\u590f","\u5c0f\u6ee1","\u8292\u79cd","\u590f\u81f3","\u5c0f\u6691","\u5927\u6691","\u7acb\u79cb","\u5904\u6691","\u767d\u9732","\u79cb\u5206","\u5bd2\u9732","\u971c\u964d","\u7acb\u51ac","\u5c0f\u96ea","\u5927\u96ea","\u51ac\u81f3"],
		sTermInfo:[ '9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e','97bcf97c3598082c95f8c965cc920f',
		'97bd0b06bdb0722c965ce1cfcc920f','b027097bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e',
	 	'97bcf97c359801ec95f8c965cc920f','97bd0b06bdb0722c965ce1cfcc920f','b027097bd097c36b0b6fc9274c91aa',
	 	'97b6b97bd19801ec9210c965cc920e','97bcf97c359801ec95f8c965cc920f', '97bd0b06bdb0722c965ce1cfcc920f',
	 	'b027097bd097c36b0b6fc9274c91aa','9778397bd19801ec9210c965cc920e','97b6b97bd19801ec95f8c965cc920f',
	 	'97bd09801d98082c95f8e1cfcc920f','97bd097bd097c36b0b6fc9210c8dc2','9778397bd197c36c9210c9274c91aa',
	 	'97b6b97bd19801ec95f8c965cc920e','97bd09801d98082c95f8e1cfcc920f', '97bd097bd097c36b0b6fc9210c8dc2',
	 	'9778397bd097c36c9210c9274c91aa','97b6b97bd19801ec95f8c965cc920e','97bcf97c3598082c95f8e1cfcc920f',
	 	'97bd097bd097c36b0b6fc9210c8dc2','9778397bd097c36c9210c9274c91aa','97b6b97bd19801ec9210c965cc920e',
	 	'97bcf97c3598082c95f8c965cc920f','97bd097bd097c35b0b6fc920fb0722','9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b97bd19801ec9210c965cc920e','97bcf97c3598082c95f8c965cc920f', '97bd097bd097c35b0b6fc920fb0722',
	 	'9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e','97bcf97c359801ec95f8c965cc920f',
	 	'97bd097bd097c35b0b6fc920fb0722','9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e',
	 	'97bcf97c359801ec95f8c965cc920f','97bd097bd097c35b0b6fc920fb0722','9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b97bd19801ec9210c965cc920e','97bcf97c359801ec95f8c965cc920f', '97bd097bd07f595b0b6fc920fb0722',
	 	'9778397bd097c36b0b6fc9210c8dc2','9778397bd19801ec9210c9274c920e','97b6b97bd19801ec95f8c965cc920f',
	 	'97bd07f5307f595b0b0bc920fb0722','7f0e397bd097c36b0b6fc9210c8dc2','9778397bd097c36c9210c9274c920e',
	 	'97b6b97bd19801ec95f8c965cc920f','97bd07f5307f595b0b0bc920fb0722','7f0e397bd097c36b0b6fc9210c8dc2',
	 	'9778397bd097c36c9210c9274c91aa','97b6b97bd19801ec9210c965cc920e','97bd07f1487f595b0b0bc920fb0722',
	 	'7f0e397bd097c36b0b6fc9210c8dc2','9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e',
	 	'97bcf7f1487f595b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b97bd19801ec9210c965cc920e','97bcf7f1487f595b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722',
	 	'9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e','97bcf7f1487f531b0b0bb0b6fb0722',
	 	'7f0e397bd097c35b0b6fc920fb0722','9778397bd097c36b0b6fc9274c91aa','97b6b97bd19801ec9210c965cc920e',
	 	'97bcf7f1487f531b0b0bb0b6fb0722','7f0e397bd07f595b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b97bd19801ec9210c9274c920e','97bcf7f0e47f531b0b0bb0b6fb0722','7f0e397bd07f595b0b0bc920fb0722',
	 	'9778397bd097c36b0b6fc9210c91aa','97b6b97bd197c36c9210c9274c920e','97bcf7f0e47f531b0b0bb0b6fb0722',
	 	'7f0e397bd07f595b0b0bc920fb0722','9778397bd097c36b0b6fc9210c8dc2','9778397bd097c36c9210c9274c920e',
	 	'97b6b7f0e47f531b0723b0b6fb0722','7f0e37f5307f595b0b0bc920fb0722', '7f0e397bd097c36b0b6fc9210c8dc2',
	 	'9778397bd097c36b0b70c9274c91aa','97b6b7f0e47f531b0723b0b6fb0721','7f0e37f1487f595b0b0bb0b6fb0722',
	 	'7f0e397bd097c35b0b6fc9210c8dc2','9778397bd097c36b0b6fc9274c91aa','97b6b7f0e47f531b0723b0b6fb0721',
	 	'7f0e27f1487f595b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b7f0e47f531b0723b0b6fb0721','7f0e27f1487f531b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722',
	 	'9778397bd097c36b0b6fc9274c91aa','97b6b7f0e47f531b0723b0b6fb0721','7f0e27f1487f531b0b0bb0b6fb0722',
	 	'7f0e397bd097c35b0b6fc920fb0722','9778397bd097c36b0b6fc9274c91aa','97b6b7f0e47f531b0723b0b6fb0721',
	 	'7f0e27f1487f531b0b0bb0b6fb0722','7f0e397bd07f595b0b0bc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
	 	'97b6b7f0e47f531b0723b0787b0721','7f0e27f0e47f531b0b0bb0b6fb0722','7f0e397bd07f595b0b0bc920fb0722',
	 	'9778397bd097c36b0b6fc9210c91aa','97b6b7f0e47f149b0723b0787b0721','7f0e27f0e47f531b0723b0b6fb0722',
	 	'7f0e397bd07f595b0b0bc920fb0722','9778397bd097c36b0b6fc9210c8dc2','977837f0e37f149b0723b0787b0721',
	 	'7f07e7f0e47f531b0723b0b6fb0722','7f0e37f5307f595b0b0bc920fb0722','7f0e397bd097c35b0b6fc9210c8dc2',
	 	'977837f0e37f14998082b0787b0721','7f07e7f0e47f531b0723b0b6fb0721','7f0e37f1487f595b0b0bb0b6fb0722',
	 	'7f0e397bd097c35b0b6fc9210c8dc2','977837f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721',
	 	'7f0e27f1487f531b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722','977837f0e37f14998082b0787b06bd',
	 	'7f07e7f0e47f531b0723b0b6fb0721','7f0e27f1487f531b0b0bb0b6fb0722','7f0e397bd097c35b0b6fc920fb0722',
	 	'977837f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721','7f0e27f1487f531b0b0bb0b6fb0722',
	 	'7f0e397bd07f595b0b0bc920fb0722','977837f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721',
	 	'7f0e27f1487f531b0b0bb0b6fb0722','7f0e397bd07f595b0b0bc920fb0722', '977837f0e37f14998082b0787b06bd',
	 	'7f07e7f0e47f149b0723b0787b0721','7f0e27f0e47f531b0b0bb0b6fb0722','7f0e397bd07f595b0b0bc920fb0722',
	 	'977837f0e37f14998082b0723b06bd','7f07e7f0e37f149b0723b0787b0721','7f0e27f0e47f531b0723b0b6fb0722',
	 	'7f0e397bd07f595b0b0bc920fb0722','977837f0e37f14898082b0723b02d5','7ec967f0e37f14998082b0787b0721',
	 	'7f07e7f0e47f531b0723b0b6fb0722','7f0e37f1487f595b0b0bb0b6fb0722','7f0e37f0e37f14898082b0723b02d5',
	 	'7ec967f0e37f14998082b0787b0721','7f07e7f0e47f531b0723b0b6fb0722','7f0e37f1487f531b0b0bb0b6fb0722',
	 	'7f0e37f0e37f14898082b0723b02d5','7ec967f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721',
	 	'7f0e37f1487f531b0b0bb0b6fb0722','7f0e37f0e37f14898082b072297c35','7ec967f0e37f14998082b0787b06bd',
	 	'7f07e7f0e47f531b0723b0b6fb0721','7f0e27f1487f531b0b0bb0b6fb0722','7f0e37f0e37f14898082b072297c35',
	 	'7ec967f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722',
	 	'7f0e37f0e366aa89801eb072297c35','7ec967f0e37f14998082b0787b06bd','7f07e7f0e47f149b0723b0787b0721',
	 	'7f0e27f1487f531b0b0bb0b6fb0722','7f0e37f0e366aa89801eb072297c35','7ec967f0e37f14998082b0723b06bd',
	 	'7f07e7f0e47f149b0723b0787b0721','7f0e27f0e47f531b0723b0b6fb0722','7f0e37f0e366aa89801eb072297c35',
	 	'7ec967f0e37f14998082b0723b06bd','7f07e7f0e37f14998083b0787b0721','7f0e27f0e47f531b0723b0b6fb0722',
	 	'7f0e37f0e366aa89801eb072297c35','7ec967f0e37f14898082b0723b02d5','7f07e7f0e37f14998082b0787b0721',
	 	'7f07e7f0e47f531b0723b0b6fb0722','7f0e36665b66aa89801e9808297c35', '665f67f0e37f14898082b0723b02d5',
	 	'7ec967f0e37f14998082b0787b0721','7f07e7f0e47f531b0723b0b6fb0722', '7f0e36665b66a449801e9808297c35',
	 	'665f67f0e37f14898082b0723b02d5','7ec967f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721',
	 	'7f0e36665b66a449801e9808297c35','665f67f0e37f14898082b072297c35', '7ec967f0e37f14998082b0787b06bd',
	 	'7f07e7f0e47f531b0723b0b6fb0721','7f0e26665b66a449801e9808297c35', '665f67f0e37f1489801eb072297c35',
	 	'7ec967f0e37f14998082b0787b06bd','7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722'],
		nStr1:["\u65e5","\u4e00","\u4e8c","\u4e09","\u56db","\u4e94","\u516d","\u4e03","\u516b","\u4e5d","\u5341"],
		nStr2:["\u521d","\u5341","\u5eff","\u5345"],
		nStr3:["\u6b63","\u4e8c","\u4e09","\u56db","\u4e94","\u516d","\u4e03","\u516b","\u4e5d","\u5341","\u51ac","\u814a"],
		lYearDays:function(y) {
			var i, sum = 348;
			for(i=0x8000; i>0x8; i>>=1) { sum += (calendar.lunarInfo[y-1900] & i)? 1: 0; }
			return(sum+calendar.leapDays(y));
		},
		leapMonth:function(y) {
			return(calendar.lunarInfo[y-1900] & 0xf);
		},
	 	leapDays:function(y) {
	 		if(calendar.leapMonth(y)) {
	 			return((calendar.lunarInfo[y-1900] & 0x10000)? 30: 29);
	 		}
	 		return(0);
	 	},
		monthDays:function(y,m) {
			if(m>12 || m<1) {return -1}
			return( (calendar.lunarInfo[y-1900] & (0x10000>>m))? 30: 29 );
		},
		solarDays:function(y,m) {
			if(m>12 || m<1) {return -1}
			var ms = m-1;
			if(ms==1) {
				return(((y%4 == 0) && (y%100 != 0) || (y%400 == 0))? 29: 28);
			}else {
				return(calendar.solarMonth[ms]);
			}
		},
		toGanZhi:function(offset) {
			return(calendar.Gan[offset%10]+calendar.Zhi[offset%12]);
		},
		getTerm:function(y,n) {
			if(y<1900 || y>2100) {return -1;}
			if(n<1 || n>24) {return -1;}
			var _table = calendar.sTermInfo[y-1900];
			var _info = [
				parseInt('0x'+_table.substr(0,5)).toString() ,
				parseInt('0x'+_table.substr(5,5)).toString(),
				parseInt('0x'+_table.substr(10,5)).toString(),
				parseInt('0x'+_table.substr(15,5)).toString(),
				parseInt('0x'+_table.substr(20,5)).toString(),
				parseInt('0x'+_table.substr(25,5)).toString()
			];
			var _calday = [
				_info[0].substr(0,1),
				_info[0].substr(1,2),
				_info[0].substr(3,1),
				_info[0].substr(4,2),
				_info[1].substr(0,1),
				_info[1].substr(1,2),
				_info[1].substr(3,1),
				_info[1].substr(4,2),
				_info[2].substr(0,1),
				_info[2].substr(1,2),
				_info[2].substr(3,1),
				_info[2].substr(4,2),
				_info[3].substr(0,1),
				_info[3].substr(1,2),
				_info[3].substr(3,1),
				_info[3].substr(4,2),
				_info[4].substr(0,1),
				_info[4].substr(1,2),
				_info[4].substr(3,1),
				_info[4].substr(4,2),
				_info[5].substr(0,1),
				_info[5].substr(1,2),
				_info[5].substr(3,1),
				_info[5].substr(4,2),
			];
			return parseInt(_calday[n-1]);
		},
		toChinaMonth:function(m) {
			if(m>12 || m<1) {return -1}
			var s = calendar.nStr3[m-1];
			s+= "\u6708";
			return s;
		},
		toChinaDay:function(d){
			var s;
			switch (d) {
				case 10:
					s = '\u521d\u5341';
					break;
				case 20:
					s = '\u4e8c\u5341';
					break;
				case 30:
					s = '\u4e09\u5341';
					break;
					default :
						s = calendar.nStr2[Math.floor(d/10)];
					s += calendar.nStr1[d%10];
			}
				return(s);
		},
		getAnimal: function(y) {
			return calendar.Animals[(y - 4) % 12]
		},
		solar2lunar:function (y,m,d) {
			if(y<1900 || y>2100) {return -1;}
			if(y==1900&&m==1&&d<31) {return -1;}
			if(!y){
				var objDate = new Date();
			}else{
				var objDate = new Date(y,parseInt(m)-1,d)
			}
			var i, leap=0, temp=0;
			var y = objDate.getFullYear(),m = objDate.getMonth()+1,d = objDate.getDate();
			var offset = (Date.UTC(objDate.getFullYear(),objDate.getMonth(),objDate.getDate()) - Date.UTC(1900,0,31))/86400000;
			for(i=1900; i<2101 && offset>0; i++) { temp=calendar.lYearDays(i); offset-=temp; }
			if(offset<0) {offset+=temp; i--;}
			var isTodayObj = new Date(),isToday=false;
			if(isTodayObj.getFullYear()==y && isTodayObj.getMonth()+1==m && isTodayObj.getDate()==d) {
				isToday = true;
			}
			var nWeek = objDate.getDay(),cWeek = calendar.nStr1[nWeek];
			if(nWeek==0) {nWeek =7;}
			var year = i;
			var leap = calendar.leapMonth(i);
			var isLeap = false;
			for(i=1; i<13 && offset>0; i++) {
				if(leap>0 && i==(leap+1) && isLeap==false){
					--i;
					isLeap = true; temp = calendar.leapDays(year);
				}else{
					temp = calendar.monthDays(year, i);
				}
				if(isLeap==true && i==(leap+1)) { isLeap = false; }
				offset -= temp;
			}
			if(offset==0 && leap>0 && i==leap+1){
				if(isLeap){
					isLeap = false;
				}else{
					isLeap = true; --i;
				}
			}
			if(offset<0){ offset += temp; --i; }
			var month = i;
			var day = offset + 1;
			var sm = m-1;
			var term3 = calendar.getTerm(year,3);
			var gzY = calendar.toGanZhi(year-4);
			gzY = calendar.toGanZhi(year-4); //modify
			var firstNode = calendar.getTerm(y,(m*2-1));
			var secondNode = calendar.getTerm(y,(m*2));
			var gzM = calendar.toGanZhi((y-1900)*12+m+11);
		 	if(d>=firstNode) {
				gzM = calendar.toGanZhi((y-1900)*12+m+12);
			}
			var isTerm = false;
			var Term = null;
			if(firstNode==d) {
			 	isTerm = true;
			 	Term = calendar.solarTerm[m*2-2];
				}
			if(secondNode==d) {
				isTerm = true;
				Term = calendar.solarTerm[m*2-1];
			}
			var dayCyclical = Date.UTC(y,sm,1,0,0,0,0)/86400000+25567+10;
			var gzD = calendar.toGanZhi(dayCyclical+d-1);
			return {'lYear':year,'lMonth':month,'lDay':day,'Animal':calendar.getAnimal(year),'IMonthCn':(isLeap?"\u95f0":'')+calendar.toChinaMonth(month),'IDayCn':calendar.toChinaDay(day),'cYear':y,'cMonth':m,'cDay':d,'gzYear':gzY,'gzMonth':gzM,'gzDay':gzD,'isToday':isToday,'isLeap':isLeap,'nWeek':nWeek,'ncWeek':"\u661f\u671f"+cWeek,'isTerm':isTerm,'Term':Term};
		}
	};
	//公历节日
	var _festival1={
		'0101':'元旦节',
		'0202':'世界湿地日',
		'0210':'国际气象节',
		'0214':'情人节',
		'0301':'国际海豹日',
		'0303':'全国爱耳日',
		'0305':'学雷锋纪念日',
		'0308':'妇女节',
		'0312':'植树节',
		'0314':'国际警察日',
		'0315':'消费者权益日',
		'0317':'中国国医节 国际航海日',
		'0321':'世界森林日 消除种族歧视国际日 世界儿歌日',
		'0322':'世界水日',
		'0323':'世界气象日',
		'0324':'世界防治结核病日',
		'0325':'全国中小学生安全教育日',
		'0401':'愚人节',
		'0407':'世界卫生日',
		'0422':'世界地球日',
		'0423':'世界图书和版权日',
		'0424':'亚非新闻工作者日',
		'0501':'劳动节',
		'0504':'青年节',
		'0515':'防治碘缺乏病日',
		'0508':'世界红十字日',
		'0512':'国际护士节',
		'0515':'国际家庭日',
		'0517':'世界电信日',
		'0518':'国际博物馆日',
		'0520':'全国学生营养日',
		'0522':'国际生物多样性日',
		'0531':'世界无烟日',
		'0601':'国际儿童节 世界牛奶日',
		'0605':'世界环境日',
		'0606':'全国爱眼日',
		'0617':'防治荒漠化和干旱日',
		'0623':'国际奥林匹克日',
		'0625':'全国土地日',
		'0626':'国际禁毒日',
		'0701':'建党节 香港回归纪念日',
		'0702':'国际体育记者日',
		'0711':'世界人口日 航海日',
		'0801':'建军节',
		'0808':'中国男子节(爸爸节)',
		'0903':'抗日战争胜利纪念日',
		'0908':'国际扫盲日 国际新闻工作者日',
		'0910':'教师节',
		'0916':'国际臭氧层保护日',
		'0918':'九·一八事变纪念日',
		'0920':'国际爱牙日',
		'0927':'世界旅游日',
		'1001':'国庆节 国际音乐日 国际老人节',
		'1002':'国际非暴力日 国际和平与民主自由斗争日',
		'1004':'世界动物日',
		'1006':'老人节',
		'1008':'全国高血压日',
		'1005':'国际教师节',
		'1009':'世界邮政日',
		'1010':'辛亥革命纪念日 世界精神卫生日',
		'1013':'世界保健日 国际减灾日',
		'1014':'世界标准日',
		'1015':'国际盲人节(白手杖节)',
		'1016':'世界粮食日',
		'1017':'世界消除贫困日',
		'1022':'世界传统医药日',
		'1024':'联合国日 世界发展信息日',
		'1031':'世界勤俭日',
		'1107':'十月社会主义革命纪念日',
		'1108':'中国记者日',
		'1109':'全国消防安全宣传教育日',
		'1110':'世界青年节',
		'1111':'国际科学与和平周(本日所属的一周)',
		'1112':'孙中山诞辰纪念日',
		'1114':'联合国糖尿病日',
		'1117':'国际大学生节',
		'1121':'世界问候日 世界电视日',
		'1129':'国际声援巴勒斯坦人民国际日',
		'1201':'世界艾滋病日',
		'1203':'世界残疾人日',
		'1204':'宪法日',
		'1205':'国际志愿人员日',
		'1209':'世界足球日',
		'1210':'世界人权日',
		'1212':'西安事变纪念日',
		'1213':'南京大屠杀纪念日',
		'1220':'澳门回归纪念',
		'1221':'国际篮球日',
		'1224':'平安夜',
		'1225':'圣诞节',
		'1226':'毛泽东诞辰纪念日'
	};
	//某月的第几个星期几,第3位为5表示最后一星期
	var _festival2={
		'0110':'黑人日',
		'0150':'世界麻风日',
		'0440':'世界儿童日',
		'0520':'国际母亲节',
		'0532':'国际牛奶日',
		'0530':'全国助残日',
		'0630':'父亲节',
		'0711':'世界建筑日',
		'0730':'被奴役国家周',
		'0936':'世界清洁地球日',
		'0932':'国际和平日',
		'0940':'国际聋人节',
		'1011':'国际住房日',
		'1024':'世界视觉日',
		'1144':'感恩节',
		'1220':'国际儿童电视广播日'
	};
	//农历节日
	var _festival3 = {
		'0101':'春节',
		'0102':'初二',
		'0103':'初三',
		'0115':'元宵节',
		'0202':'龙抬头节',
		'0323':'妈祖生辰',
		'0505':'端午节',
		'0707':'七夕节',
		'0715':'中元节',
		'0815':'中秋节',
		'0909':'重阳节',
		'1208':'腊八节',
		'1223':'小年',
		'0100':'除夕'
	};
	//假日安排数据
	var _holiday = {
		'2011':{'0402':0,'0403':1,'0404':1,'0405':1,'0430':1,'0501':1,'0502':1,'0604':1,'0605':1,'0606':1,'0910':1,'0911':1,'0912':1,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1008':0,'1009':0,'1231':0},
		'2012':{'0101':1,'0102':1,'0103':1,'0121':0,'0122':1,'0123':1,'0124':1,'0125':1,'0126':1,'0127':1,'0128':1,'0129':0,'0331':0,'0401'
		:0,'0402':1,'0403':1,'0404':1,'0428':0,'0429':1,'0430':1,'0501':1,'0622':1,'0623':1,'0624':1,'0929':0,'0930':1,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1},
		'2013':{'0101':1,'0102':1,'0103':1,'0105':0,'0106':0,'0209':1,'0210':1,'0211':1,'0212':1,'0213':1,'0214':1,'0215':1,'0216':0,'0217':0,'0404':1,'0405':1,'0406':1,'0407':0,'0427':0,'0428':0,'0429':1,'0430':1,'0501':1,'0608':0,'0609':0,'0610':1,'0611':1,'0612':1,'0919':1,'0920':1,'0921':1,'0922':0,'0929':0,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1012':0},
		'2014':{'0101':1,'0126':0,'0131':1,'0201':1,'0202':1,'0203':1,'0203':1,'0204':1,'0205':1,'0206':1,'0208':0,'0405':1,'0406':1,'0407':1,'0501':1,'0502':1,'0503':1,'0504':0,'0531':1,'0601':1,'0602':1,'0908':1,'0928':0,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1011':0},
		'2015':{'0101':1,'0102':1,'0103':1,'0104':0,'0215':0,'0218':1,'0219':1,'0220':1,'0221':1,'0222':1,'0223':1,'0224':1,'0228':0,'0404':1,'0405':1,'0406':1,'0501':1,'0502':1,'0503':1,'0620':1,'0621':1,'0622':1,'0903':1,'0904':1,'0905':1,'0906':0,'0927':1,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1010':0},
		'2016':{'0101':1,'0102':1,'0103':1,'0206':0,'0207':1,'0208':1,'0209':1,'0210':1,'0211':1,'0212':1,'0213':1,'0214':0,'0402':1,'0403':1,'0404':1,'0430':1,'0501':1,'0502':1,'0609':1,'0610':1,'0611':1,'0612':0,'0915':1,'0916':1,'0917':1,'0918':0,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1008':0,'1009':0},
		'2017':{'0101':1,'0102':1,'0122':0,'0127':1,'0128':1,'0129':1,'0130':1,'0131':1,'0201':1,'0202':1,'0204':0,'0401':0,'0402':1,'0403':1,'0404':1,'0429':1,'0430':1,'0501':1,'0527':0,'0528':1,'0529':1,'0530':1,'0930':0,'1001':1,'1002':1,'1003':1,'1004':1,'1005':1,'1006':1,'1007':1,'1008':1}
	};
	//获取日期数据
	var getDateObj = function(year,month,day){
		var date = arguments.length&&year?new Date(year,month-1,day):new Date();
		return {
			'year':date.getFullYear(),
			'month':date.getMonth()+1,
			'day':date.getDate(),
			'week':date.getDay()
		};
	};
	//当天
	var _today = getDateObj();
	//获取当月天数
	var getMonthDays = function(obj){
		var day = new Date(obj.year,obj.month,0);
		return  day.getDate();
	};
	if(!String.prototype.trim) {
		String.prototype.trim = function () {
	    	return this.replace(/^\s+|\s+$/g,'');
		};
	}
	//获取某天日期信息
	var getDateInfo = function(obj){
		var info = calendar.solar2lunar(obj.year,obj.month,obj.day);
		var cMonth = info.cMonth>9?''+info.cMonth:'0'+info.cMonth;
		var cDay = info.cDay>9?''+info.cDay:'0'+info.cDay;
		var lMonth = info.lMonth>9?''+info.lMonth:'0'+info.lMonth;
		var lDay = info.lDay>9?''+info.lDay:'0'+info.lDay;
		var code1 = cMonth + cDay;
		var code2 = cMonth + Math.ceil(info.cDay/7) + info.nWeek%7;
		var code3 = lMonth + lDay;
		var days = getMonthDays(obj);
		//节日信息
		info['festival'] = '';
		if(_festival3[code3]){
			info['festival'] += _festival3[code3];
		}
		if(_festival1[code1]){
			info['festival'] += ' '+_festival1[code1];
		}
		if(_festival2[code2]){
			info['festival'] += ' '+_festival2[code2];
		}
		if(obj['day']+7>days){
			var code4 = cMonth + 5 + info.nWeek%7;
			if(code4!=code2&&_festival2[code4]){
				info['festival'] += ' '+_festival2[code4];
			}
		}
		//info['festival'] = info['festival'].trim();
		//放假、调休等标记
		info['sign'] = '';
		if(_holiday[info.cYear]){
			var holiday = _holiday[info.cYear];
			if(typeof holiday[code1] != 'undefined'){
				info['sign'] = holiday[code1]?'holiday':'work';
			}
		}
		if(info.cYear==_today.year&&info.cMonth==_today.month&&info.cDay==_today.day){
			info['sign'] = 'today';
		}
		return info;
	};
	//获取日历信息
	return (function(date){
		var date = date||_today;
/*
		var first = getDateObj(date['year'],date['month'],1);		//当月第一天
		var days = getMonthDays(date);							//当月天数
		var data = [];										//日历信息
		var obj = {};
		//上月日期
		for(var i=first['week'];i>0;i--){
			obj = getDateObj(first['year'],first['month'],first['day']-i);
			var info = getDateInfo(obj);
			info['disabled'] = 1;
			data.push(info);
		}
		//当月日期
		for(var i=0;i<days;i++){
			obj = {
				'year':first['year'],
				'month':first['month'],
				'day':first['day']+i,
				'week':(first['week']+i)%7
			};
			var info = getDateInfo(obj);
			info['disabled'] = 0;
			data.push(info);
		}
		//下月日期
		var last = obj;
		for(var i=1;last['week']+i<7;i++){
			obj = getDateObj(last['year'],last['month'],last['day']+i);
			var info = getDateInfo(obj);
			info['disabled'] = 1;
			data.push(info);
		} */
		return {
			'date':getDateInfo(date)/*,				//当前日历选中日期
			'data':data*/
		};
	});
})();

//万年历
(function(){
	var $mod_calendar = DOMUtil.getElementsByClassName('mod-calendar')[0];
	var $table = DOMUtil.getElementsByClassName('table',$mod_calendar)[0];
	var $year = DOMUtil.getElementsByClassName('year',$mod_calendar)[0];
	var $month = DOMUtil.getElementsByClassName('month',$mod_calendar)[0];
	var $holiday = DOMUtil.getElementsByClassName('holiday',$mod_calendar)[0];
	var $goback = DOMUtil.getElementsByClassName('goback',$mod_calendar)[0];
	var $prev_year = DOMUtil.getElementsByClassName('prev-year',$mod_calendar)[0];
	var $next_year = DOMUtil.getElementsByClassName('next-year',$mod_calendar)[0];
	var $prev_month = DOMUtil.getElementsByClassName('prev-month',$mod_calendar)[0];
	var $next_month = DOMUtil.getElementsByClassName('next-month',$mod_calendar)[0];
	var $info = DOMUtil.getElementsByClassName('info',$mod_calendar)[0];
	var _data = [];
	var _day = 1;
	var holiday = {
		'2011':[
			{
				value:'2011-01-01',
				name:'元旦'
			},
			{
				value:'2011-02-03',
				name:'春节'
			},
			{
				value:'2011-04-05',
				name:'清明'
			},
			{
				value:'2011-05-01',
				name:'劳动节'
			},
			{
				value:'2011-06-06',
				name:'端午节'
			},
			{
				value:'2011-09-12',
				name:'中秋节'
			},
			{
				value:'2011-10-01',
				name:'国庆节'
			}
		],
		'2012':[
			{
				value:'2012-01-01',
				name:'元旦'
			},
			{
				value:'2012-01-23',
				name:'春节'
			},
			{
				value:'2012-04-04',
				name:'清明'
			},
			{
				value:'2012-05-01',
				name:'劳动节'
			},
			{
				value:'2012-06-23',
				name:'端午节'
			},
			{
				value:'2012-09-30',
				name:'中秋节'
			},
			{
				value:'2012-10-01',
				name:'国庆节'
			}
		],
		'2013':[
			{
				value:'2013-01-01',
				name:'元旦'
			},
			{
				value:'2013-02-10',
				name:'春节'
			},
			{
				value:'2013-04-04',
				name:'清明'
			},
			{
				value:'2013-05-01',
				name:'劳动节'
			},
			{
				value:'2013-06-12',
				name:'端午节'
			},
			{
				value:'2013-09-19',
				name:'中秋节'
			},
			{
				value:'2013-10-01',
				name:'国庆节'
			}
		],
		'2014':[
			{
				value:'2014-01-01',
				name:'元旦'
			},
			{
				value:'2014-01-31',
				name:'春节'
			},
			{
				value:'2014-04-05',
				name:'清明'
			},
			{
				value:'2014-05-01',
				name:'劳动节'
			},
			{
				value:'2014-06-02',
				name:'端午节'
			},
			{
				value:'2014-09-08',
				name:'中秋节'
			},
			{
				value:'2014-10-01',
				name:'国庆节'
			}
		],
		'2015':[
			{
				value:'2015-01-01',
				name:'元旦'
			},
			{
				value:'2015-02-19',
				name:'春节'
			},
			{
				value:'2015-04-05',
				name:'清明'
			},
			{
				value:'2015-05-01',
				name:'劳动节'
			},
			{
				value:'2015-06-20',
				name:'端午节'
			},
			{
				value:'2015-09-03',
				name:'胜利日'
			},
			{
				value:'2015-09-27',
				name:'中秋节'
			},
			{
				value:'2015-10-01',
				name:'国庆节'
			}
		],
		'2016':[
			{
				value:'2016-01-01',
				name:'元旦'
			},
			{
				value:'2016-02-08',
				name:'春节'
			},
			{
				value:'2016-04-04',
				name:'清明'
			},
			{
				value:'2016-05-01',
				name:'劳动节'
			},
			{
				value:'2016-06-09',
				name:'端午节'
			},
			{
				value:'2016-09-15',
				name:'中秋节'
			},
			{
				value:'2016-10-01',
				name:'国庆节'
			}
		],
		'2017':[
			{
				value:'2017-01-01',
				name:'元旦'
			},
			{
				value:'2017-01-28',
				name:'春节'
			},
			{
				value:'2017-04-04',
				name:'清明'
			},
			{
				value:'2017-05-01',
				name:'劳动节'
			},
			{
				value:'2017-05-30',
				name:'端午节'
			},
			{
				value:'2017-10-04',
				name:'中秋节'
			},
			{
				value:'2017-10-01',
				name:'国庆节'
			}
		]
	};
	var format = function(date){
		var result = getData(date);
		var date = result['date'];
		_data = result['data'];
		var map = {
			'work':'班',
			'holiday':'休'
		}
		var html = '<table>\
			<thead>\
				<tr><th>日</th><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th>六</th></tr>\
			</thead>\
			<tbody>\
				<tr>';
		for(var i=0,len=_data.length;i<len;i++){
			var item = _data[i];
			var className = '',className2='';
			if(item['sign']){
				className += item['sign'];
			}
			if(item['disabled']){
				className += ' disabled';
			}
			if(date&&item['cMonth']==date['cMonth']&&item['cDay']==date['cDay']){
				className2 = 'active';
			}
			var festival = item['festival'].split(' ')[0];
			if(festival.length>3){
				festival = '';
			}
			html+='<td class="'+className+'" data-id="'+i+'">\
				<a href="javascript:;"'+(className2?' class="'+className2+'"':'')+'>\
					<span class="s1">'+item['cDay']+'</span>\
					<span class="s2">'+(item['Term']||festival||item['IDayCn'])+'</span>\
					'+(item['sign']&&map[item['sign']]?'<i>'+map[item['sign']]+'</i>':'')+'\
				</a>\
			</td>';
			if(i%7==6&&i<len-1){
				html+='</tr><tr>';
			}
		}
		html+='</tr>\
			</tbody>\
		</table>';
/*
		$year.value = date['cYear'];
		$month.value = date['cMonth'];
		$info.innerHTML = '<p>'+date['cYear']+'-'+(date['cMonth']>9?date['cMonth']:'0'+date['cMonth'])+'-'+(date['cDay']>9?date['cDay']:'0'+date['cDay'])+' '+date['ncWeek']+'</p>\
		<div class="day">'+date['cDay']+'</div>\
		<div class="sub"><p>'+date['IMonthCn']+date['IDayCn']+'</p>\
		<p>'+date['gzYear']+'年 【'+date['Animal']+'年】</p>\
		<p>'+date['gzMonth']+'月 '+date['gzDay']+'日</p></div>\
		<div class="festival"><p>'+date['festival'].replace(/\s/g,'</p><p>')+'</p></div>';
		$table.innerHTML = html;
*/
	};
/*
	var format_setting = function(year){
		var year = year||(new Date()).getFullYear();
		$holiday.innerHTML = '';
		var $o = new Option("假日安排","");
		$holiday.add($o);
		if(holiday[year]){
			var items = holiday[year];
			for(var i=0;i<items.length;i++){
				var $option = new Option(items[i]['name'],items[i]['value']);
				$holiday.add($option);
			}
		}
	};
/*
	$year.onchange = function(){
		var year = $year.value;
		var month = $month.value;
		format({'year':year,'month':month,'day':_day});
		format_setting(year);
	};
	$month.onchange = function(){
		var year = $year.value;
		var month = $month.value;
		format({'year':year,'month':month,'day':_day});
	};
	$holiday.onchange = function(){
		var value = this.value;
		if(value){
			var arr = value.split('-');
			format({'year':+arr[0],'month':+arr[1],'day':+arr[2]});
		}
	};
	$goback.onclick = function(){
		format();
		format_setting();
	};
	$prev_year.onclick = function(){
		var year = $year.value;
		var month = $month.value;
		year--;
		format({'year':year,'month':month,'day':_day});
		format_setting(year);
	};
	$next_year.onclick = function(){
		var year = $year.value;
		var month = $month.value;
		year++;
		format({'year':year,'month':month,'day':_day});
		format_setting(year);
	};
	$prev_month.onclick = function(){
		var year = $year.value;
		var month = $month.value;
		month--;
		format({'year':year,'month':month,'day':_day});
		if(month==0)format_setting(--year);
	};
	$next_month.onclick = function(){
		var year = $year.value;
		var month = $month.value;
		month++;
		format({'year':year,'month':month,'day':_day});
		//alert(year);alert(month);
		if(month==13)format_setting(++year);
};
	$table.onclick = function(e){
		e = e || window.event;
		var target = e.target || e.srcElement;
		while(target.tagName!='TD'&&target.tagName!='TABLE'){
			target = target.parentNode;
		}
		var id = target.getAttribute('data-id');
		if(target.tagName=='TD'&&id){
			var data = _data[id];
			_day = data['cDay'];
			format({'year':data['cYear'],'month':data['cMonth'],'day':data['cDay']});
		}
	};
*/
//	format();
//	format_setting();
})();
