/* 婚恋流年生克制化图 */
define(function(require){
    var o={};

	o.show=function(){

		var year = jQuery('[name=a-hunlian-liunian][class*=jinnian]').data("year");

		var nm = bazi_liunian[year];
		var hnm = bazi_hunlian_liunian[year];

		// 婚恋流年生克制化图
		var graph = {
			nodes: [],
			links: {}
		};

		// 点
		for (var i=0;i<bazi_graph.nodes.length;++i) {
			var nd = bazi_graph.nodes[i];

			var im = {};
			for (var k in bazi_graph.nodes[i]) {
				im[k] = bazi_graph.nodes[i][k];
				//if (isset(im['shishen'])) { im['name']=im['shishen']['name']; }
				//else im['name']="日元";
			}

			//graph.nodes.push(bazi_graph.nodes[i]);
			graph.nodes.push(im);
		}
		// 添加一柱
		var role_gan = '流年天干';
		var role_zhi = '流年地支';
		graph.nodes.push({
			role: role_gan,
			name: bazi_liunian[year]['gan'],
			//name: bazi_liunian[year]['gan_info']['shishen']['name'],
			wuxing: bazi_liunian[year]['gan_info']['wuxing']
		});
		graph.nodes.push({
			role: role_zhi,
			name: bazi_liunian[year]['zhi'],
			//name: bazi_liunian[year]['zhi_info']['canggan'][0]['shishen']['name'],
			wuxing: bazi_liunian[year]['zhi_info']['wuxing']
		});

		// 边
		var link_keys = [
			'gan_chong', 'gan_he', 'ke', 'sheng', 'zhi_anhe', 'zhi_banhe', 
			'zhi_chong', 'zhi_hai', 'zhi_hui', 'zhi_liuhe', 'zhi_po', 'zhi_sanhe', 'zhi_xing', 'zihe', 
		];
		for (var i=0;i<link_keys.length;++i) {
			var key = link_keys[i];
			graph.links[key] = [];
			// 拷贝先天命盘中的关系
			for (var k=0; k<bazi_graph.links[key].length; ++k) {
				graph.links[key].push(bazi_graph.links[key][k]);
			}
			// 添加流年柱的关系
			switch (key) {
				case 'gan_he':
					if (hnm.gan_he==1) {
						graph.links[key].push(['日干',role_gan,'合']);
					}
					break;
				case 'sheng':
				case 'ke':
					if (isset(nm[key])) {
						var s = key=='sheng' ? '生' : '克';
						if (nm[key]=='gz') graph.links[key].push([role_gan,role_zhi,s]);
						if (nm[key]=='zg') graph.links[key].push([role_zhi,role_gan,s]);
					}
					break;
				case 'zhi_liuhe':
					if (hnm.zhi_he) {
						graph.links[key].push(['日支',role_zhi,'']);
					}
					break;
				case 'zhi_chong':
					if (hnm.zhi_chong) {
						graph.links[key].push(['日支',role_zhi,'']);
					}
					break;
			}

		}

		// 显示
		require('common/bazi_graph').show('hunlian-mingpan-div',graph);

		//console.log(nm);

		//console.log(bazi_liunian);
		//console.log(bazi_hunlian_liunian);
		//console.log(bazi_graph);

	};

	return o;
});
