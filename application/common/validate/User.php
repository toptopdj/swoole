<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    "username" => "require|unique:user",
        "password" => "require|min:8",
        "email" => "require|email|unique:user,email"
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        "username.require" => "用户名未填写",
        "username.unique" => "用户已存在",
        "password.require" => "密码未设置",
        "password.min" => "密码至少8位",
        "email.require" => "未设置邮箱",
        "email" => "邮箱格式不正确",
        "email.unique" => "邮箱已被绑定"
    ];

	public function sceneLogin()
	{
		return $this->only(['username', 'password'])
			->remove('username', 'unique');
    }

    protected $scene = [
        "register" => ['username', 'password', 'email']
    ];
}
