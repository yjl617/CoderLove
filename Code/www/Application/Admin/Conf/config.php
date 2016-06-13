<?php
/**
 * 后台专属的一些配置文件
 */
return array(

	'SHOW_PAGE_TRACE' 		=>	false,				//开启页面trace

	'TMPL_PARSE_STRING'  =>array(
		'__STYLE__' => '/Public/Assets',
		'__UPLOAD__' => '/Uploads',
	),
	
	'user_type' => array(
		1 => '个人',
		2 => '企业'
	),

	'user_state' => array(
		-1 => '禁用',
		0 => '未激活',
		1 => '正常',
	),
);