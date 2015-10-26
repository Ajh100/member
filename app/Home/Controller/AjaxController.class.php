<?php
/*
发布车源ajax数据
 */
namespace Home\Controller;
use Think\Controller;

class AjaxController extends BaseController{
    
    //车辆品牌
    /*
    public function ajax_brand(){
        $brand = F('CarBrand');
        if(!$brand){
            $branddata = M('CarBrand')->where(array(
                'is_lock' => 0
            ))->order('letter asc, sort desc')->select();
            F('CarBrand',$branddata);
        }
        echo json_encode(F('CarBrand'));
    }
    */
    
    //加载车系 品牌id
    public function ajax_series(){
        if(IS_AJAX){
            $id = I('get.id');
            $list = M('CarSubBrand')->where('bid = '.$id)->select();
            
            $str = '';
            foreach ($list as $key=>$value){
                $str .= '<li><p>'. $value['title'] .'</p></li> ';
                $str .= $this->get_carseries($value['id']);
            }
            echo $str;
        }
    }
    
    //获取二级品牌 品牌id
    public function get_carseries($bid){
        $list = M('CarSeries')->where('sub_bid = '.$bid)->select();
        if($list){
            $str = '';
            foreach ($list as $key=>$value){
                $str .= '<li><a href="javascript:void(0);" onclick="SlectMenu.series_click(\''.$value['title'].'\','. $value['id'] .');">'. $value['title'] .'</a></li>';
            }
            return $str;
        }else{
            return false;
        }
    }
    
    
    public function ajax_carclass(){
        $id = I('get.id');
        $brand_id = I('get.brand_id');
        $ids = M('CarClass')->where('series_id ='.$id)->getField('style_id',true);
        $ids = array_unique($ids);
        $map['id'] = array('in', implode(',', $ids));
        $list = M('CarYear')->where($map)->select();
        $str = '';
        if($list){
            foreach ($list as $key=>$value){
                $str .= '<li><p>'.$value['title'].'</p></li>';
		$str .= $this->get_carclass($brand_id, $id, $value['id'], $value['title']);
            }
        }	
        echo $str;
    }
    
    public function get_carclass($brandid, $seriesid, $styleid, $title){
        $map = array();
        $map['brand_id'] = $brandid;
        $map['series_id'] = $seriesid;
        $map['style_id'] = $styleid;
        $list = M('CarClass')->where($map)->select();
        $str = '';
        if($list){
            foreach ($list as $key=>$value){
                $str .= '<li><a href="javascript:void(0);" onclick="SlectMenu.class_click(\''. $title .' '. $value['title'] .'\','.$value['id'].');">'. $title .' '. $value['title'].'</a></li>';
            }
        }
        return $str;
    }
	
    public function ajax_get_carclass(){
        $id = I('get.id');
        $data = M('CarClass')->where('id ='.$id)->find();
        if($data){
            echo json_encode($data);
        }
    }
	
    public function ajax_delimg(){
        if(@unlink(C('UPLOAD_SAVE_PATH').$_GET['picname'])){
                echo 1;
        }else{
                echo 0;
        }
    }
}
