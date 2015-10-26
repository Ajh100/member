<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller{
    public function _initialize(){
        if(defined('UID')) return ;
            define('UID',is_member_login());
        if( !UID ){
            $this->redirect('public/login');
        }
		
        $userinfo = session('member_auth');
        $storeinfo = M('Store')->find($userinfo['store_id']);
        $this->assign(array(
            'storeinfo' => $storeinfo,
            'userinfo' => $userinfo
        ));
    }
}
