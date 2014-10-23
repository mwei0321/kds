<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  系统初始配置
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-24 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	$iniconfig = array(
			'MODULE_ALLOW_LIST'     => array('Book','Admin','Member',''),
			'DEFAULT_MODULE'     	=> 'Book', //默认模块
			'SESSION_AUTO_START' 	=> true, //是否开启session
			//自定义命名空间
			'AUTOLOAD_NAMESPACE' => array(
					'Library'    => ROOT_PATH.'/Library/',
			),
			//URL 配置
			'URL_MODEL'          	=> '2', //URL模式
			'URL_PATHINFO_DEPR'		=>'-', // 更改PATHINFO参数分隔符
			
			//模板配置
			'DEFAULT_THEME'    =>    'default',// 设置默认的模板主题
			// 		'VIEW_PATH'				=>'./Theme/',//视图目录指定
			'TMPL_FILE_DEPR'		=>'_',  //目录结构太深
			'ALLOW_IMAGE_EXTS'		=> 'jpg,png,gif,jpeg',
			'ALLOW_FILE_EXTS'		=> 'doc,pdf,zip,7z,txt,xls,rar',
			'ALLOW_FILE_SIZE'     	=> 300292200,
			'UPLOAD_DIY_NAME'		=> 'www.phpyrb.com'.date('YmdHms').substr(uniqid(),-5),
			
			'INSTALLAPP_PATH'       => ROOT_PATH.'/installapp/',
	);
	
	return $iniconfig;
	
	function config (){
		$m = M('SystemInitConfig');
		
	}