/* 我的日历函数库 */
var mwtcalendar = {};

// 星期
mwtcalendar.week=['日','一','二','三','四','五','六'];

// 地支对应信息
mwtcalendar.zhi={
	'子': {animal:'鼠'},
	'丑': {animal:'牛'},
	'寅': {animal:'虎'},
	'卯': {animal:'兔'},
	'辰': {animal:'龙'},
	'巳': {animal:'蛇'},
	'午': {animal:'马'},
	'未': {animal:'羊'},
	'申': {animal:'猴'},
	'酉': {animal:'鸡'},
	'戌': {animal:'狗'},
	'亥': {animal:'猪'}
};

// 农历数字
mwtcalendar.lunar_number = {
	'1':'一',   '2':'二',   '3':'三',   '4':'四',   '5':'五',   '6':'六',   '7':'七',   '8':'八',   '9':'九',   '10':'十',
	'11':'十一','12':'十二','13':'十三','14':'十四','15':'十五','16':'十六','17':'十七','18':'十八','19':'十九','20':'二十',
	'21':'廿一','22':'廿二','23':'廿三','24':'廿四','25':'廿五','26':'廿六','27':'廿七','28':'廿八','29':'廿九','30':'三十',
    '31':'三十一','32':'三十二'
};

// 判断是否闰年
mwtcalendar.is_leap_year=function(year) {
	if (year%400==0) return true;
	if (year%4==0 && year%100!=0) return true;
	return false;
};

// 获取公历年月的天数
mwtcalendar.get_days_in_year_month=function(year,month) {
	var maxdate = [31,28,31,30,31,30,31,31,30,31,30,31];
	var days = maxdate[month-1];
	if (month==2 && mwtcalendar.is_leap_year(year)) days=29;
	return days;
};

// 解析农历生日(yyyymmddX, X为1表示闰月)
mwtcalendar.parse_lunar_calendar=function(str) {
	var year = str.substr(0,4);
	var m = parseInt(str.substr(4,2));
	m = m==1 ? '正' : mwtcalendar.lunar_number[m];
	var d = parseInt(str.substr(6,2));
	d = d<10 ? '初'+mwtcalendar.lunar_number[d] : mwtcalendar.lunar_number[d];
	var run = str.substr(-1)=='0' ? '' : '闰';
	return year+'年'+run+m+'月'+d;
};

