<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	//一般的校验错误
	'alpha'         => ':field 只能包含字母',
	'alpha_dash'    => ':field 只能包含数字，字母和虚线',
	'alpha_numeric' => ':field 只能包含数字和字母',
	'color'         => ':field 必须是颜色值',
	'credit_card'   => ':field 必须是身份证号码',
	'date'          => ':field 必须是日期',
	'decimal'       => ':field 必须是有 :param2 位小数位的浮点数',
	'digit'         => ':field 必须是数字',
	'email'         => ':field 必须是邮件地址',
	'email_domain'  => ':field 必须包含有效的邮件域名',
	'equals'        => ':field 必须等于 :param2',
	'exact_length'  => ':field 必须是 :param2 个字符',
	'in_array'      => ':field 必须是有效的选项',
	'ip'            => ':field 必须是IP地址',
	'matches'       => ':field 必须匹配 :param3',
	'min_length'    => ':field 不能少于 :param2 个字符',
	'max_length'    => ':field 不能超过 :param2 个字符',
	'not_empty'     => ':field 不能为空',
	'numeric'       => ':field 必须是数字',
	'phone'         => ':field 必须是电话',
	'range'         => ':field 必须在 :param2 到 :param3 的范围内',
	'regex'         => ':field 不匹配要求的格式',
	'url'           => ':field 必须是url',
	'unique'        => ':field 已存在',
	//修改密码的校验错误
	'check_password' => ':field 错误',
	//文件上传的校验错误
	'Upload::not_empty' => ':field 不能为空',
	'Upload::valid'    	=> ':field 必须是有效数据',
	'Upload::type'    	=> ':field 的文件类型必须是 :param2',
	'Upload::size'    	=> ':field 的大小必须少于 :param2',
	'Upload::unique'    => ':field 的已经上传过',
);
