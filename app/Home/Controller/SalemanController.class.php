<?php
/*
用户管理
 */
namespace Home\Controller;
use Think\Controller;
class SalemanController extends BaseController{
    
    public function index(){
        $userinfo = session('member_auth');
        $map = array(
            'store_id' => $userinfo['store_id']
        );
        $model = D('MemberView');
        $list = $model->where($map)->order('posid')->select();
        $this->assign(array(
            'list' => $list
        ));
        $this->display();
    }
    
    public function add(){
        $userinfo = session('member_auth');
        $count = M('Saleman')->where('store_id ='.$member_info['store_id'])->count();
        if($count == 20){
            $this->error('员工已满20个，请修改其他未使用账号使用,或联系管理员');
        }
        if(IS_AJAX){
            $model = D('Saleman');            
            $data = array();
            $data['store_id'] = $userinfo['store_id'];
            $data['nickname'] = I('post.txtName');
            $data['posid'] = I('post.txtZhiwei');
            $data['weixin'] = I('post.txtWeixin');
            $data['telphone'] = I('post.txtPhone');
            $data['qq'] = I('post.txtQQ');
            $data['face'] = I('post.txtFace');
            $data['remark'] = I('post.txtRemark');
            
            if($data = $model->create($data)){
                $result = $model->add($data);
                if($result){
                    echo json_encode(array('val'=>100, 'message'=> '添加成功'));
                }else{
                    echo json_encode(array('val'=>100, 'message'=> $model->getDbError()));
                }
            }else{
                echo json_encode(array('val'=>100, 'message'=> $model->getError()));
            }
        }else{
            $postion = M('Position')->order('sort desc,id desc')->select();
            $this->assign(array(
                'store' => $store,
                'postion' => $postion
            ));
            $this->display();
        }
    }
    
    public function edit(){
        $model = M('Saleman');
        $userinfo = session('member_auth');
        if(IS_AJAX){            
            $data = array();
            $data['nickname'] = I('post.txtName');
            $data['posid'] = I('post.txtZhiwei');
            $data['weixin'] = I('post.txtWeixin');
            $data['telphone'] = I('post.txtPhone');
            $data['qq'] = I('post.txtQQ');
            $data['face'] = I('post.txtFace');
            $data['remark'] = I('post.txtRemark');
            
            if($data = $model->create($data)){
                $result = $model->where(array(
                    'store_id' => $userinfo['store_id'],
                    'id' => I('post.txtId')
                ))->save($data);
                if($result){
                    echo json_encode(array('val'=>100, 'message'=> '编辑成功'));
                }else{
                    echo json_encode(array('val'=>100, 'message'=> $model->getDbError()));
                }
            }else{
                echo json_encode(array('val'=>100, 'message'=> $model->getError()));
            }
        }else{
            $data = $model->where('id ='.I('get.id'))->find();
            $postion = M('Position')->order('sort desc,id desc')->select();
            $this->assign(array(
                'data' => $data,
                'postion' => $postion
            ));
            $this->display(); 
        }
    }


    public function ajaxupload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->exts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =     '/avatar/';
        $data = array();
        $info = $upload->uploadOne($_FILES['FileUpload']);
        
        $image = new \Think\Image(); 
        $image->open(C('UPLOAD_SAVE_PATH') . $info['savepath'] . $info['savename']);
        $image->thumb(120, 150,\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOAD_SAVE_PATH') . $info['savepath'] .'s_'. $info['savename']);
        @unlink(C('UPLOAD_SAVE_PATH') . $info['savepath'] . $info['savename']);
        
        if(!$info) {
            $data = array(
                'msg'=>0,
                "msgbox"=>$upload->getError(),
            );
        }else{
            $data = array(
                'msg'=>1,
                "msgbox"=> $info['savepath'] .'s_'. $info['savename'],
            );
        }
        echo json_encode($data);
    }
    
    public function loadSaleMan(){
        if(IS_AJAX){
            $userinfo = session('member_auth');
            $list = M('Saleman')->where('store_id ='.$userinfo['store_id'])->select();
            echo json_encode($list);
        }
    }
    
    public function loadSaleManCar(){
        if(IS_AJAX){
            $list = M('Car')->where('uid ='.I('get.saleid'))->select();
            echo json_encode($list);
        }
    }
    
    public function UpdateSaleMan(){
        if(IS_AJAX){
            $type = I('get.type');
            $userinfo = session('member_auth');
            switch ($type){
                case 1:
                    $rescar = M('Car')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'uid' => I('get.oldman')
                    ))->save(array(
                        'uid' => I('get.newman')
                    ));
                    $resman = M('Saleman')->where(array(
                        'id' => I('get.oldman'),
                        'store_id' => $userinfo['store_id'] 
                    ))->delete();
                    if($resman == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 2:
                    $manlist = array_filter(explode(',', I('post.allnewman')));
                    $carlist = M('Car')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'uid' => I('get.oldman')
                    ))->field('id,store_id,uid')->select();
                    $arrcount = count($manlist);
                    
                    $res = false;
                    $flag = 0;
                    foreach($carlist as $value){
                        if($flag == $arrcount){
                            $flag = 0;
                        }
                        $res = M('Car')->where(array(
                            'id' => $value['id']
                        ))->save(array(
                            'uid' => $manlist[$flag]
                        ));
                        $flag++;
                    }
                    
                    if($res == false){
                        echo 2;
                    }else{
                        $resman = M('Saleman')->where(array(
                            'id' => I('get.oldman'),
                            'store_id' => $userinfo['store_id'] 
                        ))->delete();
                        if($resman == false){
                            echo 2;
                        }else{
                            echo 1;
                        }
                    }
                    
                    break;
                case 3:
                    $carlist = array_filter(explode(',', I('post.carlist')));
                    $res = false;
                    foreach($carlist as $value){
                        $arr = explode('|', $value);
                        $res = M('Car')->where(array(
                            'id' => $arr[0],
                            'store_id' => $userinfo['store_id'],
                            'uid' => I('get.oldman')
                        ))->save(array(
                            'uid' => $arr[1]
                        ));
                    }
                    
                    if($res == false){
                        echo 2;
                    }else{
                        $resman = M('Saleman')->where(array(
                            'id' => I('get.oldman'),
                            'store_id' => $userinfo['store_id'] 
                        ))->delete();
                        if($resman == false){
                            echo 2;
                        }else{
                            echo 1;
                        }
                    }
                    break;
                default :
                    break;
            }
            /**
                allnewman: ""
                carlist: ""
                newman: "2"
                oldman: "57"
                type: "1"
             * 
             * allnewman: "49,50,2,"
                carlist: ""
                newman: "0"
                oldman: "57"
                type: "2"
             * 
             * 
             * 
                allnewman: ""
                carlist: "1|49,2|49,7|49,6|49,8|49,9|49,12|49,13|49,"
                newman: "0"
                oldman: "57"
                type: "3"
             */
        }
    }
}
