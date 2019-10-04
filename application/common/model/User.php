<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    protected $table = "users";

    protected $insert = ['username', 'password', 'email'];

    public function checkLogin($data)
    {
        $validate = new \app\common\validate\User();
        $res = $validate->scene('login')->check($data);
        if (!$res)
        {
            return [
                "status" => 400,
                "msg" => $validate->getError()
            ];
        }

        $result = $this->checkUser($data['username'], $data['password']);
        return $result;
    }

    public function checkUser($username, $password)
    {
        $res = $this->where([
            'username' => $username,
            'password' => md5($password)
        ])->find();
        if (!$res)
        {
            return [
                "status" => 400,
                "msg" => "用户名或密码不正确"
            ];
        }
        return [
            "status" => 200,
            "msg" => "登陆成功"
        ];
    }

}
