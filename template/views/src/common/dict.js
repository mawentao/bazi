/* 所有业务字典 */
define(function(require){
	// dict转成options
	function tooptions(dictionary,firstoption) {
		var options = [];
		if (firstoption) options.push(firstoption);
		for(var id in dictionary) {
			options.push({text:dictionary[id],value:id});
		}
		return options;
	}
    var o={};
	/////////////////////////////////////////////////////
	// 性别
	var gender_dict = {
		'x': '女',
		'y': '男'
	};
	// 获取性别
	o.get_gender=function(gender) { return gender_dict[gender] ? gender_dict[gender] : '未知'; };
	// 获取性别选项列表
	o.get_gender_options=function(firstoption) { return tooptions(gender_dict,firstoption); };

	return o;
});
