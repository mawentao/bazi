({
    baseUrl: './template/src',
    dir: './template/dist',
    modules: [
        {   
            name: 'jappengine'
        },
		{
			name: 'view/pc/foresee/page'
		}
    ],  
    fileExclusionRegExp: /^(r|build)\.js$/,
    optimizeCss: 'standard',
    removeCombined: true,

	packages: [
		{name:'frame', location:'frame', main:'main'},
		{name:'jquery', location:'../libs/jquery/1.11.2', main:'jquery.min'}
	]

})
