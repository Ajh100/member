<?php

namespace Home\Controller;
use Think\Controller;
class UsedcarController extends BaseController{
    
    public function index(){
        
        $userinfo = session('member_auth');
        $model = M('Car');
        $map = array();
        $map['store_id'] = $userinfo['store_id'];
        $map['status'] = 1;
        $parameter = array(
            'status' => 1
        );
        
        if(!empty($_REQUEST['search'])){
            $map['title'] = array('like',array('%'.$_REQUEST['search'].'%'));
            $parameter['search'] = $_REQUEST['search'];
        }
        if(!empty($_GET['status'])){
            $map['status'] = $_GET['status'];
        }
        if(!empty($_GET['uid'])){
            $map['uid'] = $_GET['uid'];
        }
        if(!empty($_GET['bid'])){
            $map['brand_id'] = $_GET['bid'];
            $parameter['bid'] = $_REQUEST['bid'];
        }
        $count = $model->where($map)->count();
        $page  = new \Think\Page($count,15);
       
        $page->parameter = array_merge($parameter,$page->parameter);
        
        $show  = $page->show();
        
        $list = $model->where($map)->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();

       
        $saleman = M('Saleman')->where(array('store_id'=>$userinfo['store_id']))->field('id,nickname')->select();
        
        $brandlist = S('BrandList');
        if(!$brandlist){
            $carlist = M('Car')->where(array('store_id' => $userinfo['store_id']))->getField('brand_id',true);
            $brandlist = M('CarBrand')->field('id,title,pinyin')->where(array('id'=> array('in', array_unique($carlist))))->order('pinyin asc')->select();
            S('BrandList',$brandlist,10);
        }
        $this->assign(array(
            'list' => $list,
            'saleman' => $saleman,
            'brandlist' => $brandlist,
            'page' => $show,
            'count' => $count,
            'map' => $map
        ));
        $this->display('usedcar/index');
    }
    
    
    
    //发布车源
    public function add(){
        $userinfo = session('member_auth');
        
        $brand = F('CarBrand');
        if(!$brand){
            $brand = M('CarBrand')->where(array(
                'is_lock' => 0
            ))->order('letter asc, sort desc')->select();
            F('CarBrand',$branddata);
        }
        //车辆颜色
        $color = F('CarColor');
        if(empty($color)){
            $color = M('CarColor')->order('sort desc,id asc')->select();
            F('CarColor',$color);
        }
        
        //员工
        $saleman = M('Saleman')->where(array(
            'store_id' => $userinfo['store_id']
        ))->select();
        
        //时间
		$updatatimeyear = range((int)date('Y'),1991);
        $datatimeyear = range((int)date('Y')+1,1991);
        $datatimedate = range(1, 12);
        
        
        $this->assign(array(
            'brand' => $brand,
            'saleman' => $saleman,
            'color' => $color,
			'updatatimeyear' => $updatatimeyear,
            'datatimeyear' => $datatimeyear,
            'datatimedate' => $datatimedate
        ));
        $this->display();
    }

    
    //保存车源信息
    public function insert(){
        if(empty($_POST['brand_title'])){
            $this->error('品牌不能为空');
        }
        if(empty($_POST['series_title'])){
            $this->error('车系不能为空');
        }
        if(empty($_POST['class_title'])){
            $this->error('车型不能为空');
        }
        if(empty($_POST['color_id'])){
            $this->error('颜色不能为空');
        }
        if(empty($_POST['filelist'])){
            $this->error('展示图片不能为空');
        }
        if(empty($_POST['price'])){
            $this->error('售价不能为空');
        }
        if(empty($_POST['description'])){
            $this->error('描述不能为空');
        }
        $member_info = session('member_auth');
        $filelist = I('post.filelist');
        $carstyle = M('CarClass')->find(I('post.class_value'));
        $data = array();
        $data['title'] = I('post.brand_title').' '.I('post.series_title').' '.I('post.class_title');
        $data['brand_id'] = I('post.brand_value');
        $data['series_id'] = I('post.series_value');
        $data['class_id'] = I('post.class_value');
        $data['color_id'] = I('post.color_id');
        $data['uid'] = I('post.saleman_id');
        $data['area_id'] = $member_info['area_id'];
        $data['city_id'] = $member_info['city_id'];
        $data['store_id'] = $member_info['store_id'];
        $data['year'] = substr(I('post.class_title'),0,4);
        $data['output'] = substr($carstyle['output'],0,-1);
        $data['esid_id'] = $carstyle['esid_id'];
        $data['gearbox'] = $carstyle['gearbox'];
        $data['level_id'] = $carstyle['level_id'];
        $data['cover'] = $filelist[0];
        $data['imglist'] = json_encode($filelist);
        $data['price'] = I('post.price');
        $data['mileage'] = I('post.mileage');
        if($_POST['regtime_title']=='年份' || $_POST['regtimedate_title']=='日期'){
            $data['regtime'] = null;
        }else{
            $data['regtime'] = I('post.regtime_title').'-'.I('post.regtimedate_title').'-1';
        }
        
        if($_POST['inspeyear_title']=='年份' || $_POST['inspemonth_title']=='日期'){
            $data['inspecyear'] = null;
        }else{
            $data['inspecyear'] = I('post.inspeyear_title').'-'.I('post.inspemonth_title').'-1';
        }
        
        if($_POST['inuyear_title']=='年份' || $_POST['inumonth_title']=='日期'){
            $data['isuranceyear'] = null;
        }else{
            $data['isuranceyear'] = I('post.inuyear_title').'-'.I('post.inumonth_title').'-1';
        }
        
        $data['shoufu'] = empty($_POST['shoufu']) ? null : I('post.shoufu');
        $data['commercial'] = I('post.commercial');
        $data['transfer'] = I('post.transfer');
        $data['fuel'] = I('post.fuel');
        $data['use'] = I('post.use');
        $data['description'] = I('post.description');
        $data['addtime'] = time();
        
        $result = D('Car')->data($data)->add();
        
        if($result){
            $this->success('发布成功', U('usedcar/index'));
        }else{
            $this->error('发布失败');
        }
    }
    
