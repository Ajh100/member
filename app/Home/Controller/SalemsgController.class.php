<?php
namespace Home\Controller;
use Think\Controller;
/**
文章
 */
class SalemsgController extends BaseController{
 
    
    public function index(){
        
        $map = array();
        if(!empty($_GET['status'])){
            $map['status'] = $_GET['status'];
        }
        $list = M('Salemsg')->where($map)->select();
        $this->assign(array(
            'list' => $list
        ));
        $this->display();
    }
    
    public function add(){
        $memberinfo = session('member_auth');
        if(IS_POST){
            $res = M('Salemsg')->add(array(
                'store_id' => $memberinfo['store_id'],
                'title' => I('post.txtTitle'),
                'content' => I('post.txthidContent'),
                'addtime' => time()
            ));
            if($res == false){
                echo 200;
            }else{
                echo 100;
            }
        }else{
            $this->display();
        }
    }
    
    public function edit(){
        $userinfo = session('member_auth');
        if(IS_POST){
            $result = M('Salemsg')->where(array(
                'store_id' => $userinfo['store_id'],
                'id' => I('post.txtId')
            ))->save(array(
                'title' => I('post.txtTitle'),
                'content' => I('post.txthidContent')
            ));
            if($result == false){
                echo 200;
            }else{
                echo 100;
            }
        }else{
            $data = M('Salemsg')->where(array(
                'store_id' => $userinfo['store_id'],
                'id' => I('get.id')
            ))->find();
            $this->assign(array(
                'data' => $data
            ));
            $this->display();
        }
    }
    
    public function PromoteSetting(){
        if(IS_AJAX){
            $action = I('get.action');
            $userinfo = session('member_auth');
            
            switch ($action){
                case 'del':
                    $result = M('Salemsg')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.id')
                    ))->delete();
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'off':
                    $result = M('Salemsg')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.id')
                    ))->save(array(
                        'status' => 2
                    ));
                    if($result == false){
                        echo $result;
                    }else{
                        echo $result;
                    }
                    break;
                case 'alldel':
                    $result = M('Salemsg')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => array('in', array_filter(explode(',', I('get.ids'))))
                    ))->delete();
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'republish':
                    $result = M('Salemsg')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.id')
                    ))->save(array(
                        'status' => 1
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                default :
                    break;
            }
        }
    }
}
