/* 我的日历函数库 */
var mwtcalendar = {};

var lunarInfo=new Array(
        0x4bd8, 0x4ae0, 0xa570, 0x54d5, 0xd260, 0xd950, 0x5554, 0x56af, 0x9ad0, 0x55d2,
        0x4ae0, 0xa5b6, 0xa4d0, 0xd250, 0xd255, 0xb54f, 0xd6a0, 0xada2, 0x95b0, 0x4977,
        0x497f, 0xa4b0, 0xb4b5, 0x6a50, 0x6d40, 0xab54, 0x2b6f, 0x9570, 0x52f2, 0x4970,
        0x6566, 0xd4a0, 0xea50, 0x6a95, 0x5adf, 0x2b60, 0x86e3, 0x92ef, 0xc8d7, 0xc95f,
        0xd4a0, 0xd8a6, 0xb55f, 0x56a0, 0xa5b4, 0x25df, 0x92d0, 0xd2b2, 0xa950, 0xb557,
        0x6ca0, 0xb550, 0x5355, 0x4daf, 0xa5b0, 0x4573, 0x52bf, 0xa9a8, 0xe950, 0x6aa0,
        0xaea6, 0xab50, 0x4b60, 0xaae4, 0xa570, 0x5260, 0xf263, 0xd950, 0x5b57, 0x56a0,
        0x96d0, 0x4dd5, 0x4ad0, 0xa4d0, 0xd4d4, 0xd250, 0xd558, 0xb540, 0xb6a0, 0x95a6,
        0x95bf, 0x49b0, 0xa974, 0xa4b0, 0xb27a, 0x6a50, 0x6d40, 0xaf46, 0xab60, 0x9570,
        0x4af5, 0x4970, 0x64b0, 0x74a3, 0xea50, 0x6b58, 0x5ac0, 0xab60, 0x96d5, 0x92e0,
        0xc960, 0xd954, 0xd4a0, 0xda50, 0x7552, 0x56a0, 0xabb7, 0x25d0, 0x92d0, 0xcab5,
        0xa950, 0xb4a0, 0xbaa4, 0xad50, 0x55d9, 0x4ba0, 0xa5b0, 0x5176, 0x52bf, 0xa930,
        0x7954, 0x6aa0, 0xad50, 0x5b52, 0x4b60, 0xa6e6, 0xa4e0, 0xd260, 0xea65, 0xd530,
        0x5aa0, 0x76a3, 0x96d0, 0x4afb, 0x4ad0, 0xa4d0, 0xd0b6, 0xd25f, 0xd520, 0xdd45,
        0xb5a0, 0x56d0, 0x55b2, 0x49b0, 0xa577, 0xa4b0, 0xaa50, 0xb255, 0x6d2f, 0xada0,
        0x4b63, 0x937f, 0x49f8, 0x4970, 0x64b0, 0x68a6, 0xea5f, 0x6b20, 0xa6c4, 0xaaef,
        0x92e0, 0xd2e3, 0xc960, 0xd557, 0xd4a0, 0xda50, 0x5d55, 0x56a0, 0xa6d0, 0x55d4,
        0x52d0, 0xa9b8, 0xa950, 0xb4a0, 0xb6a6, 0xad50, 0x55a0, 0xaba4, 0xa5b0, 0x52b0,
        0xb273, 0x6930, 0x7337, 0x6aa0, 0xad50, 0x4b55, 0x4b6f, 0xa570, 0x54e4, 0xd260,
        0xe968, 0xd520, 0xdaa0, 0x6aa6, 0x56df, 0x4ae0, 0xa9d4, 0xa4d0, 0xd150, 0xf252,
        0xd520);

//返回农历 y年的总天数
function lYearDays(y) {
    var i, sum = 348;
    for(i=0x8000; i>0x8; i>>=1) sum += (lunarInfo[y-1900] & i)? 1: 0;
    return(sum+leapDays(y));
}

//返回农历 y年闰月的天数
function leapDays(y) {
    if (leapMonth(y)) return( (lunarInfo[y - 1899] & 0xf) == 0xf ? 30 : 29);
    else return(0);
}

//返回农历 y年闰哪个月 1-12 , 没闰返回 0
function leapMonth(y) {
    var lm = lunarInfo[y - 1900] & 0xf;
    return(lm == 0xf ? 0 : lm);
}

//返回农历 y年m月的总天数
function monthDays(y,m) {
    return( (lunarInfo[y-1900] & (0x10000>>m))? 30: 29 );
}

//获取农历日期(月日)
function get_lunar_date(dat)
{
	var lunar = new Lunar(new Date(dat));
	//var year = str.substr(0,4);
	var m = lunar.month;
	m = m==1 ? '正' : mwtcalendar.lunar_number[m];
	var d = lunar.day;
	d = d<=10 ? '初'+mwtcalendar.lunar_number[d] : mwtcalendar.lunar_number[d];
	var run = lunar.isLeap ? '闰' : '';
	return run+m+'月'+d;
}

//算出农历, 传入日期控件, 返回农历日期控件, 该控件属性有 .year .month .day .isLeap
function Lunar(objDate) {
    var i, leap=0, temp=0;
    var offset   = (Date.UTC(objDate.getFullYear(),objDate.getMonth(),objDate.getDate()) - Date.UTC(1900,0,31))/86400000;
    for(i=1900; i<2100 && offset>0; i++) {
        temp=lYearDays(i);
        offset-=temp;
    }
    if(offset<0) { 
        offset+=temp; 
        i--; 
    }
    this.year = i;
    leap = leapMonth(i); //闰哪个月
    this.isLeap = false;
    for(i=1; i<13 && offset>0; i++) {
    //闰月
        if(leap>0 && i==(leap+1) && this.isLeap==false){
            --i;
            this.isLeap = true;
            temp = leapDays(this.year);
        }
        else{
            temp = monthDays(this.year, i);
        }
    //解除闰月
        if(this.isLeap==true && i==(leap+1)) this.isLeap = false;
        offset -= temp;
    }
    if(offset==0 && leap>0 && i==leap+1)
    if(this.isLeap){
        this.isLeap = false;
    }
    else{
        this.isLeap = true;
        --i;
    }
    if(offset<0){ 
        offset += temp; 
        --i; 
    }
    this.month = i;
    this.day = offset + 1;
}



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