    public function edit($id){
        
        $car = M('Car')->find($id);
        //权限验证
        $userinfo = session('member_auth');
        if($car['store_id'] !== $userinfo['store_id']){
            $this->error('无权限');
        }
        
        
        $brand = F('CarBrand');
        if(!$brand){
            $brand = M('CarBrand')->where(array(
                'is_lock' => 0
            ))->order('letter asc, sort desc')->select();
            F('CarBrand',$branddata);
        }
        //车辆颜色
        $color = F('CarColor');
        if(empty($color)){
            $color = M('CarColor')->order('sort desc,id asc')->select();
            F('CarColor',$color);
        }
        
        //员工
        $saleman = M('Saleman')->where(array(
            'store_id' => $userinfo['store_id']
        ))->select();
        
        $salename = M('Saleman')->field('nickname')->find($car['uid']);
        
		$updatatimeyear = range((int)date('Y'),1991);
		
        $datatimeyear = range((int)date('Y')+1,1991);
		
        $datatimedate = range(1, 12);
        $car_brand = M('CarBrand')->where('id='.$car['brand_id'])->find();
        $car_series = M('CarSeries')->find($car['series_id']);
        $car_class = M('CarClass')->find($car['class_id']);
        $car_year = M('CarYear')->field('title')->where('id = '.$car_class['style_id'])->find();
        $regtime = !empty($car['regtime'])?date('Y' , strtotime($car['regtime'])):'';
        $regtimedate_title = !empty($car['regtime'])?intval(date('m' , strtotime($car['regtime']))):'';
        $imglist = (array)json_decode($car['imglist']);

        $timelist = array(
            'regtime_title' => !empty($car['regtime'])?date('Y' , strtotime($car['regtime'])):'',
            'regtimedate_title' => !empty($car['regtime'])?intval(date('m' , strtotime($car['regtime']))):'',
            'inspeyear_title' => !empty($car['inspecyear'])?date('Y' , strtotime($car['inspecyear'])):'',
            'inspemonth_title' => !empty($car['inspecyear'])?intval(date('m' , strtotime($car['inspecyear']))):'',
            'inuyear_title' => !empty($car['inspecyear'])?date('Y' , strtotime($car['inspecyear'])):'',
            'inumonth_title' => !empty($car['isuranceyear'])?intval(date('m' , strtotime($car['isuranceyear']))):'',
        );
        
        $this->assign(array(
            'brand' => $brand,
            'saleman' => $saleman,
            'salename' => $salename,
            'color' => $color,
			'updatatimeyear' => $updatatimeyear,
            'datatimeyear' => $datatimeyear,
            'datatimedate' => $datatimedate,
            'cardata' => $car,
            'car_brand' => $car_brand,
            'car_series' => $car_series,
            'car_class' => $car_class,
            'car_year' => $car_year,
            'timelist' => $timelist,
            'imglist' => $imglist
        ));
        
        $this->display();
    }
    
