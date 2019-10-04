<?php

namespace app\common\controller;

use think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
//	    if (!session('userInfo')) {
//	    	return $this->error('还未登录','index/index/login');
//	    }
    }
}
