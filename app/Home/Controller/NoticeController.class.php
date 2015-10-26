<?php
namespace Home\Controller;
use Think\Controller;

class NoticeController extends BaseController{
    
    public function index(){
        $map = array();
        if(!empty($_GET['cid'])){
            $map['classid'] = $_GET['cid'];
        }
        
        $classlist = M('NoticeClass')->order('sort desc')->select();
        $list = D('NoticeView')->where($map)->order('addtime desc')->select();
        
        $this->assign(array(
            'list' => $list,
            'classlist' => $classlist
        ));
        $this->display();
    }
}
