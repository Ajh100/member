<?php
/*
店铺管理
 */
namespace Home\Controller;
use Think\Controller;

class StoreController extends BaseController{
    
    public function index(){
        $member_info = session('member_auth');
        //$service = M('Service')->order('sort desc, id asc')->select();
        $store = M('Store')->find($member_info['store_id']);
        $area = M('Area')->find($store['area']);
        $city = M('City')->find($store['city']);
        
        $this->assign(array(
            //'service' => $service,
            'store' => $store,
            'location' => $area['title'].'-'.$city['title'],
            'storeimglist' => json_decode($store['imglist'], true)
        ));
        
        $this->display();
    }
    
    public function update(){
        if(IS_AJAX){
            $member_info = session('member_auth');
            $data = array();
            $data['id'] = $member_info['store_id'];
            $data['shopkeeper'] = I('post.txtLinkman');
            $data['tel'] = I('post.txtMobile');
            $data['phone'] = I('post.txtPhone');
            $data['address'] = I('post.txtAddress');
            $data['describe'] = I('post.txtDesc');
            //$data['service'] = I('post.txtServiceInfo');
            
            $model = M('Store');
            $result = $model->save($data);
            if($result === false){
                echo '0';
            }else{
                echo '1';
            }
        }
    }
    
    public function password(){
        if(IS_POST){
            
        }else{
            $this->display();
        }
    }
    
    public function ModifyPassword(){
        if(IS_AJAX){
            $type = I('get.type');
            $userinfo = session('member_auth');
            switch ($type){
                case 1:
                    $result = M('Member')->where(array(
                        'uid' => $userinfo['uid'],
                        'store_id' => $userinfo['store_id'],
                        'pass' => I('get.oldPwd')
                    ))->find();
                    if($result){
                        echo json_encode(array(
                            'result' => 103,
                            'message'=> '验证成功'
                        ));
                    }else{
                        echo json_encode(array(
                            'result' => 100,
                            'message'=> '验证失败'
                        ));
                    }
                    break;
                case 2:
                    $result = M('Member')->where(array(
                        'uid' => $userinfo['uid'],
                        'store_id' => $userinfo['store_id'],
                        'pass' => I('get.oldPwd')
                    ))->save(array(
                        'pass' => I('get.newPwd'),
                        'password' => think_member_md5(I('get.newPwd'), C('DATA_AUTH_KEY'))
                    ));
                    if($result == false){
                        echo json_encode(array(
                            'result' => 100,
                            'message'=> '修改失败'
                        ));
                    }else{
                        echo json_encode(array(
                            'result' => 104,
                            'message'=> '修改成功,新密码为:'.I('get.newPwd')
                        ));
                    }
                    break;
                default :
                    break;
            }
        }
    }
}
