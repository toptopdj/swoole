<?php
namespace app\index\controller;

use app\common\controller\BaseController;
use app\common\model\User;
use app\common\validate\User as UserValidate;
use think\Exception;
use think\Request;

class Index extends BaseController
{
    public function index()
    {
        return view('index/index', [
        	'username' => session('userInfo')
        ]);
    }

    public function login(Request $request)
    {
    	if ($request->isAjax()) {
    		$data = [
    			'username' => input('param.username'),
    			'password' => input('param.password')
		    ];
    		$user = new User();
    		$res = $user->checkLogin($data);
    		session('userInfo', $data['username']);
    		return json($res);
	    }
        return view('index/login');
    }

    public function register(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $validate = new UserValidate();
            $result = $validate->scene('register')->check($data);
            if (!$result)
            {
                return json([
                    'status' => 400,
                    'msg' => $validate->getError()
                ]);
            }
            try {
                $user = new User($data);
                $user->allowField(true)->save();
                return json([
                    'status' => 200,
                    'msg' => '注册成功'
                ]);
            } catch (Exception $e) {
                return json([
                    'status' => 400,
                    'msg' => $e->getMessage()
                ]);
            }
        }
        return view('index/register');
    }
}