    public function update(){
        $member_info = session('member_auth');
        
        $car = M('Car')->field('store_id')->getById(I('post.id'));
        if($car['store_id'] !== $member_info['store_id']){
            $this->error('无权限');
        }
        if(empty($_POST['brand_title'])){
            $this->error('品牌不能为空');
        }
        if(empty($_POST['series_title'])){
            $this->error('车系不能为空');
        }
        if(empty($_POST['class_title'])){
            $this->error('车型不能为空');
        }
        if(empty($_POST['color_id'])){
            $this->error('颜色不能为空');
        }
        if(empty($_POST['filelist'])){
            $this->error('展示图片不能为空');
        }
        if(empty($_POST['price'])){
            $this->error('价格不能为空');
        }
        if(empty($_POST['description'])){
            $this->error('描述不能为空');
        }
        
        $carstyle = M('CarClass')->find(I('post.class_value'));
        
        $data = array();
        $data['title'] = I('post.brand_title').' '.I('post.series_title').' '.I('post.class_title');
        $data['brand_id'] = I('post.brand_value');
        $data['series_id'] = I('post.series_value');
        $data['class_id'] = I('post.class_value');
        $data['uid'] = I('post.saleman_id');
        $data['color_id'] = I('post.color_id');
        $data['year'] = substr(I('post.class_title'),0,4);
        $data['output'] = substr($carstyle['output'],0,-1);
        $data['esid_id'] = $carstyle['esid_id'];
        $data['gearbox'] = $carstyle['gearbox'];
        $data['level_id'] = $carstyle['level_id'];
        $filelist = I('post.filelist');
        $data['cover'] = $filelist[0];
        $data['imglist'] = json_encode($filelist);
        $data['price'] = I('post.price');
        
        if($_POST['regtime_title']=='年份' || $_POST['regtimedate_title']=='日期'){
            $data['regtime'] = null;
        }else{
            $data['regtime'] = I('post.regtime_title').'-'.I('post.regtimedate_title').'-1';
        }
        
        if($_POST['inspeyear_title']=='年份' || $_POST['inspemonth_title']=='日期'){
            $data['inspecyear'] = null;
        }else{
            $data['inspecyear'] = I('post.inspeyear_title').'-'.I('post.inspemonth_title').'-1';
        }
        
        if($_POST['inuyear_title']=='年份' || $_POST['inumonth_title']=='日期'){
            $data['isuranceyear'] = null;
        }else{
            $data['isuranceyear'] = I('post.inuyear_title').'-'.I('post.inumonth_title').'-1';
        }
        
        $data['shoufu'] = empty($_POST['shoufu']) ? null : I('post.shoufu');
        $data['commercial'] = I('post.commercial');
        $data['transfer'] = I('post.transfer');
        $data['fuel'] = I('post.fuel');
        $data['use'] = I('post.use');
        $data['description'] = I('post.description');
        $data['addtime'] = time();
        
        if(!empty($_POST['status']) && ($_POST['status'] == 3 ||  $_POST['status'] == 4)){
            $data['shoufu'] = null;
            $data['buyname'] = null;
            $data['buymobile'] = null;
            $data['sellprice'] = null;      
            $data['specialprice'] = null;
            $data['selltime'] = null;
            $data['overtime'] = null;
            $data['recommend'] = 1;
            $data['addtime'] = time();
            $data['clicktimes'] = null;
            $data['status'] = 1;
        }
        
 
        //保存车辆信息
        $result = D('Car')->where('id = '.I('post.id'))->data($data)->save();
        if($result){
            $this->success('修改成功', __APP__.'/usedcar/?status='.$_POST['status']);
        }else{
            $this->error('修改失败');
        }       
        
    }
    

    public function caroperate(){
        if(IS_AJAX){
            $userinfo = session('member_auth');
            $action = I('get.action');
            switch ($action){
                case 'setsell':
                    $result = M('Car')->where(array(
                        'id' => I('get.infoid')
                    ))->save(array(
                        'specialprice' => I('get.price'),
                        'status' => 2
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break; 
                case 'overtime':
                    //action=overtime&status=3&infoid=35&price=&buyname=&buyMobile=
                    $result = M('Car')->where(array(
                        'id' => I('get.infoid')
                    ))->save(array(
                        'status' => 3,
                        'overtime' => time()
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'setdown':
                    //action=setdown&status=2&infoid=12&price=&buyname=&buyMobile=
                    $result = M('Car')->where(array(
                        'id' => I('get.infoid')
                    ))->save(array(
                        'status' => 1,
                        'specialprice' => NULL
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'setselled':
                    //action=setselled&status=&infoid=4&price=45&buyname=%u4E09%u4EBA%u4EFD&buyMobile=13212312336
                    $result = M('Car')->where(array(
                        'id' => I('get.infoid')
                    ))->save(array(
                        'status' => 4,
                        'sellprice' => I('get.price'),
                        'buymobile' => I('get.buyMobile'),
                        'buyname' => I('get.buyname'),
                        'selltime' => time()
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'update':
                    //action=update&status=4&infoid=35&price=34.00&buyname=&buyMobile=
                    $result = M('Car')->where(array(
                        'id' => I('get.infoid')
                    ))->save(array(
                        'status' => 1,
                        'price' => I('get.price'),
                        'updatetime' => time()
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'del':
                    $result = M('Car')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.infoid')
                    ))->save(array(
                        'status' => 5
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                default :
                    break;
            }
        }
    }
    
    public function RecommendCarOperate(){
        if(IS_AJAX){
            $userinfo = session('member_auth');
            $action = I('get.action');
            
            switch ($action){
                case 'create':
                    $result = M('Car')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.infoid')
                    ))->save(array(
                        'recommend' => 2
                    ));
                    if($result == false){
                        echo 2;
                    }else{
                        echo 1;
                    }
                    break;
                case 'down':
                    $result = M('Car')->where(array(
                        'store_id' => $userinfo['store_id'],
                        'id' => I('get.infoid')
                    ))->save(array(
                        'recommend' => 1
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
