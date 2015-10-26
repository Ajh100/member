<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends BaseController{

    public function index(){
        
        $carAction = new \Home\Controller\UsedcarController();
        $carAction->index();
        
    }
}
