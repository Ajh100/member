<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller{
    
    public function login(){
        if(is_member_login()){
            $this->redirect('index/index');
        }
        
        $this->display();
    }
    
    public function verify(){
        $config =    array(    
            'fontSize' => 30,    
            'length'   => 4,    
            'useNoise' => false,
			'useCurve' => false,
			'fontSize' => 30
        );
        $verify=new \Think\Verify($config);
        $verify->entry();
    }


    public function checklogin(){
        if(IS_AJAX){
            if(empty($_POST['username'])){
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => '账号错误'
                    ));
                    exit();
            }
            if(empty($_POST['userpwd'])){
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => '密码必须'
                    ));
                    exit();
            }
            if(empty($_POST['code'])){
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => '验证码必须'
                    ));
                    exit();
            }
            if(!check_verify($_POST['code'])){
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => '验证码错误'
                    ));
                    exit();
            }
            
            $map['account'] = I('post.username');
            $result = D('Member')->where($map)->find();
            
            $loginErrorTimes = cookie('login_error_times');
            if(is_array($result) and $result['status'] == 1){
                if($result['password'] != think_member_md5(I('post.userpwd'), C('DATA_AUTH_KEY'))){
                    $loginErrorTimes > 0 ? $loginErrorTimes++ : $loginErrorTimes = 1;
                    cookie('login_error_times', $loginErrorTimes ,array('expire'=>180));
                    log_write(I('post.username'),'密码错误','失败'.get_client_ip());
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => '密码错误'
                    ));
                    exit();
                }else{
                    $city = M('store')->field()->find($result['store_id']);
                    $auth = array(
                        'uid'             => $result['uid'],
                        'store_id'        => $result['store_id'],
                        'account'         => $result['account'],
                        'username'        => $result['nickname'],
                        'last_login_time' => $result['last_login_time'],
                        'last_login_ip'   => $result['last_login_ip'],
                        'area_id'         => $city['area'],
                        'city_id'         => $city['city']
                    );
                    session('member_auth', $auth);
                    session('member_auth_sign', data_auth_sign($auth));
                    $data = array(
                        'uid' => $result['uid'],
                        'login_count' => $result['login_count'] + 1,
                        'last_login_time' => time(),
                        'last_login_ip' => get_client_ip()
                    );
                    $res = D('Member')->save($data);
                    log_write(I('post.username'),'登录成功','成功'.get_client_ip());
                    echo json_encode(array(
                        'status' => 2,
                        'msg' => '登录成功'
                    ));
                    exit();
                }
            }else{
                log_write(I('post.username'),'用户名错误','失败'.get_client_ip());
                $loginErrorTimes > 0 ? $loginErrorTimes++ : $loginErrorTimes = 1;
                cookie('login_error_times', $loginErrorTimes ,array('expire'=>180));
                session('login_error_times', $loginErrorTimes);
                echo json_encode(array(
                    'status' => 1,
                    'msg' => '用户名错误'
                ));
                exit();
            }         
        }else{
            exit();
        }
    }

    public function logout(){
        session('member_auth', null);
        session('member_auth_sign', null);
        $this->success('退出成功!',U('index/index'));
    }
}
